<!DOCTYPE html>
    <head>
        <title>ClassMinder - Classes</title>
        <link href="../css/index.css" text="text/css" rel="stylesheet"/>
        <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <?php
        require_once('../common/connection.php');
        include_once('../model/User.php');
        include_once('../model/Classroom.php');
        // Initialize the session
        session_start();
        // If session variable is not set it will redirect to login page
        if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
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
        ?>
    </head>

    <body>
        <table class="middleTable">
            <tr>
                <td class="btnCell">
                    <input type="button" class="button" value="Logout" onclick="window.location.href='./logout.php'" />
                </td>
                <td width="97%">
                    <h3>Signed in as <b><?php echo $_SESSION['user']->getFirstName() . " ".$_SESSION['user']->getLastName()."</b>.";?></h3>
                </td>
            </tr>
        </table>
        <div class="div1">
        <table>
            <tr>
                <td><h1>Your Classes</h1></td>
            </tr>
            <?php
                $userID = $_SESSION["userID"];
                $nQuery = "SELECT * FROM CLASSROOM";
                $records = $nConn->getQuery($nQuery);
                while($row = $records->fetch_array())
                {
                    echo "<tr><button type=\"button\" class=\"button\" onclick=\"window.location.href='classroom.php'\">";
                    echo $row["title"] . "<br>";
                    echo "</button></tr>";
                }
            ?>
            <tr><td colspan='1'><span><i class="ion-plus-round" onclick="window.location.href='./addClass.php'"></i></span></td></tr>
        </table>
        <br>
        <br>
    </div>
    <br>
    <br>
    </body>
</html>