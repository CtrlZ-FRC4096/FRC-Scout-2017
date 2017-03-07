<?php

include($_SERVER['DOCUMENT_ROOT']."/util/php/include_classes.php");

$helper = new Helper();
$currCompetition = $helper->getCurrentCompetition();
if(isset($_COOKIE['deviceID'])){

  $status = $helper->getCurrentStatusOfUser($_COOKIE['deviceID'],$currCompetition->id);
  if(strpos($status ,"teamSelection") !== false){

    if(strpos($status,"-")){
      $arr = explode("-",$status);
      $WAITING_ON_TEAM = intval($arr[1]);
      $match = new Match(intval($arr[2]),intval($currCompetition->id));
    }
    else{
      $WAITING_ON_TEAM = null;
    }

    require_once("teamSelection.php");
  }
  else{
    $arr = explode("-",$status);
    $match = new Match(intval($arr[1]),intval($currCompetition->id));
      require_once("matchScouting.php");
  }


}

else{
  setcookie("deviceID",$helper->getRandomDeviceID(),3650,"/");
  require_once("teamSelection.php");
}




?>