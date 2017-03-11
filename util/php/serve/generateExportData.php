<?php
/**
 * Created by PhpStorm.
 * User: Jayasurya
 * Date: 3/7/2016
 * Time: 9:02 PM
 */

include($_SERVER['DOCUMENT_ROOT']."/util/php/include_classes.php");

$helper = new Helper();
$currCompetition = $helper->getCurrentCompetition();
$finalData = array();
$finalData['matchData'] = array();
foreach(json_decode($_POST['matchNumbers']) as $matchNumber){

  $match = array();

  $query = "SELECT * FROM matches WHERE compID = :compID AND matchNumber = :matchNumber";
  $params = array(":compID" => $currCompetition->id,":matchNumber" => $matchNumber);
  $result = $helper->queryDB($query,$params,false);
  $match['id'] = $result[0]['id'];
  $match['matchNumber'] = $result[0]['matchNumber'];
  $match['compID'] = $result[0]['compID'];

  $match['teamMatches'] = array();

  $query = "SELECT * FROM teamMatches WHERE matchID = :matchID";
  $params = array(":matchID" => $match['id']);
  $result = $helper->queryDB($query,$params,false);
  $teamMatches = $result;

  foreach($teamMatches as $teamMatchRow){
    if($teamMatchRow['ready'] == 0){
      continue;
    }
    $teamMatch = array();

    $teamMatch['id'] = $teamMatchRow['id'];
    $teamMatch['matchID'] = $teamMatchRow['matchID'];
    $teamMatch['side'] = $teamMatchRow['side'];
    $teamMatch['position'] = $teamMatchRow['position'];
    $teamMatch['teamNumber'] = $teamMatchRow['teamNumber'];
    $teamMatch['deviceID'] = $teamMatchRow['deviceID'];
    $teamMatch['collectionStarted'] = $teamMatchRow['collectionStarted'];
    $teamMatch['collectionEnded'] = $teamMatchRow['collectionEnded'];
    $teamMatch['scouterID'] = $teamMatchRow['scouterID'];
    $teamMatch['postprocessed'] = $teamMatchRow['postprocessed'];
    $teamMatch['ready'] = $teamMatchRow['collectionEnded'];

    $teamMatch['ballfeeds'] = array();
    $query = "SELECT * FROM matchballfeeds WHERE teamMatchID = :teamMatchID";
    $params = array(":teamMatchID" => $teamMatch['id']);
    $teamMatch['ballfeeds'] = $helper->queryDB($query,$params,false);

    $teamMatch['gearfeeds'] = array();


    $query = "SELECT * FROM matchgearfeeds WHERE teamMatchID = :teamMatchID";
    $params = array(":teamMatchID" => $teamMatch['id']);
    $teamMatch['gearfeeds'] = $helper->queryDB($query,$params,false);

    $teamMatch['shoots'] = array();

    $query = "SELECT * FROM matchshoots WHERE teamMatchID = :teamMatchID";
    $params = array(":teamMatchID" => $teamMatch['id']);
    $teamMatch['shoots'] = $helper->queryDB($query,$params,false);

    $teamMatch['gears'] = array();

    $query = "SELECT * FROM matchgears WHERE teamMatchID = :teamMatchID";
    $params = array(":teamMatchID" => $teamMatch['id']);
    $teamMatch['gears'] = $helper->queryDB($query,$params,false);

    $teamMatch['climbs'] = array();

    $query = "SELECT * FROM matchclimbs WHERE teamMatchID = :teamMatchID";
    $params = array(":teamMatchID" => $teamMatch['id']);
    $teamMatch['climbs'] = $helper->queryDB($query,$params,false);

    $teamMatch['autos'] = array();

    $query = "SELECT * FROM matchautos WHERE teamMatchID = :teamMatchID";
    $params = array(":teamMatchID" => $teamMatch['id']);
    $teamMatch['autos'] = $helper->queryDB($query,$params,false);

    $teamMatch['ratings'] = array();

    $query = "SELECT * FROM matchratings WHERE teamMatchID = :teamMatchID";
    $params = array(":teamMatchID" => $teamMatch['id']);
    $teamMatch['ratings'] = $helper->queryDB($query,$params,false);

    array_push($match['teamMatches'],$teamMatch);

  }

  array_push($finalData['matchData'],$match);

}

$finalData['devices'] = array();

$query = "SELECT * FROM devices";
$params = null;
$result = $helper->queryDB($query,$params,false);

foreach($result as $row){
  array_push($finalData['devices'],$row['deviceID']);
}

echo json_encode($finalData);





?>