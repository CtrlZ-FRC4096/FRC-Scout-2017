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

$helper = new Helper();
$con = $helper->connectToDB();
$query = "SELECT * FROM teammatches;";
$params = null;
$result = $helper->queryDB($query,$params,false);

$scoutersQuery = $con->prepare("SELECT id from scouters");
$scoutersQuery->execute();
$scouters = $scoutersQuery->fetchAll(PDO::FETCH_COLUMN, 0);

$teamsQuery = $con->prepare("SELECT teamNumber from teams");
$teamsQuery->execute();
$teams = $teamsQuery->fetchAll(PDO::FETCH_COLUMN, 0);

foreach($teams as $team){
  //CREATE TEAM PROFILE
  $shoots = rand(0,10) < 7;
  $shootsHigh = rand(0,1) && $shoots;
  $shootsLow = $shoots ? ($shootsHigh ? rand(0,1): 1) : 0;
  $gears = rand(0,1);
  $gearSuccess = rand(0,20) * 5;
  $gearFeedSuccess = rand(0,20) * 5;
  $loadingLaneFeedsBalls = rand(0,1);
  $groundFeedsBalls = $loadingLaneFeedsBalls ? rand(0,1) : 1;
  $loadingLaneFeedsGears = rand(0,1);
  $groundFeedsGears = $loadingLaneFeedsGears ? rand(0,1) : 1;
  $highShootsAccuracy = rand(0,20) * 5;
  $highShootsVariability = rand(0,100);
  $lowShootsAccuracy = rand(0,20) * 5;
  $lowShootsVariability = rand(0,100);
  $actionsPerMatch = rand(0,20);
  $actionsVariability = rand(0,6);

  $autoFeeds = rand(0,1) && $loadingLaneFeedsBalls && $shoots;
  $autoShoots = rand(0,1) && $shoots;
  $autoShootLevelHigh = rand(0,1) && $shootsHigh;
  $autoGear = rand(0,1) && $gears;
  $crossesLine = rand(0,1) || $autoFeeds;

  $teleShootsHigh = (rand(0,1) && $shootsHigh) || ($autoShootLevelHigh && $autoShoots);
  $teleShootsLow = (rand(0,1) && $shootsLow) || (!$autoShootLevelHigh && $autoShoots);
  $ballCapacity = $shoots ? rand(20,80) : 0;
  $teleGear = $gears;

  echo
"<h1>$team</h1>" .
"<table>".
"<tr><td>shoots</td><td>" . b($shoots) . "</td></tr>".
"<tr><td>shootsHigh</td><td>" . b($shootsHigh) . "</td></tr>".
"<tr><td>shootsLow</td><td>" . b($shootsLow) . "</td></tr>".
"<tr><td>gears</td><td>" . b($gears) . "</td></tr>".
"<tr><td>gearSuccess</td><td>" . $gearSuccess . "</td></tr>".
"<tr><td>gearFeedSuccess</td><td>" . $gearFeedSuccess . "</td></tr>".
"<tr><td>loadingLaneFeedsBalls</td><td>" . b($loadingLaneFeedsBalls) . "</td></tr>".
"<tr><td>groundFeedsBalls</td><td>" . b($groundFeedsBalls) . "</td></tr>".
"<tr><td>loadingLaneFeedsGears</td><td>" . b($loadingLaneFeedsGears) . "</td></tr>".
"<tr><td>groundFeedsGears</td><td>" . b($groundFeedsGears) . "</td></tr>".
"<tr><td>highShootsAccuracy</td><td>" . $highShootsAccuracy . "</td></tr>".
"<tr><td>highShootsVariability</td><td>" . $highShootsVariability . "</td></tr>".
"<tr><td>lowShootsAccuracy</td><td>" . $lowShootsAccuracy . "</td></tr>".
"<tr><td>lowShootsVariability</td><td>" . $lowShootsVariability . "</td></tr>".
"<tr><td>actionsPerMatch</td><td>" . b($actionsPerMatch) . "</td></tr>".
"<tr><td>actionsVariability</td><td>" . b($actionsVariability) . "</td></tr>".
"<tr><td>autoFeeds</td><td>" . b($autoFeeds) . "</td></tr>".
"<tr><td>autoShoots</td><td>" . b($autoShoots) . "</td></tr>".
"<tr><td>autoShootLevelHigh</td><td>" . b($autoShootLevelHigh) . "</td></tr>".
"<tr><td>autoGear</td><td>" . b($autoGear) . "</td></tr>".
"<tr><td>crossesLine</td><td>" . b($crossesLine) . "</td></tr>".
"<tr><td>teleShootsHigh</td><td>" . b($teleShootsHigh) . "</td></tr>".
"<tr><td>teleShootsLow</td><td>" . b($teleShootsLow) . "</td></tr>".
"<tr><td>ballCapacity</td><td>" . $ballCapacity . "</td></tr>".
"<tr><td>teleGear</td><td>" . b($teleGear) . "</td></tr>".
"</table>";




  $query = "SELECT * FROM teammatches WHERE teamNumber=:team;";
  $params = array(
    ":team" => $team
  );
  $result = $helper->queryDB($query,$params,false);

  foreach($result as $teamMatch) {

    $autoActions = [];
    $teleActions = [];

    //AUTO MODE
    if($autoGear){
//      echo "Auto Gear 1 <br/>";
      $success = rand(0,100) < $gearSuccess;
      array_push($autoActions,createGearAction("center",$success));
    }
    if($autoFeeds){
//      echo "auto feed + shoot <br/>";
      $numBalls = $ballCapacity > 50 ? 50 : $ballCapacity;
      array_push($autoActions,createBallFeedAction($numBalls,"boilerSideClose"));
      $shootsAccuracy = $autoShootLevelHigh ? $highShootsAccuracy : $lowShootsAccuracy;
      $shootsVariability = $autoShootLevelHigh ? $highShootsVariability : $lowShootsVariability;
      $scored = getNumScored($numBalls,$shootsAccuracy,$shootsVariability);
      $missed = $numBalls - $scored;
      array_push($autoActions,createShootAction(125,25,$autoShootLevelHigh,$scored,$missed));
    }
    else{
      if($autoShoots){
//        echo "auto shoot 10 <br/>";
        $shootsAccuracy = $autoShootLevelHigh ? $highShootsAccuracy : $lowShootsAccuracy;
        $shootsVariability = $autoShootLevelHigh ? $highShootsVariability : $lowShootsVariability;
        $scored = getNumScored(10,$shootsAccuracy,$shootsVariability);
        $missed = 10 - $scored;
        $coord = getRandomShootCoordinate();
        array_push($autoActions,createShootAction($coord["x"],$coord["y"],$autoShootLevelHigh,$scored,$missed));
      }
    }

    //TELE MODE
    $ballsInTank = 0;
    $possessingGear = 0;

    for($i=0;$i<getActionsInMatch($actionsPerMatch,$actionsVariability);$i++){
      $action = rand(0,1) ? "gear" : "shoot";
//      echo "action:$action<br/>";
//      echo "ballsInTank:$ballsInTank<br/>";
//      echo "possessingGear:$possessingGear<br/>";
      if($action == "gear" && $gears){
        if($possessingGear){
          $success = rand(0,100) < $gearSuccess;
          array_push($teleActions,createGearAction("center",$success));
          $possessingGear = false;
          continue;
        }
        else{
          $success = rand(0,100) < $gearFeedSuccess;
          $method = ($groundFeedsGears && $loadingLaneFeedsGears) ?
              (rand(0,1)? "ground" : "dropped") :
              ($groundFeedsGears ? "ground" : "dropped");
          array_push($teleActions,createGearFeedAction($success,$method));
          $possessingGear = $success;
          continue;
        }
      }
      elseif($action == "shoot" && $shoots){
        if($ballsInTank){
          $high = ($shootsHigh && $shootsLow) ? rand(0,1) : $shootsHigh ;
          $shootsAccuracy = $high ? $highShootsAccuracy : $lowShootsAccuracy;
          $shootsVariability = $high ? $highShootsVariability : $lowShootsVariability;
          $scored = getNumScored($ballsInTank,$shootsAccuracy,$shootsVariability);
          $missed = $ballsInTank - $scored;
          $coord = getRandomShootCoordinate();
          if(!$high){
            $coord = array("x" =>56, "y" =>42);
          }
          array_push($teleActions,createShootAction($coord["x"],$coord["y"],$autoShootLevelHigh,$scored,$missed));
          $ballsInTank = 0;
        }
        else{
          $locations = ["returnFar","returnClose","loadingSideFar","loadingSideClose","overflow","boilerSideClose","boilerSideMiddle","boilerSideFar"];
          $location = ($groundFeedsBalls && $loadingLaneFeedsBalls) ?
            (rand(0,1)? "ground" : $locations[array_rand($locations)]) :
            ($groundFeedsBalls ? "ground" : $locations[array_rand($locations)]);

          $delta = rand(10,$ballCapacity);
          if(in_array($location,["loadingSideFar","loadingSideClose","boilerSideClose","boilerSideMiddle","boilerSideFar"])){
            $delta = $delta > 50 ? 50 : $delta;
          }

          array_push($teleActions,createBallFeedAction($delta,$location));
          $ballsInTank = $delta;
          continue;
        }
      }
    }

    //RATINGS

    $ballShootAccuracyRating = rand(0.7*arr_avg([$highShootsAccuracy,$lowShootsAccuracy]),1.3*arr_avg([$highShootsAccuracy,$lowShootsAccuracy]));
    $ballShootAccuracyRating = round($ballShootAccuracyRating /2);
    $ballShootAccuracyRating = $ballShootAccuracyRating  > 5 ? 5 : $ballShootAccuracyRating ;

    $gearFeedingRating = rand(0.7*$gearFeedSuccess,1.3*$gearFeedSuccess);
    $gearFeedingRating = round($gearFeedingRating /2);
    $gearFeedingRating = $gearFeedingRating  > 5 ? 5 : $gearFeedingRating ;

    $gearPlacingRating = rand(0.7*$gearSuccess,1.3*$gearSuccess);
    $gearPlacingRating = round($gearPlacingRating /2);
    $gearPlacingRating = $gearPlacingRating  > 5 ? 5 : $gearPlacingRating ;


    $ratings = array(
      ":ballGroundFeeding" => $groundFeedsBalls ? rand(1,5) : 0 ,
      ":ballLoadingLaneFeeding" => $loadingLaneFeedsBalls ? rand(1,5) : 0,
      ":ballShootingAccuracy" => $shoots ? $ballShootAccuracyRating : 0,
      ":ballShootingSpeed" => $shoots ? rand(1,5) : 0,
      ":gearGroundFeeding" => $groundFeedsGears ? $gearFeedingRating : 0,
      ":gearLoadingLaneFeeding" => $loadingLaneFeedsGears ? $gearFeedingRating : 0,
      ":gearPlacingAccuracy" => $gears ? $gearPlacingRating : 0,
      ":gearPlacingSpeed" => $gears ? rand(1,5) : 0,
      ":abilityToDefend" => rand(0,5),
      ":abilityToEscapeDefense" => rand(0,5)
    );

    //INSERT ALL

    $counter = 1;
    foreach($autoActions as &$autoAction){
      $autoAction["params"][":teamMatchID"] = intval($teamMatch['id']);
      $autoAction["params"][":orderID"] = $counter;
      $autoAction["params"][":mode"] = "auto";
      $helper->queryDB($autoAction["query"],$autoAction["params"],true);
      $counter +=1;
    }
    foreach($teleActions as &$teleAction){
      $teleAction["params"][":teamMatchID"] = intval($teamMatch['id']);
      $teleAction["params"][":orderID"] = $counter;
      $teleAction["params"][":mode"] = "tele";
      $helper->queryDB($teleAction["query"],$teleAction["params"],true);
      $counter +=1;
    }

    $ratingsQuery = "INSERT INTO matchratings(teamMatchID, ballGroundFeeding, ballLoadingLaneFeeding, ballShootingAccuracy, ballShootingSpeed, gearGroundFeeding, gearLoadingLaneFeeding, gearPlacingAccuracy, gearPlacingSpeed, abilityToDefend, abilityToEscapeDefense) " .
                    "VALUES (:teamMatchID, :ballGroundFeeding, :ballLoadingLaneFeeding, :ballShootingAccuracy, :ballShootingSpeed, :gearGroundFeeding, :gearLoadingLaneFeeding, :gearPlacingAccuracy, :gearPlacingSpeed, :abilityToDefend, :abilityToEscapeDefense)";

    $ratings["teamMatchID"] = intval($teamMatch["id"]);
    $helper->queryDB($ratingsQuery,$ratings,true);

    $autoQuery = "INSERT INTO matchautos(teamMatchID, crossedLine) VALUES (:teamMatchID,:crossedLine)";
    $autoParams = array(
      ":teamMatchID" => $teamMatch["id"],
      ":crossedLine" => $crossesLine
    );
    $helper->queryDB($autoQuery,$autoParams,true);


    echo "<h2>Match {$teamMatch['id']}</h2><br />";
    echo "<b>AUTO</b><br>";
    var_dump($autoActions);
    echo "<b>TELE</b><br>";
    var_dump($teleActions);
    echo "<b>RATINGS</b><br>";
    var_dump($ratings);


  }

}

echo "done";


function createBallFeedAction($delta, $location){
  return array(
    "query" => "INSERT INTO matchballfeeds(`teamMatchID`,`orderID`,`mode`,`delta`,`location`) VALUES(:teamMatchID,:orderID,:mode,:delta,:location)",
    "params" => array(
      ":delta" => $delta,
      ":location" => $location
    )
  );
}
function createGearFeedAction($result, $method){
  return array(
    "query" => "INSERT INTO matchgearfeeds(`teamMatchID`,`orderID`,`mode`,`result`,`method`) VALUES(:teamMatchID,:orderID,:mode,:result,:method)",
    "params" => array(
      ":result" => $result,
      ":method" => $method
    )
  );
}
function createShootAction($coordX, $coordY, $highLow, $scored,$missed){
  return array(
    "query" => "INSERT INTO matchshoots(`teamMatchID`,`orderID`,`mode`,`coordX`,`coordY`,`highLow`,`scored`,`missed`) VALUES(:teamMatchID,:orderID,:mode,:coordX,:coordY,:highLow,:scored,:missed)",
    "params" => array(
      ":coordX" => $coordX,
      ":coordY" => $coordY,
      ":highLow" => $highLow,
      ":scored" => $scored,
      ":missed" => $missed
    )
  );
}
function createGearAction($location,$result){
  return array(
    "query" => "INSERT INTO matchgears(`teamMatchID`,`orderID`,`mode`,`result`,`location`) VALUES(:teamMatchID,:orderID,:mode,:result,:location)",
    "params" => array(
      ":location" => $location,
      ":result" => $result,
    )
  );
}


function getNumScored($ballsShot, $scoringAccuracy, $scoringVariability){
  $marginOfError = rand(-$scoringVariability,$scoringVariability) / 100;
  $proportion = ($scoringAccuracy/100) + $marginOfError;
  $proportion = $proportion < 0 ? 0 : $proportion;
  $scored = round($ballsShot * $proportion);
  return $scored <= $ballsShot ? $scored : $ballsShot;
}

function getRandomShootCoordinate(){
  $x = rand(37,216);
  $y_max = (37418 - 155*$x)/179;
  $y = $y_max - rand(0,$y_max);
  return array(
    "x" => $x,
    "y" => $y
  );
}

function getActionsInMatch($perMatch,$variability){
  $marginOfError = rand(-$variability,$variability);
  $numPerMatch = $perMatch + $marginOfError;
  $numPerMatch = $numPerMatch < 0 ? 0 : $numPerMatch;
  return $numPerMatch;

}

function b($bool){
  return $bool ? "true" : "false";
}
function arr_avg($arr){
  return array_sum($arr) / count($arr);
}