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
        $usr->loadByEmail($_POST["email"]);
        $_SESSION['user'] = $usr;
        $_SESSION['userID'] = $usr->getUserID();
        $_SESSION['email'] = $usr->getEmail();
        $_SESSION['firstName'] = $usr->getFirstName();
        $_SESSION['lastName'] = $usr->getLastName();
        $_SESSION['isTeacher'] = $usr->getIsTeacher();
        $_SESSION['login'] = "Y";

        //login successful
        if($_SESSION['isTeacher'] == 1)
          header("location: ../php/teacherHome.php");
        else
          header("location: ../php/parentHome.php");
      }
      else
      {
          session_start();
          $_SESSION['email'] = $_POST["email"];
          header("location: ../view/loginPage.php?ERRNO=ERR101");
      }
    }
?>
