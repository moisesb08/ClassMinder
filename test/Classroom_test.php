<?php
require('../model/Classroom.php');

/**
 *
 */
class Classroom_test
{
  function __construct()
  {
    $nClassroom = new Classroom("Mr. Bernals Test Class", 1, 1); //$title, $userID, $schoolID
    echo "\nClassroom test 1:\n";
    $nClassroom->save();
    echo "\nClassroom test 2:\n";
    $nClassroom->update();
    echo "\nClassroom test 3:\n";
    $nClassroom->getRecord();
    echo "\nClassroom test 4:\n";
    $nClassroom->delete();
  }
}

?>
