<?php

require('../model/User.php');

/**
 *
 */
class User_test
{

  function __construct()
  {
    $nUser = new User("mb@mail.com", "Moises", "Bernal", "password", 1);
    echo "\nUser test 1:\n";
    $nUser->save();
    echo "\nUser test 2:\n";
    $nUser->update();
    echo "\nUser test 3:\n";
    $nUser->getRecord();
    echo "\nUser test 4:\n";
    $nUser->delete();
  }
}

?>
