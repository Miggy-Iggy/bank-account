<?php 
class Account {
  private $showDivs = array("dashboard" => 0,
                            "transaction" => 0,
                            "createAccount" => 1,
                            "accounts" => 0 );
  private $connection;

  function __construct($server,$user,$password,$db){
    $this->connection = mysqli_connect($server,$user,$password,$db);
    if(!$this->connection){
      echo "Connection Failed Report: ".mysqli_connection_error();
    }
  }

  public function getConnection(){
    return $this->connection;
  }

  public function createAccount($name,$PIN,$iDeposit){
    $sql = "INSERT INTO `user`(`account_number`,`name`,`PIN`,`money`)
            VALUES ('NULL','$name','$PIN','$iDeposit');";

    if(!mysqli_query($this->connection, $sql)){
      echo "Insert Failed Report: ".$sql."<br>".mysqli_error($this->connection);
    }

  }

  // check if mysql table has 4 rows
  public function check(){
    $result = mysqli_query($this->connection, "SELECT * FROM `user`;") or die(mysqli_error($this->connection));
    return (mysqli_num_rows($result) == 4);
  }

  public function getDivArray(){
    return $this->showDivs;
  }

  public function displayDivs(){
    $this->showDivs['dashboard'] = 1;
    $this->showDivs['createAccount'] = 0;
    $this->showDivs['accounts'] = 1;
    return $this->showDivs;
  }

  public function transac($state){
    $this->showDivs['transaction'] = $state;
    return $this->showDivs;
  }

  //get User-Account data, single SELECT query 
  public function getUserRow(){
    $data = mysqli_query($this->connection, "SELECT * FROM `user` LIMIT 1") or die(mysqli_error($this->connection));
    //fetch 1 associative array of data
    return mysqli_fetch_assoc($data);
  }

  //get default-Accounts data LIMIT: start at 1, get 3 rows
  public function getAccountRows(){
    $data = mysqli_query($this->connection, "SELECT * FROM `user` LIMIT 1, 3") or die(mysqli_error($this->connection));
    //fetch all rows as an associative array
    return mysqli_fetch_all($data, MYSQLI_ASSOC);
  }

}
?>