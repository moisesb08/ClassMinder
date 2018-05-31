<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ClassMinder - Your Meetings</title>
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/teacherHome.js"></script>
    <link rel="stylesheet" href="../css/meetings.css">
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
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
                <td colspan='6'><h1>Your Meetings</h1></td>
            </tr>
            <?php
                $userID = $_SESSION["userID"];
                $nQuery =
                "SELECT MEETING_SLOT.meetingSlotID, MEETING_SLOT.teacherID, MEETING_SLOT.meetingTime, MEETING_SLOT.location, MEETING.parentID, MEETING.description, USER.firstName, USER.lastName
                FROM MEETING_SLOT
                LEFT JOIN MEETING
                ON MEETING_SLOT.meetingTime = MEETING.meetingTime
                LEFT JOIN USER
                ON MEETING.parentID = USER.userID
                WHERE MEETING_SLOT.teacherID = $userID
                ORDER BY MEETING_SLOT.meetingTime;";
                $records = $nConn->getQuery($nQuery);
                if (mysqli_num_rows($records)==0)
                    echo "<tr><td colspan='6'>No meeting slots created</td></tr>";
                else
                    echo "<tr><th>Meeting Date</th><th>Meeting Time</th><th>Meeting Location</th><th>Parent</th><th>Description (Added by Parent)</th><th>Delete</th>";
                while($row = $records->fetch_array())
                {
                    echo "<form method='post' action='delete.php'>";
                    echo "<tr><td>";
                    $meetingSlotID = $row['meetingSlotID'];
                    $meetingDateTime = $row['meetingTime'];
                    $formatDate = new DateTime($meetingDateTime);
                    $meetingDay = $formatDate->format('m-d-Y');
                    $meetingTime = $formatDate->format('h:i a');
                    echo $meetingDay;
                    echo "</td><td>";
                    echo $meetingTime;
                    echo "</td><td>";
                    echo $row['location'];
                    echo "</td><td>";
                    if (is_null($row['parentID']))
                    {
                        echo "Available";
                    }
                    else
                    {
                        echo $row['firstName'] . " " . $row['lastName'];
                    }
                    echo "</td><td class=\"description\">";
                    if (is_null($row['description']))
                    {
                        echo "";
                    }
                    else
                    {
                        echo $row['description'];
                    }
                    echo "</td><td>";
                    echo "<input type='hidden' name='meetingSlotID' value='$meetingSlotID'>";
                    echo "<input STYLE=\"background-color: red;\" type=\"submit\" id=\"cancelBtn\" value=\"Delete Meeting\"/>";
                    echo "</td></tr>";
                    echo "</form>";
                }
            ?>
            <tr><td colspan='6' class='btnCell' style='<?php if($_SESSION['isTeacher'] == 0) echo "display:none";?>'><button onclick="window.location.href='./addMeetingSlot.php'"><span><i class="ion-plus-round"></i></span></button></td></tr>
        </table>
        </div>
    </div>
</body>
</html>