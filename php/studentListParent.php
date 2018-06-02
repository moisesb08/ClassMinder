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
        
        if($_SESSION['isTeacher'] == 1)
            header("location: studentList.php");
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
                "SELECT DISTINCT STUDENT.firstName, STUDENT.lastName, STUDENT.studentID
                FROM STUDENT
                JOIN STUDENT_PARENT ON STUDENT_PARENT.studentID = STUDENT.studentID
                WHERE STUDENT_PARENT.parentID = $userID";
                $records = $nConn->getQuery($nQuery);
                echo "<form method='post' action='studentProfile.php'>";
                while($row = $records->fetch_array())
                {
                    echo "<tr><td class='btnCell'><button type='submit' name='studentID' formmethod='post' class='button' value=" . $row['studentID'] . ">";
                    echo $row["firstName"] . " " . $row["lastName"] . "<br>ID: ".$row['studentID'];
                    echo "</td></button></tr>";
                }
                echo "</form>";
            ?>
        </table>
        </div>
    </div>
</body>
</html>