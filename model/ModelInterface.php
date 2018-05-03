<?php

/**
 *
 */
interface ModelInterface
{
  public function save();

  public function loadById($ID);

  public function getRecord();
  
  public function update();

  public function delete();

}


 ?>
