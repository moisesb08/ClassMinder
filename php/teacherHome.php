<?php
        require_once('../common/connection.php');
        include_once('../model/User.php');
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
            if($_SESSION['isTeacher'] == 0)
                header("location: parentHome.php");
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ClassMinder Teacher</title>
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/teacherHome.js"></script>
    <link rel="stylesheet" href="../css/teacherHome.css">
    <link rel="stylesheet" href="../resources/ionicons-2.0.1/css/ionicons.min.css">
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
        <table>
        <tr><td class="item1" colspan='1'><span><i class="ion-person"></i></span></td></tr>
        <tr><td class="item5" colspan='1'><span>Welcome, <?php echo $_SESSION['firstName']." ".$_SESSION['lastName'];?>.</span></td></tr>
        <?php
            $userID = $_SESSION["userID"];
            $nQuery =
            "SELECT COUNT(DATE(meetingTime)) todayMeeting
            FROM MEETING
            WHERE teacherID = $userID AND DATE(meetingTime) = CURDATE()
            GROUP BY DATE(meetingTime);";
            $records = $nConn->getQuery($nQuery);
            if (mysqli_num_rows($records)==0)
                    echo "<tr><td class=\"item5\" colspan=\'1\'><span>You have no parent meetings today.</span></td></tr>";
            else
            {
                $row = $records->fetch_array();
                $todayMeeting = $row['todayMeeting'];
                echo "<tr><td class=\"item5\" colspan=\'1\'><span>You have $todayMeeting meeting";
                if ($todayMeeting > 1)
                    echo "s";
                echo " today.</span></td></tr>";
            }
        ?>
        <tr><td class="btnCell" width="100px" colspan="1">
        <button type="button" class="button" onclick="window.location.href='studentList.php'">
        <span class="item5"><i class="ion-ios-people-outline"></i></span><span>&nbsp;Students</span></button></td></tr>
        <tr><td class="btnCell" colspan="1">
        <button type="button" class="button" onclick="window.location.href='classList.php'">
        <span class="item5"><i class="ion-university"></i></span><span>&nbsp;Classes</span></button></td></tr>
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