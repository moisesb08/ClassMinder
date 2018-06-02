<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ClassMinder - Student Profile</title>
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
        include_once('sidebar.php');
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
                    $student = new Student("","","");
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
                $isTeacher = $_SESSION["isTeacher"];
                if($isTeacher)
                {
                    $classesTitle = 'CLASSES';
                    $formAction = 'studentBehaviorAnalysis.php';
                    $nQuery =
                        "SELECT DISTINCT CLASSROOM.classroomID, CLASSROOM.title
                        FROM STUDENT
                        JOIN STUDENT_CLASS ON STUDENT_CLASS.studentID = STUDENT.studentID
                        JOIN CLASSROOM ON CLASSROOM.classroomID = STUDENT_CLASS.classroomID
                        WHERE CLASSROOM.userID = $userID AND CLASSROOM.title <> 'Unassigned Class' AND STUDENT_CLASS.studentID = $studentID;";
                }
                else
                {
                    $classesTitle = 'ANALYSIS BY CLASS';
                    $formAction = 'studentBehaviorAnalysis.php';
                    $nQuery =
                        "SELECT DISTINCT CLASSROOM.classroomID, CLASSROOM.title
                        FROM STUDENT
                        JOIN STUDENT_CLASS ON STUDENT_CLASS.studentID = STUDENT.studentID
                        JOIN CLASSROOM ON CLASSROOM.classroomID = STUDENT_CLASS.classroomID
                        WHERE CLASSROOM.title <> 'Unassigned Class' AND STUDENT_CLASS.studentID = $studentID;";
                }
                $records = $nConn->getQuery($nQuery);
                echo "<tr><td class='heading'><h1>$classesTitle</h1></td></tr>";
                while($row = $records->fetch_array())
                {
                    $classID = $row['classroomID'];
                    $title = $row['title'];
                    if($isTeacher)
                    {
                        echo "<form method='post' action='classroom.php'>";
                        echo "<input type='hidden' name='title' value='$title'>";
                        echo "<tr><td class='btnCell'><button type='submit' name='classroomID' formmethod='post' class='button' value=" . $classID . ">";
                        echo $title . "<br>";
                        echo "</td></button></tr>";
                        echo "</form>";
                    }
                    else
                    {
                        echo "<form method='post' action='studentBehaviorAnalysis.php'>";
                        echo "<input type='hidden' name='startDate' value='$startDate'>";
                        echo "<input type='hidden' name='teacherID' value='$teacherID'>";
                        echo "<input type='hidden' name='endDate' value='$endDate'>";
                        echo "<input type='hidden' name='classroomID' value='$classID'>";
                        echo "<tr><td class='btnCell'><button type='submit' name='studentID' formmethod='post' class='button' value=" . $studentID . ">";
                        echo "$title<br>";
                        echo "</td></button></tr>";
                        echo "</form>";
                    }
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
                        echo "<tr><th class='leftCol'>";
                        echo $name;
                        echo "</th></tr><tr><td class='leftCol'>";
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
                if(isset($_POST["classroomID"])&&$isTeacher)
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
            <h1>Most Recent Behaviors</h1>
            </td></tr>
            <?php
                $userID = $_SESSION["userID"];
                $studentID = $_POST["studentID"];
                if(isset($_POST["classroomID"])&&$isTeacher)
                {
                    $nQuery =
                        "SELECT BEHAVIOR.title
                        FROM BEHAVIOR
                        JOIN STUDENT_BEHAVIOR ON BEHAVIOR.behaviorID=STUDENT_BEHAVIOR.behaviorID
                        AND STUDENT_BEHAVIOR.studentID=$studentID
                        AND STUDENT_BEHAVIOR.classroomID=$classroomID
                        ORDER BY recordedTime DESC
                        LIMIT 4;";
                }
                else
                {
                    $nQuery =
                        "SELECT BEHAVIOR.title
                        FROM BEHAVIOR
                        JOIN STUDENT_BEHAVIOR ON BEHAVIOR.behaviorID=STUDENT_BEHAVIOR.behaviorID
                        AND STUDENT_BEHAVIOR.studentID=$studentID
                        ORDER BY recordedTime DESC
                        LIMIT 4;";
                }

                $records = $nConn->getQuery($nQuery);
                while($row = $records->fetch_array())
                {
                    $title = $row['title'];
                    echo "<tr><td class='rightCol'>";
                    echo $title . "<br>";
                    echo "</td></tr>";
                }
                if($isTeacher)
                {
                    echo '<tr>
                        <form action="linkParent.php" method="post">
                        <td class="btnCell" colspan="1">';
                        echo "<input type='hidden' name='studentID' value='$studentID'>";
                    echo '<div>
                        <input type="submit" class="submitBtn" name="submit" value="Link A Parent Account"/>
                                    </div>
                                </td>
                        </form>
                        </tr>
                        <tr>
                        <form action="delete.php" method="post">
                                <td class="btnCell" colspan="1">';
                    echo "<input type='hidden' name='studentID' value='$studentID'>";
                    echo '<div><input STYLE="background-color: red;" type="submit" id="cancelBtn" value="Delete Student"/></div>
                                </td>
                        </form>
                        </tr>';
                }
            ?>
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
<?php
    if($isTeacher)
        $params = "'$studentID', '$classroomID', '$startDate', '$endDate', '$classTitle'";
    else
        $params = "'$studentID', '', '$startDate', '$endDate', '$classTitle'";
?>
<script>beginSingleChart(<?php echo $params;?>);</script>
</html>