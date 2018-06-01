<?php
    require_once('../common/connection.php');
    include_once('../model/User.php');
    include_once('sidebar.php');
    // Initialize the session
    session_start();
    // If session variable is not set it will redirect to login page
    if(!isset($_SESSION['user']) || empty($_SESSION['user'])){
        header("location: ../view/loginPage.php");
        exit;
    }
    if($_SESSION['isTeacher'] == 1)
        header("location: teacherHome.php");
    $nConn = new Connection();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ClassMinder Parent</title>
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/parentHome.js"></script>
    <link rel="stylesheet" href="../css/teacherHome.css">
    <link rel="stylesheet" href="../resources/ionicons-2.0.1/css/ionicons.min.css">
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
        <tr><td class="item1" colspan='1'><span><i class="ion-person"></i></span></td></tr>
        <tr><td class="item5" colspan='1'><span>Welcome, <?php echo $_SESSION['firstName']." ".$_SESSION['lastName'];?>.</span></td></tr>
        <tr><td class="btnCell" width="100px" colspan="1">
        <?php
            $userID = $_SESSION["userID"];
            $nQuery =
            "SELECT COUNT(DATE(meetingTime)) todayMeeting
            FROM MEETING
            WHERE parentID = $userID AND DATE(meetingTime) = CURDATE()
            GROUP BY DATE(meetingTime);";
            $records = $nConn->getQuery($nQuery);
            if (mysqli_num_rows($records)!=0)
            {
                $row = $records->fetch_array();
                $todayMeeting = $row['todayMeeting'];
                echo "<tr><td class='item5' colspan='1'><span>You have a meeting today!</span></td></tr>";
            }
        ?>
        <button type="button" class="button" onclick="window.location.href='studentList.php'">
        <span class="item5"><i class="ion-ios-people-outline"></i></span><span>&nbsp;Students</span></button></td></tr>
        <tr><td class="btnCell" colspan="1">
        <button type="button" class="button" onclick="window.location.href='meetings.php'">
        <span class="item5"><i class="ion-android-calendar"></i></span><span>&nbsp;Meetings</span></button></td></tr>
        <tr><td class="btnCell" colspan="1">
        <button type="button" class="button" onclick="window.location.href='resources.php'">
        <span class="item5"><i class="ion-ios-bookmarks-outline"></i></span><span>&nbsp;Resources</span></button></td></tr>
        <tr><td class="btnCell" colspan="1">
        <button type="button" class="button" onclick="window.location.href='help.php'">
        <span class="item5"><i class="ion-help"></i></span><span>&nbsp;Help</span></button></td></tr>
        </table>
        
        </div>
    </div>
</body>
</html>