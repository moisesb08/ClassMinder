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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ClassMinder - Create Student</title>
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/teacherHome.js"></script>
    <link rel="stylesheet" href="../css/studentList.css">
    <link rel="stylesheet" href="../css/chart.css">
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
        $nConn = new Connection();

        if(isset($_POST["studentID"]))
        {
            $_SESSION['post'] = $_POST;
        }
        else
            $_POST = $_SESSION['post'];

        $saved = false;
        $teacherLastName = ucwords($_SESSION['lastName']);
        if(isset($_POST["studentID"]))
        {
            if(!isset($_POST["classroomID"]))
            {
                $byDates = true;
                $startDate = $_POST["startDate"];
                $endDate = $_POST["endDate"];
                $studentID = $_POST["studentID"];
                $classroomID = '';
                $classTitle = '';
                $student = new Student("","");
                $student->loadByID($studentID);
                $firstName = ucwords($student->getFirstName());
                $lastName = ucwords($student->getLastName());
                $teacherLastName = ucwords($_SESSION['lastName']);
                $teacherNote = '';
                $saved = true;
            }
            else if(isset($_POST["startDate"])&&isset($_POST["endDate"]))
            {
                //pass start and end date
                $byDates = true;
                $startDate = $_POST["startDate"];
                $endDate = $_POST["endDate"];
                $studentID = $_POST["studentID"];
                $classroomID = $_POST["classroomID"];
                $classroom = new Classroom("","","");
                $classroom->loadByID($classroomID);
                $classTitle = ucwords($classroom->getTitle());
                $student = new Student("","");
                $student->loadByID($studentID);
                $firstName = ucwords($student->getFirstName());
                $lastName = ucwords($student->getLastName());
                $teacherLastName = ucwords($_SESSION['lastName']);
                $teacherNote = ""; //$_POST['teacherNote']
                $saved = true;
            }
            else if(isset($_POST['weeks']))
            {
                //pass n weeks
                $byWeeks = true;
                
                $d=strtotime("today");
                $startDate = date("Y-m-d", $d);
                if(isset($_POST['weeks']))
                    $weeks = $_POST['weeks'];
                else
                    $weeks = 2;
                $days = 7*$weeks;
                $endDate = date( 'Y-m-d', strtotime( $startDate . ' -'.($days-1).' day' ) );
                $studentID = $_POST["studentID"];
                $classroomID = $_POST["classroomID"];
                $classroom = new Classroom("","","");
                $classroom->loadByID($classroomID);
                $classTitle = ucwords($classroom->getTitle());
                $student = new Student("","");
                $student->loadByID($studentID);
                $firstName = ucwords($student->getFirstName());
                $lastName = ucwords($student->getLastName());
                $teacherLastName = ucwords($_SESSION['lastName']);
                $saved = true;
            }
        }
        else
        {
            header("location: ../php/teacherHome.php");
        }
        if(!$saved)
        {
            $startDate = '2018-05-04';
            $endDate = '2018-05-25';
            $studentID = 2;
            $classroomID = 10;
            $classTitle = 'Algebra 2';
            $firstName = 'fName';
            $lastName = 'lName';
            $teacherLastName = ucwords($_SESSION['lastName']);
            $teacherNote = "";//$_POST['teacherNote']
        }
        ?>
</head>
<body>
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
        <div class="charts">
        <form id="Form" method="post" action="analysisPrint.php" target="_new">
        <input type="hidden" name="startDate" value="<?php echo $startDate;?>" />
        <input type="hidden" name="endDate" value="<?php echo $endDate;?>" />
        <input type="hidden" name="studentID" value="<?php echo $studentID;?>" />
        <input type="hidden" name="classroomID" value="<?php echo $classroomID;?>" />
        <input type="hidden" name="firstName" value="<?php echo $firstName;?>" />
        <input type="hidden" name="lastName" value="<?php echo $lastName;?>" />
        <input type="hidden" name="teacherLastName" value="<?php echo $teacherLastName;?>" />
        <input type="hidden" name="teacherNote" value="<?php echo $teacherNote;?>" />
        <input type="hidden" name="<?php if(isset($_POST['classroomID'])) echo'classTitle';?>" value="<?php echo $classTitle;?>" />
        </form>
        <button onclick="printerFriendly()"><span><i class="ion-android-print"></i></span>&nbsp;Printer Friendly Version</button>
        <script>
        function printerFriendly() {
            var f = document.getElementById('Form');
            window.open('', '_new', "toolbar=yes,scrollbars=yes,resizable=yes,top=10,left=10,width=1100,height=700");
            f.submit();
            //window.open("analysisPrint.php", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=10,left=10,width=1100,height=700");
        }
        </script><br><br>
            <table>
            <tr>
                <td class='heading' colspan="4"><h2>Behavior Analysis Report</h2></td>
            </tr>
            <tr>
                <th class="heading" colspan='4'><?php echo $firstName . ' ' . $lastName;?></th>
            </tr>
            <tr>
                <td colspan='4'><?php echo date("l, F d Y", strtotime($startDate)); ?> - <?php echo date("l, F d Y", strtotime($endDate)); ?></td>
            </tr>
            <?php
             if(isset($_POST['classroomID']))
             {
                echo "<tr><td class='right'>Teacher:</td>
                <td class='left' colspan='3'>$teacherLastName</td></tr><tr>
                <td class='right'>Class:</td>
                <td class='left' colspan='3'>$classTitle</td></tr><tr>
                <td class='right'>Teacher Note:</td>
                <td class='left' colspan='3'>$teacherNote</td></tr>";
             }
            ?>
            <tr>
            <td colspan='2' class='topCell'><div class="graph">
                <canvas id="chart1" height="140" width="140" style="margin: 15px 10px 10px 0"></canvas>
            </div>
            </td><td colspan='2' class="topCell">
            <div class="graph">
                <canvas id="chart2" height="140" width="140" style="margin: 15px 10px 10px 0"></canvas>
            </div>
            </td>
            </tr>
            <tr>
            <td colspan='2' class='topCell'><div class="graph">
                <canvas id="chart3" height="140" width="140" style="margin: 15px 10px 10px 0"></canvas>
            </div>
            </td><td colspan='2' class="topCell">
            <div class="graph">
                <canvas id="chart4" height="140" width="140" style="margin: 15px 10px 10px 0"></canvas>
            </div>
            </td>
            </tr>
            <tr>
            <td colspan='2' class='topCell'><div class="graph">
                <canvas id="chart5" height="140" width="140" style="margin: 15px 10px 10px 0"></canvas>
            </div>
            </td><td colspan='2' class="topCell">
            <div class="graph">
                <canvas id="chart6" height="140" width="140" style="margin: 15px 10px 10px 0"></canvas>
            </div>
            </td>
            </tr>
            <tr>
            <td class='topCell' colspan="4"><div class="graph3">
                <canvas id="chart7" height="80" width="140" style="margin: 15px 10px 10px 0"></canvas>
            </div>
            </td>
            </tr>
            </table>
        </div>
    </div>
</body>
<script>begin(<?php echo "'$startDate', '$endDate', '$studentID', '$classroomID', '$firstName', '$lastName', '$classTitle'";?>);</script>
</html>