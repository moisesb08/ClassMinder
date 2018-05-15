<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ClassMinder - Classroom</title>
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/editSeatingChart.js"></script>
    <link rel="stylesheet" href="../css/editSeatingChart.css">
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <?php
        require_once('../common/connection.php');
        include_once('../model/User.php');
        include_once('../model/Student.php');
        include_once('../model/Classroom.php');
        // Initialize the session
        session_start();
        // If session variable is not set it will redirect to login page
        if(!isset($_SESSION['user']) || empty($_SESSION['user'])){
            header("location: ../view/loginPage.php");
            exit;
        }
        if(isset($_POST["classroomID"]))
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
        <script>
        init();
        </script>
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
            <h1><?php echo $_POST['title']; ?></h1>
            <?php
                $userID = $_SESSION["userID"];
                $classroomID = $_POST["classroomID"];
                $title = $_POST["title"];
                $nQuery =
                "SELECT max(STUDENT_CLASS.x) AS xMax, max(STUDENT_CLASS.y) AS yMax, count(STUDENT_CLASS.studentID) AS totalStudents
                FROM STUDENT
                JOIN STUDENT_CLASS ON STUDENT_CLASS.studentID = STUDENT.studentID
                JOIN CLASSROOM ON CLASSROOM.classroomID = STUDENT_CLASS.classroomID
                WHERE CLASSROOM.userID = $userID AND CLASSROOM.classroomID = $classroomID";
                $result = $nConn->getQuery($nQuery);
                $row = $result->fetch_row();
                $xMax = (int)$row["xMax"];
                $yMax = (int)$row["yMax"];
                $newX = max(1, $xMax);
                $newY = max(1, $yMax);
                $totalStudents = max(1, $row["totalStudents"]);
                while($newX*$newY < max(16, $totalStudents) || min($newX, $newY) >=10)
                {
                    if(min($newX, $newY)==$newX)
                    {
                        $newX++;
                    }
                    else
                    {
                        $newY++;
                    }
                }
                $nQuery =
                "SELECT STUDENT.firstName, STUDENT.lastName, STUDENT.studentID, STUDENT_CLASS.x, STUDENT_CLASS.y
                FROM STUDENT
                JOIN STUDENT_CLASS ON STUDENT_CLASS.studentID = STUDENT.studentID
                JOIN CLASSROOM ON CLASSROOM.classroomID = STUDENT_CLASS.classroomID
                WHERE CLASSROOM.userID = $userID AND CLASSROOM.classroomID = $classroomID ORDER BY STUDENT_CLASS.x, STUDENT_CLASS.y";
                $records = $nConn->getQuery($nQuery);
                echo "<form method='post' action='studentProfile.php'>";
                echo "<table>";
                $count = 0;
                for($y=0; $y<$newY; $y++)
                {
                    echo "<tr>";
                    for($x=0; $x<$newX; $x++)
                    {
                        $count++;
                        echo "<td>";
                        if($row = $records->fetch_array())
                        {
                            echo '
                            <div id="x'.$x.'y'.$y.'" class="divCell" ondrop="drop(event)" ondragover="allowDrop(event)">
                                <button value="'.$row['studentID'].'" type="button" ondragstart="drag(event)" draggable="true" id="btn'.$count.'"><i class="ion-android-person"></i><br>
                                '.$row['firstName'].'<br>'.$row['lastName'].'<br>ID: '.$row['studentID'].'</button>
                            </div>
                            ';
                        }
                        else
                        {
                            echo '
                            <div id="x'.$x.'y'.$y.'" class="divCell" ondrop="drop(event)" ondragover="allowDrop(event)">
                            </div>
                            ';
                        }
                        echo "</td>";
                    }
                    echo "</tr>";
                }
                echo "</table></form>";
                echo "<form method='post' action=''>";
                echo "<input type='hidden' name='title' value='$title'>";
                echo "<tr><td class='btnCell'><div class='btnPlus'><button type='submit' name='classroomID' formmethod='post' class='button' value=" . $classroomID . ">";
                echo "<span>Save</span></td></button></div></tr>";
                echo "</form><br>";
                echo "<form method='post' action='classroom.php'>";
                echo "<input type='hidden' name='title' value='$title'>";
                echo "<tr><td class='btnCell'><div class='btnBehaviors'><button type='submit' name='classroomID' formmethod='post' class='button' value=" . $classroomID . ">";
                echo "<span>Cancel</span></td></button></div></tr>";
                echo "</form>";
            ?>
        </div>
    </div>
</div>
</body>
</html>