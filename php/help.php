<?php
    session_start();
    if(isset($_SESSION['user']))
    {
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
    <title>ClassMinder - Help</title>
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/teacherHome.js"></script>
    <link rel="stylesheet" href="../css/teacherHome.css">
    <link rel="stylesheet" href="../resources/ionicons-2.0.1/css/ionicons.min.css">
</head>
<body>
    <?php
        include_once('sidebar.php');
        if($_SESSION["isTeacher"])
            echo teacherSidebar();
        else
            echo parentSidebar();
    ?>
    <div class="div1">
        <div class="midContainer">
        <table>
        <?php
        if($_SESSION["isTeacher"])
        {
            echo '<tr><td><h1>Application Help</h1></td></tr>
            <tr><td class="btnCell" width="100px" colspan="1">
            <button type="button" class="button" onclick="unhide('."'q1'".')">
            <span>How do I add students?</span></button></td></tr>
            <tr><td id="q1" hidden=true width="100px" colspan="1">
            Go to your Students page and click on the + icon.</td></tr>
            <tr><td class="btnCell" colspan="1">
            <button type="button" class="button" onclick="unhide('."'q2'".')">
            <span>How do I add classes?</span></button></td></tr>
            <tr><td id="q2" hidden=true width="100px" colspan="1">
            Go to your Classes page and click on the + icon.</td></tr>
            <tr><td class="btnCell" colspan="1">
            <button type="button" class="button" onclick="unhide('."'q3'".')">
            <span>How do I add students to classes?</span></button></td></tr>
            <tr><td id="q3" hidden=true width="100px" colspan="1">
            Select a class from your Classes page and click on the + icon.</td></tr>
            <tr><td class="btnCell" colspan="1">
            <button type="button" class="button" onclick="unhide('."'q4'".')">
            <span>What is Preferences/Account Settings?</span></button></td></tr>
            <tr><td id="q4" hidden=true width="100px" colspan="1">
            These features have not been implemented yet.</td></tr>';
        }
        else
        {
            echo '<tr><td><h1>Application Help</h1></td></tr>
            <tr><td class="btnCell" width="100px" colspan="1">
            <button type="button" class="button" onclick="unhide('."'q1'".')">
            <span>How do I see behavior reports?</span></button></td></tr>
            <tr><td id="q1" hidden=true width="100px" colspan="1">
            Go to your Students page. Click on a class to see class analysis or click on full analysis to see analysis for all classes.</td></tr>
            <tr><td class="btnCell" colspan="1">
            <button type="button" class="button" onclick="unhide('."'q2'".')">
            <span>How do I schedule a meeting?</span></button></td></tr>
            <tr><td id="q2" hidden=true width="100px" colspan="1">
            Go to your Meetings page and click on the "Schedule a meeting" button. Then select a meeting slot and you will be prompted to give a reason for the meeting.</td></tr>';
        }
            
        ?>
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