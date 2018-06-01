<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ClassMinder - Classroom</title>
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/teacherHome.js"></script>
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
</head>
<body>
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
        <table>
            <?php
                $userID = $_SESSION["userID"];
                $classroomID = $_POST["classroomID"];
                $title = $_POST["title"];
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
                "SELECT STUDENT.firstName, STUDENT.lastName, STUDENT.studentID, STUDENT_CLASS.x, STUDENT_CLASS.y
                FROM STUDENT
                JOIN STUDENT_CLASS ON STUDENT_CLASS.studentID = STUDENT.studentID
                JOIN CLASSROOM ON CLASSROOM.classroomID = STUDENT_CLASS.classroomID
                WHERE CLASSROOM.userID = $userID AND CLASSROOM.classroomID = $classroomID AND STUDENT.isActive = 1
                ORDER BY STUDENT_CLASS.y=-1, STUDENT_CLASS.y, STUDENT_CLASS.x";
                $records = $nConn->getQuery($nQuery);
                echo "<form method='post' action='studentProfile.php'>";
                echo '<table>';
                $count = 0;
                $notSeatedArr = array();
                $fetch=true;
                $rowVal = $newY+1+$_POST["extraRows"];
                $columnVal = $newX+1+$_POST["extraColumns"];
                echo"<tr><td class='divCell2' colspan='".$columnVal."'><h1>".$_POST['title']."</h1></td></tr>";
                for($y=1; $y<$rowVal; $y++)
                {
                    echo "<tr>";
                    for($x=1; $x<$columnVal; $x++)
                    {
                        $count++;
                        echo "<td>";

                        if(!$fetch)
                        {
                            if($x == $row["x"] && $y == $row["y"])
                            {
                                echo "<input type='hidden' name='classroomID' value='$classroomID'>";
                                echo '
                                <div id="divCell'.$count.'" data-value="'.$x.':'.$y.'" class="divCellNone" ondrop="drop(event)" ondragover="allowDrop(event)">
                                    <button value="'.$row['studentID'].'" type="submit" name="studentID" formmethod="post" id="btn'.$count.'"><i class="ion-android-person"></i><br>
                                    '.$row['firstName'].'<br>'.$row['lastName'].'<br>ID: '.$row['studentID'].'</button>
                                </div>
                                ';
                                $fetch = true;
                            }
                            elseif($row['x']==-1||$row['y']==-1)
                            {
                                array_push($notSeatedArr, $row);
                                echo '
                                <div id="divCell'.$count.'" data-value="'.$x.':'.$y.'" class="divCellNone" ondrop="drop(event)" ondragover="allowDrop(event)">
                                </div>
                                ';
                                $fetch = true;
                            }
                            else
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
                            //console.log("yes");
                            //console.log($x."?=".$row["x"]);
                            //echo "<script>console.log('".$x."?=".$row['x']." (:x-y:)".$y."?=".$row['y']."');</script>";
                            
                            if($x == $row["x"] && $y == $row["y"])
                            {
                                echo "<input type='hidden' name='classroomID' value='$classroomID'>";
                                echo '
                                <div id="divCell'.$count.'" data-value="'.$x.':'.$y.'" class="divCellNone" ondrop="drop(event)" ondragover="allowDrop(event)">
                                    <button value="'.$row['studentID'].'" type="submit" name="studentID" formmethod="post" id="btn'.$count.'"><i class="ion-android-person"></i><br>
                                    '.$row['firstName'].'<br>'.$row['lastName'].'<br>ID: '.$row['studentID'].'</button>
                                </div>
                                ';
                                $fetch = true;
                            }
                            elseif($row['x']==-1||$row['y']==-1)
                            {
                                array_push($notSeatedArr, $row);
                                echo '
                                <div id="divCell'.$count.'" data-value="'.$x.':'.$y.'" class="divCellNone" ondrop="drop(event)" ondragover="allowDrop(event)">
                                </div>
                                ';
                                $fetch = true;
                            }
                            else
                            {
                                echo '
                                <div id="divCell'.$count.'" data-value="'.$x.':'.$y.'" class="divCellNone" ondrop="drop(event)" ondragover="allowDrop(event)">
                                </div>
                                ';
                                $fetch = false;
                            }
                        }
                        else
                        {
                            echo '
                            <div id="divCell'.$count.'" data-value="'.$x.':'.$y.'" class="divCellNone" ondrop="drop(event)" ondragover="allowDrop(event)">
                            </div>
                            ';
                        }
                        echo "</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
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
                            echo "<input type='hidden' name='classroomID' value='$classroomID'>";
                            echo '
                            <div id="div2Cell'.$count.'" data-value="-1:-1" class="divCellNone divCell2" ondrop="drop(event)" ondragover="allowDrop(event)">
                                <button value="'.$currentRow['studentID'].'" type="submit" name="studentID" formmethod="post" id="btn'.$count.'"><i class="ion-android-person"></i><br>
                                '.$currentRow['firstName'].'<br>'.$currentRow['lastName'].'<br>ID: '.$currentRow['studentID'].'</button>
                            </div>
                            ';
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
                echo "</table></form>";
                echo "</form>";
                echo "<br><form method='post' action='addStudentClass.php'>";
                echo "<input type='hidden' name='title' value='$title'>";
                echo "<tr><td class='btnCell'><div class='btnPlus'><button type='submit' name='classroomID' formmethod='post' class='button' value=" . $classroomID . ">";
                echo "<span><i class=\"ion-plus-round\"></i></span></div></td></button></tr>";
                echo "</form>";

                // Record Behaviors
                echo "<br><form method='post' action='recordBehaviors.php'>";
                echo "<input type='hidden' name='title' value='$title'>";
                echo "<tr><td class='btnCell' colspan='2'><div class='btnBehaviors'><button type='submit' name='classroomID' formmethod='post' class='button' value=" . $classroomID . ">";
                echo "<span>Record Behaviors</span></td></button></div></tr>";
                echo "</form>";
                
                // Edit Seating Chart
                echo "<br><form method='post' action='editSeatingChart.php'>";
                echo "<input type='hidden' name='title' value='$title'>";
                echo "<tr><td class='btnCell'><div class='btnBehaviors'><button type='submit' name='classroomID' formmethod='post' class='button' value=" . $classroomID . ">";
                echo "<span>Edit Seating Chart</span></td></button></div></tr>";
                echo "</form>";
            ?>
            <br>
            <form action="excelInput.php" method="post" enctype="multipart/form-data">
                <tr>
                    <td colspan="0.5">
                        <input type="hidden" name="classroomID" value="<?php echo $classroomID; ?>">
                        <input type="hidden" name="title" value="<?php echo $title; ?>">
                        <input type="file" name="excelFile" id="excelFile">
                    </td>
                    <td class="btnCell" colspan="0.5">
                        <input type="submit" class="submitBtn" name="submit" value="Add Class By Excel"/>
                    </td>
                </tr>
            </form>
            <form action="delete.php" method='post'>
                    <td class="btnCell" colspan="1">
                        <?php
                            echo "<input type='hidden' name='classroomID' value='$classroomID'>";
                        ?>
                        <div>
                            <input STYLE="background-color: red;" type="submit" id="cancelBtn" value="Delete Class"/>
                        </div>
                    </td>
            </form>
        </table>
        </div>
    </div>
</div>
</body>
</html>