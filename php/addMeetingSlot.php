<!DOCTYPE html>
<head>
    <title>Create Meeting Slot</title>
    <link href="../css/register.css" text="text/css" rel="stylesheet"/>
    <?php
        require_once('../common/connection.php');
        include_once('../model/User.php');
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

        $msgs = [];
        $success = false;
        $error = false;
        $weekCounter = 0;
        $weeks = 0;
        $days = 0;
        
        if(isset($_POST["meetingTime"]) && isset($_POST["meetingLength"])
            && isset($_POST["meetingLocation"]) && isset($_POST["meetingDivision"]))
        {
            $teacherID = $_SESSION['userID'];
            $meetingTime = $_POST["meetingTime"];
            $meetingLength = $_POST["meetingLength"];
            $meetingLocation = $_POST["meetingLocation"];
            $meetingDivision = $_POST["meetingDivision"];
            $formatDate = new DateTime($meetingTime);
            $meetingIntervals = floor($meetingLength / $meetingDivision);
            if ($meetingIntervals == 0)
            {
                echo'<script>';
                echo'function myFunction() {
                    alert("Number of slots greater than available timeframe.");
                }
                myFunction();
                </script>';
                $error = true;
            }
            $meetingSingleInterval = new DateInterval('PT'.$meetingIntervals.'M');
            if(isset($_POST["meetingCount"]) && $_POST["meetingCount"]!=1)
            {
                $weeks = $_POST["meetingWeeks"];
            }
            if(isset($_POST["meetingCount"]) && $_POST["meetingCount"]==2)
            {
                $days = 6;
            }
            while ($weekCounter <= $weeks)
            {
                $dayCounter = 0;
                while ($dayCounter <= $days)
                {
                    if (!$error)
                    {
                        if (($formatDate->format('l')=='Saturday' || $formatDate->format('l')=='Sunday') && $_POST["meetingCount"]==2)
                        {
                            $dayCounter++;
                            $dayInterval = new DateInterval('P'.$dayCounter.'D');
                            $meetingTime = $_POST["meetingTime"];
                            $formatDate = new DateTime($meetingTime);
                            $formatDate = $formatDate->add($dayInterval);
                            $meetingTime = $formatDate->format('Y-m-d H:i:s');
                            continue;
                        }
                        for ($i = 0; $i < $meetingDivision; $i++)
                        {
                            $nQuery = "INSERT INTO MEETING_SLOT (meetingTime, minutes, teacherID, location) VALUES (\'$meetingTime\', $meetingLength, $teacherID, \'$meetingLocation\')";
                            $arr = array('meetingTime'=>$meetingTime, 'minutes'=>$meetingIntervals, 'teacherID'=>$teacherID, 'location'=>$meetingLocation);
                            $meetingSlotID = $nConn->save("MEETING_SLOT", $arr);
                            $formatDate = $formatDate->add($meetingSingleInterval);
                            $meetingTime = $formatDate->format('Y-m-d H:i:s');
                        }
                    }
                    $dayCounter++;
                    $dayInterval = new DateInterval('P'.$dayCounter.'D');
                    $meetingTime = $_POST["meetingTime"];
                    $formatDate = new DateTime($meetingTime);
                    $formatDate = $formatDate->add($dayInterval);
                    $meetingTime = $formatDate->format('Y-m-d H:i:s');
                }
                $weekCounter++;
                $weekIntervalDays = $weekCounter * 7;
                $weeklyInterval = new DateInterval('P'.$weekIntervalDays.'D');
                $meetingTime = $_POST["meetingTime"];
                $formatDate = new DateTime($meetingTime);
                $formatDate = $formatDate->add($weeklyInterval);
                $meetingTime = $formatDate->format('Y-m-d H:i:s');
            }
            $success = true;
        }
        
    ?>
</head>
<body>
<?php
        if($success)
        {   
            header("location:./meetings.php");
            die;
        }  
    ?>
<div class="div1">
        <div class="midContainer">
    <table>
        <tr>
            <td class="imageCell" colspan="2"><img src="../resources/images/templogoWhiteTransparent.png" width="360px"></td>
        </tr>
        <?php
            if(!empty($msgs))
            {   
                foreach($msgs as $msg)
                    echo "<tr><td class='msgs' colspan='2'>*". $msg ."</td></tr>";
            }
        ?>
        <tr>
            <td colspan="2"><h4>Please enter the date and time you want to create a meeting slot for.</h4></td>
        </tr>
            <form action="" method="post">
            <tr>
                <td class="leftAlign">
                    <label for "meetingTime" class="require"> Meeting Time: </label>
                </td>
                <td>
                    <input type="datetime-local" name="meetingTime" value="<?php if (isset($_POST['meetingTime'])) echo $_POST['meetingTime']?>" required/>
                </td>
            </tr>
            <tr>
                <td class="leftAlign">
                    <label for "meetingLength" class="require"> Available Timeframe: </label>
                </td>
                <td>
                    <input type="number" name="meetingLength" min="1" placeholder="In minutes." value="<?php if (isset($_POST['meetingLength'])) echo $_POST['meetingLength']?>" required/>
                </td>
            </tr>
            <tr>
                <td class="leftAlign">
                    <label for "meetingDivision" class="require"> Meeting Slots<br>in timeframe: </label>
                </td>
                <td>
                    <input type="number" name="meetingDivision" min="1" value="1"/>
                </td>
            </tr>
            <tr>
                <td class="leftAlign">
                    <label for "meetingLocation" class="require"> Meeting Location: </label>
                </td>
                <td>
                    <input type="text" size="20" name="meetingLocation" placeholder="ex: Room 2A" value="<?php if (isset($_POST['meetingLocation'])) echo $_POST['meetingLocation']?>" required/>
                </td>
            </tr>
            <tr>
                <td class="leftAlign">
                    <label for "meetingCount" class="require"> Repeat Meetings: </label>
                </td>
                <td class="leftAlign">
                    <input type="radio" name="meetingCount" value=1 checked="checked">Single Day Only</input>
                    </td></tr><tr><td>&nbsp;</td><td class="leftAlign">
                    <input type="radio" name="meetingCount" value=2>Daily Meetings (M-F) <input type="number" name="meetingWeeks" placeholder="For the next X weeks"/></input>
                    </td></tr><tr><td>&nbsp;</td><td class="leftAlign">
                    <input type="radio" name="meetingCount" value=3>Weekly Meetings <input type="number" name="meetingWeeks" placeholder="For the next X weeks"/></input>
                </td>
            </tr>
            <tr>
                <td class="btnCell" colspan="1">
                    <input type="submit" class="submitBtn" value="Create Meeting Slot"/>
                </td>
                <td class="btnCell" colspan="1">
                    <button type="button" class="button" onclick="window.location.href='meetings.php'">Cancel</button>
                </td>
            </tr>
            </form>
        </table>
    </div>
</div>
</body>
</html>