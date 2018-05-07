<!DOCTYPE html>
<head>
    <title>ClassMinder - Create Student</title>
    <link href="../css/register.css" text="text/css" rel="stylesheet"/>
    <?php
        include("../model/Classroom.php");

        $user = "";
        $msgs = [];
        $success = false;
        
        if(isset($_POST["cTitle"]))
        {
            if(empty($msgs))
            {
                $classroom = new Classroom($_POST["cTitle"], $_SESSION['userID'], 1);
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
            <td colspan="2"><h4>Please enter class information</h4></td>
        </tr>
            <form action="" method="post">
            <tr>
                <td class="leftAlign">
                    <label for "cTitle" class="require"> Class Name: </label>
                </td>
                <td>
                    <input type="text" size="20" name="cTitle" value="<?php if (isset($_POST['cTitle'])) echo $_POST['cTitle']?>" required/>
                </td>
            </tr>
            <tr>
                <td class="btnCell" colspan="1">
                    <input type="submit" class="submitBtn" value="Create New Class"/>
                </td>
                <td class="btnCell" colspan="1">
                    <button type="button" class="button" onclick="window.location.href='./classList.php'">Cancel</button>
                </td>
            </tr>
            </form>
        </table>
    </div>
</div>
</body>
</html>