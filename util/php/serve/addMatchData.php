<?php
/**
 * Created by PhpStorm.
 * User: Jayasurya
 * Date: 2/18/2016
 * Time: 10:08 PM
 */
ini_set("display_errors", "1");
error_reporting(E_ALL);

include($_SERVER['DOCUMENT_ROOT']."/util/php/include_classes.php");
include($_SERVER['DOCUMENT_ROOT']."/util/php/serve/postprocess.php");
$JSONdata = $_POST['data'];
$data = json_decode($JSONdata);
$teamMatchID = $data->teamMatch->id;
$helper = new Helper();
$helper->con = $helper->connectToDB();
$helper->con->beginTransaction();

foreach($data->actions as $record){

  $query="";
  $params = array();

  switch($record->eventType){
    case "gear":
//      var_dump($record);
      try {
        $query = "INSERT INTO matchgears( teamMatchID,  orderID, `mode`,  location,  result)
                                 VALUES (:teamMatchID, :orderID, :mode , :location, :result)";
        $stmt = $helper->con->prepare($query);
        $stmt->bindValue(":teamMatchID", $teamMatchID);
        $stmt->bindValue(":orderID", $record->orderID);
        $stmt->bindValue(":mode", $record->mode);
        $stmt->bindValue(":location", trim(substr($record->location,0,-4)));
        $stmt->bindValue(":result", intval($record->scoreMiss));

        $stmt->execute();
      }
      catch (PDOException $e) {
        echo "gear";
        echo "fail";
        echo $e->getMessage();
        $helper->con->rollBack();
        return;
      }

      break;
    case "feedBall":

      try {
        $query = "INSERT INTO matchballfeeds_preprocess(teamMatchID, orderID, `mode`, `before`, `after`, location, `count`,inputMethod)
                              VALUES (:teamMatchID, :orderID, :mode, :before, :after, :location, :count, :inputMethod)";
        $stmt = $helper->con->prepare($query);
        $stmt->bindValue(":teamMatchID", $teamMatchID);
        $stmt->bindValue(":orderID", $record->orderID);
        $stmt->bindValue(":mode", $record->mode);
        $stmt->bindValue(":before", (is_null($record->before) ? null : intval($record->before)));
        $stmt->bindValue(":after", (is_null($record->after) ? null : intval($record->after)));
        $stmt->bindValue(":location", trim($record->location));
        $stmt->bindValue(":count", (is_null($record->count) ? null : intval($record->count)));
        $stmt->bindValue(":inputMethod", $record->inputMethod);
        $stmt->execute();
      }
      catch (PDOException $e) {
        echo $e->getMessage();
        echo "feedBall";
        $helper->con->rollBack();
        echo "fail";
        return;
      }

      break;
    case "feedGear":

      try {
        $query = "INSERT INTO matchgearfeeds(teamMatchID, orderID, `mode`, result, method)
                                    VALUES(:teamMatchID,:orderID,:mode,:result,:method)";
        $stmt = $helper->con->prepare($query);
        $stmt->bindValue(":teamMatchID", $teamMatchID);
        $stmt->bindValue(":orderID", $record->orderID);
        $stmt->bindValue(":mode", $record->mode);
        $stmt->bindValue(":result", trim($record->result));
        $stmt->bindValue(":method", trim($record->method));
        $stmt->execute();
      }
      catch (PDOException $e) {
        echo $e->getMessage();
        echo "feedGear";
        $helper->con->rollBack();
        echo "fail";
        return;
      }

      break;
    case "shoot":

      try {
        $query = "INSERT INTO matchshoots_preprocess( teamMatchID,  orderID, `mode`,  coordX,  coordY,  scored,  missed,  leftover, `before`, `after`,  accuracy,  highLow,  inputMethod)
                                              VALUES(:teamMatchID, :orderID, :mode , :coordX, :coordY, :scored, :missed, :leftover, :before , :after , :accuracy, :highLow, :inputMethod)";
        $stmt = $helper->con->prepare($query);
        $stmt->bindValue(":teamMatchID", $teamMatchID);
        $stmt->bindValue(":orderID", $record->orderID);
        $stmt->bindValue(":mode", $record->mode);
        $stmt->bindValue(":coordX", trim($record->coordX));
        $stmt->bindValue(":coordY", trim($record->coordY));
        $stmt->bindValue(":scored",   (is_null($record->scored) ? null : intval($record->scored)));
        $stmt->bindValue(":missed",   (is_null($record->missed) ? null : intval($record->missed)));
        $stmt->bindValue(":leftover",   (is_null($record->missed) ? null : intval($record->leftover)));
        $stmt->bindValue(":before",   (is_null($record->before) ? null : intval($record->before)));
        $stmt->bindValue(":after",    (is_null($record->after) ? null : intval($record->after)));
        $stmt->bindValue(":accuracy", (is_null($record->accuracy) ? null : intval($record->accuracy)));
        $stmt->bindValue(":highLow", intval($record->level));
        $stmt->bindValue(":inputMethod", $record->inputMethod);
        $stmt->execute();
      }
      catch (PDOException $e) {
        echo $e->getMessage();
        echo "matchSchoots";
        $helper->con->rollBack();
        echo "fail";
        return;
      }

      break;
  }
}

try {

  $query = "INSERT INTO matchautos(teamMatchID, crossedLine)
                                    VALUES(:teamMatchID,:crossedLine)";
  $stmt = $helper->con->prepare($query);
  $stmt->bindValue(":teamMatchID", $teamMatchID);
  $stmt->bindValue(":crossedLine", ($data->otherFields->autoLineCrossed ? 1 : 0));
  $stmt->execute();
  if(trim($data->otherFields->duration) != "0:00" || $data->otherFields->climbSuccess){
    $query = "INSERT INTO matchclimbs(teamMatchID, touchpad, duration)
                                    VALUES(:teamMatchID,:touchpad,:duration)";
    $stmt = $helper->con->prepare($query);
    $time = explode(":",$data->otherFields->duration);
    $stmt->bindValue(":duration", intval($time[0]) * 60 + intval($time[1]));
    $stmt->bindValue(":teamMatchID", $teamMatchID);
    $stmt->bindValue(":touchpad", ($data->otherFields->climbSuccess ? 1 : 0));
    $stmt->execute();

  }
  $query = "INSERT INTO matchratings( teamMatchID,  ballGroundFeeding,  ballLoadingLaneFeeding,  ballShootingSpeed,  gearGroundFeeding,  gearLoadingLaneFeeding,  gearPlacingSpeed,  abilityToDefend,  abilityToEscapeDefense, notes)
                              VALUES(:teamMatchID, :ballGroundFeeding, :ballLoadingLaneFeeding, :ballShootingSpeed, :gearGroundFeeding, :gearLoadingLaneFeeding, :gearPlacingSpeed, :abilityToDefend, :abilityToEscapeDefense, :notes)";
  $stmt = $helper->con->prepare($query);
  $stmt->bindValue(":teamMatchID", $teamMatchID);
  $stmt->bindValue(":ballGroundFeeding", $data->otherFields->ballGroundFeedingRating);
  $stmt->bindValue(":ballLoadingLaneFeeding", $data->otherFields->ballLaneFeedingRating);
  $stmt->bindValue(":ballShootingSpeed", $data->otherFields->ballShootingSpeedRating);
  $stmt->bindValue(":gearGroundFeeding", $data->otherFields->gearGroundFeedingRating);
  $stmt->bindValue(":gearLoadingLaneFeeding", $data->otherFields->gearLaneFeedingRating);
  $stmt->bindValue(":gearPlacingSpeed", $data->otherFields->gearPlacingSpeedRating);
  $stmt->bindValue(":abilityToDefend", $data->otherFields->defenseRating);
  $stmt->bindValue(":abilityToEscapeDefense", $data->otherFields->defenseEscapeRating);
  $stmt->bindValue(":notes", $data->otherFields->notes);
  $stmt->execute();

  $query = "UPDATE teammatches SET collectionEnded = 1 WHERE id = :teamMatchID";
  $stmt = $helper->con->prepare($query);
  $stmt->bindValue(":teamMatchID", $teamMatchID);

  $stmt->execute();
}
catch (PDOException $e) {
  echo $e->getMessage();
  echo "otherFields";
  $helper->con->rollBack();
  echo "fail";
  return;
}

try{
  $query = "UPDATE matches SET lastUpdated = :now WHERE id =
                (SELECT matchID FROM teammatches WHERE id = :teamMatchID)";
  $stmt = $helper->con->prepare($query);
  $stmt->bindValue(":teamMatchID", $teamMatchID);
  $stmt->bindValue(":now", time());
  $stmt->execute();

}
catch(PDOException $e){
  echo $e->getMessage();
  echo "setMatchUpdated";
  $helper->con->rollBack();
  echo "fail";
  return;
}

$helper->con->commit();

$pitQuery = "SELECT * FROM teampit WHERE teamNumber = :teamNumber";
$result = $helper->queryDB($pitQuery,array(":teamNumber"=>$data->teamMatch->teamNumber), false);
if(sizeof($result) > 0){
  $result = $result[0];
  if($result['groundFeedsBalls'] !== null && $result['ballCapacity'] !== null){
//  echo "postprocessing";
    postProcessTeamMatch($teamMatchID,intval($result['ballCapacity']), ($result['groundFeedsBalls'] == 1 ? true : false));
  }
}

echo "Success";

?>