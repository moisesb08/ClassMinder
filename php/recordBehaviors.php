<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ClassMinder - Record Behaviors</title>
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/teacherHome.js"></script>
    <link rel="stylesheet" href="../css/recordBehaviors.css">
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
            echo "<form name='goToForm' method='post' action='classroom.php'>";
            echo "<input type='hidden' name='title' value='$title'>";
            echo "<input type='hidden' name='classroomID' value='$classroomID'>";
            echo "</form>";
            echo "<script>document.goToForm.submit();</script>";
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
        if(isset($_POST["submitted"])&&(isset($_POST["posBehaviors"])||isset($_POST["negBehaviors"])))
        {
            $behaviors = array();
            if(isset($_POST["posBehaviors"]))
            {
                foreach($_POST["posBehaviors"] as $posBehavior)
                {
                    array_push($behaviors, $posBehavior);
                }
            }
            if(isset($_POST["negBehaviors"]))
            {
                foreach($_POST["negBehaviors"] as $negBehavior)
                {
                    array_push($behaviors, $negBehavior);
                }
            }

            foreach($_POST["students"] as $student_ID)
            {
                foreach($behaviors as $behavior_ID)
                {
                    $classroomID = $_POST["classroomID"];
                    $title = $_POST["title"];
                    $str="INSERT INTO STUDENT_BEHAVIOR (studentID, behaviorID) values ($student_ID,$behavior_ID)";
                    //echo $str;
                    $nConn->getQuery($str);
                    //testing dialog
                    /*echo'<script>';
                    echo'function myFunction() {
                        alert("'.$title.":".$classroomID.'");
                    }
                    myFunction();
                    </script>';*/
                    echo "<form name='goToForm' method='post' action='behaviorSuccess.php'>";
                    echo "<input type='hidden' name='title' value='$title'>";
                    echo "<input type='hidden' name='classroomID' value='$classroomID'>";
                    echo "</form>";
                    echo "<script>document.goToForm.submit();</script>";
                }
            }
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
            <?php
                
                $userID = $_SESSION["userID"];
                $classroomID = $_POST["classroomID"];
                $title = $_POST["title"];
                // Display students
                $nQuery =
                "SELECT STUDENT.firstName, STUDENT.lastName, STUDENT.studentID
                FROM STUDENT
                JOIN STUDENT_CLASS ON STUDENT_CLASS.studentID = STUDENT.studentID
                JOIN CLASSROOM ON CLASSROOM.classroomID = STUDENT_CLASS.classroomID
                WHERE CLASSROOM.userID = $userID AND CLASSROOM.classroomID = $classroomID";
                $records = $nConn->getQuery($nQuery);
                echo "<div class='ss'><span>SELECT STUDENTS</span></div>";
                echo "<form method='post' action=''>";
                while($row = $records->fetch_array())
                {
                    echo "
                    <label class='container'>".$row["firstName"] . " " . $row["lastName"]."<br>ID: ".$row['studentID'].
                    "<input type='checkbox' name='students[]' value='". $row['studentID'] ."'>
                    <span class='checkmark checkmarkStudent'></span>
                    </label>";
                }

                // Display Positive Behaviors
                $nQuery =
                "SELECT * FROM BEHAVIOR WHERE userID=$userID OR userID=0 HAVING isPositive=1";
                $records = $nConn->getQuery($nQuery);
                echo "<div class='positive'><span>SELECT POSITIVE BEHAVIORS</span></div>";
                while($row = $records->fetch_array())
                {
                    echo "
                    <label class='container'>".$row["title"].
                    "<input type='checkbox' name='posBehaviors[]' value='". $row['behaviorID'] ."'>
                    <span class='checkmark'></span>
                    </label>";
                }
                                echo "
                    <label class='container'>Other:".
                    "<input type='checkbox' name='addPositive' value='1'>
                    <span class='checkmark'></span>
                    </label>
                    <label class='container others'>Title:<br>".
                    "<input type='text' placeholder='Title' id='positiveTitle' name='positiveTitle'>
                    </label>
                    <br>
                    <label class='container'>Description:<br><textarea  id='positiveDescription' name='positiveDescription' rows='4' cols='50' placeholder='Enter Description Here'></textarea></label>";
                // Display Negative Behaviors
                $nQuery =
                "SELECT * FROM BEHAVIOR WHERE userID=$userID OR userID=0 HAVING isPositive=0";
                $records = $nConn->getQuery($nQuery);
                echo "<div class='negative'><span>SELECT NEGATIVE BEHAVIORS</span></div>";
                while($row = $records->fetch_array())
                {
                    echo "
                    <label class='container'>".$row["title"].
                    "<input type='checkbox' name='negBehaviors[]' value='". $row['behaviorID'] ."'>
                    <span class='checkmark checkmarkRed'></span>
                    </label>";
                }
                echo "
                    <label class='container'>Other:".
                    "<input type='checkbox' name='addNegative' value='1'>
                    <span class='checkmark checkmarkRed'></span>
                    </label>
                    <label class='container others'>Title:<br>".
                    "<input type='text' placeholder='Title' id='negativeTitle' name='negativeTitle'>
                    </label>
                    <br>
                    <label class='container'>Description:<br><textarea  id='negativeDescription' name='negativeDescription' rows='4' cols='50' placeholder='Enter Description Here'></textarea></label>";
                echo "<input type='hidden' name='submitted' value='1'>";
                echo "<tr><td class='btnCell'><button type='button' name='title' formmethod='post' class='button' value='$title'>";
                echo "<span></td></button></tr>";
                echo "<input type='hidden' name='title' value='$title'>";
                echo "<tr><td class='btnCell'><div class='btnBehaviors'><button type='submit' name='classroomID' formmethod='post' class='button' value=" . $classroomID . ">";
                echo "<span>Submit</span></td></button></div></tr>";
                echo "</form>";
            ?>
        </table>
        </div>
    </div>
</div>
</body>
</html>