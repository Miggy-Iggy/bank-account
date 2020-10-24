<?php 
    require_once('account.php');
    require_once('transaction.php');
    $defaultAccount = new Account("localhost","root","","bank");
    $trans = new Transaction();
    
    // divState determines which box to show
    $divState = $defaultAccount->getDivArray();

    // check if database has 4 rows inserted.
    if($defaultAccount->check()){
        $divState = $defaultAccount->displayDivs();

        // returns the first row which is the user row
        $userRow = $defaultAccount->getUserRow();
        // return the rest of the rows
        $AccountRows = $defaultAccount->getAccountRows();
    }

    if(isset($_POST['submit'])){
        $name = mysqli_escape_string($defaultAccount->getConnection(), $_POST['name']);
        $PIN = mysqli_escape_string($defaultAccount->getConnection(), $_POST['PIN']);
        $iDeposit = mysqli_escape_string($defaultAccount->getConnection(), $_POST['iDeposit']);;

        // create accounts
        $defaultAccount->createAccount($name,$PIN,$iDeposit);
        $defaultAccount->createAccount('Thomas Shelby','1111','10000');
        $defaultAccount->createAccount('Arthur Shelby','2222','5000');
        $defaultAccount->createAccount('John Shelby','3333','3000');

        // check if database has 4 rows inserted.
        if($defaultAccount->check()){
            $divState = $defaultAccount->displayDivs();
            header('location: '.$_SERVER['PHP_SELF']);
        }
    }

    if(isset($_POST['deposit'])){
        $divState = $defaultAccount->transac(1);
        $content = '<span>Deposit Money</span><br>
                    <label for="amount">Amount: </label> <br>   
                    <input type="number" name="amount"> <br>
                    <label for="PIN">PIN: </label> <br>   
                    <input type="number" name="PIN"> <br>
                    <input type="submit" name="getDeposit">';     
    }

    if(isset($_POST['withdraw'])){
        $divState = $defaultAccount->transac(1);
        $content = '<span>Withdraw Money</span><br>
                    <label for="amount">Amount: </label> <br>
                    <input type="number" name="amount"> <br>
                    <label for="PIN">PIN: </label> <br>   
                    <input type="number" name="PIN"> <br>
                    <input type="submit" name="getWithdraw">';
    }

    if(isset($_POST['transfer'])){
        $divState = $defaultAccount->transac(1);
        $content = '<span>Transfer Money</span><br>
                    <label for="amount">Amount: </label> <br>
                    <input type="number" name="amount"> <br>
                    <label for="account_number">Send to (Enter Account Number): </label> <br>
                    <input type="number" name="account_number"> <br>
                    <label for="PIN">PIN: </label> <br> 
                    <input type="number" name="PIN"> <br>
                    <input type="submit" name="getTransfer">';
    }
    // $userRow = $defaultAccount->getUserRow();
    if(isset($_POST['getDeposit'])){
        if($userRow['PIN'] == $_POST['PIN']){
            $trans->deposit($defaultAccount->getUserRow(),$_POST['amount'],$defaultAccount->getConnection());
        }else{
            $message =  "Incorrect PIN"; // make into somekind of console type message later.
        }
    }

    if(isset($_POST['getWithdraw'])){
        if($userRow['PIN'] == $_POST['PIN']){
            $trans->withdraw($defaultAccount->getUserRow(),$_POST['amount'],$defaultAccount->getConnection());
        
        }else{
            $message = "Incorrect PIN"; // make into somekind of console type message later.
        }
    }
    if(isset($_POST['getTransfer'])){
        if($userRow['PIN'] == $_POST['PIN']){
            $trans->transfer($defaultAccount->getUserRow(),$_POST['account_number'],$_POST['amount'],$defaultAccount->getConnection());
        }else{
            $message = "Incorrect PIN"; // make into somekind of console type message later.
        }    
    }
    if(isset($_POST['close'])){
        $divState = $defaultAccount->transac(0);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div id="container">
        <!-- First Row, Header -->
        <header>
            <div id="title"><h1>La Casa De Papel</h1></div>
            <div id="console"><?php  echo $message ?? "" ?> </div>
        </header>
        
        <!-- Second Row, The User -->
        <div id="dashboard" class="accountHolder" style="opacity: <?php echo $divState['dashboard']; ?>;">
            <h2>Dashboard</h2>
            <hr>
            <p>Account Number: <code><?php echo $userRow['account_number']; ?></code></p>
            <p>Name: <code><?php echo $userRow['name']; ?></code></p>
            <p>Money: <code><?php echo $userRow['money']; ?></code></p>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>"  method="POST">
                <input type="submit" value="Deposit" name="deposit">
                <input type="submit" value="Withdraw" name="withdraw">
                <input type="submit" value="Transfer" name="transfer">
            </form>
        </div>

        <div id="transaction" class="accountHolder" style="opacity: <?php echo $divState['transaction']; ?>;">
            <h2>Transaction</h2>
            <hr>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <input type="submit" name="close" value=" X ">    
                <?php echo $content ?? ""; ?>
            </form>
        </div>
        
        <div id="createAccount" style="opacity: <?php echo $divState['createAccount']; ?>;">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>"  method="POST">
                <label for="name">Name: </label> <br>
                <input type="text" name="name" required> <br>

                <label for="PIN">4-Digit PIN: </label> <br>
                <input type="number" name="PIN" min="1000" max="10000" required> <br>

                <label for="iDeposit">Initial Deposit: </label> <br>
                <input type="number" name="iDeposit" min="1" required> <br>

                <input type="submit" value="Submit" name="submit">
            </form>
        </div>

        <!-- Third Row, 3 Pre-defined Accounts -->
        <?php if(isset($AccountRows)): ?>
            <?php foreach($AccountRows as $acc): ?>
                <div class="accountHolder dummy" style="opacity: <?php echo $divState['accounts']; ?>;">
                    <p>Account Number: <code><?php echo htmlspecialchars($acc['account_number']); ?></code></p>
                    <p>Name: <code><?php echo htmlspecialchars($acc['name']); ?></code></p>
                    <p>Money: <code><?php echo htmlspecialchars($acc['money']); ?></code></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</body>
</html>