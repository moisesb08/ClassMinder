<!DOCTYPE html>
<head>
    <title>Register</title>
    <link href="../css/register.css" text="text/css" rel="stylesheet"/>
    <?php
        include("../model/User.php");
        include("../model/UserFactory.php");

        $user = "";
        $msgs = [];
        $success = false;
        
        if(isset($_POST["fName"]) && isset($_POST["lName"])
            && isset($_POST["email"]) && isset($_POST["password"])
            && isset($_POST["confirmPass"]))
        {
            if($_POST["password"] != $_POST["confirmPass"])
                array_push($msgs, "Passwords do not match");
            if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
                array_push($msgs, "Invalid Email");

            if(empty($msgs))
            {
                $userFactory = new UserFactory();
                $userType = $userFactory->getUserType($_POST["userType"]);
                $user = $userType->getUser($_POST["email"], $_POST["fName"], $_POST["lName"], sha1($_POST["password"]));
                if(!is_null($user))
                    $success = true;
            }
        }
        
    ?>
</head>
<body>
    <?php
        if($success)
        {   
            header("location:./registerSuccess.php");
            die;
        }
        else if(isset($_POST["fName"]) && empty($msgs))
            array_push($msgs, "An account already exists with this email");
        
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
            <td colspan="2"><h4>Please enter your information to create your account</h4></td>
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
                <td class="leftAlign">
                    <label for "email" class="require"> Email: </label>
                </td>
                <td>
                    <input type="text" size="20" name="email" value="<?php if (isset($_POST['email'])) echo $_POST['email']?>" required/>
                </td>
            </tr>
            <tr>
                <td class="leftAlign">
                    <label for "password" class="require"> Password: </label>
                </td>
                <td>
                    <input type="password" size="20" name="password" value="" required/>
                </td>
            </tr>
            <tr>
                <td class="leftAlign">
                    <label for "confirmPass" class="require"> Password Confirmation: </label>
                </td>
                <td>
                    <input type="password" size="20" name="confirmPass" value="" required/>
                </td>
            </tr>
            <tr>
                <td>
                    <label><i>Teacher </i>
                    <input type="radio" name="userType" value="Teacher" checked>
                    </label>
                </td><td>
                    <label><i>Parent </i>
                    <input type="radio" name="userType" value="Parent">
                    </label>
                </td>
            </tr>
            <tr>
                <td class="btnCell" colspan="1">
                    <input type="submit" class="submitBtn" value="Register"/>
                </td>
                <td class="btnCell" colspan="1">
                    <button type="button" class="button" onclick="window.location.href='../index.php'">I have an account</button>
                </td>
            </tr>
            </form>
        </table>
    </div>
</div>
</body>
</html>