<?php
    include("../common/appConstants.php");
    // If session variable is not set it will redirect to login page
    session_start();
    if(isset($_SESSION['user']))
    {
        header("location: ../php/home.php");
        exit;
    }
    $temp_email = $_SESSION['email'];
    session_destroy();
?>

<!DOCTYPE html>
<head>
    <title>Login</title>
    <link href="../css/register.css" text="text/css" rel="stylesheet"/>
</head>
<body>
<div  class="div1">
<div  class="midContainer">
    <table>
        <tr>
            <td class="imageCell" colspan="2"><img src="../resources/images/templogoWhiteTransparent.png" width="360px"></td>
        </tr>
        <tr>
            <td class="msgs" colspan="2">
                <?php
                    if(isset($_GET["ERRNO"]) && $_GET["ERRNO"] == "ERR101")
                        echo ERR101;
                ?>
            </td>
        </tr>
        <form action="../controller/loginController.php" method="post">
        <tr>
            <td class="leftAlign">
                <label for "email" class="require"> Email: </label>
            </td>
            <td>
                <input type="text" size="20" name="email" value="<?php echo $temp_email;?>" required/>
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
            <td colspan="1">
                <input type="submit" class="submitBtn" value="Sign In"/>
            </td>
            <td class="btnCell" colspan="1">
                <button type="button" class="button" onclick="window.location.href='../php/register.php'">Create an account</button>
            </td>
        </tr>
        </form>
    </table>
    </div>
    </div>
</body>
</html>
