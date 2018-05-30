<?php
    require_once('../common/connection.php');
    include_once('../model/User.php');

    // Initialize the session
    session_start();
    // If session variable is not set it will redirect to login page
    if(!isset($_SESSION['user']) || empty($_SESSION['user'])){
        header("location: ../view/loginPage.php");
        exit;
    }
    $nConn = new Connection();
    // Create a $user and store it for session 
    if(!isset($_SESSION['user']) || empty($_SESSION['user']))
    {
        $email = $_SESSION['username'];
        $nQuery = "SELECT userID FROM USER WHERE email='$email'";
        $records = $nConn->getQuery($nQuery);
        $row = $records->fetch_array();
        $user = new User("", "", "", "", "");
        $id = $row["userID"];
        $user->loadByID($id);
        $_SESSION['user'] = $user;
        $_SESSION['userID'] = $_SESSION['user']->getUserID();
    }

    if(isset($_POST["studentID"]))
    {
        $studentID = $_POST["studentID"];
        deleteStudent($studentID, $nConn);
        header("location: studentList.php");
        exit;
    }
    if(isset($_POST["classroomID"]))
    {
        $classroomID = $_POST["classroomID"];
        deleteClass($classroomID, $nConn);
        header("location: classList.php");
        exit;
    }
    if(isset($_POST["meetingSlotID"]))
    {
        $meetingSlotID = $_POST["meetingSlotID"];
        echo'<script> alert("meetingSlotID passed, going to delete function.") </script>';
        deleteMeeting($meetingSlotID, $nConn);
        header("location: meetings.php");
        exit;
    }

    function deleteStudent($studentID, $nConn)
    {
        if($studentID == "")
            return;
        $nQuery = "UPDATE STUDENT SET isActive = 0 WHERE studentID = $studentID";
        $nConn->getQuery($nQuery);
    }

    function deleteClass($classroomID, $nConn)
    {
        if($classroomID == "")
            return;
        $nQuery = "DELETE FROM STUDENT_CLASS WHERE classroomID = $classroomID";
        $nConn->getQuery($nQuery);
        $nQuery = "DELETE FROM CLASSROOM WHERE classroomID = $classroomID";
        $nConn->getQuery($nQuery);
    }

    function deleteMeeting($meetingSlotID, $nConn)
    {
        if($meetingSlotID == "")
            return;
        $userID = $_SESSION['userID'];
        $nQuery = "SELECT MEETING_SLOT.meetingTime
        FROM MEETING_SLOT, MEETING
        WHERE MEETING_SLOT.meetingTime=MEETING.meetingTime AND MEETING_SLOT.teacherID=$userID AND meetingSlotID=$meetingSlotID";
        $records = $nConn->getQuery($nQuery);
        $row = $records->fetch_array();
        $meetingTime = $row['meetingTime'];
        if ($meetingTime != "")
        {
            $nQuery = "DELETE FROM MEETING WHERE meetingTime = '$meetingTime' AND teacherID=$userID";
            $nConn->getQuery($nQuery);
        }
        $nQuery = "DELETE FROM MEETING_SLOT WHERE meetingSlotID = $meetingSlotID AND teacherID=$userID";
        $nConn->getQuery($nQuery);
    }

?>