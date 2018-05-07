<!DOCTYPE html>
<head>
    <title>ClassMinder - Create Student</title>
    <link href="../css/register.css" text="text/css" rel="stylesheet"/>
    <?php
        include("../model/Student.php");

        $user = "";
        $msgs = [];
        $success = false;
        
        if(isset($_POST["fName"]) && isset($_POST["lName"]))
        {
            if(empty($msgs))
            {
                $student = new Student($_POST["fName"], $_POST["lName"]);
                $studentCreated = $student->save()!=0;
                if(!is_null($studentCreated))
                    $success = true;
            }
        }
        
    ?>
</head>
<body>
    <?php
        if($success)
        {   
            header("location:./studentList.php");
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
                    <button type="button" class="button" onclick="window.location.href='./studentList.php'">Cancel</button>
                </td>
            </tr>
            </form>
        </table>
    </div>
</div>
</body>
</html>