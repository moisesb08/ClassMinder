<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ClassMinder - Record Behaviors</title>
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/recordBehaviors.js"></script>
    <link rel="stylesheet" href="../css/recordBehaviors.css">
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
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
            echo "<form name='goToForm' method='post' action='classroom.php'>";
            echo "<input type='hidden' name='title' value='$title'>";
            echo "<input type='hidden' name='classroomID' value='$classroomID'>";
            echo "</form>";
            echo "<script>document.goToForm.submit();</script>";
            exit;
        }
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
        $userID=$_SESSION['userID'];
        if(isset($_POST["submitted"])&&(isset($_POST["posBehaviors"])
            ||isset($_POST["negBehaviors"])||isset($_POST["addPositive"])
            ||isset($_POST["addNegative"])))
        {
            $behaviors = array();
            if(isset($_POST["posBehaviors"]))
            {
                foreach($_POST["posBehaviors"] as $posBehavior)
                {
                    array_push($behaviors, $posBehavior);
                }
            }
            if(isset($_POST["negBehaviors"]))
            {
                foreach($_POST["negBehaviors"] as $negBehavior)
                {
                    array_push($behaviors, $negBehavior);
                }
            }
            
            if(isset($_POST["addPositive"]))
            {
                $behavTitle = $_POST["positiveTitle"];
                $behavDescription = $_POST["positiveDescription"];
                $arr = array('title'=>$behavTitle, 'description'=>$behavDescription,'userID'=>$userID, 'isPositive'=>'1');
                $behaviorID = $nConn->save("BEHAVIOR", $arr);
                array_push($behaviors, $behaviorID);
            }

            if(isset($_POST["addNegative"]))
            {
                $behavTitle = $nConn->sanitize($_POST["negativeTitle"]);
                $behavDescription = $nConn->sanitize($_POST["negativeDescription"]);
                $arr = array('title'=>$behavTitle, 'description'=>$behavDescription,'userID'=>$userID, 'isPositive'=>'0');
                $behaviorID = $nConn->save("BEHAVIOR", $arr);
                array_push($behaviors, $behaviorID);
            }
            $message = 'CHANGES:';
            foreach($_POST["students"] as $student_ID)
            {
                foreach($behaviors as $behavior_ID)
                {
                    $classroomID = $_POST["classroomID"];
                    $title = $_POST["title"];
                    $arr = array('studentID'=>$student_ID, 'behaviorID'=>$behavior_ID);
                    $nConn->save("STUDENT_BEHAVIOR", $arr);
                    $str="SELECT * FROM BEHAVIOR
                        JOIN STUDENT_BEHAVIOR on BEHAVIOR.behaviorID=STUDENT_BEHAVIOR.behaviorID
                        JOIN STUDENT on STUDENT.studentID=STUDENT_BEHAVIOR.studentID
                        WHERE STUDENT_BEHAVIOR.studentID=$student_ID AND STUDENT_BEHAVIOR.behaviorID=$behavior_ID";
                    //echo $str;
                    $results = $nConn->getQuery($str);
                    $record = $results->fetch_assoc();
                    $message .= '\nAdded behavior \"'.$record["title"].'\" to '.$record["firstName"].' '.$record["lastName"]; 
                }
            }
            //testing dialog
                    echo'<script>';
                    echo'function myFunction() {
                        alert("'.$message.'");
                    }
                    myFunction();
                    </script>';
            echo "<form name='goToForm' method='post' action=''>";
            echo "<input type='hidden' name='title' value='$title'>";
            echo "<input type='hidden' name='classroomID' value='$classroomID'>";
            echo "</form>";
            echo "<script>document.goToForm.submit();</script>";
        }
        ?>
</head>
<body onload="start()">
    <div class="all">
        <div class="modal">
            <div class="modalBox">
                <span>modal box</span>
            </div>
        </div>
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
            <?php
                $classroomID = $_POST["classroomID"];
                $title = $_POST["title"];
                // Dimensions of table
                $nQuery =
                "SELECT max(STUDENT_CLASS.x) AS xMax, max(STUDENT_CLASS.y) AS yMax, count(STUDENT_CLASS.studentID) AS totalStudents
                FROM STUDENT
                JOIN STUDENT_CLASS ON STUDENT_CLASS.studentID = STUDENT.studentID
                JOIN CLASSROOM ON CLASSROOM.classroomID = STUDENT_CLASS.classroomID
                WHERE CLASSROOM.userID = $userID AND CLASSROOM.classroomID = $classroomID";
                $result = $nConn->getQuery($nQuery);
                $row = $result->fetch_row();
                $xMax = (int)$row[0];
                $yMax = (int)$row[1];
                $newX = max(1, $xMax);
                $newY = max(1, $yMax);
                $totalStudents = max(1, $row["totalStudents"]);
                while($newX*$newY < max(25, $totalStudents))
                {
                    if(min($newX, $newY)==$newX)
                        $newX++;
                    else
                        $newY++;
                    //echo "<script>console.log('".$newX." (:x-y:)".$newY."');</script>";
                    if(min($newX, $newY)>10)
                        break;
                }
                $rowVal = $newY+1+$_POST["extraRows"];
                $columnVal = $newX+1+$_POST["extraColumns"];
                if($yMax<0)
                    $yMax=($count/$columnVal)+1;

                // Display students
                $nQuery =
                "SELECT STUDENT.firstName, STUDENT.lastName, STUDENT.studentID, STUDENT_CLASS.x, STUDENT_CLASS.y
                FROM STUDENT
                JOIN STUDENT_CLASS ON STUDENT_CLASS.studentID = STUDENT.studentID
                JOIN CLASSROOM ON CLASSROOM.classroomID = STUDENT_CLASS.classroomID
                WHERE CLASSROOM.userID = $userID AND CLASSROOM.classroomID = $classroomID ORDER BY STUDENT_CLASS.y=-1, STUDENT_CLASS.y, STUDENT_CLASS.x";
                $records = $nConn->getQuery($nQuery);
                echo "<form method='post' action=''>";
                echo '<table>';
                $count = 0;
                $notSeatedArr = array();
                $fetch=true;
                echo"<tr><td class='divCell2' colspan='".$columnVal."'><h1>".$_POST['title']."</h1></td></tr>";
                for($y=1; $y<=$columnVal; $y++)
                {
                    if($y<=$yMax)
                        echo "<tr>";
                    for($x=1; $x<$columnVal; $x++)
                    {
                        $count++;
                        if($y<=$yMax)
                            echo "<td>";
                        if(!$fetch)
                        {
                            if($x == $row["x"] && $y == $row["y"])
                            {
                                echo '
                                <div id="divCell'.$count.'" data-value="'.$x.':'.$y.'" class="divCellNone" ondrop="drop(event)" ondragover="allowDrop(event)">'.
                                    "<label class='container'>".
                                    "<input type='checkbox' name='students[]' value='". $row['studentID'] ."'>
                                    <br><span class='checkmark checkmarkStudent'></span>
                                    </label><div class='textCheck'><span class='checkmark2 checkmarkStudent2'>"
                                    .$row["firstName"] . "<br>" . $row["lastName"]."<br>ID: ".$row['studentID'].
                                "</span></div></div>
                                ";
                                $fetch = true;
                            }
                            elseif($row['x']==-1||$row['y']==-1)
                            {
                                array_push($notSeatedArr, $row);
                                if($y<=$yMax)
                                {
                                    echo '
                                    <div id="divCell'.$count.'" data-value="'.$x.':'.$y.'" class="divCellNone" ondrop="drop(event)" ondragover="allowDrop(event)">
                                    </div>
                                    ';
                                    $fetch = true;
                                }
                                
                            }
                            elseif($y<=$yMax)
                            {
                                echo '
                                <div id="divCell'.$count.'" data-value="'.$x.':'.$y.'" class="divCellNone" ondrop="drop(event)" ondragover="allowDrop(event)">
                                </div>
                                ';
                                $fetch = false;
                            }
                        }
                        elseif($row = $records->fetch_array())
                        {              
                            if($x == $row["x"] && $y == $row["y"])
                            {
                                echo '
                                <div id="divCell'.$count.'" data-value="'.$x.':'.$y.'" class="divCellNone" ondrop="drop(event)" ondragover="allowDrop(event)">'.
                                    "<label class='container'>".
                                    "<input type='checkbox' name='students[]' value='". $row['studentID'] ."'>
                                    <br><span class='checkmark checkmarkStudent'></span>
                                    </label><div class='textCheck'><span class='checkmark2 checkmarkStudent2'>"
                                    .$row["firstName"] . "<br>" . $row["lastName"]."<br>ID: ".$row['studentID'].
                                "</span></div></div>
                                ";
                                $fetch = true;
                            }
                            elseif($row['x']==-1||$row['y']==-1)
                            {
                                array_push($notSeatedArr, $row);
                                
                                if($y<=$yMax)
                                {
                                    echo '
                                    <div id="divCell'.$count.'" data-value="'.$x.':'.$y.'" class="divCellNone" ondrop="drop(event)" ondragover="allowDrop(event)">
                                    </div>
                                    ';
                                    $fetch = true;
                                }
                            }
                            elseif($y<=$yMax)
                            {
                                echo '
                                <div id="divCell'.$count.'" data-value="'.$x.':'.$y.'" class="divCellNone" ondrop="drop(event)" ondragover="allowDrop(event)">
                                </div>
                                ';
                                $fetch = false;
                            }
                        }
                        elseif($y<=$yMax)
                        {
                            echo '
                            <div id="divCell'.$count.'" data-value="'.$x.':'.$y.'" class="divCellNone" ondrop="drop(event)" ondragover="allowDrop(event)">
                            </div>
                            ';
                        }
                        if($y<=$yMax)
                            echo "</td>";
                    }
                    if($y<=$yMax)
                        echo "</tr>";
                }
                echo "</table><br>";

                //not seated
                echo '<table><tr><td colspan="'.$columnVal.'">Students not assigned seats:</td></tr>';
                $count=0;
                $breakRow = false;
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
                            <div id="div2Cell'.$count.'" data-value="-1:-1" class="divCellNone divCell2" ondrop="drop(event)" ondragover="allowDrop(event)">'.
                                "<label class='container studentContainer'>".
                                "<input type='checkbox' name='students[]' value='". $currentRow['studentID'] ."'>
                                <br><span class='checkmark checkmarkStudent'></span>
                                </label><div class='textCheck'><span class='checkmark2 checkmarkStudent2'>"
                                .$currentRow["firstName"] . "<br>" . $currentRow["lastName"]."<br>ID: ".$currentRow['studentID'].
                            "</span></div></div>
                            ";
                        }
                        else
                        {
                            $breakRow = true;
                            echo '
                            <div id="div2Cell'.$count.'" data-value="-1:-1" class="divCellNone divCell2" ondrop="drop(event)" ondragover="allowDrop(event)">
                            </div>
                            ';
                        }
                        echo "</td>";
                    }
                    echo "</tr>";
                    if($breakRow)
                        break;
                }
                echo "</table>";

                // Display Positive Behaviors
                $nQuery =
                "SELECT * FROM BEHAVIOR WHERE userID=$userID OR userID=0 HAVING isPositive=1";
                $records = $nConn->getQuery($nQuery);
                echo "<div class='positive'><span>SELECT POSITIVE BEHAVIORS</span></div>";
                while($row = $records->fetch_array())
                {
                    echo "
                    <label class='container'>".$row["title"].
                    "<input type='checkbox' name='posBehaviors[]' value='". $row['behaviorID'] ."'>
                    <span class='checkmark'></span>
                    </label>";
                }
                                echo "
                    <label class='container'>Other:".
                    "<input type='checkbox' name='addPositive[]' id='addPositive' value='1'>
                    <span class='checkmark'></span>
                    </label>
                    <label class='container others'>Good Behavior:<br>".
                    "<input type='text' placeholder='Title' id='positiveTitle' name='positiveTitle'>
                    </label>
                    <br>
                    <label class='container'>Description:<br><textarea  id='positiveDescription' name='positiveDescription' rows='4' cols='50' placeholder='Enter Description Here'></textarea></label>";
                // Display Negative Behaviors
                $nQuery =
                "SELECT * FROM BEHAVIOR WHERE userID=$userID OR userID=0 HAVING isPositive=0";
                $records = $nConn->getQuery($nQuery);
                echo "<div class='negative'><span>SELECT NEGATIVE BEHAVIORS</span></div>";
                while($row = $records->fetch_array())
                {
                    echo "
                    <label class='container'>".$row["title"].
                    "<input type='checkbox' name='negBehaviors[]' value='". $row['behaviorID'] ."'>
                    <span class='checkmark checkmarkRed'></span>
                    </label>";
                }
                echo "
                    <label class='container'>Other:".
                    "<input type='checkbox' name='addNegative[]' id='addNegative' value='1'>
                    <span class='checkmark checkmarkRed'></span>
                    </label>
                    <label class='container others'>Bad Behavior:<br>".
                    "<input type='text' placeholder='Title' id='negativeTitle' name='negativeTitle'>
                    </label>
                    <br>
                    <label class='container'>Description:<br><textarea  id='negativeDescription' name='negativeDescription' rows='4' cols='50' placeholder='Enter Description Here'></textarea></label>";
                echo "<input type='hidden' name='submitted' value='1'>";
                echo "<input type='hidden' name='title' value='$title'>";
                echo "<tr><td class='btnCell'><div class='btnBehaviors'><button type='submit' name='classroomID' formmethod='post' class='button' value=" . $classroomID . ">";
                echo "<span>Submit</span></td></button></div></tr>";
                echo "</form><br>";
                echo "<form action='classroom.php' method='post'>";
                echo "<input type='hidden' name='title' value='$title'>";
                echo "<tr><td class='btnCell'><div class='btnBehaviors'><button type='submit' name='classroomID' formmethod='post' class='button' value=" . $classroomID . ">";
                echo "<span>Back to classroom</span></td></button></div></tr>";
                echo "</form>";
            ?>
        </table>
        </div>
    </div>
</div>
</body>
</html>