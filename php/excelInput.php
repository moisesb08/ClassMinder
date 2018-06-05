<?php
session_start();
if(isset($_SESSION['user']))
{
    if($_SESSION['isTeacher'] == 0)
        header("location: parentHome.php");
}
else
{
    header("location: ../view/loginPage.php");
}
// If user is a parent he/she will be redirected to the parent homepage
if($_SESSION['isTeacher'] == 0)
    header("location: parentHome.php");
require_once('../common/connection.php');
include("../model/Student.php");
include_once('../model/Classroom.php');

if(isset($_POST["submit"])) {
    $file = $_FILES['excelFile']['tmp_name'];
    $handle = fopen($file, "r");
    if($file == NULL) {
        if(isset($_POST["classroomID"])) {
            $classroomID = $_POST['classroomID'];
            $title = $_POST['title'];
            echo "<form name='goToForm' method='post' action='classroom.php'>";
            echo "<input type='hidden' name='title' value='$title'>";
            echo "<input type='hidden' name='classroomID' value='$classroomID'>";
            echo "</form>";
            echo "<script>document.goToForm.submit();</script>";
        }
        else {
            header("location:./studentList.php");
        }
        die;
    } else {
        $nConn = new Connection();
        $userID = $_SESSION['userID'];
        $str = "INSERT INTO CLASSROOM (title, userID, schoolID)
        SELECT * FROM (SELECT 'Unassigned Class', $userID, 0) AS tmp
        WHERE NOT EXISTS (
            SELECT CLASSROOM.title FROM CLASSROOM WHERE CLASSROOM.title = 'Unassigned Class' AND userID=$userID
        ) LIMIT 1;";
        $nConn->getQuery($str);
        $arr = array('schoolID'=>'0', 'userID'=>$userID, 'title'=>'Unassigned Class');
        $classroomSelected = $nConn->getRecordByArr('CLASSROOM', $arr);
        $title = $_POST['title'];
        $classroomID = $classroomSelected['classroomID'];
        if(isset($_POST["classroomID"])) {
            $classroomIDSet = $_POST["classroomID"];
        }
        $i = 0;
        while(($file_row = fgetcsv($handle, 1000, ",")) !== false)
        {
            if ($i < 2)
            {
                $i++;
                continue;
            }
            // student ID will be column 0 and name (last, first) will be columns 1 and 2
            $sID = $file_row[0];
            $lastName = $file_row[1];
            $firstName = $file_row[2];
            $student = new Student($firstName, $lastName, $sID);
            $studentCreated = $student->save()!=0;
            $studentID = $student->getStudentID();
            $str = "INSERT INTO STUDENT_CLASS (studentID, classroomID)
                    VALUES ($studentID, $classroomID)";
            $nConn->getQuery($str);
            if(isset($_POST["classroomID"])) {
                $str = "INSERT INTO STUDENT_CLASS (studentID, classroomID)
                    VALUES ($studentID, $classroomIDSet)";
                $nConn->getQuery($str);
            }
        }
        if(isset($_POST["classroomID"])) {
            echo "<form name='goToForm' method='post' action='classroom.php'>";
            echo "<input type='hidden' name='title' value='$title'>";
            echo "<input type='hidden' name='classroomID' value='$classroomIDSet'>";
            echo "</form>";
            echo "<script>document.goToForm.submit();</script>";
        }
        else
        {
            header("location:./studentList.php");
        }
        die;
    }
}
?>