<?php

require_once('../common/connection.php');
require_once('ModelInterface.php');
/**
 * User class that implements ModelInterface, teachers and parents are users
 */

class User implements ModelInterface
{
  const TABLE_NAME = "USER";
  private $userID;
  private $email;
  private $firstName;
  private $lastName;
  private $password;  
  private $isTeacher;

  function __construct($email, $firstName, $lastName, $password, $isTeacher)
  {
    echo "inside User:constructor\n";
    $this->userID = "";
    $this->email = $email;
    $this->firstName = $firstName;
    $this->lastName = $lastName;
    $this->password = $password;
    $this->isTeacher = $isTeacher;
  }


  public function save()
  {
    echo "inside User:save\n";
    $nConn = new Connection();
    $arr = array('email'=>$this->email, 'firstName'=>$this->firstName,'lastName'=>$this->lastName, 'password'=>$this->password, 'isTeacher'=>$this->isTeacher);
    $this->userID = $nConn->save(User::TABLE_NAME, $arr);
    return $this->userID;
  }

  public function loadByID($userID)
  {
    echo "inside User:loadByID\n";
    $this->userID = $userID;
    $record = $this->getRecord();
    if(!empty($record))
    {
      $this->userID = $record['userID'];
      $this->email = $record['email'];
      $this->firstName = $record['firstName'];
      $this->lastName = $record['lastName'];
      $this->password = $record['password'];
      $this->isTeacher = $record['isTeacher'];
      return true;
    }
    echo "No record for userID=".$userID."\n";
    return false;
  }

  public function getRecord()
  {
    if($this->userID === "")
      return;
    $nConn = new Connection();
    return $nConn->getRecord(User::TABLE_NAME, $this->userID);
  }

  public function update()
  {
    echo "inside User:update\n";
    $nConn = new Connection();
    $arr = array('email'=>$this->email, 'firstName'=>$this->firstName, 'lastName'=>$this->lastName, 'password'=>$this->password, 'isTeacher'=>$this->isTeacher);
    $nConn->update(User::TABLE_NAME, $this->userID, $arr);
  }

  public function delete()
  {
    if($this->userID === "")
      return;
    $nConn = new Connection();
    $nConn->delete(User::TABLE_NAME, $this->userID);
  }

  public function checkUser() {
    $nConn = new Connection();
    $clause = " email = '$this->email' and password ='$this->password'";
    echo $clause;
    return $nConn->getCount(User::TABLE_NAME, $clause);
  }

  // accessor methods
  public function getUserID()
  {
    return $this->userID;
  }
  public function getEmail()
  {
    return $this->email;
  }
  public function getFirstName()
  {
    return $this->firstName;
  }
  public function getLastName()
  {
    return $this->lastName;
  }
  public function getIsTeacher()
  {
    return $this->isTeacher;
  }
  
}

?>
