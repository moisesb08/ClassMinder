<?php
require('../model/Meeting.php');

/**
 *
 */
class Meeting_test
{
  function __construct()
  {
    $nMeeting = new Meeting("Test meeting", "2018-05-04 10:30:00", "45");
    echo "\nMeeting test 1:\n";
    $nMeeting->save();
    echo "\nMeeting test 2:\n";
    $nMeeting->update();
    echo "\nMeeting test 3:\n";
    $nMeeting->getRecord();
    echo "\nMeeting test 4:\n";
    $nMeeting->delete();
  }
}

?>
