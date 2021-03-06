<?php
/**
 * Created by PhpStorm.
 * User: Jayasurya
 * Date: 1/31/2016
 * Time: 5:51 PM
 */

class Helper {

  public $con;

  public $LEFT_TEAM = "red";
  public $RIGHT_TEAM = "blue";
  public $BOILERS_POS = "close";
  public $autoCycleDBConnection = true;

  const HALF_FIELD_LENGTH_INCHES = 492.7;
  const HALF_FIELD_HEIGHT_INCHES = 360;


  public function connectToDB()
  {
       $host = 'localhost';
       $username = 'user';
       $password = 'user';

    $dbname = 'scouting2017_v2';
    try {
      $mycon = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
      $mycon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $mycon->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      return $mycon;
    } catch (PDOException $e) {
      return "Failed to connect to MySQL: " . $e->getMessage();
    }
  }

  public function queryDB($query, $params, $update)
  {
    if($this->autoCycleDBConnection){
      $this->con = $this->connectToDB();
    }

    $stmt = $this->con->prepare($query);
    if ($params !== null) {
      foreach ($params as $key => $param) {
        $stmt->bindValue($key, trim($param));
      }
    }
    try {
      $stmt->execute();
    } catch (PDOException $e) {
      // echo $e->getMessage();
      echo "<h2 style='color:red'>ERROR!</h2>";
      echo $e->getMessage();
      return $e;
    }

    if (!$update) {
      $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if($this->autoCycleDBConnection) {
        $this->con = null;
      }
      return $r;
    } else {
      if($this->autoCycleDBConnection) {
        $this->con = null;
      }

      return true;
    }
  }

  public function getCurrentCompetition(){

    $query = "SELECT id FROM competitions WHERE current = 1 LIMIT 0,1";
    $params = null;
    $result = $this->queryDB($query,$params,false);
    return new Competition(intval($result[0]['id']));


  }

  public function getTeamNumbersForCompetition($compID){

    $query = "SELECT DISTINCT teamNumber FROM teammatches
                  JOIN matches ON matches.id = teammatches.matchID
                  JOIN competitions ON competitions.id = matches.compID
                  WHERE compID = :compID
              ORDER BY teamNumber ASC";
    $params = array(":compID" => $compID);
    $result = $this->queryDB($query,$params,false);
    $arr = array();
    foreach($result as $row){
      array_push($arr,$row['teamNumber']);
    }

    return $arr;
  }



  function generateRandomString($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  function getRandomDeviceID(){
    $id = $this->generateRandomString();
    $query = "SELECT COUNT(*) as amt FROM devices WHERE deviceID = :id";
    $params = array(":id" => $id);
    $result = $this->queryDB($query,$params,false);
    if(intval($result[0]['amt']) > 0){
      return $this->getRandomDeviceID();
    }
    else{
      return $id;
    }
  }


  public function addDevice($deviceID){
    $query = "INSERT IGNORE INTO devices(deviceID)
                                   VALUES(:id)";
    $params = array(":id" => $deviceID);
    $result = $this->queryDB($query,$params, true);
    return $result;
  }

  public function getDevices(){
    $query = "SELECT * FROM devices";
    $result = $this->queryDB($query,null, false);
    return $result;
  }

  public function getCurrentStatusOfUser($deviceID, $compID){
    $query = "SELECT *
              FROM teammatches
              JOIN matches ON matches.id = teammatches.matchID
              WHERE deviceID = :deviceID
              AND matches.compID = :compID
              AND collectionEnded = 0
              ORDER BY matchNumber DESC
              LIMIT 0,1";
    $params = array(":deviceID" => $deviceID,":compID" => $compID);
    $result = $this->queryDB($query,$params,false);

    if(count($result) == 0){
      return "teamSelection";
    }
    else{
      return "startMatch-" . $result[0]['matchNumber'];
    }

  }

  public function setCollectionStarted($matchID, $teamNumber)
  {
    $query = "UPDATE teammatches SET collectionStarted = 1 WHERE matchID = :matchID AND teamNumber = :teamNumber";

    $params = array(
      ":matchID" => $matchID,
      ":teamNumber" => $teamNumber);

//   var_dump($params);
    $result = $this->queryDB($query, $params, true);
    return $result;
  }


  public function getScouters(){
    $query = "SELECT * FROM scouters";
    $params = null;
    $result = $this->queryDB($query,$params,false);
    return $result;
    }



} 