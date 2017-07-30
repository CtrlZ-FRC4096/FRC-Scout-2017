<?php
/**
 * Created by PhpStorm.
 * User: Jayasurya
 * Date: 3/8/2016
 * Time: 7:27 PM
 */
include($_SERVER['DOCUMENT_ROOT']."/util/php/include_classes.php");

$helper = new Helper();

$helper->autoCycleDBConnection = false;
$helper->con = $helper->connectToDB();

$json = file_get_contents($_FILES['file']['tmp_name']);
$data = json_decode($json,true);

foreach($data['devices'] as $deviceID){
  $query = "INSERT IGNORE INTO devices (deviceID) VALUES (:deviceID)";
//
//  WHERE NOT EXISTS (
//    SELECT deviceID FROM devices WHERE deviceID = :deviceID2
//            ) LIMIT 1;

  $params = array(":deviceID" => $deviceID);
  $result = $helper->queryDB($query,$params,false);
}

foreach($data['matchData'] as $match){
  $query = "SELECT id
            FROM matches
            WHERE matchNumber = :matchNumber
              AND compID = :compID";
  $params = array(":matchNumber" => $match['matchNumber'],":compID"=> $match['compID']);
  $result = $helper->queryDB($query,$params,false);
  $matchID = $result[0]['id'];


  $query = "SELECT COALESCE(sum(a), 0)  as ct FROM (
            SELECT COUNT(*) as a FROM matchballfeeds WHERE teamMatchID IN (SELECT id FROM teamMatches WHERE matchID = :matchID1)
            UNION
            SELECT COUNT(*) as a FROM matchgearfeeds WHERE teamMatchID IN (SELECT id FROM teamMatches WHERE matchID = :matchID2)
            UNION
            SELECT COUNT(*) as a FROM matchgears WHERE teamMatchID IN (SELECT id FROM teamMatches WHERE matchID = :matchID3)
            UNION
            SELECT COUNT(*) as a FROM matchshoots WHERE teamMatchID IN (SELECT id FROM teamMatches WHERE matchID = :matchID4)
            UNION
            SELECT COUNT(*) as a FROM matchclimbs WHERE teamMatchID IN (SELECT id FROM teamMatches WHERE matchID = :matchID5)
            UNION
            SELECT COUNT(*) as a FROM matchautos WHERE teamMatchID IN (SELECT id FROM teamMatches WHERE matchID = :matchID6)
            UNION
            SELECT COUNT(*) as a FROM matchratings WHERE teamMatchID IN (SELECT id FROM teamMatches WHERE matchID = :matchID7)) as b
";

  $params = array(
    ":matchID1" => $matchID,
    ":matchID2" => $matchID,
    ":matchID3" => $matchID,
    ":matchID4" => $matchID,
    ":matchID5" => $matchID,
    ":matchID6" => $matchID,
    ":matchID7" => $matchID
  );

  $result = $helper->queryDB($query,$params,false);
  if($result[0]['ct'] > 0){
    $matchAction = "Replaced";
  }
  else{
    $matchAction = "Added";
  }
  $params = array(
    ":matchID" => $matchID
  );
  $query = "DELETE FROM matchautos      WHERE teamMatchID IN (SELECT id FROM teammatches WHERE matchID = :matchID)";
    $result = $helper->queryDB($query,$params,false);
  $query = "DELETE FROM matchclimbs     WHERE teamMatchID IN (SELECT id FROM teammatches WHERE matchID = :matchID)";
    $result = $helper->queryDB($query,$params,false);
  $query = "DELETE FROM matchgearfeeds  WHERE teamMatchID IN (SELECT id FROM teammatches WHERE matchID = :matchID)";
    $result = $helper->queryDB($query,$params,false);
  $query = "DELETE FROM matchgears      WHERE teamMatchID IN (SELECT id FROM teammatches WHERE matchID = :matchID)";
    $result = $helper->queryDB($query,$params,false);
  $query = "DELETE FROM matchratings    WHERE teamMatchID IN (SELECT id FROM teammatches WHERE matchID = :matchID)";
    $result = $helper->queryDB($query,$params,false);
  $query = "DELETE FROM matchballfeeds  WHERE teamMatchID IN (SELECT id FROM teammatches WHERE matchID = :matchID)";
    $result = $helper->queryDB($query,$params,false);
  $query = "DELETE FROM matchshoots     WHERE teamMatchID IN (SELECT id FROM teammatches WHERE matchID = :matchID)";
    $result = $helper->queryDB($query,$params,false);


  $teams = array();
  foreach($match['teamMatches'] as $teamMatch){
    array_push($teams,$teamMatch['teamNumber']);
  }

  $query = "DELETE FROM teammatches WHERE matchID = :matchID
            AND teamNumber NOT IN(" . implode(', ', $teams) . ")";

  $query = "SELECT * FROM teamMatches WHERE matchID = :matchID";
  $params = array(":matchID" => $matchID);
  $result = $helper->queryDB($query,$params,false);
  foreach($match['teamMatches'] as $teamMatch){
    $matchFound = false;
    foreach($result as $row){
      if($row['teamNumber'] == $teamMatch['teamNumber']){
        $teamMatch['id'] = $row['id'];
        $matchFound = true;
      }
    }
    if(!$matchFound){
      $query = "INSERT INTO teammatches(
                    matchID,
                    side,
                    position,
                    teamNumber,
                    deviceID,
                    collectionStarted,
                    collectionEnded,
                    scouterID,
                    postprocessed,
                    ready)
                    VALUES(
                    :matchID,
                    :side,
                    :position,
                    :teamNumber,
                    :deviceID,
                    :collectionStarted,
                    :collectionEnded,
                    :scouterID,
                    :postprocessed,
                    :ready
                    )";
      $params = array(
        ":matchID" => $teamMatch['matchID'],
        ":side" => $teamMatch['side'],
        ":position" => $teamMatch['position'],
        ":teamNumber" => $teamMatch['teamNumber'],
        ":deviceID" => $teamMatch['deviceID'],
        ":collectionStarted" => $teamMatch['collectionStarted'],
        ":collectionEnded" => $teamMatch['collectionEnded'],
        ":scouterID" => $teamMatch['scouterID'],
        ":postprocessed" => $teamMatch['postprocessed'],
        ":ready" => $teamMatch['ready']
      );
      $helper->queryDB($query,$params,true);

      $teamMatch['id'] = $helper->con->lastInsertId();
    }


    $query = "UPDATE teamMatches
              SET
                    matchID = :matchID,
                    side = :side,
                    position = :position,
                    teamNumber = :teamNumber,
                    deviceID = :deviceID,
                    collectionStarted = :collectionStarted,
                    collectionEnded = :collectionEnded,
                    scouterID = :scouterID,
                    postprocessed = :postprocessed,
                    ready = :ready
              WHERE id = :id";

    $params = array(
      ":matchID" => $teamMatch['matchID'],
      ":side" => $teamMatch['side'],
      ":position" => $teamMatch['position'],
      ":teamNumber" => $teamMatch['teamNumber'],
      ":deviceID" => $teamMatch['deviceID'],
      ":collectionStarted" => $teamMatch['collectionStarted'],
      ":collectionEnded" => $teamMatch['collectionEnded'],
      ":scouterID" => $teamMatch['scouterID'],
      ":postprocessed" => $teamMatch['postprocessed'],
      ":ready" => $teamMatch['ready'],
      ":id" => $teamMatch['id']
    );
    $helper->queryDB($query,$params,true);

    foreach($teamMatch['ballfeeds'] as $ballfeed){
      $query = "INSERT INTO matchballfeeds(teamMatchID, orderID, `mode`, delta, location) VALUES(:teamMatchID, :orderID, :mode, :delta, :location)";
      $params = array(":teamMatchID" => $teamMatch['id'],
                      ":orderID" => $ballfeed['orderID'],
                      ":mode" => $ballfeed['mode'],
                      ":delta" => $ballfeed['delta'],
                      ":location" => $ballfeed['location']);
      $helper->queryDB($query,$params,false);
    }
    foreach($teamMatch['gearfeeds'] as $gear){
      $query = "INSERT INTO matchgearfeeds(teamMatchID, orderID, `mode`, result, method) VALUES (:teamMatchID, :orderID, :mode, :result, :method)";
      $params = array(":teamMatchID" => $teamMatch['id'],
                      ":orderID" => $gear['orderID'],
                      ":mode" => $gear['mode'],
                      ":result" => $gear['result'],
                      ":method" => $gear['method']
      );
      $helper->queryDB($query,$params,false);

    }
    foreach($teamMatch['shoots'] as $shoot){

      $query = "INSERT INTO matchshoots(teamMatchID, orderID, `mode`, coordX, coordY, highLow, scored, missed) VALUES (:teamMatchID, :orderID, :mode, :coordX, :coordY, :highLow, :scored, :missed)";
      $params = array(":teamMatchID" => $teamMatch['id'],
        ":orderID" => $shoot['orderID'],
        ":mode" => $shoot['mode'],
        ":coordX" => $shoot['coordX'],
        ":coordY" => $shoot['coordY'],
        ":highLow" => $shoot['highLow'],
        ":scored" => $shoot['scored'],
        ":missed" => $shoot['missed']
      );
      $helper->queryDB($query,$params,false);
    }

    foreach($teamMatch['gears'] as $gear){
      $query = "INSERT INTO matchgears(teamMatchID, orderID, `mode`, location, result) VALUES (:teamMatchID, :orderID, :mode, :location, :result)";
      $params = array(":teamMatchID" => $teamMatch['id'],
        ":orderID" => $gear['orderID'],
        ":mode" => $gear['mode'],
        ":location" => $gear['location'],
        ":result" => $gear['result']
      );
      $helper->queryDB($query,$params,false);
    }
    foreach($teamMatch['climbs'] as $climb){
      $query = "INSERT INTO matchclimbs(teamMatchID, touchpad, duration) VALUES (:teamMatchID, :touchpad, :duration)";
      $params = array(":teamMatchID" => $teamMatch['id'],
        ":touchpad" => $climb['touchpad'],
        ":duration" => $climb['duration']
      );
      $helper->queryDB($query,$params,false);
    }
    foreach($teamMatch['autos'] as $auto){
      $query = "INSERT INTO matchautos(teamMatchID, crossedLine) VALUES (:teamMatchID, :crossedLine)";
      $params = array(":teamMatchID" => $teamMatch['id'],
        ":crossedLine" => $auto['crossedLine']
      );
      $helper->queryDB($query,$params,false);
    }
    foreach($teamMatch['ratings'] as $rating){
      $query = "INSERT INTO matchratings(teamMatchID, ballGroundFeeding, ballLoadingLaneFeeding, ballShootingSpeed, gearGroundFeeding, gearLoadingLaneFeeding, gearPlacingSpeed, abilityToDefend, abilityToEscapeDefense) VALUES (:teamMatchID, :ballGroundFeeding, :ballLoadingLaneFeeding, :ballShootingSpeed, :gearGroundFeeding, :gearLoadingLaneFeeding, :gearPlacingSpeed, :abilityToDefend, :abilityToEscapeDefense)";
      $params = array(
      ":teamMatchID" => $rating['teamMatchID'],
      ":ballGroundFeeding" => $rating['ballGroundFeeding'],
      ":ballLoadingLaneFeeding" => $rating['ballLoadingLaneFeeding'],
      ":ballShootingSpeed" => $rating['ballShootingSpeed'],
      ":gearGroundFeeding" => $rating['gearGroundFeeding'],
      ":gearLoadingLaneFeeding" => $rating['gearLoadingLaneFeeding'],
      ":gearPlacingSpeed" => $rating['gearPlacingSpeed'],
      ":abilityToDefend" => $rating['abilityToDefend'],
      ":abilityToEscapeDefense" => $rating['abilityToEscapeDefense']
      );
      $helper->queryDB($query,$params,false);
    }

  }
}

$helper->con = null;
$helper->autoCycleDBConnection = true;
echo "Success";

?>