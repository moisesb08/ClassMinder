<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ClassMinder - Your Students</title>
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/teacherHome.js"></script>
    <link rel="stylesheet" href="../css/studentList.css">
    <link rel="stylesheet" href="../resources/ionicons-2.0.1/css/ionicons.min.css">
    <?php
        require_once('../common/connection.php');
        include_once('../model/User.php');
        include_once('../model/Student.php');
        include_once('sidebar.php');
        // Initialize the session
        session_start();
        // If session variable is not set it will redirect to login page
        if(!isset($_SESSION['user']) || empty($_SESSION['user'])){
            header("location: ../view/loginPage.php");
            exit;
        }
        $nConn = new Connection();
        if($_SESSION['isTeacher'] == 0)
            header("location: studentListParent.php");
        ?>
</head>
<body>
    <?php
        if($_SESSION["isTeacher"])
            echo teacherSidebar();
        else
            echo parentSidebar();
    ?>
    <div class="div1">
        <div class="midContainer">
        <table>
            <tr>
                <td><h1>Your Students</h1></td>
            </tr>
            <?php
                $userID = $_SESSION["userID"];
                $nQuery =
                "SELECT DISTINCT STUDENT.firstName, STUDENT.lastName, STUDENT.studentID, STUDENT.sID
                FROM STUDENT
                JOIN STUDENT_CLASS ON STUDENT_CLASS.studentID = STUDENT.studentID
                JOIN CLASSROOM ON CLASSROOM.classroomID = STUDENT_CLASS.classroomID
                WHERE CLASSROOM.userID = $userID AND STUDENT.isActive = 1";
                $records = $nConn->getQuery($nQuery);
                echo "<form method='post' action='studentProfile.php'>";
                while($row = $records->fetch_array())
                {
                    $sID = $row["sID"];
                    echo "<input type='hidden' name='sID' value='$sID'>";
                    echo "<tr><td class='btnCell'><button type='submit' name='studentID' formmethod='post' class='button' value=" . $row['studentID'] . ">";
                    echo $row["firstName"] . " " . $row["lastName"] . "<br>ID: ".$row['sID'];
                    echo "</td></button></tr>";
                }
                echo "</form>";
            ?>
            <tr><td colspan='1' class='btnCell' style='<?php if($_SESSION['isTeacher'] == 0) echo "display:none";?>'><button onclick="window.location.href='./addStudent.php'"><span><i class="ion-plus-round"></i></span></button></td></tr>
        </table>
        </div>
    </div>
</body>
</html>