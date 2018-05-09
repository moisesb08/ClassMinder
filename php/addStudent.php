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
    <link rel="stylesheet" href="../css/studentList.css">
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <?php
        include("../model/Student.php");

        $user = "";
        $msgs = [];
        $success = false;
        
        if((isset($_POST["fName"]) && isset($_POST["lName"])) || isset($_POST['studentClass']))
        {
            if(empty($msgs))
            {
                $nConn = new Connection();
                if(!isset($_POST['studentClass']))
                {
                    $userID = $_SESSION['userID'];
                    $str = "INSERT INTO classroom (title, userID, schoolID)
                    SELECT * FROM (SELECT 'Unassigned Class', $userID, 0) AS tmp
                    WHERE NOT EXISTS (
                        SELECT classroom.title FROM classroom WHERE classroom.title = 'Unassigned Class'
                    ) LIMIT 1;";
                    $nConn->getQuery($str);
                    $arr = array('schoolID'=>'0', 'userID'=>$userID);
                    $classroomSelected = $nConn->getRecordByArr('CLASSROOM', $arr);
                    $classroomID = $classroomSelected['classroomID'];
                    $student = new Student($_POST["fName"], $_POST["lName"]);
                    $studentCreated = $student->save()!=0;
                    $studentID = $student->getStudentID();
                }
                else
                {
                    $json = $_POST['studentClass'];
                    $obj = json_decode($json);
                    $studentID = $obj->{'studentID'};
                    $classroomID = $obj->{'classroomID'};
                    $title = $obj->{'title'};
                    $gotoClassroom = true;
                }

                if(!is_null($studentCreated) || isset($_POST['studentClass']))
                {
                    $str = "INSERT INTO STUDENT_CLASS (studentID, classroomID)
                    VALUES ($studentID, $classroomID)";
                    $nConn->getQuery($str);
                    $success = true;
                }
            }
        }
        
    ?>
</head>
<body>
    <?php
        if($success)
        {   
            if($gotoClassroom)
            {
                echo "<form name='goToForm' method='post' action='classroom.php'>";
                echo "<input type='hidden' name='title' value='$title'>";
                echo "<input type='hidden' name='classroomID' value='$classroomID'>";
                echo "</form>";
                echo "<script>document.goToForm.submit();</script>";
            }
            else
            {
                header("location:./studentList.php");
            }
            die;
        }  
    ?>
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
                if(!empty($msgs))
                {   
                    foreach($msgs as $msg)
                        echo "<tr><td class='msgs' colspan='2'>*". $msg ."</td></tr>";
                }
            ?>
            <tr>
                <td colspan="2"><h4>Please enter student information</h4></td>
            </tr>
                <form action="" method="post">
                <tr>
                    <td class="leftAlign">
                        <label for "fName" class="require"> First Name: </label>
                    </td>
                    <td>
                        <input type="text" size="20" name="fName" value="<?php if (isset($_POST['fName'])) echo $_POST['fName']?>" required/>
                    </td>
                </tr>
                <tr>
                    <td class="leftAlign">
                        <label for "lName" class="require"> Last Name: </label>
                    </td>
                    <td>
                        <input type="text" size="20" name="lName" value="<?php if (isset($_POST['lName'])) echo $_POST['lName']?>" required/>
                    </td>
                </tr>
                <tr>
                    <td class="btnCell" colspan="1">
                        <input type="submit" class="submitBtn" value="Create New Student"/>
                    </td>
                    <td class="btnCell" colspan="1">
                        <button type="button" class="button" id="cancelBtn" onclick="window.location.href='./studentList.php'">Cancel</button>
                    </td>
                </tr>
                </form>
            </table>
        </div>
    </div>
</body>
</html>