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
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $studentID = $_POST['studentID'];
        $classroomID = $_POST['classroomID'];
        $classTitle = $_POST['classTitle'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $teacherLastName = $_POST['teacherLastName'];
        $teacherNote = $_POST['teacherNote'];
    ?>
</head>
    <div class="charts">
        <table>
        <tr>
            <td><img src="../resources/images/templogoAtransparent.png" height="50px"></td><td class='heading left' colspan="3"></td>
        </tr>
        <tr>
            <th class="heading" colspan='4'><h2>Behavior Analysis Report</h2><?php echo $firstName." ".$lastName;?></th>
        </tr>
        <tr>
            <td colspan='4'><?php echo date("l, F d Y", strtotime($startDate)); ?> - <?php echo date("l, F d Y", strtotime($endDate)); ?></td>
        </tr>
        <?php
            if(isset($_POST['classTitle']))
                echo "<tr>
            <td class='right'>Teacher:</td>
            <td class='left' colspan='3'>$teacherLastName</td>
        </tr>
        <tr>
            <td class='right'>Class:</td>
            <td class='left' colspan='3'>$classTitle</td>
        </tr>
        <tr>
            <td class='right'>Teacher Note:</td>
            <td class='left' colspan='3'>$teacherNote</td>
        </tr>
        <tr>"
        ?>
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
            <td class='heading' colspan="4"></td>
        </tr>
        <tr>
            <td class='heading' colspan="4"></td>
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
</body>
<script>begin(<?php echo "'$startDate', '$endDate', '$studentID', '$classroomID', '$firstName', '$lastName'";?>);</script>
</html>