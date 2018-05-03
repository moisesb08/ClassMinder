<?php

include('Behavior_test.php');
include('Classroom_test.php');
include('Meeting_test.php');
include('School_test.php');
include('Student_test.php');
include('User_test.php');

echo "\nRunning User tests\n";
$ut = new User_test();

echo "\n\nRunning School tests";
$tt = new School_test();

echo "\n\nRunning Behavior tests";
$ft = new Behavior_test();

echo "\n\nRunning Classroom tests";
$ft = new Classroom_test();

echo "\n\nRunning Meeting tests";
$ft = new Meeting_test();

echo "\n\nRunning Student tests";
$pt = new Student_test();

?>
