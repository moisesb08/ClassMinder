<?php
    include("../model/User.php");
    if(isset($_POST["email"]) && isset($_POST["password"]))
    {
      //echo $_POST["password"]." ,".$_POST["email"];
      $password_sha1 = sha1($_POST['password']);
      $usr = new User($_POST["email"], "","", $password_sha1, "");
      $res = $usr->checkUser();

      //echo "--$res--";
      if($res == "1")
      {
        session_start();
        $_SESSION['username'] = $_POST["email"];
        $_SESSION['login'] = "Y";
        //login successful
        header("location: ../php/home.php");
      }
      else
      {
          header("location: ../view/loginPage.php?ERRNO=ERR101");
      }
    }
?>
