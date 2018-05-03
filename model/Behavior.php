<?php

require_once('../common/connection.php');
require_once('ModelInterface.php');

/**
 *
 */
class Behavior implements ModelInterface
{
  const TABLE_NAME = "BEHAVIOR"; //access with Behavior::TABLE_NAME
  private $behaviorID;
  private $title;
  private $description;
  private $userID;
  private $isPositive;

  function __construct($title, $description, $userID, $isPositive)
  {
    $this->behaviorID = "";
    $this->title = $title;
    $this->description = $description;
    $this->userID = $userID;
    $this->isPositive = $isPositive;
  }

  public function save()
  {
    echo "inside Behavior:save\n";
    $nConn = new Connection();
    $arr = array('title'=>$this->title, 'description'=>$this->description, 'userID'=>$this->userID, 'isPositive'=>$this->isPositive);
    $this->behaviorID = $nConn->save(Behavior::TABLE_NAME, $arr);
    return $this->behaviorID;
  }

  public function loadByID($behaviorID)
  {
    echo "inside Behavior:loadByID\n";
    $this->behaviorID = $behaviorID;
    $record = $this->getRecord();
    if(!empty($record))
    {
        $this->behaviorID = $record['behaviorID'];
        $this->title = $record['title'];
        $this->description = $record['description'];
        $this->userID = $record['userID'];
        $this->isPositive = $record['isPositive'];
        return true;
    }
    echo "No record for behaviorID=".$behaviorID."\n";
    return false;
  }

  public function getRecord()
  {
    if($this->behaviorID === "")
      return;
    $nConn = new Connection();
    return $nConn->getRecord(Behavior::TABLE_NAME, $this->behaviorID);
  }

  public function update()
  {
    echo "inside Behavior:update\n";
    $nConn = new Connection();
    $arr = array('title'=>$this->title, 'description'=>$this->description, 'isPositive'=>$this->isPositive, 'userID'=>$this->userID);
    $nConn->update(Behavior::TABLE_NAME, $this->behaviorID, $arr);
  }

  public function delete()
  {
    if($this->behaviorID === "")
      return;
    $nConn = new Connection();
    $nConn->delete(Behavior::TABLE_NAME, $this->behaviorID);
  }

  // accessor methods
  public function getBehaviorID()
  {
    return $this->behaviorID;
  }
  public function getTitle()
  {
    return $this->title;
  }
  public function getDescription()
  {
    return $this->description;
  }
  public function getIsPositive()
  {
    return $this->isPositive;
  }
  public function getUserID()
  {
    return $this->userID;
  }

}


?>
