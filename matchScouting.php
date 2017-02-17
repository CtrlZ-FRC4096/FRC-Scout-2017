<?php
/**
 * Created by PhpStorm.
 * User: Jayasurya
 * Date: 2/9/2016
 * Time: 8:47 PM
 */
$SCOUTING_TEAM_MATCH = $match->getClaimedTeamMatchForDevice($_COOKIE['deviceID']);
$SCOUTING_TEAM = $SCOUTING_TEAM_MATCH['teamNumber'];
$SCOUTING_TEAM_COLOR = $SCOUTING_TEAM_MATCH['side'];


if (isset($_COOKIE['matchData'])) {
  $RESUMING_MATCH = true;
} else {
  $RESUMING_MATCH = false;
  $helper->setCollectionStarted($match->id, $SCOUTING_TEAM);
}
?>

<!DOCTYPE HTML>
<html style="overflow-x: hidden">
<head>

  <?php require_once($_SERVER['DOCUMENT_ROOT'] . "/client_includes.php"); ?>

  <style>
    #taskSwitcher h2, #taskSwitcher h1 {
      font-weight: bold;
      color: white;
      margin: 0;
      padding: 5%;
    }
    #taskSwitcher div {
      padding: 0;
      cursor: pointer;
    }
    .orange {
      background-color: #FF7F2A

    }
    .darkBlue {
      background-color: #2A7FFF

    }
    #taskSwitcher div::after {
      width: 0;
      height: 0;
      border-left: 20px solid transparent;
      border-right: 20px solid transparent;
      content: "";
      display: none;
    }
    #taskSwitcher div[data-color='darkBlue']::after {
      border-top: 20px solid #2A7FFF;
    }
    #taskSwitcher div[data-color='orange']::after {
      border-top: 20px solid #FF7F2A;
    }
    #taskSwitcher div.active::after {
      display: inline-block;
    }
    .historyItem {
      width: 100%;
      height: 50px;
      display: flex;
      border-bottom: 1px solid black;
      align-items: center;
      justify-content: center;
    }
    .circle {
      border-radius: 50%;
    }
    .blurred {
      filter: blur(3px);
      -webkit-filter: blur(3px);
    }


    .toast-warning{
      opacity: 1 !important;
    }

    g{
      pointer-events: all;
    }

  </style>

</head>
<?php
if($RESUMING_MATCH){
?>
<div id="resumingMatchNotice" style="
      position: absolute;
      width: 100%;
      height: 100%;
      background-color: white;
      display: flex;
      align-items: center;
      z-index: 15;">

  <h2 style="width: 100%;text-align: center;font-size: 50px">Resuming Match...</h2>
</div>

<?php } ?>


<div id="redirectingNotice" style="
      position: absolute;
      width: 100%;
      height: 100%;
      background-color: white;
      display:none;
      align-items: center;
      z-index: 15;">

  <h2 style="width: 100%;text-align: center;font-size: 50px">Redirecting...</h2>
</div>
<body style="height: 100%;display: flex;flex-direction: column">

<div class="row">
  <div style="display: flex; align-items: center; justify-content: space-between;"
       class="col-sm-12 <?= $SCOUTING_TEAM_COLOR ?>">

    <h2 style="float: left;color: white;margin: 12px;margin-right: 24px; margin-left: 24px;">
      Team <?= $SCOUTING_TEAM ?></h2>

    <h2 id="modeHeading" data-mode="auto" style="/* float: left; */color: white;margin: 12px;margin-right: 24px; margin-left: 24px;">AUTO MODE</h2>

    <h2 style="float: right;color: white;margin: 12px;margin-right: 24px; margin-left: 24px;">Match <?= $match->matchNumber ?>
      - <?= $currCompetition->name ?>

      <a id="abortMatchConfirm" style="border:2px solid black;margin: 0 auto;margin-left:10px" href="#" class="button button-pill button-caution button-large">Abort Match</a>
    </h2>

  </div>
</div>

<div class="row" style="text-align: center;" id="taskSwitcher">
  <div class="col-sm-3 active" data-color="orange" div-id="gearPage">
    <h1 class="orange">Gear</h1>
  </div>
  <div class="col-sm-3" data-color="darkBlue" div-id="feedPage">
    <h1 class="darkBlue">Feed</h1>
  </div>
  <div class="col-sm-3" data-color="orange" div-id="shootPage">
    <h1 class="orange">Shoot</h1>
  </div>
  <div class="col-sm-3" data-color="darkBlue" div-id="endGamePage">
    <h1 class="darkBlue">End Game</h1>
  </div>
</div>

<div style="align-items: center; flex: 1; display: flex;flex-direction: column">


  <div class="row" style="flex:1;width: 100%;display:flex;justify-content: space-around;flex-direction: row">


    <div id="gearPage" style="display:flex;flex: 0 1 75%;flex-direction:column;">
      <div style="margin: 15px;flex: 1; display: flex;">
        <div style="display: flex;flex-direction: row;width: 60%;">
          <object style="display:flex;flex: 1;" type="image/svg+xml" data="/util/svg/gear.svg" id="gearSVG"></object>
        </div>

        <div style="position:relative;display: flex;flex-direction: column;width: 40%;">
          <a id="cancelGear"
             style="border:2px solid black;position: absolute;margin-top:16px;margin-left:16px"
             class="button button-pill button-caution ">Cancel</a>

          <div style="display: flex; flex-direction: column; width: 80%; flex-flow: column; height: 10px; margin: 0 auto; flex: 1 0 100%; justify-content: center; align-items: center;">

            <div
              style="cursor:pointer;display:flex;flex-direction:row;flex: 0 1 80px; width: 100%; margin: 5% auto 5% auto;border: 2px solid black;">
              <div id="gearScore" style="display:flex;align-items:center;flex: 1;border-right: 2px solid black">
                <h2 style="font-weight:bold;flex: 1;text-align: center">Score</h2>
              </div>
              <div id="gearMiss" style="display:flex;align-items:center;flex: 1">
                <h2 style="font-weight:bold;flex: 1;text-align: center">Miss</h2>
              </div>
            </div>

          </div>

        </div>


      </div>
    </div>
    <div id="feedPage" style="display:flex;flex: 0;width: 0;height: 0;overflow: hidden">

      <div style="margin: 15px;flex: 1; display: flex;">
        <div style="display: flex;flex-direction: row;width: 60%;">
          <object style="display:flex;flex: 1;" type="image/svg+xml" data="/util/svg/boilersClose/leftBlueFeedForRed.svg" id="feedSVG"></object>
        </div>

        <div style="position:relative;display: flex;flex-direction: column;width: 40%;">
          <a id="cancelFeed"
             style="border:2px solid black;position: absolute;margin-top:16px;margin-left:16px"
             class="button button-pill button-caution ">Cancel</a>

          <div style="display: flex; flex-direction: column; width: 80%; flex-flow: column; height: 10px; margin: 0 auto; flex: 1 0 100%; justify-content: center; align-items: center;">

            <div
              style="cursor:pointer;display:flex;flex-direction:row;flex: 0 1 80px; width: 100%; margin: 5% auto 5% auto;border: 2px solid black;">
              <div id="cancelScore" style="display:flex;align-items:center;flex: 1;border-right: 2px solid black">
                <h2 style="font-weight:bold;flex: 1;text-align: center">Score</h2>
              </div>
              <div id="cancelMiss" style="display:flex;align-items:center;flex: 1">
                <h2 style="font-weight:bold;flex: 1;text-align: center">Miss</h2>
              </div>
            </div>

          </div>

        </div>


      </div>

    </div>
    <div id="shootPage" style="display:flex;flex: 0%;width: 0;height: 0;overflow: hidden;flex-direction:column;">

      <div style="margin: 15px;flex: 1; display: flex;">
        <div style="display: flex;flex-direction: row;width: 60%;">
          <object style="cursor:crosshair;display:flex;flex: 1;" type="image/svg+xml"
                  field-direction="<?= ($helper->LEFT_TEAM == $SCOUTING_TEAM_COLOR ? "right" : "left") ?>" id="shootSVG"
                  data="/util/svg/<?= ($SCOUTING_TEAM_COLOR == "red" ? "blue" : "red") . ($helper->LEFT_TEAM == $SCOUTING_TEAM_COLOR ? "Right" : "Left") ?>Shoot.svg"></object>
        </div>

        <div style="position:relative;display: flex;flex-direction: column;width: 40%;">
          <a id="cancelShoot"
             style="border:2px solid black;position: absolute;margin-top:16px;margin-left:16px"
             class="button button-pill button-caution ">Cancel</a>

          <div style="display: flex; flex-direction: column; width: 80%; flex-flow: column; height: 10px; margin: 0 auto; flex: 1 0 100%; justify-content: center; align-items: center;">
            <div
              style="cursor:pointer;display:flex;flex-direction:row;flex: 0 1 80px; width: 100%; margin: 5% auto 5% auto;border: 2px solid black;">
              <div id="shootHigh" style="display:flex;align-items:center;flex: 1;border-right: 2px solid black">
                <h2 style="font-weight:bold;flex: 1;text-align: center">High</h2>
              </div>
              <div id="shootLow" style="display:flex;align-items:center;flex: 1">
                <h2 style="font-weight:bold;flex: 1;text-align: center">Low</h2>
              </div>
            </div>
            <div
              style="cursor:pointer;display:flex;flex-direction:row;flex: 0 1 80px; width: 100%; margin: 5% auto 5% auto;border: 2px solid black;">
              <div id="shootScore" style="display:flex;align-items:center;flex: 1;border-right: 2px solid black">
                <h2 style="font-weight:bold;flex: 1;text-align: center">Score</h2>
              </div>
              <div id="shootMiss" style="display:flex;align-items:center;flex: 1">
                <h2 style="font-weight:bold;flex: 1;text-align: center">Miss</h2>
              </div>
            </div>

          </div>

        </div>


      </div>


    </div>
    <div id="endGamePage" style="flex-direction: column; display: flex; flex: 0;width: 0;height: 0;overflow: hidden;align-items: center;">

      <div style="width: 100%">
        <div style="width: 50%;float: left;height: 2px"></div>
        <div style="width: 50%;float: left">
          <h1 style="text-align: center;font-weight: bold">Climb</h1>
        </div>
      </div>
      <div style="flex-direction:row;display: flex;flex: 0 1 200px;width: 100%;">

        <div id="reachedBatter" data-reached="false" style="cursor:pointer;display:flex;align-items:center;justify-content: center;flex: 1 1 50%; background-color: #BD5A5A;margin: 0px 50px 0 50px;">
          <h1 style="margin:15px;text-align: center;font-weight: bold;color:white">Did Not Reach Batter</h1>

        </div>

        <div data-started="false" id="climbStartEnd" style="cursor:pointer;display:flex;flex: 1 1 50%; background-color: #FF7F2A;margin: 0px 50px 0 50px;">
          <div  style="display:flex;align-items:center;justify-content: center;flex: 1;margin: 50px;">
            <h1 style="margin:15px;text-align: center;font-weight: bold;color:white">Start</h1>

          </div>
        </div>

      </div>
      <div style="width: 100%">
        <div style="width: 50%;float: left;height: 2px"></div>
        <div style="width: 50%;float: left">
          <h1 id="climbSuccess" data-climbSuccess="false"
            style="margin: 25px 50px 0 50px;
                   text-align: center;
                   font-weight: bold;
                   color:white;
                   background-color: #BD5A5A;
                   padding: 20px;">Did Not Complete Climb</h1>
        </div>
      </div>
      <div style="width: 100%">
        <div style="width: 50%;float: left;height: 2px"></div>
        <div style="width: 50%;float: left">
          <h1 style="text-align: center;font-weight: bold" id="climbTimer">00:00 mins</h1>
        </div>
      </div>
      <hr style="width: 100%"/>
      <div class="row" style="margin-top:20px;margin-bottom:10px;width: 100%;">
        <div class="col-sm-6" style="display: flex">
          <div style="margin: 0 auto;width: 60%">
            <h4 style="text-align: center;margin-top: 0">Give this team an offensive rating</h4>
            <div id="offensiveRating" style="width: 100%;margin: 0 auto"></div>
          </div>

        </div>
        <div class="col-sm-6" style="display: flex">
          <div style="margin: 0 auto;width: 60%">
            <h4 style="text-align: center;margin-top: 0">Give this team an defensive rating</h4>
            <div id="defensiveRating" style="width: 100% !important;margin: 0 auto"></div>
          </div>

        </div>
      </div>
      <hr style="width: 100%"/>
      <div style="margin-top: 10px" class="row">
        <div class="col-sm-12">
          <a id="comfirmSubmitMatch" style="margin: 0 auto;" href="#" class="button button-pill button-flat-royal button-large">Submit</a>
        </div>
      </div>
    </div>
    <div style="min-width:25%;flex:0 1;display: flex;flex-direction: column;">
      <div style="margin: 15px;flex: 1;display: flex;height: 100%;flex-direction: column;">
        <div style="margin:0;width:100%;border: 1px solid black;padding: 10px 0 10px 0;text-align: center;">
          <h2 style="font-weight: bold;margin: 0;">History</h2>
          <h4 id="historyModeText" style=" margin: 0; margin-top: 5px;">Auto Mode</h4>
        </div>


        <div id="historyList"
             style="border: 1px solid black;border-top:0;width: 100%;border-right:2px solid black;flex: 1;overflow: scroll">

        </div>

      </div>

    </div>
  </div>


</div>


<div class="remodal" data-remodal-id="confirmSubmitModal" id="confirmSubmitModal">
  <button data-remodal-action="close" class="remodal-close"></button>
  <h1>Are you sure?</h1>
  <p>
    Take a second to make sure you have finished scouting this match completely, and correctly.
  </p>
  <br>
  <button data-remodal-action="cancel" class="remodal-cancel">Make some changes.</button>
  <button id="submitMatch" data-remodal-action="confirm" class="remodal-confirm">Submit my data.</button>
</div>
<div class="remodal" data-remodal-id="confirmAbortMatch" id="confirmAbortMatch">
  <button data-remodal-action="close" class="remodal-close"></button>
  <h1>Are you sure?</h1>
  <p>
    If you abort this match, you will lose all the data you have collected so far!
  </p>
  <br>
  <button data-remodal-action="cancel" class="remodal-cancel">Cancel</button>
  <button id="abortMatch" data-remodal-action="confirm" class="remodal-confirm">Abort</button>
</div>

</body>
<script>
String.prototype.capitalize = function(allWords) {
  return (allWords) ? // if all words
  this.split(' ').map(word => word.capitalize()).join(' ') : //break down phrase to words then  recursive calls until capitalizing all words
  this.charAt(0).toUpperCase() + this.slice(1); // if allWords is undefined , capitalize only the first word , mean the first char of the whole string
}

var gearSVGDoc;
var shootSVGDoc;
var feedSVGDoc;
var HALF_FIELD_LENGTH_INCHES = <?=$helper::HALF_FIELD_LENGTH_INCHES?>;
var HALF_FIELD_HEIGHT_INCHES = <?=$helper::HALF_FIELD_HEIGHT_INCHES?>;

var SHOOT_POS_X = null;
var SHOOT_POS_Y = null;
var SHOOT_LEVEL = null;
var SHOOT_RESULT = null;

var GEAR_LOCATION = null;
var GEAR_RESULT = null;

var FEED_LOCATION = null;

var RESUMING_MATCH = <?=($RESUMING_MATCH ? "true" : "false")?>;
var LEFT_TEAM_COLOR = "<?=($helper->LEFT_TEAM)?>";
var DISABLE_NEXT_BREACH_HOVEROUT = false;
var BREACH_MAP_FILTER = "origin";

var FEEDSVG_IDS = ["overflowHolder","returnFarHolder","returnCloseHolder","boilerSideCloseHolder","boilerSideMiddleHolder","boilerSideFarHolder","loadingSideCloseHolder","loadingSideFarHolder"]



$(document).ready(function () {
  if (!RESUMING_MATCH) {
    $.cookie("matchData", "", {expires: 3650, path: '/'});
  }
  else{
      $(window).load(function(){
        if($.cookie("matchData") != "") {


          var data = JSON.parse($.cookie("matchData"));
          $("#taskSwitcher > div").eq(data.currTab).trigger("click");

          data.actions.sort(
            function compare(a,b) {
              if (a.orderID < b.orderID)
                return -1;
              else if (a.orderID > b.orderID)
                return 1;
              else
                return 0;
            });

          $.each(data.actions,function(index,action){
            switch(action.eventType){
              case "breach":
                var defenseColumnIndex = $("[zone-name='" + action.startZone + "']").index() + (action.direction == "right" ? 1 : -1);
                var defenseColumn = $("#breachMap > div").eq(defenseColumnIndex);
                var defenseIndex = $(defenseColumn).find("div.defense[defense-id='" + action.defenseID + "']").index();
                var defenseCircle = $(defenseColumn).find(".defenseCircle").eq(defenseIndex);
                var startCircleContainer = $("[zone-name='" + action.startZone + "'] .sideCircleContainer").eq(defenseIndex);;
                var endingCircle,clickContainer;
                if(action.endZone != null){

                  endingCircle = $("[zone-name='" + action.endZone + "'] .backOutFromBreachCircle").eq(defenseIndex);

                  clickContainer = $(endingCircle).parent();

                }
                else{
                  endingCircle = null;
                  clickContainer = $("#breachStuck");
                }
                writeBreachHistoryItem(startCircleContainer,defenseCircle,clickContainer,endingCircle,action.mode);
                break;
              case "shoot" :
                SHOOT_POS_X = parseFloat(action.coordX);
                SHOOT_POS_Y = parseFloat(action.coordY);
                SHOOT_LEVEL = parseInt(action.highLow);
                SHOOT_RESULT = parseInt(action.scoreMiss);
                checkAndAddShootHistoryItem(action.mode);
                break;
              case "gear" :
                GEAR_LOCATION = action.location;
                GEAR_RESULT = parseInt(action.scoreMiss);
                checkAndAddGearHistoryItem(action.mode);
                break;
              default:
                break;
            }
          });

          var oppMode;

          if(data.currMode == "auto"){
            oppMode = "tele";
          }
          else{
            oppMode = "auto";
          }

          $("#historyList .historyItem[data-mode='"+data.currMode+"']").show();
          $("#historyList .historyItem[data-mode='"+oppMode+"']").hide();
          if(data.currMode == "tele"){
            $("#modeHeading").trigger("click");
          }

          $("#offensiveRating").rateYo("rating" , data.endGame.offensiveRating);
          $("#defensiveRating").rateYo("rating" , data.endGame.defensiveRating);
          if(data.endGame.batterReached == "true"){
            $("#reachedBatter").trigger("click");
          }
          if(data.endGame.success == "true"){
            $("#climbSuccess").trigger("click");
          }

          $("#climbTimer").text(data.endGame.duration + " mins");
          generateJSON();

        }

        $("#resumingMatchNotice").fadeOut(600);

      });


  }


  $(document).keyup(function(e){
    handleKeypress(e.originalEvent);
  });

  $("#cancelBreach").click(function(){
    clearBreach();
  });
  $("#cancelShoot").click(function(){
    clearShoot();
  });
  $("#abortMatch").click(function(){
    $.ajax({
      type: "POST",
      data: {matchNumber:<?=$match->matchNumber?>, compID: <?=$currCompetition->id?>,teamNumber: <?=$SCOUTING_TEAM?>},
      url: "/util/php/serve/cancelMatch.php",
      success: function (data) {
        if(data=="success"){
          $("#redirectingNotice").css("display","flex");

          toastr["success"]("The match has been aborted", "Redirecting...");
          location.reload();
        }
        else{
          toastr["error"]("Could not cancel the match!", "Aw Shucks!");
          console.log(data);
        }

      }
    });
  })
  $("#taskSwitcher div").click(function () {
    $("#taskSwitcher div").removeClass("active");
    $(this).addClass("active");

    $("#gearPage,#feedPage,#shootPage,#endGamePage").css("flex", "0").css("width", "0").css("height", "0").css("overflow", "hidden");
    $("#" + $(this).attr("div-id")).css("flex", "0 1 75%").css("width", "").css("height", "").css("overflow", "");

    generateJSON();
  });
  $("#historyList").on("click", ".deleteHistoryItem", function () {
    $(this).parent().remove();
    generateJSON();
  });



  $("#shootPage #shootHigh, #shootPage #shootLow").click(function () {
    $(this).css("background-color", "#FF7F2A").attr("selected");
    $(this).siblings().eq(0).css("background-color", "").removeAttr("selected");
    if($(this).attr("id") == "shootHigh"){
      SHOOT_LEVEL = 1;
    }
    else{
      SHOOT_LEVEL = 0;
    }

    checkAndAddShootHistoryItem( $("#modeHeading").attr("data-mode"));

  });
  $("#shootPage #shootScore, #shootPage #shootMiss").click(function () {

    $(this).css("background-color", "#2A7FFF").attr("selected");
    $(this).siblings().eq(0).css("background-color", "").removeAttr("selected");
    if($(this).attr("id") == "shootScore"){
      SHOOT_RESULT = 1;
    }
    else{
      SHOOT_RESULT = 0;
    }
    checkAndAddShootHistoryItem($("#modeHeading").attr("data-mode"));

  });
  $("#gearPage #gearScore, #gearPage #gearMiss").click(function () {

    $(this).css("background-color", "#2A7FFF").attr("selected");
    $(this).siblings().eq(0).css("background-color", "").removeAttr("selected");
    if($(this).attr("id") == "gearScore"){
      GEAR_RESULT = 1;
    }
    else{
      GEAR_RESULT= 0;
    }
    checkAndAddGearHistoryItem($("#modeHeading").attr("data-mode"));

  });
  $("#endGamePage #reachedBatter").click(function(){
    if($(this).attr("data-reached") == "false"){
      $(this).attr("data-reached","true").css("background-color","#458045").find("h1").text("Reached Batter");
    }
    else{
      $(this).attr("data-reached","false").css("background-color","#BD5A5A").find("h1").text("Did Not Reach Batter");;
    }
    generateJSON();
  });
  $("#endGamePage #climbSuccess").click(function(){
    if($(this).attr("data-climbSuccess") == "false"){
      $(this).attr("data-climbSuccess","true").css("background-color","#458045").text("Completed Climb");
    }
    else{
      $(this).attr("data-climbSuccess","false").css("background-color","#BD5A5A").text("Did Not Complete Climb");;
    }
    generateJSON();
  })
  var sec = 0;
  function pad ( val ) { return val > 9 ? val : "0" + val; }
var climbTimer;
  $("#endGamePage #climbStartEnd").click(function(){

    if($(this).attr("data-started") == "false"){
      sec = 0;
      $("#climbTimer").text( pad(parseInt(sec/60,10)) + ":" + pad(sec%60) + " mins");

      climbTimer = setInterval( function(){
        var seconds = pad(++sec%60);
        var mins = pad(parseInt(sec/60,10));
        $("#climbTimer").text(mins+":"+seconds + " mins");
        generateJSON();
      }, 1000);
      $(this).attr("data-started","true").find("h1").text("End");
    }
    else{
      clearInterval(climbTimer);
      $(this).attr("data-started","false").find("h1").text("Start")
      generateJSON()
    }


  });
  $("#modeHeading").click(function(){
    var currMode = $(this).attr("data-mode");

    if(currMode == "auto"){
      $(this).text("TELE MODE").attr("data-mode","tele");
      $("#historyModeText").text("Tele Mode");
      $("#historyList .historyItem[data-mode='auto']").hide();
      $("#historyList .historyItem[data-mode='tele']").show();
    }
    else if(currMode == "tele"){
      $(this).text("AUTO MODE").attr("data-mode","auto");
      $("#historyModeText").text("Auto Mode");

      $("#historyList .historyItem[data-mode='tele']").hide();
      $("#historyList .historyItem[data-mode='auto']").show();
    }
    generateJSON();

  })
  $("#submitMatch").click(function(){
    generateJSON();
    $.ajax({
      type: "POST",
      url: "/util/php/serve/addMatchData.php",
      data: {data: $.cookie("matchData")},
      async: false,
      success: function (data) {
        if(data == "Success"){
          toastr["success"]("The match has been updated successfully", "Success!")
          $.removeCookie('matchData', { path: '/' });
          $("#redirectingNotice").css("display","flex");
          location.reload();
        }
      }
    });



  })
  $("#comfirmSubmitMatch").click(function(){
    var inst = $("#confirmSubmitModal").remodal();
    inst.open();
  })
  $("#abortMatchConfirm").click(function(){
    var inst = $("#confirmAbortMatch").remodal();
    inst.open();
  })
});

$(window).on('load', function () {

  // Get the Object by ID
  var a = document.getElementById("gearSVG");
  // Get the SVG document inside the Object tag
  gearSVGDoc = a.contentDocument;
  // Get one of the SVG items by ID;
  gearSVGDoc.addEventListener("mouseover", function (e) {
    // svgItem.setAttribute("fill", "#50ce4c");
    gearSVGDocMouseOver(e);
  });
  gearSVGDoc.addEventListener("touchstart", function (e) {
    // svgItem.setAttribute("fill", "#4c9dce");
    gearSVGDocMouseOver(e);
    e.preventDefault();
  });
  gearSVGDoc.addEventListener("mouseout", function (e) {
    // svgItem.setAttribute("fill", "#4c9dce");
    gearSVGDocMouseOut(e);

  });
  gearSVGDoc.addEventListener("touchend", function (e) {
    // svgItem.setAttribute("fill", "#4c9dce");
    gearSVGDocMouseOut(e);
    e.preventDefault();
    gearSVGDocClick(e);

  });
  gearSVGDoc.addEventListener("click", function (e) {
    // svgItem.setAttribute("fill", "#4c9dce");
    gearSVGDocClick(e);

  });

  var a = document.getElementById("feedSVG");
  // Get the SVG document inside the Object tag
  feedSVGDoc = a.contentDocument;
  // Get one of the SVG items by ID;
  feedSVGDoc.addEventListener("mouseover", function (e) {
    // svgItem.setAttribute("fill", "#50ce4c");
    feedSVGDocMouseOver(e);
  });
  feedSVGDoc.addEventListener("touchstart", function (e) {
    // svgItem.setAttribute("fill", "#4c9dce");
    feedSVGDocMouseOver(e);
    e.preventDefault();
  });
  feedSVGDoc.addEventListener("mouseout", function (e) {
    // svgItem.setAttribute("fill", "#4c9dce");
    feedSVGDocMouseOut(e);

  });
  feedSVGDoc.addEventListener("touchend", function (e) {
    // svgItem.setAttribute("fill", "#4c9dce");
    feedSVGDocMouseOut(e);
    e.preventDefault();
    feedSVGDocClick(e);

  });
  feedSVGDoc.addEventListener("click", function (e) {
    // svgItem.setAttribute("fill", "#4c9dce");
    feedSVGDocClick(e);

  });



  a = document.getElementById("shootSVG");
  // Get the SVG document inside the Object tag
  shootSVGDoc = a.contentDocument;
  shootSVGDoc.addEventListener("click", function (e) {
    // svgItem.setAttribute("fill", "#4c9dce");
    shootSVGDocClick(e);

  });
  shootSVGDoc.addEventListener("keyup", function (e) {
    handleKeypress(e);
  });
  gearSVGDoc.addEventListener("keyup", function (e) {
    handleKeypress(e);
  });

  dragula([document.getElementById("historyList")],
    {
      moves: function (el, container, handle) {
        return handle.className === 'moveHistoryItem';
      }
    }
  ).on('drop', function (el, container) {
      generateJSON();
    });



  $("#gearPage,#feedPage,#shootPage,#endGamePage").css("flex", "0").css("width", "0").css("height", "0").css("overflow", "hidden");
  $("#endGamePage").css("flex", "0 1 75%").css("width", "").css("height", "").css("overflow", "");



  $("#offensiveRating").rateYo({
    numStars: 10,
    fullStar: true,
    starWidth: $("#offensiveRating").width() / 10 + "px",
    maxValue : 10,
    rating: 1

  });

  $("#defensiveRating").rateYo({
    numStars: 10,
    fullStar: true,
    starWidth: $("#defensiveRating").width() / 10 + "px",
    maxValue : 10,
    rating: 1

  });

  $("#defensiveRating,#offensiveRating").on("rateyo.set",function(e,data){
    var rateyo = $(this)
    var rating = $(rateyo).rateYo("rating");
    if(rating == 0){
      $(rateyo).rateYo("rating", 1);
    }

    generateJSON();

  });


  $("#gearPage,#feedPage,#shootPage,#endGamePage").css("flex", "0").css("width", "0").css("height", "0").css("overflow", "hidden");
  $("#gearPage").css("flex", "0 1 75%").css("width", "").css("height", "").css("overflow", "");


});


function gearSVGDocMouseOver(e) {
  var id = e.target.getAttribute("id");
//  console.log(id);
  if ( id == "centerGearHolder" || id == "rightGearHolder" || id == "leftGearHolder") {

    var gearID = id.substring(0,id.length-6);
    var gear = gearSVGDoc.getElementById(gearID);
    $(gear).css("opacity", 1);
  }

}
function gearSVGDocMouseOut(e) {
  var id = e.target.getAttribute("id");
//  console.log(id);
  if (( id == "centerGearHolder" || id == "rightGearHolder" || id == "leftGearHolder" ) && (GEAR_LOCATION + "Holder") !== id) {
    var gearID = id.substring(0,id.length-6);
    var gear = gearSVGDoc.getElementById(gearID);
    $(gear).css("opacity", 0);
  }

}
function gearSVGDocClick(e) {
  var id = e.target.getAttribute("id");
  if ( id == "centerGearHolder" || id == "rightGearHolder" || id == "leftGearHolder") {
    var gearID = id.substring(0,id.length-6);
    var centerGear = gearSVGDoc.getElementById("centerGear");
    var rightGear = gearSVGDoc.getElementById("rightGear");
    var leftGear = gearSVGDoc.getElementById("leftGear");
    var gear = gearSVGDoc.getElementById(gearID);
    $(centerGear).css("opacity", 0);
    $(leftGear).css("opacity", 0);
    $(rightGear).css("opacity", 0);
    $(gear).css("opacity", 1);
    GEAR_LOCATION = gearID;
    checkAndAddGearHistoryItem($("#modeHeading").attr("data-mode"))
  }
}



function feedSVGDocMouseOver(e) {
  var id = e.target.getAttribute("id");
  if ( FEEDSVG_IDS.indexOf(id) != -1) {
    var placeID = id.substring(0,id.length-6);

    var place = feedSVGDoc.getElementById(placeID);
    $(place).css("opacity", 1);
  }

}
function feedSVGDocMouseOut(e) {
  var id = e.target.getAttribute("id");

  if ( FEEDSVG_IDS.indexOf(id) != -1 && (FEED_LOCATION + "Holder") !== id) {
    var placeID = id.substring(0,id.length-6);
    var place = feedSVGDoc.getElementById(placeID);
    $(place).css("opacity", 0);
  }

}

function feedSVGDocClick(e) {
  var id = e.target.getAttribute("id");
  if ( FEEDSVG_IDS.indexOf(id) != -1) {
    var placeID = id.substring(0,id.length-6);

    for(i in FEEDSVG_IDS){
      var tmpID = FEEDSVG_IDS[i].substring(0,FEEDSVG_IDS[i].length-6);
      var tmpPlace = $(feedSVGDoc.getElementById(tmpID))
      tmpPlace.css("opacity", 0)
    }
    var place = feedSVGDoc.getElementById(placeID);
    $(place).css("opacity", 1);
    FEED_LOCATION = placeID;
    console.log("FEED_LOCATION: " + FEED_LOCATION);
//    checkAndAddGearHistoryItem($("#modeHeading").attr("data-mode"))
  }
}



function checkAndAddGearHistoryItem(mode){
  if(GEAR_LOCATION != null && GEAR_RESULT != null){
    var result,bg;
    if(GEAR_RESULT == 1){
      result = "Scored";
      bg = "#A1FFA1"
    }
    else{
      result = "Missed";
      bg = "#FFA1A1"
    }

    var loc = GEAR_LOCATION.substring(0,GEAR_LOCATION.length-4);

    $("#historyList").prepend(
         "<div " +
           "data-scoreMiss='"+GEAR_RESULT+"' " +
           "data-actionType='gear' " +
           "data-mode="+mode+" " +
           "data-location="+GEAR_LOCATION+" " +
           "class='historyItem' " +
           "style='background: "+bg+"; display: flex'> " +
              "<img class='deleteHistoryItem' src='/util/img/redX.gif' style='cursor:pointer;flex: 0 0 10%;height: 85%;'/>" +
              "<h3 style='flex: 1 1 80%;text-align: center;line-height: 21px'>" +
                  "<b>" + (loc).capitalize() + " Gear</b> - " + result +
              "</h3>" +
              "<img class='moveHistoryItem' src='/util/img/upDownImage.png' style='cursor:pointer;flex: 0 0 10%;height: 85%;'/>" +
          "</div>");

    clearGear();
    generateJSON();
    checkAutoCount();
  }
}
function clearGear(){
  d3.select(gearSVGDoc.getElementById(GEAR_LOCATION)).transition().duration(400).style("opacity", 0);
  $("#gearPage #gearScore, #gearPage #gearMiss").animate({ backgroundColor: "transparent"}, 'slow').removeAttr("selected");
  GEAR_LOCATION = null;
  GEAR_RESULT = null;
}
function shootSVGDocClick(e) {
  var layer1 = shootSVGDoc.getElementById("layer1").getBoundingClientRect();

  var minWidth = layer1.left;
  var maxWidth = minWidth + layer1.width;
  var minHeight = layer1.top;
  var maxHeight = minHeight + layer1.height;

  if (e.clientX >= minWidth && e.clientX <= maxWidth && e.clientY >= minHeight && e.clientY <= maxHeight) {

    var clickX = e.clientX - minWidth;
    var clickY = maxHeight - e.clientY;

    var clickXInches = (clickX / layer1.width) * HALF_FIELD_LENGTH_INCHES;
    var clickYInches = (clickY / layer1.height) * HALF_FIELD_HEIGHT_INCHES;

    var direction = $("#shootSVG").attr("field-direction");

    var actualClickXInches, actualClickYInches;

    if (direction == "left") {
      actualClickXInches = clickXInches;
      actualClickYInches = clickYInches;
    }
    else {
      actualClickXInches = HALF_FIELD_LENGTH_INCHES - clickXInches;
      actualClickYInches = HALF_FIELD_HEIGHT_INCHES - clickYInches;
    }
    if (shootSVGDoc.getElementById("shootPosition")) {
      d3.select(shootSVGDoc.getElementById("shootPosition"))
        .attr("cx", (clickX / layer1.width) * 498.90457)
        .attr("cy", ((maxHeight-clickY) / layer1.height) * 489.37781)
    }
    else {

      d3.select(shootSVGDoc.rootElement).append("svg:circle")
        .attr("cx", (clickX / layer1.width) * 498.90457)
        .attr("cy", ((maxHeight-clickY) / layer1.height) * 489.37781)
        .attr("r", 10)
        .attr("id", "shootPosition")
        .attr("style", "cursor:crosshair;fill: #ff6600; fill-opacity: 1; fill-rule: nonzero; stroke: #000000; stroke-width: 6.58412218; stroke-linecap: round; stroke-linejoin: bevel; stroke-miterlimit: 4; stroke-opacity: 1; stroke-dasharray: none; stroke-dashoffset: 0; stroke-width: 3px;");

    }


    SHOOT_POS_X = actualClickXInches;
    SHOOT_POS_Y = actualClickYInches;
    checkAndAddShootHistoryItem($("#modeHeading").attr("data-mode"));
//    console.log("(" + (clickX / layer1.width) * 498.90457 + "," + (clickY / layer1.height) * 489.37781 + ")");

  }
}
function checkAndAddShootHistoryItem(mode){

  if(SHOOT_LEVEL != null && SHOOT_RESULT != null && SHOOT_POS_X != null && SHOOT_POS_Y != null){

    var result,level,bg;

    if(SHOOT_RESULT == 1){
      result = "Scored";
      bg = "#A1FFA1"
    }
    else{
      result = "Missed";
      bg = "#FFA1A1"
    }

    if(SHOOT_LEVEL == 1){
      level = "High";
    }
    else{
      level = "Low";
    }

    $("#historyList").prepend("<div data-coordX='"+SHOOT_POS_X+"'" +
    " data-coordY='"+SHOOT_POS_Y+"'" +
    " data-highLow='"+SHOOT_LEVEL+"'" +
    " data-scoreMiss='"+SHOOT_RESULT+"' " +
    "data-actionType=\"shoot\" data-mode="+mode+" class=\"historyItem\" style='background: "+bg+"; display: flex'> " +
    "<img class='deleteHistoryItem' src='/util/img/redX.gif' style='cursor:pointer;flex: 0 0 10%;height: 85%;'/>" +
    "<h3 style='flex: 1 1 80%;text-align: center;line-height: 21px'><b>" + result +"</b> " + level + " Goal</h3>" +
    "<img class='moveHistoryItem' src='/util/img/upDownImage.png' style='cursor:pointer;flex: 0 0 10%;height: 85%;'/>" +
    "</div>");
    clearShoot();
    generateJSON();
    checkAutoCount();
  }
}
function clearShoot(){
  d3.select(shootSVGDoc.getElementById("shootPosition")).transition().duration(400).style("opacity", 0).each("end", function(){
    d3.select(shootSVGDoc.getElementById("shootPosition")).remove();
  });
  $("#shootPage #shootScore, #shootPage #shootMiss").animate({ backgroundColor: "transparent"}, 'slow').removeAttr("selected");
  $("#shootPage #shootHigh, #shootPage #shootLow").animate({ backgroundColor: "transparent"}, 'slow').removeAttr("selected");

  SHOOT_LEVEL = SHOOT_POS_X = SHOOT_POS_Y = SHOOT_RESULT = null;

}
function generateJSON(){


  var records = [];
  var counter = 1;
  $("#historyList .historyItem[data-mode='auto']").each(function(){

    records.push(getRecord(this,"auto"));

    counter++;
  });
  counter = 1;
  $("#historyList .historyItem[data-mode='tele']").each(function(){

    records.push(getRecord(this,"tele"));

    counter++;
  });

  var endGame = {
    batterReached : $("#reachedBatter").attr("data-reached"),
    duration : $("#climbTimer").text().substr(0,5),
    defensiveRating:$("#defensiveRating").rateYo("rating"),
    offensiveRating:$("#offensiveRating").rateYo("rating"),
    success: $("#climbSuccess").attr("data-climbSuccess")
  };

  var matchData = {
    actions: records,
    endGame:  endGame,
    teamMatch: <?=json_encode($SCOUTING_TEAM_MATCH)?>,
    compID: <?=$currCompetition->id?>,
    matchNumber : <?=$match->matchNumber?>,
    currMode : $("#modeHeading").attr("data-mode"),
    currTab : $("#taskSwitcher div.active").index()
  };

  var j = 1;
  for(var i = records.length - 1 ; i >= 0;i--){
    records[i].orderID = j;
    j++;
  }


  $.cookie("matchData",JSON.stringify(matchData));

  function getRecord(e,mode){

    var record = {};
    switch($(e).attr("data-actionType")){
      case "gear":
        record.location = $(e).attr("data-location");
        record.scoreMiss = $(e).attr("data-scoreMiss");
        record.orderID = counter;
        record.eventType = "gear";
        break;
      case "breach":
        record.startZone = $(e).attr("data-startZone");
        record.defenseID = $(e).attr("data-defenseID");
        record.endZone = $(e).attr("data-endZone");
        record.endZone = (record.endZone == "null" ? null : record.endZone);
        record.fail = $(e).attr("data-fail");
        record.direction = $(e).attr("data-direction");
        record.orderID = counter;
        record.eventType = "breach";

        break;
      case "shoot":
        record.coordX = $(e).attr("data-coordX");
        record.coordY = $(e).attr("data-coordY");
        record.scoreMiss = $(e).attr("data-scoreMiss");
        record.highLow = $(e).attr("data-highLow");
        record.orderID = counter;
        record.eventType = "shoot";

        break;
      default:
        break;
    }
    record.mode= mode;

    return record;

  }

}
function checkAutoCount(){

  if($("#modeHeading").attr("data-mode") == "auto"){
    if($("#historyList .historyItem[data-mode='auto']").length > 2){
      toastr["warning"]("Most teams don't do so much in auto mode. Don't forget to switch", "Don't forget to switch to TELE mode!");

    }
  }
}
function isTouchDevice(){
    var bool;
    if (('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch) {
      bool = true;
    } else {
      // include the 'heartz' as a way to have a non matching MQ to help terminate the join
      // https://git.io/vznFH
      var query = ['@media (', prefixes.join('touch-enabled),('), 'heartz', ')', '{#modernizr{top:9px;position:absolute}}'].join('');
      testStyles(query, function(node) {
        bool = node.offsetTop === 9;
      });
    }
    return bool;

}
function writeBreachHistoryItem(startCircleContainer,defenseCircle,clickContainer,endingCircle,mode){

  var startedZone = $(startCircleContainer).parent().attr("zone-name");
  var defense = $(defenseCircle).parent().parent();
  var defenseName = $(defenseCircle).parent().parent().attr("defense-name");
  var defenseImg = $(defenseCircle).parent().parent().find(".imageContainer").css("background-image");

  var rightColor = "#FFFFFF",leftColor = "#FFFFFF";
  var img1,img2;
  var fail,startZone,endZone = null ,defenseID = $(defense).attr("defense-id");
  var startColor = startedZone.split(" ")[0];
  var direction = ($(defense).parent().index() < $(startCircleContainer).parent().index() ? "left" : "right");

  if(startColor == "Red"){
    startColor = "#FC2C16";
    startZone = "Red Home";
  }
  else  if(startColor == "Blue"){
    startColor = "#4C9DCE";
    startZone = "Blue Home";
  }
  else{
    startColor = "#FDFF6E";
    startZone = "Neutral Territory";
  }

  if (direction == "right") {
    leftColor = startColor;
  }
  else {
    rightColor = startColor;
  }


  if($(clickContainer).hasClass("sideCircleContainer")){
    fail = "false";
    var endingZone = $(endingCircle).parent().parent().attr("zone-name");
    var endColor = endingZone.split(" ")[0];
    if(endColor == "Red"){
      endColor = "#FC2C16";
      endZone = "Red Home";

    }
    else if(endColor == "Blue"){
      endColor = "#4C9DCE";
      endZone = "Blue Home";

    }
    else{
      endColor = "#FDFF6E";
      endZone = "Neutral Territory";

    }

    if (direction == "right") {
      rightColor = endColor;
    }
    else {
      leftColor = endColor;
    }

    if(leftColor != rightColor){
      img1 = img2 =  '/util/img/' + direction + 'Arrow.png';
    }
    else{
      if(direction == "right"){
        img1 =  '/util/img/' + direction + 'Arrow.png';
        img2 = '/util/img/leftUTurn.png';
      }
      else{
        img2 =  '/util/img/' + direction + 'Arrow.png';
        img1 = '/util/img/rightUTurn.png';
      }
    }

  }
  else{
    fail = "true";
    if(direction == "right"){
      img1 =  '/util/img/' + direction + 'Arrow.png';
      img2 = '/util/img/trapped.png';
    }
    else{
      img2 =  '/util/img/' + direction + 'Arrow.png';
      img1 = '/util/img/trapped.png';
    }
  }


  var html = '<div ' +
    ' data-startZone="'+startZone+'"' +
    ' data-endZone="'+endZone+'"' +
    ' data-defenseID="'+defenseID+'"' +
    ' data-fail="'+fail+'" ' +
    ' data-actionType="breach"' +
    ' data-mode="'+mode+'" ' +
    ' data-direction="'+direction+'" ' +
    'class="historyItem" style="display: flex">' +
    '<img class="deleteHistoryItem" src="/util/img/redX.gif" style="cursor:pointer;flex: 0 0 10%;height: 85%;">' +
    '<div style="display: flex;flex: 1 1 80%;flex-direction: row;height: 100%">' +
    '<div style="flex : 1 0 20%;display: flex;align-items: center;background-color: ' + leftColor + '">' +
    '<img src="'+img1+'" style="width: 80%;margin: 0 auto"/>' +
    '</div>' +
    '<div style="flex : 1 0 60%;background-image: ' + defenseImg.replace(new RegExp('"', 'g'), "'") + ';background-size: contain; background-repeat: no-repeat; background-position: center;"></div>' +
    '<div style="flex : 1 0 20%;display: flex;align-items: center;background-color: ' + rightColor + '">' +
    '<img src="'+img2+'" style="width: 80%;margin: 0 auto"/>' +
    '</div>' +
    '</div>' +
    '<img class="moveHistoryItem" src="/util/img/upDownImage.png" style="cursor:pointer;flex: 0 0 10%;height: 85%;">' +
    '</div> ';
  $("#historyList").prepend(html);


  generateJSON();
  checkAutoCount();

}
function clearBreach(){

  var startCircleContainer = $(".sideCircleContainer[startedHere='true']");
  var defenseCircle = $(".defenseCircle:visible");
  var endingCircle = $(".backOutFromBreachCircle:visible");
  $(startCircleContainer).removeAttr("startedHere").find(".startBreachCircle").hide();
  $(defenseCircle).parent().siblings().eq(0).removeClass("blurred");
  $(defenseCircle).hide();
  $(endingCircle).hide();
  BREACH_MAP_FILTER = "origin";
}
function handleKeypress(e){
    if(e.code.indexOf("Digit") > -1){
      var index = parseInt(e.code.substring(e.code.length -1)) -1;
      clearBreach();
      clearShoot();
      $("#taskSwitcher > div").eq(index).trigger("click");
    }
    else  if (e.keyCode == 27) { // escape key maps to keycode `27`
      if($("#breachPage").width() > 0){
        $("#cancelBreach").trigger("click");
      }
      else if($("#shootPage").width() > 0){
        $("#cancelShoot").trigger("click");
      }
    }
    else if (e.keyCode == 90 && e.ctrlKey){
      $("#historyList div.historyItem").eq(0).find(".deleteHistoryItem").trigger("click")
    }
}



</script>
</html>