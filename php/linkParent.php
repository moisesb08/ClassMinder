<?php
    session_start();
    if(isset($_SESSION['user']))
    {
        if($_SESSION['isTeacher'] == 0)
            header("location: parentHome.php");
        $firstName = $_SESSION['firstName'];
        $lastName = $_SESSION['lastName'];
        $teacherName = $firstName . " " . $lastName;
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
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <?php
        require_once('../common/connection.php');
        include_once('../model/User.php');
        
        // Import PHPMailer classes into the global namespace
        // These must be at the top of your script, not inside a function
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

        //Load Composer's autoloader
        require '../phpmailer/vendor/autoload.php';
        // Initialize the session
        session_start();
        // If session variable is not set it will redirect to login page
        if(!isset($_SESSION['user']) || empty($_SESSION['user'])){
            header("location: ../view/loginPage.php");
            exit;
        }
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
        $studentID = $_POST["studentID"];

        
        if(isset($_POST["email"]))
        {
            if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
                array_push($msgs, "Invalid Email");
            if(empty($msgs))
            {
                $email = $_POST["email"];
                $nQuery = "SELECT userID FROM USER WHERE email = '$email' AND isTeacher = 0";
                $result = $nConn->getQuery($nQuery);
                $record = $result->fetch_assoc();
                if(empty($record))
                {
                    //Send e-mail
                    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
                    try {
                            //Server settings
                            //$mail->SMTPDebug = 1;                                 // Enable verbose debug output
                            $mail->isSMTP();                                      // Set mailer to use SMTP
                            $mail->Host = 'smtp.sendgrid.net';  // Specify main and backup SMTP servers
                            $mail->SMTPAuth = true;                               // Enable SMTP authentication
                            $mail->Username = 'USERNAME';                 // Set SMTP username
                            $mail->Password = 'PASSWORD';                           // set SMTP password
                            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                            $mail->Port = 587;                                    // TCP port to connect to
                        
                            //Recipients
                            $mail->setFrom('noreply@classminder.net', $teacherName);
                            $mail->addAddress($email);     // Add a recipient

                            //Content
                            $mail->isHTML(true);                                  // Set email format to HTML
                            $mail->Subject = 'Create a ClassMinder Account';

                            $msg = 'Your student\'s teacher has requested that you create a ClassMinder account.  
                            Go to classminder.net/php/register.php?studentID='.$studentID.' and make an account to see your student\'s behavior in class!';
                            $mail->Body    = $msg;
                            $mail->AltBody    = strip_tags($msg);
                        
                            $mail->send();
                    } catch (Exception $e) {
                            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                    }
                    echo "<script>
                        alert('Email was sent to $email.');
                        window.location.href='./studentProfile.php';
                        </script>";
                }
                else
                {
                    $email = $_POST["email"];
                    $parentID = $record['userID'];
                    $email = $_POST["email"];
                    $nQuery = "INSERT INTO STUDENT_PARENT VALUES($studentID, $parentID)";
                    $parentLinked = $nConn->getQuery($nQuery);
                    if(!is_null($parentLinked))
                        $success = true;
                }
            }
        }
        
    ?>
</head>
<body>
    <?php
        /*if($success)
        {   
            header("location:./studentProfile.php");
            die;
        }*/
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
                <td colspan="2"><h4>Please enter parent e-mail. Parents will be notified to create an account if they do not have one.</h4></td>
            </tr>
                <form action="" method="post">
                <tr>
                    <td class="leftAlign">
                        <label for "email" class="require"> Parent E-mail: </label>
                    </td>
                    <td>
                        <input type="email" size="20" name="email" value="<?php if (isset($_POST['email'])) echo $_POST['email']?>" required/>
                    </td>
                </tr>
                <tr>
                    <td class="btnCell" colspan="1">
                        <input type="hidden" name="studentID" value='<?php echo $studentID ?>'/>
                        <input type="submit" class="submitBtn" value="Link Parent"/>
                    </td>
                    <td class="btnCell" colspan="1">
                        <button type="button" class="button" id="cancelBtn" onclick="window.location.href='./studentProfile.php'">Cancel</button>
                    </td>
                </tr>
                </form>
            </table>
        </div>
    </div>
</body>
</html>