<?php
    session_start();
    if(isset($_SESSION['user']))
    {
        if($_SESSION['isTeacher'] == 0)
            header("location: parentHome.php");
        $firstName = $_SESSION['firstName'];
        $lastName = $_SESSION['lastName'];
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
    <title>ClassMinder - Resources</title>
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/teacherHome.js"></script>
    <link rel="stylesheet" href="../css/teacherHome.css">
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
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
        <tr><td><h1>Application Help</h1></td></tr>
        <tr><td class="btnCell" width="100px" colspan="1">
        <button type="button" class="button" onclick="unhide('q1')">
        <span>How do I add students?</span></button></td></tr>
        <tr><td id="q1" hidden=true width="100px" colspan="1">
        Go to your Students page and click on the + icon.</td></tr>
        <tr><td class="btnCell" colspan="1">
        <button type="button" class="button" onclick="unhide('q2')">
        <span>How do I add classes?</span></button></td></tr>
        <tr><td id="q2" hidden=true width="100px" colspan="1">
        Go to your Classes page and click on the + icon.</td></tr>
        <tr><td class="btnCell" colspan="1">
        <button type="button" class="button" onclick="unhide('q3')">
        <span>How do I add students to classes?</span></button></td></tr>
        <tr><td id="q3" hidden=true width="100px" colspan="1">
        Select a class from your Classes page and click on the + icon.</td></tr>
        <tr><td class="btnCell" colspan="1">
        <button type="button" class="button" onclick="unhide('q4')">
        <span>What is Preferences/Account Settings?</span></button></td></tr>
        <tr><td id="q4" hidden=true width="100px" colspan="1">
        These features have not been implemented yet.</td></tr>
        </table>
        </div>
    </div>

    <script>
    function unhide(id) {
        var element = document.getElementById(id);
        if (element.hidden === true) {
            element.hidden = false;
        } else {
            element.hidden = true;
        }
    }
    </script>
</body>
</html>