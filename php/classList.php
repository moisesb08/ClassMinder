<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ClassMinder - Your Classes</title>
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/teacherHome.js"></script>
    <link rel="stylesheet" href="../css/studentList.css">
    <link rel="stylesheet" href="../resources/ionicons-2.0.1/css/ionicons.min.css">
    <?php
        require_once('../common/connection.php');
        include_once('../model/User.php');
        include_once('../model/Classroom.php');
        include_once('sidebar.php');
        // Initialize the session
        session_start();
        // If session variable is not set it will redirect to login page
        if(!isset($_SESSION['user']) || empty($_SESSION['user'])){
            header("location: ../view/loginPage.php");
            exit;
        }
        // If user is a parent he/she will be redirected to the parent homepage
        if($_SESSION['isTeacher'] == 0)
            header("location: parentHome.php");
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
                <td><h1>Your Classes</h1></td>
            </tr>
            <?php
                $userID = $_SESSION["userID"];
                $nQuery =
                "SELECT CLASSROOM.title, CLASSROOM.classroomID
                FROM CLASSROOM
                WHERE CLASSROOM.userID = $userID AND CLASSROOM.title <> 'Unassigned Class'";
                $records = $nConn->getQuery($nQuery);
                while($row = $records->fetch_array())
                {
                    $title = $row['title'];
                    echo "<form method='post' action='classroom.php'>";
                    echo "<input type='hidden' name='title' value='$title'>";
                    echo "<tr><td class='btnCell'><button type='submit' name='classroomID' formmethod='post' class='button' value=" . $row['classroomID'] . ">";
                    echo $title . "<br>";
                    echo "</td></button></tr>";
                    echo "</form>";
                }
            ?>
            <tr><td colspan='1' class='btnCell'><button onclick="window.location.href='./addClass.php'"><span><i class="ion-plus-round"></i></span></button></td></tr>
        </table>
        </div>
    </div>
</body>
</html>