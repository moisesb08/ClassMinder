<!DOCTYPE html>
<head>
    <title>Parent Meetings</title>
    <link href="../css/register.css" text="text/css" rel="stylesheet"/>
    <link href="../css/teacherHome.css" text="text/css" rel="stylesheet"/>
    <link rel="stylesheet" href="../resources/ionicons-2.0.1/css/ionicons.min.css">
    <title>ClassMinder - Behavior Analysis</title>
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script>
        function isConfirmed(message)
        {
            return confirm(message);
        }
    </script>
    <?php
        require_once('../common/connection.php');
        include_once('../model/User.php');
        include_once('sidebar.php');

        // Initialize the session
        session_start();

        // If session variable is not set it will redirect to login page
        if(!isset($_SESSION['user']) || empty($_SESSION['user']))
        {
            header("location: ../view/loginPage.php");
            exit;
        }

        // If user is a parent he/she will be redirected to the parent homepage
        if($_SESSION['isTeacher'] == 1)
            header("location: meetings.php");
        $userID = $_SESSION['userID'];
        $nConn = new Connection();
    ?>
</head>
<body>
<?php
        if($_SESSION["isTeacher"])
            echo teacherSidebar();
        else
            echo parentSidebar();  
        $d=strtotime("today");
            $startDate = date("Y-m-d", $d);
        
        if(isset($_POST["meetingID"]))
        {
            // Cancel meeting
            $meetingID = $_POST["meetingID"];
            $meetingSlotID = $_POST["meetingSlotID"];
            $nQuery = "DELETE FROM MEETING WHERE meetingID=$meetingID;";
            $nConn->getQuery($nQuery);
            $nQuery = "UPDATE MEETING_SLOT SET isAvailable='1' WHERE meetingSlotID=$meetingSlotID";
            $nConn->getQuery($nQuery);
        }
        else if(isset($_POST["meetingSlotID"]))
        {
            // Create meeting
            $description = $_POST["description"];
            $description = $nConn->sanitize($description);
            $meetingSlotID = $_POST["meetingSlotID"];
            $nQuery = "INSERT INTO MEETING(description, minutes, meetingTime, teacherID, parentID, location)
                SELECT '$description', minutes, meetingTime, teacherID, $userID, location
                FROM MEETING_SLOT WHERE meetingSlotID=$meetingSlotID;";
            $nConn->getQuery($nQuery);
            $nQuery = "UPDATE MEETING_SLOT SET isAvailable='0' WHERE meetingSlotID=$meetingSlotID";
            $nConn->getQuery($nQuery);
        }

?>
<div class="div1">
    <div class="midContainer">
    <table>
        <tr>
            <td><h3>Meetings</h3></td>
        </tr> 
    </table>
    <div id='slotsDiv'>
    <table>
<?php
    $data = "";
    $nQuery =
        "SELECT meetingID, description, MEETING.minutes, MEETING.meetingTime,
        MEETING.teacherID, MEETING.location, firstName, lastName, meetingSlotID
        FROM MEETING
        JOIN MEETING_SLOT
        ON MEETING.teacherID=MEETING_SLOT.teacherID
        AND MEETING.meetingTime=MEETING_SLOT.meetingTime
        JOIN USER
        ON MEETING.teacherID=USER.userID
        WHERE MEETING.meetingTime > timestamp(current_date)
        AND parentID = $userID;";
    $records = $nConn->getQuery($nQuery);
    
    if (mysqli_num_rows($records)==0)
        echo "<tr><td colspan='6'>No upcoming meetings</td></tr>";
    else
    {
        echo "<tr><th>Meeting Date</th><th>Meeting Time</th><th>Length</th><th>Meeting Location</th><th>Teacher</th><th width='200px'>Description</th><th></th>";
        while($row = $records->fetch_array())
        {
            echo "<form method='post' action='' ";
            echo 'onsubmit="';
            echo "return isConfirmed('Are you sure you want to cancel the meeting?')";
            echo '"><tr><td>';
            $minutes = $row['minutes'];
            $meetingID = $row['meetingID'];
            $meetingSlotID = $row['meetingSlotID'];
            $meetingDateTime = $row['meetingTime'];
            $formatDate = new DateTime($meetingDateTime);
            $meetingDay = $formatDate->format('m-d-Y');
            $meetingTime = $formatDate->format('h:i a');
            echo $meetingDay;
            echo "</td><td>";
            echo $meetingTime;
            echo "</td><td>";
            echo $minutes;
            echo " minutes</td><td>";
            echo $row['location'];
            echo "</td><td>";
            echo $row['lastName'];
            echo "</td>";
            echo "</td><td class='leftAlign'>";
            echo $row['description'];
            echo "</td>";
            echo "<td>";
            echo "<input type='hidden' name='meetingID' value='$meetingID'>";
            echo "<input type='hidden' name='meetingSlotID' value='$meetingSlotID'>";
            echo '<input type="submit" ';
            echo 'class="selectBtn" value="Cancel"/>';
            echo "</td></tr></form>";
        }
    }
?>
    </table> 
    </div>
    <table>       
            <tr>
                <td class="btnCell" colspan="4">
                    <button type="button" class="button" onclick="window.location.href='scheduleMeeting.php'">Schedule a meeting</button>
                </td>
            </tr>
    </table>
    </div>
</div>
</body>
</html>