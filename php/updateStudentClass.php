<?php
    require_once('../common/connection.php');
    if(isset($_POST["arr"]))
    {
        $nConn = new Connection();
        //echo '<pre>'; print_r($array); echo '</pre>';
        $obj = json_decode($_POST["arr"]);
        $classroomID = $obj->{'classroomID'};
        foreach($obj as $key => $value)
        {
            if($key!=="classroomID")
            {
                $coordinateArr = explode(",", $value);
                $xCoor = $coordinateArr[0];
                $yCoor = $coordinateArr[1];
                $nQuery ="UPDATE STUDENT_CLASS SET `x`=$xCoor, `y`=$yCoor WHERE `classroomID`=$classroomID and`studentID`=$key";
                $nConn->getQuery($nQuery);
            }
        }
        echo "Seating chart was updated";
    }
?>