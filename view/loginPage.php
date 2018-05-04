<?php
    include("../common/boardconstants.php");
    // If session variable is not set it will redirect to login page
    session_start();
    if(isset($_SESSION['username']))
    {
        header("location: ../php/home.php");
        exit;
    }
    session_destroy();
?>

<!DOCTYPE html>
<head>
    <title>Login</title>
    <link href="../css/index.css" text="text/css" rel="stylesheet"/>
</head>
<body>
    <?php
        if(isset($_GET["ERRNO"]) && $_GET["ERRNO"] == "ERR101")
            echo "<h4 style='color:#FF0000'>".ERR101."</h4>";
    ?>
    <table>
        <form action="../controller/loginController.php" method="post">
        <tr>
            <td>
                <label for "email" class="require"> Email: </label>
            </td>
            <td>
                <input type="text" size="20" name="email" value="" required/>
            </td>
        </tr>
        <tr>
            <td>
                <label for "pass" class="require"> Password: </label>
            </td>
            <td>
                <input type="password" size="20" name="pass" value="" required/>
            </td>
        </tr>
        <tr>
            <td class="center" colspan="1">
                <input type="submit" class="submitBtn" value="Sign In"/>
            </td>
            <td class="center" colspan="1">
                <input type="button" value="Create an account" onclick="window.location.href='../php/register.php'" />
            </td>
        </tr>
        </form>
    </table>
</body>
</html>
