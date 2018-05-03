<?php

require_once('../common/connection.php');
require_once('ModelInterface.php');

/**
 *
 */
class Meeting implements ModelInterface
{
  const TABLE_NAME = "MEETING"; //access with Meeting::TABLE_NAME
  private $meetingID;
  private $description;
  private $meetingTime;
  private $minutes;
  private $timeCreated;
  private $attendees;

  function __construct($description, $meetingTime, $minutes)
  {
    $this->meetingID = "";
    $this->description = $description;
    $this->minutes = $minutes;
    $this->meetingTime = $meetingTime;
    $this->timeCreated = "";
    $this->attendees = [];
  }

  public function save()
  {
    //echo "inside Meeting:save\n";
    $nConn = new Connection();
    $arr = array('description'=>$this->description, 'meetingTime'=>$this->meetingTime, 'minutes'=>$this->minutes);
    $this->meetingID = $nConn->save(Meeting::TABLE_NAME, $arr);
    return $this->meetingID;
  }

  public function loadByID($meetingID)
  {
    //echo "inside Meeting:loadByID\n";
    $this->meetingID = $meetingID;
    $record = $this->getRecord();
    if(!empty($record))
    {
        $this->meetingID = $record['meetingID'];
        $this->description = $record['description'];
        $this->minutes = $record['minutes'];
        $this->meetingTime = $record['meetingTime'];
        $this->timeCreated = $record['timeCreated'];
        $nQuery = "SELECT USER.userID FROM USER
          JOIN USER_MEETING ON USER.userID = USER_MEETING.userID
          JOIN MEETING ON USER_MEETING.meetingID = MEETING.meetingID
          WHERE MEETING.meetingID = $meetingID";
        $nConn = new Connection();
        $records = $nConn->getQuery($nQuery);
        
        while($row = $records->fetch_array())
        {
          $attendee = new User("", "", "", "", "");
          $attendee->loadByID($row["userID"]);
          if($attendee->getUserID() != 0)
            $this->attendees[$attendee->getUserID()] = $attendee;
        }
        return true;
    }
    //echo "No record for meetingID=".$meetingID."\n";
    return false;
  }

  public function getRecord()
  {
    if($this->meetingID === "")
      return;
    $nConn = new Connection();
    return $nConn->getRecord(Meeting::TABLE_NAME, $this->meetingID);
  }

  public function update()
  {
    //echo "inside Meeting:update\n";
    $nConn = new Connection();
    $arr = array('description'=>$this->description, 'meetingTime'=>$this->meetingTime, 'minutes'=>$this->minutes);
    $nConn->update(Meeting::TABLE_NAME, $this->meetingID, $arr);
  }

  public function delete()
  {
    if($this->meetingID === "")
      return;
    $nConn = new Connection();
    $nConn->delete(Meeting::TABLE_NAME, $this->meetingID);
  }

  // accessor methods
  public function getMeetingID()
  {
    return $this->meetingID;
  }
  public function getDescription()
  {
    return $this->description;
  }
  public function getMeetingTime()
  {
    return $this->meetingTime;
  }
  public function getMinutes()
  {
    return $this->minutes;
  }
  public function getTimeCreated()
  {
    return $this->timeCreated;
  }
  public function getAttendees()
  {
    return $this->attendees;
  }

}


?>
