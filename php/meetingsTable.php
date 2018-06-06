<?php
    require_once('../common/connection.php');

    function createMeetingSlotTable($parentID, $startDate, $endDate)
    {
        $nConn = new Connection();
        $data = "";
        $nQuery =
        "SELECT meetingSlotID, meetingTime, minutes, teacherID, location FROM MEETING_SLOT WHERE MEETING_SLOT.teacherID
        IN (SELECT DISTINCT userID FROM STUDENT_CLASS JOIN CLASSROOM on STUDENT_CLASS.classroomID=CLASSROOM.classroomID AND STUDENT_CLASS.studentID
        IN (SELECT studentID FROM STUDENT_PARENT WHERE parentID = $parentID)
        GROUP BY STUDENT_CLASS.classroomID)
        AND date(meetingTime) BETWEEN '$startDate' AND '$endDate' AND isAvailable = 1;";
        $records = $nConn->getQuery($nQuery);
        if (mysqli_num_rows($records)==0)
            $data .= "<tr><td colspan='6'>No meeting slots available</td></tr>";
        else
        {
            $data .= "<tr><th>Meeting Date</th><th>Meeting Time</th><th>Length</th><th>Meeting Location</th><th></th>";
            while($row = $records->fetch_array())
            {
                $data .= "<tr><td>";
                $minutes = $row['minutes'];
                $meetingSlotID = $row['meetingSlotID'];
                $meetingDateTime = $row['meetingTime'];
                $formatDate = new DateTime($meetingDateTime);
                $meetingDay = $formatDate->format('m-d-Y');
                $meetingTime = $formatDate->format('h:i a');
                $data .= $meetingDay;
                $data .= "</td><td>";
                $data .= $meetingTime;
                $data .= "</td><td>";
                $data .= $minutes;
                $data .= " minutes</td><td>";
                $data .= $row['location'];
                $data .= "</td>";
                $data .= "<td>";
                $data .= "<form method='post' action='meetingsParent.php'> <input type='hidden' name='meetingSlotID' value='$meetingSlotID'>";
                $data .= "<input type='submit' name='description' onclick='return getDescription(this)' onmouseover='overBtn(this)' onmouseout='outBtn(this)' class='selectBtn' value='Select Meeting'/></form>";
                $data .= "</td></tr>";
            }
        }
        return $data;
    }

    echo createMeetingSlotTable($_POST['parentID'], $_POST['startDate'], $_POST['endDate']);
?>