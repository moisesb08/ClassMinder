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
    <title>ClassMinder - Create Class</title>
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/teacherHome.js"></script>
    <link rel="stylesheet" href="../css/studentList.css">
    <link rel="stylesheet" href="../resources/ionicons-2.0.1/css/ionicons.min.css">
    <?php
        include("../model/Classroom.php");

        $user = "";
        $msgs = [];
        $success = false;
        
        if(isset($_POST["title"]))
        {
            if(empty($msgs))
            {
                $classroom = new Classroom($_POST["title"], $_SESSION['userID'], 0);
                $classroomCreated = $classroom->save()!=0;
                if(!is_null($classroomCreated))
                    $success = true;
            }
        }
        
    ?>
</head>
<body>
    <?php
        if($success)
        {   
            header("location:./classList.php");
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
                <td colspan="2"><h4>Please enter class information</h4></td>
            </tr>
                <form action="" method="post">
                <tr>
                    <td class="leftAlign">
                        <label for "title" class="require"> Title: </label>
                    </td>
                    <td>
                        <input type="text" size="20" name="title" value="<?php if (isset($_POST['title'])) echo $_POST['title']?>" required/>
                    </td>
                </tr>
                <tr>
                    <td class="btnCell" colspan="1">
                        <input type="submit" class="submitBtn" value="Create New Class"/>
                    </td>
                    <td class="btnCell" colspan="1">
                        <button type="button" class="button" id="cancelBtn" onclick="window.location.href='./classList.php'">Cancel</button>
                    </td>
                </tr>
                </form>
            </table>
        </div>
    </div>
</body>
</html>