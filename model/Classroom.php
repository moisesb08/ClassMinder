<?php

require_once('../common/connection.php');
require_once('ModelInterface.php');

/**
 *
 */
class Classroom implements ModelInterface
{
  const TABLE_NAME = "CLASSROOM"; //access with Classroom::TABLE_NAME
  private $classroomID;
  private $title;
  private $userID;
  private $schoolID;
  private $students;

  function __construct($title, $userID, $schoolID)
  {
    $this->classroomID = "";
    $this->title = $title;
    $this->userID = $userID;
    $this->schoolID = $schoolID;
    $this->students = [];
  }

  public function save()
  {
    echo "inside Classroom:save\n";
    $nConn = new Connection();
    $arr = array('title'=>$this->title, 'userID'=>$this->userID, 'schoolID'=>$this->schoolID);
    $this->classroomID = $nConn->save(Classroom::TABLE_NAME, $arr);
    return $this->classroomID;
  }

  public function loadByID($classroomID)
  {
    echo "inside Classroom:loadByID\n";
    $this->classroomID = $classroomID;
    $record = $this->getRecord();
    if(!empty($record))
    {
        $this->classroomID = $record['classroomID'];
        $this->title = $record['title'];
        $this->userID = $record['userID'];
        $this->schoolID = $record['schoolID'];
        $nQuery = "SELECT STUDENT.studentID FROM STUDENT
          JOIN STUDENT_CLASS ON STUDENT.studentID = STUDENT_CLASS.studentID
          JOIN CLASSROOM ON STUDENT_CLASS.classroomID = CLASSROOM.classroomID
          WHERE CLASSROOM.classroomID = $classroomID";
        $nConn = new Connection();
        $records = $nConn->getQuery($nQuery);
        
        while($row = $records->fetch_array())
        {
          $student = new User("", "", "", "", "");
          $student->loadByID($row["userID"]);
          if($student->getUserID() != 0)
            $this->students[$student->getUserID()] = $student;
        }
        return true;
    }
    echo "No record for classroomID=".$classroomID."\n";
    return false;
  }

  public function getRecord()
  {
    if($this->classroomID === "")
      return;
    $nConn = new Connection();
    return $nConn->getRecord(Classroom::TABLE_NAME, $this->classroomID);
  }

  public function update()
  {
    echo "inside Classroom:update\n";
    $nConn = new Connection();
    $arr = array('title'=>$this->title, 'schoolID'=>$this->schoolID, 'userID'=>$this->userID);
    $nConn->update(Classroom::TABLE_NAME, $this->classroomID, $arr);
  }

  public function delete()
  {
    if($this->classroomID === "")
      return;
    $nConn = new Connection();
    $nConn->delete(Classroom::TABLE_NAME, $this->classroomID);
  }

  // accessor methods
  public function getClassroomID()
  {
    return $this->classroomID;
  }
  public function getTitle()
  {
    return $this->title;
  }
  public function getSchoolID()
  {
    return $this->schoolID;
  }
  public function getUserID()
  {
    return $this->userID;
  }
  public function getStudents()
  {
    return $this->students;
  }

}


?>
