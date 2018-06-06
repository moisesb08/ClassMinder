<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ClassMinder - Seating Chart</title>
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/editSeatingChart.js"></script>
    <link rel="stylesheet" href="../css/editSeatingChart.css">
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
        // If user is a parent he/she will be redirected to the parent homepage
        if($_SESSION['isTeacher'] == 0)
            header("location: parentHome.php");
        if(isset($_POST["classroomID"]))
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
        <script>
        init();
        </script>
</head>
<body onload="getValues()">
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
            <h1><?php echo $_POST['title']; ?></h1>
            <?php
                $userID = $_SESSION["userID"];
                $classroomID = $_POST["classroomID"];
                $title = $_POST["title"];
                $nQuery =
                "SELECT min(STUDENT_CLASS.x) AS xMin,
                min(STUDENT_CLASS.y) AS yMin,
                max(STUDENT_CLASS.x) AS xMax,
                max(STUDENT_CLASS.y) AS yMax,
                count(STUDENT_CLASS.studentID) AS totalStudents
                FROM STUDENT
                JOIN STUDENT_CLASS ON STUDENT_CLASS.studentID = STUDENT.studentID
                JOIN CLASSROOM ON CLASSROOM.classroomID = STUDENT_CLASS.classroomID
                WHERE CLASSROOM.userID = $userID AND CLASSROOM.classroomID = $classroomID
                AND x >=0 AND y >=0;";
                //echo $nQuery;
                $result = $nConn->getQuery($nQuery);
                $row = $result->fetch_assoc();
                if($row['xMin']=="-1"||$row['xMin']==NULL||$row['xMin']=="")
                    $xMin = 0;
                else
                    $xMin = (int)$row['xMin'];
                
                if($row['xMax']=='-1'||$row['xMax']==NULL||$row['xMax']=="")
                    $xMax = 0;
                else
                    $xMax = (int)$row['xMax'];
                
                if($row['yMin']=='-1'||$row['yMin']==NULL||$row['yMin']=="")
                    $yMin = 0;
                else
                    $yMin = (int)$row['yMin'];
                
                if($row['yMax']=='-1'||$row['yMax']==NULL||$row['yMax']=="")
                    $yMax = 0;
                else
                    $yMax = (int)$row['yMax'];
                $newX = max(1, $xMax);
                $newY = max(1, $yMax);
                $totalStudents = max(1, $row["totalStudents"]);
                while($newX*$newY < max(49, $totalStudents))
                {
                    if(min($newX, $newY)==$newX)
                    {
                        $newX++;
                    }
                    else
                    {
                        $newY++;
                    }
                    //echo "<script>console.log('".$newX." (:x-y:)".$newY."');</script>";
                    if(min($newX, $newY)>10)
                        break;
                }
                $nQuery =
                "SELECT STUDENT.firstName, STUDENT.lastName, STUDENT.studentID, STUDENT_CLASS.x, STUDENT_CLASS.y, STUDENT.sID
                FROM STUDENT
                JOIN STUDENT_CLASS ON STUDENT_CLASS.studentID = STUDENT.studentID
                JOIN CLASSROOM ON CLASSROOM.classroomID = STUDENT_CLASS.classroomID
                WHERE CLASSROOM.userID = $userID AND CLASSROOM.classroomID = $classroomID ORDER BY STUDENT_CLASS.y=-1, STUDENT_CLASS.y, STUDENT_CLASS.x";
                $records = $nConn->getQuery($nQuery);
                $seatingChart = "<form method='post' action='studentProfile.php'>";
                $count = 0;
                $notSeatedArr = array();
                $fetch=true;
                $rowVal = $newY+1+$_POST["extraRows"];
                $columnVal = $newX+1+$_POST["extraColumns"];
                $seatingChart .= '<table><tr><td class="center" colspan="'.$columnVal.'"><h4>Seating chart (Drag & Drop) <i class="ion-android-arrow-down"></i></h4></td></tr>';
                $seatingChart .= '<tr><td class="frontCell" colspan="'.$columnVal.'">Front of Class</td></tr>';
                for($y=1; $y<$rowVal; $y++)
                {
                    $seatingChart .= "<tr>";
                    for($x=1; $x<$columnVal; $x++)
                    {
                        $count++;
                        $seatingChart .= "<td>";

                        if(!$fetch)
                        {
                            //echo "<script>console.log('".$x."?=".$row['x']." (:x-y:)".$y."?=".$row['y']."');</script>";
                            
                            if($x == $row["x"] && $y == $row["y"])
                            {
                                $seatingChart .= '
                                <div id="divCell'.$count.'" data-value="'.$x.':'.$y.'" class="divCell" ondrop="drop(event)" ondragover="allowDrop(event)">
                                    <button value="'.$row['studentID'].'" type="button" ondragstart="drag(event)" draggable="true" id="btn'.$count.'"><i class="ion-android-person"></i><br>
                                    '.$row['firstName'].'<br>'.$row['lastName'].'<br>ID: '.$row['sID'].'</button>
                                </div>
                                ';
                                $fetch = true;
                            }
                            elseif($row['x']==-1||$row['y']==-1)
                            {
                                array_push($notSeatedArr, $row);
                                $seatingChart .= '
                                <div id="divCell'.$count.'" data-value="'.$x.':'.$y.'" class="divCell" ondrop="drop(event)" ondragover="allowDrop(event)">
                                </div>
                                ';
                                $fetch = true;
                            }
                            else
                            {
                                $seatingChart .= '
                                <div id="divCell'.$count.'" data-value="'.$x.':'.$y.'" class="divCell" ondrop="drop(event)" ondragover="allowDrop(event)">
                                </div>
                                ';
                                $fetch = false;
                            }
                        }
                        elseif($row = $records->fetch_array())
                        {
                            //console.log("yes");
                            //console.log($x."?=".$row["x"]);
                            //echo "<script>console.log('".$x."?=".$row['x']." (:x-y:)".$y."?=".$row['y']."');</script>";
                            
                            if($x == $row["x"] && $y == $row["y"])
                            {
                                $seatingChart .= '
                                <div id="divCell'.$count.'" data-value="'.$x.':'.$y.'" class="divCell" ondrop="drop(event)" ondragover="allowDrop(event)">
                                    <button value="'.$row['studentID'].'" type="button" ondragstart="drag(event)" draggable="true" id="btn'.$count.'"><i class="ion-android-person"></i><br>
                                    '.$row['firstName'].'<br>'.$row['lastName'].'<br>ID: '.$row['sID'].'</button>
                                </div>
                                ';
                                $fetch = true;
                            }
                            elseif($row['x']==-1||$row['y']==-1)
                            {
                                array_push($notSeatedArr, $row);
                                $seatingChart .= '
                                <div id="divCell'.$count.'" data-value="'.$x.':'.$y.'" class="divCell" ondrop="drop(event)" ondragover="allowDrop(event)">
                                </div>
                                ';
                                $fetch = true;
                            }
                            else
                            {
                                $seatingChart .= '
                                <div id="divCell'.$count.'" data-value="'.$x.':'.$y.'" class="divCell" ondrop="drop(event)" ondragover="allowDrop(event)">
                                </div>
                                ';
                                $fetch = false;
                            }
                        }
                        else
                        {
                            $seatingChart .= '
                            <div id="divCell'.$count.'" data-value="'.$x.':'.$y.'" class="divCell" ondrop="drop(event)" ondragover="allowDrop(event)">
                            </div>
                            ';
                        }
                        $seatingChart .= "</td>";
                    }
                    $seatingChart .= "</tr>";
                }
                $seatingChart .= "</table></form>";

                echo "<form method='post' action='classroom.php'>";
                echo "<input type='hidden' name='title' value='$title'>";
                echo "<tr><td class='btnCell'><div class='btnBehaviors'><button type='submit' id='classroomID' name='classroomID' formmethod='post' class='button' value=" . $classroomID . ">";
                echo "<span>Back to Classroom</span></td></button></div></tr>";
                echo "</form>";
                // save button
                echo "<br>";
                echo "<tr><td class='btnCell'><div class='btnPlus'><button type='button' onclick='updateSeating()' name='classroomID' formmethod='post' class='button' value=" . $classroomID . ">";
                echo "<span>Save</span></td></button></div></tr>";
                //not seated
                $endRow = false;
                echo '<table><tr><td colspan="'.$columnVal.'"><h4>Students not assigned seats:</h4></td></tr>';
                $count=0;
                for($y=1; $y<$rowVal; $y++)
                {
                    echo "<tr>";
                    for($x=1; $x<$columnVal; $x++)
                    {
                        $count++;
                        echo "<td>";
                        if($count <= count($notSeatedArr))
                        {
                            $currentRow = $notSeatedArr[$count-1];
                            echo '
                            <div id="div2Cell'.$count.'" data-value="-1:-1" class="divCell divCell2" ondrop="drop(event)" ondragover="allowDrop(event)">
                                <button value="'.$currentRow['studentID'].'" type="button" ondragstart="drag(event)" draggable="true" id="btnB'.$count.'"><i class="ion-android-person"></i><br>
                                '.$currentRow['firstName'].'<br>'.$currentRow['lastName'].'<br>ID: '.$currentRow['sID'].'</button>
                            </div>
                            ';
                        }
                        else
                        {
                            $endRow = true;
                            echo '
                            <div id="div2Cell'.$count.'" data-value="-1:-1" class="divCell divCell2" ondrop="drop(event)" ondragover="allowDrop(event)">
                            </div>
                            ';
                        }
                        echo "</td>";
                    }
                    echo "</tr>";
                    if($endRow)
                        break;
                }
                echo "</table>";
                // seated
                echo $seatingChart;
            ?>
        </div>
    </div>
</div>
</body>
</html>