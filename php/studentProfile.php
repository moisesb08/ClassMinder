<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ClassMinder - Student</title>
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/teacherHome.js"></script>
    <link rel="stylesheet" href="../css/studentList.css">
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <?php
        require_once('../common/connection.php');
        include_once('../model/User.php');
        include_once('../model/Student.php');
        // Initialize the session
        session_start();
        // If session variable is not set it will redirect to login page
        if(!isset($_SESSION['user']) || empty($_SESSION['user'])){
            header("location: ../view/loginPage.php");
            exit;
        }
        if(isset($_POST["studentID"]))
        {
            $_SESSION['post'] = $_POST;
        }
        else
            $_POST = $_SESSION['post'];
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
    <div class="all">
        <div class="modal">
            <div class="modalBox">
                <span>modal box</span>
            </div>
        </div>
    <div class="leftMenu">
        <ul>
            
            <li><span class="topItem">
                <br>
                <div class="logoMid"><img src="../resources/images/templogoWhiteTransparent-box.png" height="30px"></div>
                <span>ClassMinder</span>
                </span>
            </li>
            <li class="logout"><span class="menuItem">
                <a href="logout.php" class="underlined">
                    <span><i class="ion-log-out"></i></span>
                    <span class="iconText">Logout</span>
                </a>
                </span></li>
            <li><span class="menuItem">
                <a href="teacherHome.php" class="underlined">
                    <span><i class="ion-ios-home-outline"></i></span>
                    <span class="iconText">Home</span>
                </a>
                </span></li>
            <li><span class="menuItem">
                <a href="studentList.php" class="underlined">
                    <span><i class="ion-ios-people"></i></span>
                    <span class="iconText">Students</span>
                </a>
                </span></li>
            <li><span class="menuItem">
                <a href="classList.php" class="underlined">
                    <span><i class="ion-university"></i></span>
                    <span class="iconText">Classes</span>
                </a>
                </span></li>
            <li><span class="menuItem">
                <a href="resources.php" class="underlined">
                    <span><i class="ion-ios-bookmarks-outline"></i></span>
                    <span class="iconText">Resources</span>
                </a>
                </span></li>
            <li><span class="menuItem">
                <a href="preferences.php" class="underlined">
                    <span><i class="ion-ios-settings"></i></span>
                    <span class="iconText">Preferences</span>
                </a>
                </span></li>
            <li><span class="menuItem">
                <a href="settings.php" class="underlined">
                    <span><i class="ion-ios-gear-outline"></i></span>
                    <span class="iconText">Account Settings</span>
                </a>
                </span></li>
            <li><span class="menuItem">
                <a href="help.php" class="underlined">
                    <span><i class="ion-help"></i></span>
                    <span class="iconText">Help</span>
                </a>
                </span></li>
        </ul>
    </div>
    <div class="div1">
        <div class="midContainer">
        <table>
            <tr>
                <td><h1>
                <?php
                    $studentID = $_POST["studentID"];
                    $student = new Student("","");
                    $student->loadByID($studentID);
                    echo $student->getFirstName() . " " . $student->getLastName();
                ?>
                </h1></td>
            </tr>
            <?php
                $userID = $_SESSION["userID"];
                $studentID = $_POST["studentID"];
                $title = $_POST["title"];
                $nQuery =
                "SELECT DISTINCT CLASSROOM.classroomID, CLASSROOM.title
                FROM STUDENT
                JOIN STUDENT_CLASS ON STUDENT_CLASS.studentID = STUDENT.studentID
                JOIN CLASSROOM ON CLASSROOM.classroomID = STUDENT_CLASS.classroomID
                WHERE CLASSROOM.userID = $userID AND CLASSROOM.title <> 'Unassigned Class' AND STUDENT_CLASS.studentID = $studentID;";
                $records = $nConn->getQuery($nQuery);
                echo "<tr><td><h1>Classes</h1></td></tr>";
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
            <tr><td>
            <h1>Most Recent Behavior</h1>
            </td></tr>
            <?php
                $userID = $_SESSION["userID"];
                $studentID = $_POST["studentID"];
                $nQuery =
                "SELECT BEHAVIOR.title
                FROM BEHAVIOR, STUDENT_BEHAVIOR
                WHERE BEHAVIOR.behaviorID=STUDENT_BEHAVIOR.behaviorID AND STUDENT_BEHAVIOR.studentID=$studentID
                ORDER BY recordedTime DESC
                LIMIT 4;";
                $records = $nConn->getQuery($nQuery);
                while($row = $records->fetch_array())
                {
                    $title = $row['title'];
                    echo "<tr><td>";
                    echo $title . "<br>";
                    echo "</td></tr>";
                }
            ?>
            <tr><td>
            <h1>Behavior Graph</h1>
            </td></tr>
            <form action="behaviorGraph.php" method='post'>
                    <td class="btnCell" colspan="1">
                        <?php
                            $classroomID = 1;
                            echo "<input type='hidden' name='studentID' value='$studentID'>";
                            echo "<input type='hidden' name='classroomID' value='$classroomID'>";
                        ?>
                        <input type="submit" class="button" id="cancelBtn" value="Behavior Graph"/>
                    </td>
            </form>
            <tr><td>
            <h1>Parents</h1>
            </td></tr>
            <?php
                $userID = $_SESSION["userID"];
                $studentID = $_POST["studentID"];
                $nQuery =
                "SELECT USER.firstName, USER.lastName, USER.email
                FROM USER, STUDENT_PARENT
                WHERE USER.userID = STUDENT_PARENT.parentID AND STUDENT_PARENT.studentID = $studentID";
                $records = $nConn->getQuery($nQuery);
                while($row = $records->fetch_array())
                {
                    $name = $row['firstName'] . " " . $row['lastName'];
                    $email = $row['email'];
                    echo "<tr><td>";
                    echo $name;
                    echo "</td></tr><tr><td>";
                    echo $email;
                    echo "</td></tr>";
                }
            ?>
        </table>
        </div>
    </div>
</div>
</body>
</html>