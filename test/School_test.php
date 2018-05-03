<?php
require('../model/School.php');

/**
 *
 */
class School_test
{
  function __construct()
  {
    $nSchool = new School("FHS", "Fresno", "CA");
    echo "\nSchool test 1:\n";
    $nSchool->save();
    echo "\nSchool test 2:\n";
    $nSchool->update();
    echo "\nSchool test 3:\n";
    $nSchool->getRecord();
    echo "\nSchool test 4:\n";
    $nSchool->delete();
  }
}

?>
