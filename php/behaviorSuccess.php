<!DOCTYPE html>
<head>
    <title>Behavior Success</title>
    <link href="../css/register.css" text="text/css" rel="stylesheet"/>
</head>
<body>
<div class="div1">
    <div class="midContainer">
    <table>
        <tr>
            <td><h3> You have successfully added the behaviors. </h3></td>
        </tr>
        <tr><td>
            <?php
                $classroomID = $_POST["classroomID"];
                $title = $_POST["title"];
                echo "<form name='goToForm' method='post' action='classroom.php'>";
                echo "<input type='hidden' name='title' value='$title'>";
                echo "<input type='hidden' name='classroomID' value='$classroomID'>";
            ?>
            <button type='submit' name='success' formmethod='post' class='button' value='true'><span>Back to Classroom</span></td></button>
            </form>
        </td></tr>
    </table>
    </div>
</div>
</body>
</html>