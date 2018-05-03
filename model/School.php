<?php

require_once('../common/connection.php');
require_once('ModelInterface.php');

/**
 *
 */
class School implements ModelInterface
{
  const TABLE_NAME = "SCHOOL"; //access with School::TABLE_NAME
  private $schoolID;
  private $name;
  private $city;
  private $state;

  function __construct($name, $city, $state)
  {
    $this->schoolID = "";
    $this->name = $name;
    $this->city = $city;
    $this->state = $state;
  }

  public function save()
  {
    echo "inside School:save\n";
    $nConn = new Connection();
    $arr = array('name'=>$this->name, 'city'=>$this->city, 'state'=>$this->state);
    $this->schoolID = $nConn->save(School::TABLE_NAME, $arr);
    return $this->schoolID;
  }

  public function loadByID($schoolID)
  {
    echo "inside School:loadByID\n";
    $this->schoolID = $schoolID;
    $record = $this->getRecord();
    if(!empty($record))
    {
        $this->schoolID = $record['schoolID'];
        $this->name = $record['name'];
        $this->city = $record['city'];
        $this->state = $record['state'];
        return true;
    }
    echo "No record for schoolID=".$schoolID."\n";
    return false;
  }

  public function getRecord()
  {
    if($this->schoolID === "")
      return;
    $nConn = new Connection();
    return $nConn->getRecord(School::TABLE_NAME, $this->schoolID);
  }

  public function update()
  {
    echo "inside School:update\n";
    $nConn = new Connection();
    $arr = array('name'=>$this->name, 'city'=>$this->city, 'state'=>$this->state);
    $nConn->update(School::TABLE_NAME, $this->schoolID, $arr);
  }

  public function delete()
  {
    if($this->schoolID === "")
      return;
    $nConn = new Connection();
    $nConn->delete(School::TABLE_NAME, $this->schoolID);
  }

  // accessor methods
  public function getSchoolID()
  {
    return $this->schoolID;
  }
  public function getName()
  {
    return $this->name;
  }
  public function getCity()
  {
    return $this->city;
  }
  public function getState()
  {
    return $this->state;
  }

}


?>
