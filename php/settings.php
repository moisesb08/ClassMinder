<!DOCTYPE html>
<head>
    <title>Account Settings</title>
    <link href="../css/register.css" text="text/css" rel="stylesheet"/>
    <link href="../css/teacherHome.css" text="text/css" rel="stylesheet"/>
    <link rel="stylesheet" href="../resources/ionicons-2.0.1/css/ionicons.min.css">
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
        $nConn = new Connection();
        $msgs = [];
        $success = false;
        
        if(isset($_POST["fName"]) || isset($_POST["lName"])
            || isset($_POST["email"]) || isset($_POST["newPassword"])
            || isset($_POST["newConfirmPass"]))
        {
            $password_sha1 = sha1($_POST['password']);
            echo "<script>alert('".$_SESSION["email"].$password_sha1."')</script>";
            $usr = new User($_SESSION["email"], "","", $password_sha1, "");
            $res = $usr->checkUser();

            //echo "--$res--";
            if($res == "1")
            {
                if($_POST["newPassword"] != $_POST["newConfirmPass"])
                    array_push($msgs, "Passwords do not match");
                
                $arrs = array();
                if(isset($_POST["fName"])&&$_POST["fName"]!="")
                {
                    $firstName = $_POST["fName"];
                    $arr1 = array('firstName'=>$firstName);
                    array_push($arrs, $arr1);
                    $_SESSION['firstName'] = $firstName;
                }
                if(isset($_POST["lName"])&&$_POST["lName"]!="")
                {
                    $lastName = $_POST["lName"];
                    $arr2 = array('lastName'=>$lastName);
                    array_push($arrs, $arr2);
                    $_SESSION['lastName'] = $lastName;
                }
                if(isset($_POST["email"])&&$_POST["email"]!="")
                {
                    if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
                        array_push($msgs, "Invalid Email");
                    else
                    {
                        $email = $_POST["email"];
                        $arr3 = array('email'=>$email);
                        array_push($arrs, $arr3);
                    }
                }
                if(isset($_POST["newPassword"])&&$_POST["newPassword"]!="")
                {
                    $newPassword = $_POST["newPassword"];
                    $arr4 = array('password'=>sha1($newPassword));
                    array_push($arrs, $arr4);
                }

                if(empty($msgs))
                {
                    $userID = $_SESSION['userID'];
                    $nConn = new Connection();
                    foreach($arrs as $arr)
                    {
                        if ($arr == $arr3)
                            if (!$nConn->update('USER', $userID, $arr))
                                array_push($msgs, "Email is taken");
                            else
                                $_SESSION['email'] = $email;
                        else
                            $nConn->update('USER', $userID, $arr);
                    }
                    if(empty($msgs))
                        $success = true;
                }
            }
            else
            {
                array_push($msgs, "Incorrect Password");
            }
        }
        
    ?>
</head>
<body>
    <?php
        if($success)
        {   
            header("location:./teacherHome.php");
            die;
        }
        if($_SESSION["isTeacher"])
            echo teacherSidebar();
        else
            echo parentSidebar();
    ?>
<div class="div1">
        <div class="midContainer">
    <table>
        <tr>
            <td colspan="2"><h3>Enter all changes you would like to make to your account</h3></td>
        </tr>
        <?php
            if(!empty($msgs))
            {   
                foreach($msgs as $msg)
                    echo "<tr><td class='msgs' colspan='2'>*". $msg ."</td></tr>";
            }
        ?>
            <form action="" method="post">
            <tr>
                <th colspan="2">
                    Change Profile Details
                </th>
            </tr>
            <tr>
                <td class="leftAlign">
                    <label for "fName"> New First Name: </label>
                </td>
                <td>
                    <input type="text" size="20" name="fName" value="<?php if (isset($_POST['fName'])) echo $_POST['fName']?>"/>
                </td>
            </tr>
            <tr>
                <td class="leftAlign">
                    <label for "lName"> New Last Name: </label>
                </td>
                <td>
                    <input type="text" size="20" name="lName" value="<?php if (isset($_POST['lName'])) echo $_POST['lName']?>"/>
                </td>
            </tr>
            <tr>
                <td class="leftAlign">
                    <label for "email"> New Email: </label>
                </td>
                <td>
                    <input type="text" size="20" name="email" value="<?php if (isset($_POST['email'])) echo $_POST['email']?>"/>
                </td>
            </tr>
            <tr>
                <th colspan="2">
                    Change Password
                </th>
            </tr>
            <tr>
                <td class="leftAlign">
                    <label for "newPassword"> New Password: </label>
                </td>
                <td>
                    <input type="password" size="20" name="newPassword" value=""/>
                </td>
            </tr>
            <tr>
                <td class="leftAlign">
                    <label for "newConfirmPass"> New Password Confirmation: </label>
                </td>
                <td>
                    <input type="password" size="20" name="newConfirmPass" value=""/>
                </td>
            </tr>
            <tr>
                <th colspan="2">
                    Save Changes
                </th>
            </tr>
            <tr>
                <th class="leftAlign">
                    <label for "password" class="require"> Enter Current Password To Save: </label>
                </th>
                <td>
                    <input type="password" size="20" name="password" value="" required/>
                </td>
            </tr>
            <tr>
                <td class="btnCell" colspan="1">
                    <input type="submit" class="submitBtn" value="Save Changes"/>
                </td>
                <td class="btnCell" colspan="1">
                    <button type="button" class="button" onclick="window.location.href='teacherHome.php'">Cancel</button>
                </td>
            </tr>
            </form>
        </table>
    </div>
</div>
</body>
</html>