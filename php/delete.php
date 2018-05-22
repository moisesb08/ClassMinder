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

    function deleteStudent($studentID, $nConn)
    {
        if($studentID == "")
            return;
        $nQuery = "DELETE FROM STUDENT_CLASS WHERE studentID = $studentID";
        $nConn->getQuery($nQuery);
        $nQuery = "DELETE FROM STUDENT_BEHAVIOR WHERE studentID = $studentID";
        $nConn->getQuery($nQuery);
        $nQuery = "DELETE FROM STUDENT_PARENT WHERE studentID = $studentID";
        $nConn->getQuery($nQuery);
        $nQuery = "DELETE FROM STUDENT WHERE studentID = $studentID";
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

?>