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
    <title>ClassMinder - Resources</title>
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
        <tr><td><h1>Behavior Resources</h1></td></tr>
        <tr><td class="btnCell" width="100px" colspan="1">
        <button type="button" class="button" onclick="window.location.href='https://www.ncbi.nlm.nih.gov/pmc/articles/PMC4432234/'">
        <span>NCBI - Good Behavior</span></button></td></tr>
        <tr><td class="btnCell" colspan="1">
        <button type="button" class="button" onclick="window.location.href='https://sc.edu/about/offices_and_divisions/cte/teaching_resources/goodteaching/handling_classroom_distractions/index.php'">
        <span>USC - Classroom Distractions</span></button></td></tr>
        <tr><td class="btnCell" colspan="1">
        <button type="button" class="button" onclick="window.location.href='https://www.cmu.edu/teaching/designteach/teach/problemstudent.html'">
        <span>CMU - Addressing Problems</span></button></td></tr>
        <tr><td class="btnCell" colspan="1">
        <button type="button" class="button" onclick="window.location.href='https://iris.peabody.vanderbilt.edu/module/beh1/cresource/q1/p01/'">
        <span>PC - Academic Impact</span></button></td></tr>
        <tr><td class="btnCell" colspan="1">
        <button type="button" class="button" onclick="window.location.href='https://www.cte.cornell.edu/teaching-ideas/building-inclusive-classrooms/connecting-with-your-students.html'">
        <span>CU - Student Connections</span></button></td></tr>
        </table>
        </div>
    </div>
</body>
</html>