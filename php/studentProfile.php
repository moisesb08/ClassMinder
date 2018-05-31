<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ClassMinder - Student</title>
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/teacherHome.js"></script>
    <link rel="stylesheet" href="../css/studentProfile.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
    <script src="../js/createGraph.js"></script>
    <script src="../js/Chart.PieceLabel.min.js"></script>
    <link rel="stylesheet" href="../resources/ionicons-2.0.1/css/ionicons.min.css">
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
                    <td>

 <table>
            <tr>
                <td class="name">
                <?php
                    //get start and end dates
                    $d=strtotime("20 DAYS ago");
                    $startDate = date("Y-m-d", $d);
                    if(isset($_POST['weeks']))
                        $weeks = $_POST['weeks'];
                    else
                        $weeks = 3;
                    $days = 7*$weeks;
                    $endDate = date( 'Y-m-d', strtotime( $startDate . ' +'.($days-1).' day' ) );
                    
                    //get student info
                    $studentID = $_POST["studentID"];
                    $student = new Student("","");
                    $student->loadByID($studentID);
                    echo '<div class="personIcon"><i class="ion-person"></i></div>';
                    echo $student->getFirstName() . " " . $student->getLastName();
                ?>
                </td>
            </tr>
        </table>
                    </td>
                    <td colspan='2' class='rightCol'><div class="graph">
                            <canvas id="chart1" height="140" width="140" style="margin: 15px 10px 10px 0"></canvas>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
<table>
            <?php
                $userID = $_SESSION["userID"];
                $studentID = $_POST["studentID"];
                $title = $_POST["title"];
                $classroomID = $_POST["classroomID"];
                $nQuery =
                "SELECT DISTINCT CLASSROOM.classroomID, CLASSROOM.title
                FROM STUDENT
                JOIN STUDENT_CLASS ON STUDENT_CLASS.studentID = STUDENT.studentID
                JOIN CLASSROOM ON CLASSROOM.classroomID = STUDENT_CLASS.classroomID
                WHERE CLASSROOM.userID = $userID AND CLASSROOM.title <> 'Unassigned Class' AND STUDENT_CLASS.studentID = $studentID;";
                $records = $nConn->getQuery($nQuery);
                echo "<tr><td class='heading'><h1>Classes</h1></td></tr>";
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
            <tr><td class="heading">
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
                if(!empty($records))
                {
                    while($row = $records->fetch_array())
                    {
                        $name = $row['firstName'] . " " . $row['lastName'];
                        $email = $row['email'];
                        echo "<tr><td class='leftCol'>";
                        echo $name;
                        echo "</td></tr><tr><td class='leftCol'>";
                        echo $email;
                        echo "</td></tr>";
                    }
                }
            ?>
        </table>
                    </td>
                    <td class="rightCol">
            <table>
            <?php
                echo "<form method='post' action='studentBehaviorAnalysis.php'>";
                echo "<input type='hidden' name='startDate' value='$startDate'>";
                echo "<input type='hidden' name='endDate' value='$endDate'>";
                echo "<tr><td class='btnCell'><button type='submit' name='studentID' formmethod='post' class='button' value=" . $studentID . ">";
                echo "See Full Analysis<br>";
                echo "</td></button></tr>";
                echo "</form>";
                if(isset($_POST["classroomID"]))
                {
                    echo "<form method='post' action='studentBehaviorAnalysis.php'>";
                    echo "<input type='hidden' name='startDate' value='$startDate'>";
                    echo "<input type='hidden' name='endDate' value='$endDate'>";
                    echo "<input type='hidden' name='classroomID' value='$classroomID'>";
                    echo "<tr><td class='btnCell'><button type='submit' name='studentID' formmethod='post' class='button' value=" . $studentID . ">";
                    echo "Student Class Analysis<br>";
                    echo "</td></button></tr>";
                    echo "</form>";
                }
            ?>
            <tr>
            <td class="heading rightCol">
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
                    echo "<tr><td class='rightCol'>";
                    echo $title . "<br>";
                    echo "</td></tr>";
                }
            ?>
            <tr>
            <form action="linkParent.php" method='post'>
                    <td class="btnCell" colspan="1">
                        <?php
                            echo "<input type='hidden' name='studentID' value='$studentID'>";
                        ?>
                        <div>
                            <input type="submit" class="submitBtn" name="submit" value="Link A Parent Account"/>
                        </div>
                    </td>
            </form>
            </tr>
            <tr>
            <form action="delete.php" method='post'>
                    <td class="btnCell" colspan="1">
                        <?php
                            echo "<input type='hidden' name='studentID' value='$studentID'>";
                        ?>
                        <div>
                            <input STYLE="background-color: red;" type="submit" id="cancelBtn" value="Delete Student"/>
                        </div>
                    </td>
            </form>
        </tr>
        </table>
        <?php
                if(isset($_POST["classroomID"]))
                {
                    $classroom = new Classroom("","","");
                    $classroom->loadByID($classroomID);
                    $classTitle = $classroom->getTitle();
                }
                else
                {
                    $classTitle = '';
                }
        ?>
        </div>
    </div>
</div>
</body>
<script>beginSingleChart(<?php echo "'$studentID', '$classroomID', '$startDate', '$endDate', '$classTitle'";?>);</script>
</html>