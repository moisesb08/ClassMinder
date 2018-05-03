<?php
require('../model/Student.php');

/**
 *
 */
class Student_test
{
  function __construct()
  {
    $nStudent = new Student("Adam", "Alvarez");
    echo "\nStudent test 1:\n";
    $nStudent->save();
    echo "\nStudent test 2:\n";
    $nStudent->update();
    echo "\nStudent test 3:\n";
    $nStudent->getRecord();
    echo "\nStudent test 4:\n";
    $nStudent->delete();
  }
}

?>
