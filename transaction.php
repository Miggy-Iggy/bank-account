<?php  
// withdraw, deposit, transfer
class Transaction{

    public function deposit($data, $amount, $connection){
        $newValue = $data['money'] + $amount;
        $account_number = $data['account_number'];

        mysqli_query($connection, "UPDATE `user` SET `money` =  $newValue WHERE `account_number` = '$account_number'") 
        or die(mysqli_error($this->connection));

        header('location: '.$_SERVER['PHP_SELF']);
    }


    public function withdraw($data, $amount, $connection){
        $newValue = $data['money'] - $amount;
        $account_number = $data['account_number'];

        mysqli_query($connection, "UPDATE `user` SET `money` =  $newValue WHERE `account_number` = '$account_number'") 
        or die(mysqli_error($this->connection));

        header('location: '.$_SERVER['PHP_SELF']);
    }

    public function transfer($from, $account_number, $amount, $connection){
        $this->withdraw($from,$amount,$connection);
        $to = mysqli_query($connection, "SELECT * FROM `user` WHERE `account_number` = '$account_number';") 
              or die(mysqli_error($this->connection));
              
        $this->deposit(mysqli_fetch_assoc($to),$amount,$connection);
        header('location: '.$_SERVER['PHP_SELF']);
    }
}

?>