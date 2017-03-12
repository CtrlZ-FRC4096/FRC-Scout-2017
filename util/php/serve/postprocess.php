<?php
/**
 * Created by PhpStorm.
 * User: Jayasurya
 * Date: 3/7/2017
 * Time: 4:19 PM
 */
include($_SERVER['DOCUMENT_ROOT']."/util/php/include_classes.php");

function postProcessTeamMatch($teamMatchID,$ballCapacity, $hasGroundFeeder, $debug=false){
  $helper = new Helper();
  $query = "SELECT * FROM matchballfeeds_preprocess WHERE teamMatchID = :teamMatchID";
  $feedRows = $helper->queryDB($query,array(":teamMatchID"=>$teamMatchID),false);
  foreach($feedRows as &$feedRow){
    $feedRow['eventType'] = "feedBall";
  }
  $query = "SELECT * FROM matchshoots_preprocess WHERE teamMatchID = :teamMatchID";
  $shootRows = $helper->queryDB($query,array(":teamMatchID"=>$teamMatchID),false);
  foreach($shootRows as &$feedRow){
    $feedRow['eventType'] = "shoot";
  }
  $allRows = array_merge($feedRows,$shootRows);
  function cmp($a,$b){
    if($a["mode"] == $b["mode"]){
      return ($a['orderID'] < $b['orderID'] ? -1: 1);
    }
    else{
      return ($a['mode'] == "tele" ? 1 : -1);
    }
  }
  usort($allRows,'cmp');
//  var_dump($allRows);
  $tank = 0;
  $toAdd = array();
  $discrepancies = array();
  foreach($allRows as $index => &$row){
    if($row['eventType'] == "feedBall"){

      if($row['inputMethod'] == "count"){
        $before = $tank;
        $after = $tank + $row['count'];
        if($after > $ballCapacity){
          array_push($discrepancies,array("discrepancyType"=>"Feed > Capacity","eventType"=>"feedBall","orderID" => $row['orderID'], "mode"=> $row['mode'], "time"=>"after", "expected"=>$ballCapacity,"relationship"=>"less than", "received"=>$after));//insert discrepancy
          $after = $ballCapacity; //set to $ballCapacity. User was inputting count, so it was likely an approximation error.
        }
        // now $tank is the same as $before
        //execute feed action so $tank becomes same as $after
        $row['computedDelta'] = $after - $before;
        $tank = $after;
      }
      else{
        $before = ($row['before'] / 100) * $ballCapacity;
        $after = ($row['after'] / 100) * $ballCapacity;
        if($before > $tank){
          //they had more before than expected
          //could have ground fed
          if($hasGroundFeeder){
            //insert ground feed
            $groundFeedAmount = $before - $tank;
            $toAdd[$index] = (array("eventType" => "groundFeed","delta" => $groundFeedAmount, "afterAction" => $before, "mode" => $row['mode']));
          }
          else{
            // insert discrepancy.
            array_push($discrepancies,array("discrepancyType"=>"Before != Expected","eventType"=>"feedBall","orderID" => $row['orderID'], "mode"=> $row['mode'], "time"=>"before", "expected"=>$tank,"relationship"=>"equal to", "received"=>$before));
          }
          $tank = $before;
        }
        else if($before < $tank){
          // they had less than expected
          // Balls may have fallen out.
          // insert discrepancy.
          array_push($discrepancies,array("discrepancyType"=>"Before != Expected","eventType"=>"feedBall","orderID" => $row['orderID'], "mode"=> $row['mode'], "time"=>"before", "expected"=>$tank,"relationship"=>"equal to", "received"=>$before));
          $tank = $before;
        }
        $row['computedDelta'] = $after - $before;
        $tank = $after;
      }
      $row['afterAction'] = $tank;

    }
    else if($row['eventType'] == "shoot"){
      $oldTank = $tank;
      if($row["inputMethod"] == "count"){
        $after = ($row['leftover']/100) * $ballCapacity;
      }
      else {
        $after = ($row['after']/100) * $ballCapacity;
      }
      if($row["inputMethod"] == "count"){
        $shot = $row['scored'] + $row['missed'];
        $scored = $row['scored'];
        $missed = $row['missed'];
      }
      else{
        $shot = (($row['before'] - $row['after']) / 100) * $ballCapacity;
      }
     // after cannot be larger than ballCapacity
      if($row["inputMethod"] == "count"){
        $before = $after + $shot;

        if($before > $ballCapacity){
          //insert discrepancy
          array_push($discrepancies,array("discrepancyType"=>"Before > Capacity","eventType"=>"shoot","orderID" => $row['orderID'], "mode"=> $row['mode'], "time"=>"before", "expected"=>$ballCapacity,"relationship"=>"less than", "received"=>$before));//insert discrepancy
          $excess = $before - $ballCapacity;
          $before = $ballCapacity;
          $after -= $excess;
          if($after < 0){
            $decreaseScoredBy = round(-$after * ($row['scored'] / $shot));
            $decreaseMissedBy = -$after - $decreaseScoredBy;
            $scored -=$decreaseScoredBy;
            $missed -=$decreaseMissedBy;
            $shot = $scored + $missed;
            $after = 0;
          }
        }
      }
      else {
        $before = ($row['before']/100) * $ballCapacity;
      }
      // before cannot be larger than ballCapacity


      if($before > $tank){
        //ground feed may have occured
        if($hasGroundFeeder){
          //insert ground feed
          $groundFeedAmount = $before - $tank;
          $toAdd[$index] = (array("eventType" => "groundFeed","delta" => $groundFeedAmount, "afterAction" => $before, "mode" => $row['mode']));
        }
        else{
          // insert discrepancy.
          array_push($discrepancies,array("discrepancyType"=>"Before != Expected","eventType"=>"shoot","orderID" => $row['orderID'], "mode"=> $row['mode'], "time"=>"before", "expected"=>$tank,"relationship"=>"equal to", "received"=>$before));
        }
        $tank = $before;
      }
      else if($before < $tank){
        // they had less than expected
        // Balls may have fallen out.
        // insert discrepancy.
        array_push($discrepancies,array("discrepancyType"=>"Before != Expected","eventType"=>"shoot","orderID" => $row['orderID'], "mode"=> $row['mode'], "time"=>"before", "expected"=>$tank,"relationship"=>"equal to", "received"=>$before));
        $tank = $before;
      }

      // now $tank is equal to $before
      if($row["inputMethod"] == "count"){
        $row['computedScored'] = $scored;
        $row['computedMissed'] = $missed;
      }
      else{
        $row['computedScored'] = round(($shot) * ($row['accuracy']/100));
        $row['computedMissed'] = round(($shot) * ((100 - $row['accuracy'])/100));
      }
      $tank = $after;
      $tank = round($tank);
      $row['afterAction'] = $tank;

    }
  }
  foreach($toAdd as $index=>$addRow){
//    var_dump($index);

//    var_dump($allRows);
    array_splice($allRows,$index,0,array($addRow));
  }


if($debug){
    var_dump($allRows);
  var_dump($discrepancies);
  return;
}

  $helper->con = $helper->connectToDB();
  $helper->con->beginTransaction();

  foreach($allRows as $record){

    $query="";
    $params = array();

    switch($record['eventType']){
      case "feedBall":

        try {
          $query = "INSERT INTO matchballfeeds(teamMatchID, orderID, `mode`, location, delta)
                              VALUES (:teamMatchID, :orderID, :mode, :location, :delta)";
          $stmt = $helper->con->prepare($query);
          $stmt->bindValue(":teamMatchID", $teamMatchID);
          $stmt->bindValue(":orderID", $record['orderID']);
          $stmt->bindValue(":mode", $record['mode']);
          $stmt->bindValue(":location", trim($record['location']));
          $stmt->bindValue(":delta", $record['computedDelta']);
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

      case "groundFeed":

        try {
          $query = "INSERT INTO matchballfeeds(teamMatchID, orderID, `mode`, location, delta)
                              VALUES (:teamMatchID, :orderID, :mode, :location, :delta)";
          $stmt = $helper->con->prepare($query);
          $stmt->bindValue(":teamMatchID", $teamMatchID);
          $stmt->bindValue(":orderID",null);
          $stmt->bindValue(":mode", $record['mode']);
          $stmt->bindValue(":location", "ground");
          $stmt->bindValue(":delta", $record['delta']);
          $stmt->execute();
        }
        catch (PDOException $e) {
          echo $e->getMessage();
          echo "groundFeed";
          $helper->con->rollBack();
          echo "fail";
          return;
        }

        break;
      case "shoot":

        try {
          $query = "INSERT INTO matchshoots( teamMatchID,  orderID, `mode`,  coordX,  coordY,  scored,  missed,  highLow)
                                      VALUES(:teamMatchID,  :orderID, :mode,  :coordX,  :coordY,  :scored,  :missed,  :highLow)";
          $stmt = $helper->con->prepare($query);
          $stmt->bindValue(":teamMatchID", $teamMatchID);
          $stmt->bindValue(":orderID", $record['orderID']);
          $stmt->bindValue(":mode", $record['mode']);
          $stmt->bindValue(":coordX", floatval($record['coordX']));
          $stmt->bindValue(":coordY", floatval($record['coordY']));
          $stmt->bindValue(":scored", intval($record['scored']));
          $stmt->bindValue(":missed", intval($record['missed']));
          $stmt->bindValue(":highLow", intval($record['highLow']));
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

  foreach($discrepancies as $d){
    try{
      $query = "INSERT INTO postprocessdiscrepancies(teamMatchID, eventType, orderID, `mode`, `time`, expected, relationship, received, discrepancyType)
                                            VALUES(:teamMatchID, :eventType, :orderID, :mode, :time, :expected, :relationship, :received, :discrepancyType) ";

      $stmt = $helper->con->prepare($query);
      $stmt->bindValue(":teamMatchID",$teamMatchID);
      $stmt->bindValue(":eventType",$d['eventType']);
      $stmt->bindValue(":orderID",$d['orderID']);
      $stmt->bindValue(":mode",$d['mode']);
      $stmt->bindValue(":time",$d['time']);
      $stmt->bindValue(":expected",$d['expected']);
      $stmt->bindValue(":relationship",$d['relationship']);
      $stmt->bindValue(":received",$d['received']);
      $stmt->bindValue(":discrepancyType",$d['discrepancyType']);
      $stmt->execute();
    }
    catch (PDOException $e) {
      echo $e->getMessage();
      echo "discrepancies";
      $helper->con->rollBack();
      echo "fail";
      return;
    }

  }

  try{
    $query = "UPDATE teamMatches SET postprocessed = 1, ready = 1 WHERE id = :id";
    $stmt = $helper->con->prepare($query);
    $stmt->bindValue(":id",$teamMatchID);
    $stmt->execute();
  }
  catch(PDOException $e){
    echo $e->getMessage();
    echo "discrepancies";
    $helper->con->rollBack();
    echo "fail";
    return;
  }


  $helper->con->commit();
//  echo "Success";

}

//postProcessTeamMatch(568,58,false,true);

?>