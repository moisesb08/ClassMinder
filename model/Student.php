<?php

require_once('../common/connection.php');
require_once('ModelInterface.php');

/**
 *
 */
class Student implements ModelInterface
{
  const TABLE_NAME = "STUDENT"; //access with Student::TABLE_NAME
  private $studentID;
  private $firstName;
  private $lastName;

  function __construct($firstName, $lastName)
  {
    $this->studentID = "";
    $this->firstName = $firstName;
    $this->lastName = $lastName;
  }

  public function save()
  {
    echo "inside Student:save\n";
    $nConn = new Connection();
    $arr = array('firstName'=>$this->firstName, 'lastName'=>$this->lastName);
    $this->studentID = $nConn->save(Student::TABLE_NAME, $arr);
    return $this->studentID;
  }

  public function loadByID($studentID)
  {
    echo "inside Student:loadByID\n";
    $this->studentID = $studentID;
    $record = $this->getRecord();
    if(!empty($record))
    {
        $this->studentID = $record['studentID'];
        $this->firstName = $record['firstName'];
        $this->lastName = $record['lastName'];
        return true;
    }
    echo "No record for studentID=".$studentID."\n";
    return false;
  }

  public function getRecord()
  {
    if($this->studentID === "")
      return;
    $nConn = new Connection();
    return $nConn->getRecord(Student::TABLE_NAME, $this->studentID);
  }

  public function update()
  {
    echo "inside Student:update\n";
    $nConn = new Connection();
    $arr = array('firstName'=>$this->firstName, 'lastName'=>$this->lastName);
    $nConn->update(Student::TABLE_NAME, $this->studentID, $arr);
  }

  public function delete()
  {
    if($this->studentID === "")
      return;
    $nConn = new Connection();
    $nConn->delete(Student::TABLE_NAME, $this->studentID);
  }

  // accessor methods
  public function getStudentID()
  {
    return $this->studentID;
  }
  public function getFirstName()
  {
    return $this->firstName;
  }
  public function getLastName()
  {
    return $this->lastName;
  }

}


?>
