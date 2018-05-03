<?php
require('../model/Behavior.php');

/**
 *
 */
class Behavior_test
{
  function __construct()
  {
    $nBehavior = new Behavior("No Homework", "Did not turn in homework.", 1, 0);
    echo "\nBehavior test 1:\n";
    $nBehavior->save();
    echo "\nBehavior test 2:\n";
    $nBehavior->update();
    echo "\nBehavior test 3:\n";
    $nBehavior->getRecord();
    echo "\nBehavior test 4:\n";
    $nBehavior->delete();
  }
}

?>
