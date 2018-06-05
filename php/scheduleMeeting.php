<!DOCTYPE html>
<head>
    <title>Schedule Meeting</title>
    <link href="../css/register.css" text="text/css" rel="stylesheet"/>
    <link href="../css/teacherHome.css" text="text/css" rel="stylesheet"/>
    <link rel="stylesheet" href="../resources/ionicons-2.0.1/css/ionicons.min.css">
    <title>ClassMinder - Behavior Analysis</title>
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/meetings.js"></script>
    <?php
        require_once('../common/connection.php');
        include_once('../model/User.php');
        include_once('sidebar.php');

        // Initialize the session
        session_start();

        // If session variable is not set it will redirect to login page
        if(!isset($_SESSION['user']) || empty($_SESSION['user']))
        {
            header("location: ../view/loginPage.php");
            exit;
        }

        // If user is a parent he/she will be redirected to the parent homepage
        if($_SESSION['isTeacher'] == 1)
            header("location: teacherHome.php");
        $userID = $_SESSION['userID'];
        $nConn = new Connection();
    ?>
</head>
<body>
<?php
        if($_SESSION["isTeacher"])
            echo teacherSidebar();
        else
            echo parentSidebar();  
        $d=strtotime("today");
            $startDate = date("Y-m-d", $d);
    ?>
<div class="div1">
        <div class="midContainer">
    <table>
        <tr>
            <td colspan="4"><h4>Select available meeting slot</h4></td>
        </tr>
        <tr>
            <td class="leftAlign">
                <label for "meetingTime"> From: </label>
            </td>
            <td>
                <input type="date" name="meetingTime" id="startDate" class="selectDate" value="<?php echo $startDate;?>" min="<?php echo $startDate;?>"/>
            </td>
            <td class="leftAlign">
                <label for "meetingTime"> To: </label>
            </td>
            <td>
                <input type="date" name="meetingTime" id="endDate" class="selectDate" value="<?php echo $startDate;?>" min="<?php echo $startDate;?>"/>
            </td>
        </tr>
    </table>
    <table>
        <div id="slotsDiv">
            <tr>
            </tr>

            <tr>
            </tr>

            <tr>
            </tr>

            <tr>
            </tr>
        </div>
        <script>begin(<?php echo "'$userID', '$startDate', '$startDate'";?>);</script>
    </table> 
    <table> 
    <form>    
            <tr>
                <td class="btnCell" colspan="4">
                    <button type="button" class="button" onclick="window.location.href='meetings.php'">Cancel</button>
                </td>
            </tr>
    </form>
    </table>
    </div>
</div>
</body>
</html>