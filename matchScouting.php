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

  $helper->setCollectionStarted($match->id, $SCOUTING_TEAM);
$BOILERS_POS = "boilers" . ucwords($helper->BOILERS_POS);
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
      height: auto;
      display: flex;
      border-bottom: 1px solid black;
      align-items: center;
      justify-content: center;
      flex: 1 1;
    }
    .historyItem h3{
      word-wrap: break-word;
      overflow: hidden;
      margin: 10px 0 10px 0;

    }
    .circle {
      border-radius: 50%;
    }
    .blurred {
      filter: blur(3px);
      -webkit-filter: blur(3px);
    }


    .toast-warning, .toast-error{
      opacity: 1 !important;
    }



    g{
      pointer-events: all;
    }

    .moveHistoryItem{
      cursor:pointer;height: 33px;
    }
    .deleteHistoryItem{
      cursor:pointer;height: 33px;
    }

    input.largerCheckbox {
      width: 30px;
      height: 30px;
    }

    .rating{
      margin: 0 auto;
      padding-left: 20px;
      padding-right: 20px;
    }

    #ratingsContainer > div {
      margin-bottom: 30px;
    }

    .keyboard_shortcut{
      text-decoration: underline;
    }

  </style>

</head>
<div id="resumingMatchNotice" style="
      display:none;
      position: absolute;
      width: 100%;
      height: 100%;
      background-color: white;
      align-items: center;
      z-index: 15;">
  <h2 style="width: 100%;text-align: center;font-size: 50px">Resuming Match...</h2>
</div>
<script>
  if(localStorage.getItem("matchData") !== null && localStorage.getItem("matchData") != "" ) {

    $("#resumingMatchNotice").css("display","flex");

  }
</script>
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
<body style="display: flex;flex-direction: column">
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
  <div class="col-sm-3" data-color="darkBlue" div-id="otherFieldsPage">
    <h1 class="darkBlue">Other</h1>
  </div>
</div>
<div id="contentBody" style="align-items: center; flex: 1; display: flex;flex-direction: column">
  <div class="row" style="flex:1;width: 100%;display:flex;justify-content: space-around;flex-direction: row">
    <div id="gearPage" style="display:flex;flex: 0 1 70%;flex-direction:column;">
      <div style="margin: 15px;flex: 1; display: flex;">
        <div style="display: flex;flex-direction: row;width: 60%;">
          <object style="display:flex;flex: 1;" type="image/svg+xml" data="/util/svg/gears/gear<?=ucwords($SCOUTING_TEAM_COLOR)?><?=$SCOUTING_TEAM_COLOR == $helper->LEFT_TEAM ? "Left":"Right"?>.svg" id="gearSVG"></object>
        </div>

        <div style="position:relative;display: flex;flex-direction: column;width: 40%;">
          <a id="cancelGear"
             style="border:2px solid black;position: absolute;margin-top:16px;margin-left:16px"
             class="button button-pill button-caution ">Cancel</a>

          <div style="display: flex; flex-direction: column; width: 80%; flex-flow: column; height: 10px; margin: 0 auto; flex: 1 0 100%; justify-content: center; align-items: center;">

            <div
              style="cursor:pointer;display:flex;flex-direction:row;flex: 0 1 80px; width: 100%; margin: 5% auto 5% auto;border: 2px solid black;">
              <div id="gearScore" style="display:flex;align-items:center;flex: 1;border-right: 2px solid black">
                <h2 style="font-weight:bold;flex: 1;text-align: center"><span class="keyboard_shortcut">S</span>core</h2>
              </div>
              <div id="gearMiss" style="display:flex;align-items:center;flex: 1">
                <h2 style="font-weight:bold;flex: 1;text-align: center"><span class="keyboard_shortcut">M</span>iss</h2>
              </div>
            </div>

          </div>

        </div>


      </div>
    </div>
    <div id="feedPage" style="display:flex;flex: 0 1 0%;width: 0;height: 0;overflow: hidden">

      <div style="margin: 15px;flex: 1; display: flex;">
        <div style="display: flex;flex-direction: row;width: 60%;">
          <object style="flex: 1;visibility:hidden" type="image/svg+xml" data="/util/svg/<?=$BOILERS_POS?>/left<?=ucwords($helper->LEFT_TEAM)?>FeedFor<?=ucwords($SCOUTING_TEAM_COLOR)?>.svg" id="feedSVG"></object>

        </div>

        <div style="position:relative;display: flex;flex-direction: column;width: 40%;">
          <a id="cancelFeed"
             style="border:2px solid black;position: absolute;margin-top:16px;margin-left:16px"
             class="button button-pill button-caution ">Cancel</a>

          <style>

            #feedBody{
              display: flex;flex-direction: column;width: 80%;flex-flow: column;height: 10px;margin: 0 auto;flex: 1 0 100%;justify-content: center;align-items: center;
            }
            #feedTypeSwitcher{
              cursor:pointer;display:flex;flex-direction:row;flex: 0 1 80px;width: 100%;margin: 5% auto 5% auto;border: 2px solid black;;min-height:46px
            }
            #feedBallPercentage,#feedGear,#feedBall, #feedBallCount{
              width: 100%
            }
            #feedGearOption,#feedBallOption,#feedGearDropped,#feedGearGround,#feedGearSuccess,#feedGearFailure{
              display:flex;align-items:center;flex: 1
            }
            #feedBallPercentage, #feedBall, #feedBallCount{
              cursor: pointer; display: flex; flex-direction: column; flex: 0 1 260px; width: 100%;
            }
            #feedBallPercentageOptions,#feedGearOutcomeOptions,#feedGearMethodOptions, #feedBallCountOptions{
              cursor:pointer;display:flex;flex-direction:row;flex: 0 1 80px; width: 100%; margin: 5% auto 5% auto;border: 2px solid black;min-height:46px
            }
            #feedBallPercentageBefore,#feedBallPercentageAfter, #feedBallAmount{
              display:flex;align-items:center;flex: 1;width:50%;flex-direction:column;justify-content: center;
            }
            #feedBallOption,#feedBallPercentageBefore,#feedGearSuccess,#feedGearDropped{
               border-right: 2px solid black;
             }
            #feedBallPercentageAfter input, #feedBallPercentageBefore input, #feedBallAmount input{
              font-weight:bold;text-align: center;width:80%;margin: 0 auto;height: 70%;flex: 0 1 60px;outline:none;border:none;
            }
            #feedBallPercentageAfter span, #feedBallPercentageBefore span, #feedBallAmount span{
              margin-top: 5px;
            }
          </style>



          <div id="feedBody" style="">
            <div id="feedTypeSwitcher" style="">
              <div id="feedBallOption" style="">
                <h2 style="font-weight:bold;flex: 1;text-align: center"><span class="keyboard_shortcut">B</span>all</h2>
              </div>
              <div id="feedGearOption" style="">
                <h2 style="font-weight:bold;flex: 1;text-align: center"><span class="keyboard_shortcut">G</span>ear</h2>
              </div>
            </div>
            <div id="feedBall" style="display: none">
              <div id="feedBallPercentage" style="display:none">
                <h2 style=" margin: 0;text-align:center ">Percentage of Robot's Ball Pit:</h2>
                <div style="width: 100%;display:flex;flex-direction: row;flex: 1 1 65px;margin-top:10px">
                  <div data-action="increment" data-for="feedBallPercentageBefore" style="flex: 1 1;height:100%;display:flex;align-items:center;justify-content:center;min-width: 0;padding:0" class='numericStepper button button-flat-action'><h1>+</h1></div>
                  <div data-action="decrement" data-for="feedBallPercentageBefore" style="flex: 1 1;height:100%;display:flex;align-items:center;justify-content:center;min-width: 0;padding:0" class='numericStepper button button-flat-caution'><h1>-</h1></div>
                  <div data-action="increment" data-for="feedBallPercentageAfter"  style="flex: 1 1;height:100%;display:flex;align-items:center;justify-content:center;min-width: 0;padding:0" class='numericStepper button button-flat-action'><h1>+</h1></div>
                  <div data-action="decrement" data-for="feedBallPercentageAfter"  style="flex: 1 1;height:100%;display:flex;align-items:center;justify-content:center;min-width: 0;padding:0" class='numericStepper button button-flat-caution'><h1>-</h1></div>
                </div>
                <div id="feedBallPercentageOptions" style="">
                  <div id="feedBallPercentageBefore" style="/">
                    <span>Before %</span>
                    <input autofocus maxlength="3" onkeypress="return restrictCharacters(this, event, /[0-9]/g);" type="number" pattern="[0-9]{3}" inputmode="numeric" min="0" max="100" step="5">
                  </div>
                  <div id="feedBallPercentageAfter" style="/">
                    <span>After %</span>
                    <input maxlength="3" onkeypress="return restrictCharacters(this, event, /[0-9]/g);" type="number" pattern="[0-9]{3}" inputmode="numeric" min="0" max="100" step="5">
                  </div>
                </div>
                <a id="submitFeedBallPercentage"
                   style="border:2px solid black;"
                   class="button button-pill button-primary ">Add</a>
                <a id="feedBallSwitchToCount" style="color:#4d00ff; margin-top: 15px; text-align:right">Give Count</a>
              </div>
              <div id="feedBallCount">
                <h2 style=" margin: 0;text-align:center;">Number of Balls Fed:</h2>
                <div style="width: 100%;display:flex;flex-direction: row;flex: 1 1 65px;margin-top:10px">
                  <div data-action="increment" data-for="feedBallAmount" style="flex: 1 1;height:100%;display:flex;align-items:center;justify-content:center;min-width: 0;padding:0" class='button button-flat-action  numericStepper'><h1>+</h1></div>
                  <div data-action="decrement" data-for="feedBallAmount" style="flex: 1 1;height:100%;display:flex;align-items:center;justify-content:center;min-width: 0;padding:0" class='button button-flat-caution numericStepper'><h1>-</h1></div>
               </div>
                <div id="feedBallCountOptions" style="">
                  <div id="feedBallAmount" style="/">
                    <input autofocus maxlength="3" onkeypress="return restrictCharacters(this, event, /[0-9]/g);" type="number" pattern="[0-9]{3}" inputmode="numeric" min="0" step="1" placeholder="50">
                  </div>
                </div>
                <a id="submitFeedBallCount"
                   style="border:2px solid black;"
                   class="button button-pill button-primary ">Add</a>
                <a id="feedBallSwitchToPercentage" style="color:#4d00ff; margin-top: 15px; text-align:right">Give percentages</a>

              </div>
            </div>
            <div id="feedGear" style="display:none">
              <div id="feedGearOutcomeOptions" style="">
                <div id="feedGearSuccess" style="">
                  <h2 style="font-weight:bold;flex: 1;text-align: center"><span class="keyboard_shortcut">S</span>uccess</h2>
                </div>
                <div id="feedGearFailure" style="">
                  <h2 style="font-weight:bold;flex: 1;text-align: center"><span class="keyboard_shortcut">F</span>ailure</h2>
                </div>
              </div>
              <div id="feedGearMethodOptions" style="">
                <div id="feedGearDropped" style="">
                  <h2 style="font-weight:bold;flex: 1;text-align: center"><span class="keyboard_shortcut">D</span>ropped In</h2>
                </div>
                <div id="feedGearGround" style="">
                  <h2 style="font-weight:bold;flex: 1;text-align: center">From G<span class="keyboard_shortcut">r</span>ound</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
    <div id="shootPage" style="display:flex;flex: 0 1 0%;width: 0;height: 0;overflow: hidden;flex-direction:column;">

      <div style="margin: 15px;flex: 1; display: flex;">
        <div style="display: flex;flex-direction: row;width: 60%;">
          <object style="cursor:crosshair;display:flex;flex: 1;" type="image/svg+xml"
                  field-direction="<?= ($helper->LEFT_TEAM == $SCOUTING_TEAM_COLOR ? "right" : "left") ?>" id="shootSVG"
                  data="/util/svg/<?=$BOILERS_POS?>/<?= ($SCOUTING_TEAM_COLOR) . ($helper->LEFT_TEAM == $SCOUTING_TEAM_COLOR ? "Left" : "Right") ?>Shoot.svg"></object>
        </div>

        <div style="position:relative;display: flex;flex-direction: column;width: 40%;">
          <a id="cancelShoot"
             style="border:2px solid black;position: absolute;margin-top:16px;margin-left:16px"
             class="button button-pill button-caution ">Cancel</a>

          <style>

            #shootBody{
              display: flex;flex-direction: column;width: 80%;flex-flow: column;height: 10px;margin: 0 auto;flex: 1 0 100%;justify-content: center;align-items: center;
            }
            #shootLevel{
              cursor:pointer;display:flex;flex-direction:row;flex: 0 1 80px;width: 100%;margin: 5% auto 5% auto;border: 2px solid black;;min-height:46px
            }
            #shootPercentages,#shootCounts{
              width: 100%
            }
            #shootHighOption,#shootLowOption{
              display:flex;align-items:center;flex: 1
            }
            #shootPercentages,#shootCounts{
              cursor: pointer; display: flex; flex-direction: column; flex: 0 1 278px; width: 100%;
            }
            #shootPercentageOptions,#shootCountOptions{
              cursor:pointer;display:flex;flex-direction:row;flex: 0 1 80px; width: 100%; margin: 5% auto 5% auto;;min-height:46px;border:2px solid black;
            }
            .shootInputContainer{
              display:flex;align-items:center;flex: 1;flex-direction:column;justify-content: center;
            }
            #shootPercentageAfter,#shootPercentageBefore,#shootCountAttempted,#shootHighOption,#shootCountAccuracy{
              border-right: 2px solid black;
            }
            .shootInputContainer input{
              font-weight:bold;text-align: center;width:90%;margin: 0 auto;height: 100%;flex: 0 1 60px;outline:none;border:none;
            }
            .shootInputContainer span{
              margin-top: 5px;text-align:center;
            }

          </style>

          <div id="shootBody" style="">
            <div id="shootLevel" style="">
              <div id="shootHighOption" style="">
                <h2 style="font-weight:bold;flex: 1;text-align: center"><span class="keyboard_shortcut">H</span>igh</h2>
              </div>
              <div id="shootLowOption" style="">
                <h2 style="font-weight:bold;flex: 1;text-align: center"><span class="keyboard_shortcut">L</span>ow</h2>
              </div>
            </div>
            <div id="shootPercentages" style="display:none">
              <h2 style=" margin: 0;text-align:center ">Percentage of Robot's Ball Pit:</h2>
              <div style="width: 100%;display:flex;flex-direction: row;flex: 1 1 65px;margin-top:10px">
                <div data-action="increment" data-for="shootPercentageBefore"   style="flex: 1 1;height:100%;display:flex;align-items:center;justify-content:center;min-width: 0;padding:0" class='button button-flat-action numericStepper'><h1>+</h1></div>
                <div data-action="decrement" data-for="shootPercentageBefore"   style="flex: 1 1;height:100%;display:flex;align-items:center;justify-content:center;min-width: 0;padding:0" class='button button-flat-caution numericStepper'><h1>-</h1></div>
                <div data-action="increment" data-for="shootPercentageAfter"    style="flex: 1 1;height:100%;display:flex;align-items:center;justify-content:center;min-width: 0;padding:0" class='button button-flat-action numericStepper'><h1>+</h1></div>
                <div data-action="decrement" data-for="shootPercentageAfter"    style="flex: 1 1;height:100%;display:flex;align-items:center;justify-content:center;min-width: 0;padding:0" class='button button-flat-caution numericStepper'><h1>-</h1></div>
                <div data-action="increment" data-for="shootPercentageAccuracy" style="flex: 1 1;height:100%;display:flex;align-items:center;justify-content:center;min-width: 0;padding:0" class='button button-flat-action numericStepper'><h1>+</h1></div>
                <div data-action="decrement" data-for="shootPercentageAccuracy" style="flex: 1 1;height:100%;display:flex;align-items:center;justify-content:center;min-width: 0;padding:0" class='button button-flat-caution numericStepper'><h1>-</h1></div>
             </div>
              <div id="shootPercentageOptions" style="">
                <div id="shootPercentageBefore" class="shootInputContainer" style="/">
                  <span>% Before</span>
                  <input autofocus maxlength="3" onkeypress="return restrictCharacters(this, event, /[0-9]/g);" type="number" pattern="[0-9]{3}" inputmode="numeric" min="0" max="100" step="5" >
                </div>
                <div id="shootPercentageAfter" class="shootInputContainer" style="/">
                  <span>% After</span>
                  <input maxlength="3" onkeypress="return restrictCharacters(this, event, /[0-9]/g);" type="number" pattern="[0-9]{3}" inputmode="numeric" min="0" max="100" step="5" >
                </div>
                <div id="shootPercentageAccuracy" class="shootInputContainer" style="/">
                  <span>Acc. %</span>
                  <input maxlength="3"  onkeypress="return restrictCharacters(this, event, /[0-9]/g);" type="number" pattern="[0-9]{3}" inputmode="numeric" min="0" max="100" step="5" >
                </div>
              </div>
              <a id="submitShootPercentages"
                 style="border:2px solid black;"
                 class="button button-pill button-primary ">Add</a>
              <a id="shootSwitchToCount" style="color:#4d00ff; margin-top: 15px; text-align:right">Give Count</a>
            </div>
            <div id="shootCounts" >
              <h2 style=" margin: 0; text-align:center">Count the balls shot:</h2>
              <div style="width: 100%;display:flex;flex-direction: row;flex: 1 1 65px;margin-top:10px">
                <div data-action="increment" data-for="shootCountAttempted"   style="flex: 1 1;height:100%;display:flex;align-items:center;justify-content:center;min-width: 0;padding:0" class='button button-flat-action numericStepper'><h1>+</h1></div>
                <div data-action="decrement" data-for="shootCountAttempted"   style="flex: 1 1;height:100%;display:flex;align-items:center;justify-content:center;min-width: 0;padding:0" class='button button-flat-caution numericStepper'><h1>-</h1></div>
                <div data-action="increment" data-for="shootCountAccuracy"    style="flex: 1 1;height:100%;display:flex;align-items:center;justify-content:center;min-width: 0;padding:0" class='button button-flat-action numericStepper'><h1>+</h1></div>
                <div data-action="decrement" data-for="shootCountAccuracy"    style="flex: 1 1;height:100%;display:flex;align-items:center;justify-content:center;min-width: 0;padding:0" class='button button-flat-caution numericStepper'><h1>-</h1></div>
                <div data-action="increment" data-for="shootCountLeftoverPercentage" style="flex: 1 1;height:100%;display:flex;align-items:center;justify-content:center;min-width: 0;padding:0" class='button button-flat-action numericStepper'><h1>+</h1></div>
                <div data-action="decrement" data-for="shootCountLeftoverPercentage" style="flex: 1 1;height:100%;display:flex;align-items:center;justify-content:center;min-width: 0;padding:0" class='button button-flat-caution numericStepper'><h1>-</h1></div>
              </div>
              <div id="shootCountOptions" style="">
                <div id="shootCountAttempted" class="shootInputContainer"  style="/">
                  <span>Attempts</span>
                  <input autofocus onkeypress="return restrictCharacters(this, event, /[0-9]/g);" type="number" inputmode="numeric" min="0" step="1">
                </div>
                <div id="shootCountAccuracy" class="shootInputContainer" style="/">
                  <span>% Scored</span>
                  <input  onkeypress="return restrictCharacters(this, event, /[0-9]/g);" type="number" inputmode="numeric" min="0" max="100" step="5">
                </div>
                <div id="shootCountLeftoverPercentage" class="shootInputContainer" style="/">
                  <span>% Ball Pit Left</span>
                  <input  onkeypress="return restrictCharacters(this, event, /[0-9]/g);" type="number" inputmode="numeric" min="0" max="100" step="5" value="0">
                </div>
              </div>
              <a id="submitShootCount"
                 style="border:2px solid black;"
                 class="button button-pill button-primary ">Add</a>
              <a id="shootSwitchToPercentage" style="color:#4d00ff; margin-top: 15px; text-align:right">Give percentages</a>

            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="otherFieldsPage" style="flex-direction: column; display: flex; flex: 0 1 0%;width: 0;height: 0;overflow: hidden;align-items: center;">

      <div style="align-self: flex-start;margin: 0 auto;width:100%;padding-left: 50px;">
        <div style="width:65%;float:left">
          <h3 style="font-weight: bold;font-size: 1.5vw">
            Climb Time - <span id="climbTimer">0:00 mins</span>
            <a id="startClimbTimer" style="margin: 0 auto;" href="#" class="button button-flat-action ">Start</a>
            <a id="endClimbTimer" style="margin: 0 auto;" href="#" class="button button-flat-caution disabled ">Stop</a>
            <a id="clearClimbTimer" style="margin: 0 auto;" href="#" class="button button-flat-caution disabled ">Clear</a>
          </h3>
          <h3 style="font-weight: bold;font-size: 1.5vw">
            <input style="width: 20px;height: 20px" type="checkbox" id="reachedLineInAuto"> <label for="reachedLineInAuto">Reached Line in Auto?</label>
          </h3>
        </div>
        <div style="width:35%;float:right">

          <h3 style="font-weight: bold;font-size: 1.5vw">
            <input style="width: 20px;height: 20px" type="checkbox" id="climbSuccess"> <label for="climbSuccess">Reached Touchpad?</label>
          </h3>
        </div>

      </div>

      <div class="row" style="margin-top:20px;margin-bottom:10px;width: 100%;" id="ratingsContainer">
        <div class="col-md-3 col-sm-6" style="display: flex">
          <div style="margin: 0 auto;width: 95%">
            <h4 style="text-align: center;margin-top: 0">Ball Feeding (Ground):</h4>
            <div id="ballGroundFeedingRating" class="rating" ></div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6" style="display: flex">
          <div style="margin: 0 auto;width: 95%">
            <h4 style="text-align: center;margin-top: 0">Ball Feeding (L/D Lanes):</h4>
            <div id="ballLaneFeedingRating" class="rating" ></div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6" style="display: flex;border-right: 1px solid black">
          <div style="margin: 0 auto;width: 95%">
            <h4 style="text-align: center;margin-top: 0">Ball Shooting Speed:</h4>
            <div id="ballShootingSpeedRating" class="rating" ></div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6" style="display: flex">
          <div style="margin: 0 auto;width: 95%">
            <h4 style="text-align: center;margin-top: 0">Ability to Defend: </h4>
            <div id="defenseRating" class="rating" ></div>
          </div>
        </div>

        <div class="col-md-3 col-sm-6" style="display: flex">
          <div style="margin: 0 auto;width: 95%">
            <h4 style="text-align: center;margin-top: 0">Gear Feeding (Ground):</h4>
            <div id="gearGroundFeedingRating" class="rating" style="width: 100%;margin: 0 auto"></div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6" style="display: flex">
          <div style="margin: 0 auto;width: 95%">
            <h4 style="text-align: center;margin-top: 0">Gear Feeding (L/D Lanes):</h4>
            <div id="gearLaneFeedingRating" class="rating" style="width: 100%;margin: 0 auto"></div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6" style="display: flex;border-right: 1px solid black">
          <div style="margin: 0 auto;width: 95%">
            <h4 style="text-align: center;margin-top: 0">Gear Placing Speed:</h4>
            <div id="gearPlacingSpeedRating" class="rating" ></div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6" style="display: flex">
          <div style="margin: 0 auto;width: 95%">
            <h4 style="text-align: center;margin-top: 0">Ability to Escape Defense: </h4>
            <div id="defenseEscapeRating" class="rating" ></div>
          </div>
        </div>
        <div class="col-md-12 col-sm-12" style="display: flex;flex-direction: column">
          <h3 style="text-align:center;width: 100%">Notes:</h3>
          <textarea style="width: 70%;margin:0 auto; height: 60px" id="matchNotes"></textarea>

        </div>

        <hr style="width: 100%"/>
        <a id="comfirmSubmitMatch" style="margin: 0 auto;" href="#" class="button button-pill button-flat-royal button-large">Submit</a>

      </div>

    </div>
    <div style="min-width:30%;flex:0 1;display: flex;flex-direction: column;">
      <div style="margin: 15px;flex: 1;display: flex;height: 100%;flex-direction: column;">
        <div style="margin:0;width:100%;border: 1px solid black;padding: 10px 0 10px 0;text-align: center;">
          <h2 style="font-weight: bold;margin: 0;">History</h2>
          <h4 id="historyModeText" style=" margin: 0; margin-top: 5px;">Auto Mode</h4>
        </div>


        <div id="historyList"
             style="border: 1px solid black;border-top:0;width: 100%;flex:1;border-right:2px solid black;overflow: scroll;flex-direction:column;">

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
function triggerKeyboardEvent(el, e){
  var eventObj = document.createEventObject ?
    document.createEventObject() : document.createEvent("Events");

  if(eventObj.initEvent){
    eventObj.initEvent("keyup", true, true);
  }

  eventObj.keyCode = e.keyCode;
  eventObj.which = e.keyCode;
  eventObj.target = el;
  eventObj.code = e.code;

  el.dispatchEvent ? el.dispatchEvent(eventObj) : el.fireEvent("onkeyup", eventObj);

}
function restrictCharacters(myfield, e, restrictionType) {
  if (!e) var e = window.event
  if (e.keyCode) code = e.keyCode;
  else if (e.which) code = e.which;
  var character = String.fromCharCode(code);
  // if they pressed esc... remove focus from field...
  if (code==27) { this.blur(); return false; }
  // ignore if they are press other keys
  // strange because code: 39 is the down key AND ' key...
  // and DEL also equals .
  if (!e.ctrlKey && code!=9 && code!=8 && code!=36 && code!=37 && code!=38 && (code!=39 || (code==39 && character=="'")) && code!=40) {
    if (character.match(restrictionType)) {
      return true;
    } else {
     triggerKeyboardEvent(document.body, e);
      return false;
    }
  }
}
var gearSVGDoc;
var shootSVGDoc;
var feedSVGDoc;
var HALF_FIELD_LENGTH_INCHES = <?=$helper::HALF_FIELD_LENGTH_INCHES?>;
var HALF_FIELD_HEIGHT_INCHES = <?=$helper::HALF_FIELD_HEIGHT_INCHES?>;

var SHOOT_POS_X = null;
var SHOOT_POS_Y = null;
var SHOOT_LEVEL = null;
var SHOOT_AMT_PERCENT_START = null;
var SHOOT_AMT_PERCENT_END = null;
var SHOOT_ACCURACY_PERCENT = null;
var SHOOT_AMT_COUNT_SCORED = null;
var SHOOT_AMT_COUNT_MISSED = null;
var SHOOT_AMT_COUNT_LEFTOVER = 0;
//var SHOOT_INPUT_METHOD = null;

var GEAR_LOCATION = null;
var GEAR_RESULT = null;
var NUMERIC_STEPPER_INTERVAL = null;
var FEED_BALL_LOCATION = null;
var FEED_BALL_PERCENT_START = null;
var FEED_BALL_PERCENT_END = null;
var FEED_BALL_COUNT = null;
var FEED_GEAR_RESULT = null;
var FEED_GEAR_METHOD = null;

var RESUMING_MATCH =localStorage.getItem("matchData") !== null && localStorage.getItem("matchData") != "";
var LEFT_TEAM_COLOR = "<?=($helper->LEFT_TEAM)?>";
var SCOUTING_TEAM_COLOR = "<?=$SCOUTING_TEAM_COLOR?>";

var feedSVG_IDS = ["overflowHolder","returnFarHolder","returnCloseHolder","boilerSideCloseHolder","boilerSideMiddleHolder","boilerSideFarHolder","loadingSideCloseHolder","loadingSideFarHolder"]
$(document).ready(function () {
  if (!RESUMING_MATCH) {
    localStorage.setItem("matchData", "");
  }
  else{
      $(window).load(function(){
        if(localStorage.getItem("matchData") !== null && localStorage.getItem("matchData") != "") {
          var data = JSON.parse(localStorage.getItem("matchData"));
          if(data.currMode == "tele"){
            $("#modeHeading").trigger("click");
          }
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
              case "feedBall":
                FEED_BALL_LOCATION = action.location;
                FEED_BALL_PERCENT_START = parseInt(action.before) || null;
                FEED_BALL_PERCENT_END = parseInt(action.after) || null;
                FEED_BALL_COUNT = parseInt(action.count) || null;
                checkAndAddBallFeedHistoryItem(action.mode,action.inputMethod);
               break;
              case "feedGear":
                FEED_GEAR_RESULT = parseInt(action.result);
                FEED_GEAR_METHOD = action.method;
                checkAndAddGearFeedHistoryItem(action.mode);
                break;
              case "shoot" :
                SHOOT_POS_X = parseFloat(action.coordX);
                SHOOT_POS_Y = parseFloat(action.coordY);
                SHOOT_LEVEL = parseInt(action.level);
                SHOOT_AMT_PERCENT_START = parseInt(action.before);
                SHOOT_AMT_PERCENT_END = parseInt(action.after);
                SHOOT_ACCURACY_PERCENT = parseInt(action.accuracy);
                SHOOT_AMT_COUNT_SCORED = parseInt(action.scored);
                SHOOT_AMT_COUNT_MISSED = parseInt(action.missed);
                SHOOT_AMT_COUNT_LEFTOVER = parseInt(action.leftover);
                checkAndAddShootHistoryItem(action.mode, action.inputMethod);
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

            $("#reachedLineInAuto").prop("checked",data.otherFields.autoLineCrossed);
            $("#climbSuccess").prop("checked",data.otherFields.climbSuccess);
            $("#ballGroundFeedingRating").rateYo("rating", data.otherFields.ballGroundFeedingRating);
            $("#ballLaneFeedingRating").rateYo("rating", data.otherFields.ballLaneFeedingRating);
            $("#ballShootingSpeedRating").rateYo("rating", data.otherFields.ballShootingSpeedRating);
            $("#gearGroundFeedingRating").rateYo("rating", data.otherFields.gearGroundFeedingRating);
            $("#gearLaneFeedingRating").rateYo("rating", data.otherFields.gearLaneFeedingRating);
            $("#gearPlacingSpeedRating").rateYo("rating", data.otherFields.gearPlacingSpeedRating);
            $("#defenseRating").rateYo("rating", data.otherFields.defenseRating);
            $("#defenseEscapeRating").rateYo("rating", data.otherFields.defenseEscapeRating);
            $("#climbTimer").text(data.otherFields.duration + " mins");
          generateJSON();

        }

        $("#resumingMatchNotice").fadeOut(600);

      });
  }
  $("#contentBody").click(function(e){
    if(e.target.tagName !== "INPUT"){

      focusOnAvailableInput();
    }
  });
  $(".numericStepper").mousedown(function(){
    var _this = this;
      NUMERIC_STEPPER_INTERVAL = setInterval(function(){
        var id = $(_this).attr("data-for");
        var step = $("#"+ id + " input").attr("step") || 1;
        var factor = $(_this).attr("data-action") == "increment" ? 1 : -1
        var min = $("#"+ id + " input").attr("min") || -9007199254740991;
        var max = $("#"+ id + " input").attr("max") ||  9007199254740991;
        var val = $("#"+ id + " input").val() || 0
        if(parseInt(val) + (step*factor) <= max && parseInt(val) + (step*factor) >= min){
          $("#"+ id + " input").val(parseInt(val) + (step*factor));
        }
      },100)
  }).mouseup(function(){
    var _this = this;
    clearInterval(NUMERIC_STEPPER_INTERVAL);
  });
  $("#cancelFeed").click(function(){
    clearFeed();
  });
  $("#cancelShoot").click(function(){
    clearShoot();
  });
  $("#cancelGear").click(function(){
    clearGear();
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

    $("#gearPage,#feedPage,#shootPage,#otherFieldsPage").css("flex", "0").css("width", "0").css("height", "0").css("overflow", "hidden");
    $("#" + $(this).attr("div-id")).css("flex", "0 1 70%").css("width", "").css("height", "").css("overflow", "");
    generateJSON();
  });
  $("#historyList").on("click", ".deleteHistoryItem", function () {
    $(this).parent().remove();
    generateJSON();
  });
  $("#shootPage #shootHighOption, #shootPage #shootLowOption").click(function () {
    $(this).css("background-color", "#FF7F2A").attr("selected");
    $(this).siblings().eq(0).css("background-color", "").removeAttr("selected");
    if($(this).attr("id") == "shootHighOption"){
      SHOOT_LEVEL = 1;
    }
    else{
      SHOOT_LEVEL = 0;
    }

  });
  $("#shootPage #shootSwitchToPercentage").click(function(){
    $("#shootPage #shootCounts").hide();
    $("#shootPage #shootPercentages").show();
//    SHOOT_INPUT_METHOD = "percent";
    focusOnAvailableInput();
  });
  $("#shootPage #shootSwitchToCount").click(function(){
    $("#shootPage #shootCounts").show();
    $("#shootPage #shootPercentages").hide();
//    SHOOT_INPUT_METHOD = "count";
    focusOnAvailableInput();
  });
  $("#shootPage #submitShootCount").click(function () {
    var attempted = $("#shootCountAttempted input").val();
    var accuracy = $("#shootCountAccuracy input").val();
    var leftover = $("#shootCountLeftoverPercentage input").val();
    var errors = false;
    if(attempted != "" && accuracy != ""){
      SHOOT_AMT_COUNT_SCORED = Math.round(attempted * (parseInt(accuracy)/100));
      SHOOT_AMT_COUNT_MISSED = attempted - SHOOT_AMT_COUNT_SCORED;
    }
    else{
      toastr["error"]("Please fill out and try again", "A attempted count and accuracy is required!");
      errors = true;
    }
    if(leftover != ""){
      SHOOT_AMT_COUNT_LEFTOVER = leftover;
    }
    else{
      toastr["error"]("Please fill out and try again", "A leftover percentage is required!");
      errors = true;
    }
    if(errors){return;}
    checkAndAddShootHistoryItem( $("#modeHeading").attr("data-mode"), "count");
    generateJSON();
    checkAutoCount();
  });
  $("#shootPage #submitShootPercentages").click(function () {
    var before = $("#shootPercentageBefore input").val();
    var after = $("#shootPercentageAfter input").val();
    var accuracy = $("#shootPercentageAccuracy input").val();
    var errors = false;
    if(before != ""){
      SHOOT_AMT_PERCENT_START = before;
    }
    else{
      toastr["error"]("Please fill out and try again", "A Before % is required!");
      errors = true;
    }
    if(after != ""){
      SHOOT_AMT_PERCENT_END = after;
    }
    else{
      toastr["error"]("Please fill out and try again", "An After % is required!");
      errors = true;
    }
    if(accuracy != ""){
      SHOOT_ACCURACY_PERCENT = accuracy;
    }
    else{
      toastr["error"]("Please fill out and try again", "An Accuracy % is required!");
      errors = true;
    }
    if(parseInt(before) > 100 || parseInt(after) > 100 || parseInt(accuracy) > 100){
      toastr["error"]("Percents cannot go over 100!");
      errors = true;
    }
    if(errors) {return;}
    checkAndAddShootHistoryItem( $("#modeHeading").attr("data-mode"),"percent");
    generateJSON();
    checkAutoCount();
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
  $("#feedPage #feedBallOption, #feedPage #feedGearOption").click(function () {
    $(this).css("background-color", "#FF7F2A").attr("selected");
    $(this).siblings().eq(0).css("background-color", "").removeAttr("selected");
    if($(this).attr("id") == "feedBallOption"){
      $("#feedPage #feedBall").show();
      $("#feedPage #feedGear").hide();
      $("#feedSVG").css("visibility","visible");
      FEED_TYPE = "ball";
    }
    else{
      $("#feedPage #feedBall").hide();
      $("#feedPage #feedGear").show();
      $("#feedSVG").css("visibility","hidden");
      FEED_TYPE = "gear";
    }
    focusOnAvailableInput();
  });
  $("#feedPage #feedGearSuccess, #feedPage #feedGearFailure").click(function () {
    $(this).css("background-color", "rgb(42, 127, 255)").attr("selected");
    $(this).siblings().eq(0).css("background-color", "").removeAttr("selected");
    if($(this).attr("id") == "feedGearSuccess"){
      FEED_GEAR_RESULT = 1;
    }
    else{
      FEED_GEAR_RESULT = 0;
    }
    checkAndAddFeedHistoryItem( $("#modeHeading").attr("data-mode"),FEED_TYPE);
    generateJSON();
    checkAutoCount();
  });
  $("#feedPage #feedGearDropped, #feedPage #feedGearGround").click(function () {
    $(this).css("background-color", "#FF7F2A").attr("selected");
    $(this).siblings().eq(0).css("background-color", "").removeAttr("selected");
    if($(this).attr("id") == "feedGearDropped"){
      FEED_GEAR_METHOD = "dropped";
    }
    else{
      FEED_GEAR_METHOD = "ground";
    }
    checkAndAddFeedHistoryItem( $("#modeHeading").attr("data-mode"),FEED_TYPE);
    generateJSON();
    checkAutoCount();
  });
  $("#feedPage #submitFeedBallPercentage").click(function () {
    var before = $("#feedBallPercentageBefore input").val();
    var after = $("#feedBallPercentageAfter input").val();
    var errors = false;
    if(before != ""){
      FEED_BALL_PERCENT_START = before != "" ? before: null;
    }
    else{
      toastr["error"]("Please fill out and try again", "A Before % is required!");
      errors = true;
    }

    if(after != ""){
      FEED_BALL_PERCENT_END= after != "" ? after: null;
    }
    else{
      toastr["error"]("Please fill out and try again", "An After % is required!");
      errors = true;
    }
    if(parseInt(before) > 100 || parseInt(after) > 100){
      toastr["error"]("Percents cannot go over 100!");
      errors = true;
    }
    if(errors){return;}
    checkAndAddFeedHistoryItem( $("#modeHeading").attr("data-mode"),FEED_TYPE,"percent");
    generateJSON();
    checkAutoCount();
  });
  $("#feedPage #submitFeedBallCount").click(function () {
    var amount = $("#feedBallAmount input").val();
    var errors = false;
    if(amount != ""){
      FEED_BALL_COUNT = amount != "" ? amount: null;
    }
    else{
      toastr["error"]("Please fill out and try again", "A count is required!");
      errors = true;
    }
    if(errors){return;}
    checkAndAddFeedHistoryItem( $("#modeHeading").attr("data-mode"),FEED_TYPE,"count");
    generateJSON();
    checkAutoCount();
  });
  $("#feedPage #feedBallSwitchToPercentage, #feedPage #feedBallSwitchToCount").click(function(){
    $("#feedPage #feedBallCount").toggle();
    $("#feedPage #feedBallPercentage").toggle();
    focusOnAvailableInput();
  });
  $('#otherFieldsPage #reachedLineInAuto,#otherFieldsPage #climbSuccess').on('click', function(){
    generateJSON();
  });


  var sec = 0;
  function pad ( val ) { return val > 9 ? val : "0" + val; }
var climbTimer;
  $("#otherFieldsPage #startClimbTimer").click(function(){
      if($(this).hasClass("disabled")){return;}
//      sec = 0;
      $("#climbTimer").text( pad(parseInt(sec/60,10)) + ":" + pad(sec%60) + " mins");
      $(this).addClass("disabled");
      $("#otherFieldsPage #endClimbTimer").removeClass("disabled");
      climbTimer = setInterval( function(){
        var seconds = pad(++sec%60);
        var mins = pad(parseInt(sec/60,10));
        $("#climbTimer").text(mins+":"+seconds + " mins");
        generateJSON();
      }, 1000);

  });
  $("#otherFieldsPage #endClimbTimer").click(function(){
    if($(this).hasClass("disabled")){return;}
    $(this).addClass("disabled");
    $("#otherFieldsPage #startClimbTimer").removeClass("disabled");
    $("#otherFieldsPage #clearClimbTimer").removeClass("disabled");
    clearInterval(climbTimer);
      generateJSON()
  });

  $("#otherFieldsPage #clearClimbTimer").click(function() {
    if($(this).hasClass("disabled")){return;}
    $(this).addClass("disabled");
    $("#climbTimer").text( "0:00 mins");
    sec = 0;
    generateJSON();
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
      data: {data: localStorage.getItem("matchData")},
      async: false,
      success: function (data) {
        if(data == "Success"){
          toastr["success"]("The match has been updated successfully", "Success!")
          localStorage.removeItem('matchData');
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


    //show otherFieldsPage for rateYo creation
  $("#gearPage,#feedPage,#shootPage,#otherFieldsPage").css("flex", "0").css("width", "0").css("height", "0").css("overflow", "hidden");
  $("#otherFieldsPage").css("flex", "0 1 70%").css("width", "").css("height", "").css("overflow", "");

  var ids = ["ballGroundFeedingRating","ballLaneFeedingRating","ballShootingSpeedRating","gearGroundFeedingRating","gearLaneFeedingRating","gearPlacingSpeedRating","defenseRating","defenseEscapeRating"];

  ids.forEach( function(id) {

    $("#" + id).rateYo({
      numStars: 5,
      fullStar: true,
      starWidth: $("#" + id).width() / 10 + "px",
      maxValue : 5,
      rating: 0
    });

    $("#" + id).on("rateyo.set",function(e,data){
      generateJSON();
    });

  } );

  //rateYo creation done. Hide otherFieldsPage again.
  $("#gearPage,#feedPage,#shootPage,#otherFieldsPage").css("flex", "0").css("width", "0").css("height", "0").css("overflow", "hidden");
  $("#gearPage").css("flex", "0 1 70%").css("width", "").css("height", "").css("overflow", "");


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
  if ( feedSVG_IDS.indexOf(id) != -1) {
    var placeID = id.substring(0,id.length-6);

    var place = feedSVGDoc.getElementById(placeID);
    $(place).css("opacity", 1);
  }

}
function feedSVGDocMouseOut(e) {
  var id = e.target.getAttribute("id");

  if ( feedSVG_IDS.indexOf(id) != -1 && (FEED_BALL_LOCATION + "Holder") !== id) {
    var placeID = id.substring(0,id.length-6);
    var place = feedSVGDoc.getElementById(placeID);
    $(place).css("opacity", 0);
  }

}
function feedSVGDocClick(e) {
  var id = e.target.getAttribute("id");
  if ( feedSVG_IDS.indexOf(id) != -1) {
    var placeID = id.substring(0,id.length-6);

    for(i in feedSVG_IDS){
      var tmpID = feedSVG_IDS[i].substring(0,feedSVG_IDS[i].length-6);
      var tmpPlace = $(feedSVGDoc.getElementById(tmpID))
      tmpPlace.css("opacity", 0)
    }
    var place = feedSVGDoc.getElementById(placeID);
    $(place).css("opacity", 1);
    FEED_BALL_LOCATION = placeID;
    console.log("FEED_BALL_LOCATION: " + FEED_BALL_LOCATION);
//    checkAndAddGearHistoryItem($("#modeHeading").attr("data-mode"))
  }
}
function checkAndAddFeedHistoryItem(mode, ballOrGear, percentOrCount) {

  if (FEED_BALL_LOCATION != null || (FEED_BALL_LOCATION == null && FEED_TYPE == "gear")) {
    if (ballOrGear == "ball") {
      checkAndAddBallFeedHistoryItem(mode,percentOrCount)
    }
    else if(ballOrGear == "gear"){
      checkAndAddGearFeedHistoryItem(mode)
    }
  }
  else{
    toastr['error']("Must have a location selected");
  }
}
function checkAndAddBallFeedHistoryItem(mode,percentOrCount){
  if(FEED_BALL_LOCATION != null){
    if(percentOrCount == "percent"){
      if(FEED_BALL_PERCENT_START != null && FEED_BALL_PERCENT_END != null){
        $("#historyList").prepend(
          "<div " +
          "data-location='"+FEED_BALL_LOCATION+"' " +
          "data-actionType='feedBall' " +
          "data-mode="+mode+" " +
          "data-inputMethod=percent " +
          "data-before="+FEED_BALL_PERCENT_START+" " +
          "data-after="+FEED_BALL_PERCENT_END+" " +
          "class='historyItem' " +
          "style=' display: flex'> " +
          "<img class='deleteHistoryItem' src='/util/img/redX.gif' style=''/>" +
          "<h3 style='flex: 1 1 80%;text-align: center;line-height: 21px'>" +
          "<b>" + (FEED_BALL_LOCATION).capitalize() + " Feed </b>" + FEED_BALL_PERCENT_START+ "% &rarr; " + FEED_BALL_PERCENT_END + "%" +
          "</h3>" +
          "<img class='moveHistoryItem' src='/util/img/upDownImage.png' style=''/>" +
          "</div>");
        clearFeed();
        generateJSON();
        checkAutoCount();
      }
    }
    else if(percentOrCount == "count"){
      if(FEED_BALL_COUNT != null){

        $("#historyList").prepend(
          "<div " +
          "data-location='"+FEED_BALL_LOCATION+"' " +
          "data-actionType='feedBall' " +
          "data-mode="+mode+" " +
          "data-inputMethod=count " +
          "data-count="+FEED_BALL_COUNT+" " +
          "class='historyItem' " +
          "style=' display: flex'> " +
          "<img class='deleteHistoryItem' src='/util/img/redX.gif' />" +
          "<h3 style='flex: 1 1 80%;text-align: center;line-height: 21px'>" +
          "<b>" + (FEED_BALL_LOCATION).capitalize() + " Feed </b>" + FEED_BALL_COUNT + " balls" +
          "</h3>" +
          "<img class='moveHistoryItem' src='/util/img/upDownImage.png' />" +
          "</div>");
        clearFeed();
        generateJSON();
        checkAutoCount();
      }
    }
  }

}
function checkAndAddGearFeedHistoryItem(mode){
  if(FEED_GEAR_METHOD != null && FEED_GEAR_RESULT!= null){
    var result,bg;
    if(FEED_GEAR_RESULT == 1){
      result = "Scored";
      bg = "#A1FFA1"
    }
    else{
      result = "Missed";
      bg = "#FFA1A1"
    }

//    var loc = GEAR_LOCATION.substring(0,GEAR_LOCATION.length-4);

    $("#historyList").prepend(
         "<div " +
           "data-actionType='feedGear' " +
           "data-mode="+mode+" " +
           "data-method="+FEED_GEAR_METHOD+" " +
           "data-result="+FEED_GEAR_RESULT+" " +
           "class='historyItem' " +
           "style='background-color: "+bg+";display: flex'> " +
              "<img class='deleteHistoryItem' src='/util/img/redX.gif' />" +
              "<h3 style='flex: 1 1 80%;text-align: center;line-height: 21px'>" +
                  "<b> Feed Gear ( "+FEED_GEAR_METHOD+" )</b><br />" + (FEED_GEAR_RESULT ? "Scored" : "Missed") +
              "</h3>" +
              "<img class='moveHistoryItem' src='/util/img/upDownImage.png' />" +
          "</div>");

    clearFeed();
    generateJSON();
    checkAutoCount();
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
              "<img class='deleteHistoryItem' src='/util/img/redX.gif' />" +
              "<h3 style='flex: 1 1 80%;text-align: center;line-height: 21px'>" +
                  "<b>" + (loc).capitalize() + " Gear</b> - " + result +
              "</h3>" +
              "<img class='moveHistoryItem' src='/util/img/upDownImage.png' />" +
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
//  var minHeight = 0;
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
      actualClickYInches = clickYInches;
    }
    if (shootSVGDoc.getElementById("shootPosition")) {
      d3.select(shootSVGDoc.getElementById("shootPosition"))
        .attr("cx", (clickX / layer1.width) * 696)
        .attr("cy", ((maxHeight-(clickY+minHeight)) / layer1.height) * 508)
    }
    else {

      d3.select(shootSVGDoc.rootElement).append("svg:circle")
        .attr("cx", (clickX / layer1.width) * 696)
        .attr("cy", ((maxHeight-(clickY+minHeight)) / layer1.height) * 508)
        .attr("r", 10)
        .attr("id", "shootPosition")
        .attr("style", "cursor:crosshair;fill: #ff6600; fill-opacity: 1; fill-rule: nonzero; stroke: #000000; stroke-width: 6.58412218; stroke-linecap: round; stroke-linejoin: bevel; stroke-miterlimit: 4; stroke-opacity: 1; stroke-dasharray: none; stroke-dashoffset: 0; stroke-width: 3px;");

    }


    SHOOT_POS_X = actualClickXInches;
    SHOOT_POS_Y = actualClickYInches;
//    checkAndAddShootHistoryItem($("#modeHeading").attr("data-mode"), SHOOT_INPUT_METHOD);
//    console.log("(" + (clickX / layer1.width) * 498.90457 + "," + (clickY / layer1.height) * 489.37781 + ")");

  }
}
function checkAndAddShootHistoryItem(mode,percentOrCount){

  if(SHOOT_LEVEL == null){
    toastr["error"]("Must select high/low goal");
    return;
  }
  if(SHOOT_POS_X == null || SHOOT_POS_Y == null){
    toastr["error"]("Must have a location selected");
    return;
  }

    var result,level,bg="white";

    if(SHOOT_LEVEL == 1){
      level = "High";
    }
    else{
      level = "Low";
    }
    if(percentOrCount == "percent"){
      $("#historyList").prepend("<div" +
      " data-coordX='"+SHOOT_POS_X+"'" +
      " data-coordY='"+SHOOT_POS_Y+"'" +
      " data-level='"+SHOOT_LEVEL+"'" +
      " data-before='"+SHOOT_AMT_PERCENT_START+"' " +
      " data-after='"+SHOOT_AMT_PERCENT_END+"' " +
      " data-accuracy='"+SHOOT_ACCURACY_PERCENT+"' " +
      " data-inputMethod='percent' " +
      "data-actionType=\"shoot\" data-mode="+mode+" class=\"historyItem\" style='background: "+bg+"; display: flex'> " +
      "<img class='deleteHistoryItem' src='/util/img/redX.gif' />" +
      "<h3 style='flex: 1 1 80%;text-align: center;line-height: 21px'><b>Shot "+level+" Goal</b><br/>"+SHOOT_AMT_PERCENT_START+"% &rarr;"+SHOOT_AMT_PERCENT_END+"% , "+SHOOT_ACCURACY_PERCENT+"% accuracy</h3>" +
      "<img class='moveHistoryItem' src='/util/img/upDownImage.png' />" +
      "</div>");
    }
    else{
      $("#historyList").prepend("<div" +
      " data-coordX='"+SHOOT_POS_X+"'" +
      " data-coordY='"+SHOOT_POS_Y+"'" +
      " data-level='"+SHOOT_LEVEL+"'" +
      " data-scored='"+SHOOT_AMT_COUNT_SCORED+"' " +
      " data-missed='"+SHOOT_AMT_COUNT_MISSED+"' " +
      " data-leftover='"+SHOOT_AMT_COUNT_LEFTOVER+"' " +
      " data-inputMethod='count' " +
      "data-actionType=\"shoot\" data-mode="+mode+" class=\"historyItem\" style='background: "+bg+"; display: flex'> " +
      "<img class='deleteHistoryItem' src='/util/img/redX.gif' />" +
      "<h3 style='flex: 1 1 80%;text-align: center;line-height: 21px'><b>Shot "+level+" Goal</b><br/>"+SHOOT_AMT_COUNT_SCORED +" scored, "+SHOOT_AMT_COUNT_MISSED+" missed, "+SHOOT_AMT_COUNT_LEFTOVER+"% left</h3>" +
      "<img class='moveHistoryItem' src='/util/img/upDownImage.png' />" +
      "</div>");
    }

    clearShoot();
    generateJSON();
    checkAutoCount();
}
function clearShoot(){
  d3.select(shootSVGDoc.getElementById("shootPosition")).transition().duration(400).style("opacity", 0).each("end", function(){
    d3.select(shootSVGDoc.getElementById("shootPosition")).remove();
  });
  $("#shootPage #shootCountAttempted input, " +
    "#shootPage #shootCountAccuracy input, " +
    "#shootPage #shootPercentageBefore input, " +
    "#shootPage #shootPercentageAfter input, " +
    "#shootPage #shootPercentageAccuracy input")
    .val("");
  $("#shootPage #shootCountLeftoverPercentage input").val("0");
  $("#shootPage #shootHighOption, #shootPage #shootLowOption").animate({ backgroundColor: "transparent"}, 'slow').removeAttr("selected");
  SHOOT_POS_X = null;
  SHOOT_POS_Y = null;
  SHOOT_LEVEL = null;
  SHOOT_AMT_PERCENT_START = null;
  SHOOT_AMT_PERCENT_END = null;
  SHOOT_ACCURACY_PERCENT = null;
  SHOOT_AMT_COUNT_SCORED = null;
  SHOOT_AMT_COUNT_MISSED = null;
  SHOOT_AMT_COUNT_LEFTOVER = 0;
  SHOOT_INPUT_METHOD = null;

}
function generateJSON(){


  var records = [];
  var autoElements = $("#historyList .historyItem[data-mode='auto']");
  var teleElements = $("#historyList .historyItem[data-mode='tele']");
  var counter = autoElements.length;
  autoElements.each(function(){

    records.push(getRecord(this,"auto",counter));

    counter--;
  });
  counter = teleElements.length;
  teleElements.each(function(){

    records.push(getRecord(this,"tele",counter));

    counter--;
  });

  var otherFields = {
    autoLineCrossed :        $("#reachedLineInAuto").is(":checked"),
    duration :               $("#climbTimer").text().substr(0,5),
    climbSuccess:            $("#climbSuccess").is(":checked"),
    ballGroundFeedingRating: $("#ballGroundFeedingRating").rateYo("rating"),
    ballLaneFeedingRating:   $("#ballLaneFeedingRating").rateYo("rating"),
    ballShootingSpeedRating: $("#ballShootingSpeedRating").rateYo("rating"),
    gearGroundFeedingRating: $("#gearGroundFeedingRating").rateYo("rating"),
    gearLaneFeedingRating:   $("#gearLaneFeedingRating").rateYo("rating"),
    gearPlacingSpeedRating:  $("#gearPlacingSpeedRating").rateYo("rating"),
    defenseRating:           $("#defenseRating").rateYo("rating"),
    defenseEscapeRating:     $("#defenseEscapeRating").rateYo("rating"),
    notes:                   $("#matchNotes").val()
  };

  var matchData = {
    actions: records,
    otherFields:  otherFields,
    teamMatch: <?=json_encode($SCOUTING_TEAM_MATCH)?>,
    compID: <?=$currCompetition->id?>,
    matchNumber : <?=$match->matchNumber?>,
    currMode : $("#modeHeading").attr("data-mode"),
    currTab : $("#taskSwitcher div.active").index()
  };
  localStorage.setItem("matchData",JSON.stringify(matchData));

  function getRecord(e,mode,orderID){
    var record = {};

    record.mode= mode;

    switch($(e).attr("data-actionType")){
      case "gear":
        record.location = $(e).attr("data-location");
        record.scoreMiss = $(e).attr("data-scoreMiss");
        record.orderID = orderID;
        record.eventType = "gear";
        break;
      case "feedBall":
        record.inputMethod = $(e).attr("data-inputMethod");
        record.before = (record.inputMethod == "percent" ? $(e).attr("data-before") : null);
        record.after = (record.inputMethod == "percent" ? $(e).attr("data-after") : null);
        record.count = (record.inputMethod == "count" ? $(e).attr("data-count") : null);
        record.location = $(e).attr("data-location");
        record.orderID = orderID;
        record.eventType = "feedBall";
        break;
      case "feedGear":
        record.result = $(e).attr("data-result");
        record.method = $(e).attr("data-method");
        record.orderID = orderID;
        record.eventType = "feedGear";
        break;
      case "shoot":
        record.inputMethod = $(e).attr("data-inputMethod");
        record.coordX = $(e).attr("data-coordX");
        record.coordY = $(e).attr("data-coordY");
        record.level = $(e).attr("data-level");
        record.before = (record.inputMethod == "percent" ? $(e).attr("data-before") : null)
        record.after = (record.inputMethod == "percent" ? $(e).attr("data-after") : null)
        record.accuracy = (record.inputMethod == "percent" ? $(e).attr("data-accuracy") : null)
        record.scored = (record.inputMethod == "count" ? $(e).attr("data-scored") : null);
        record.missed = (record.inputMethod == "count" ? $(e).attr("data-missed") : null);
        record.leftover = (record.inputMethod == "count" ? $(e).attr("data-leftover") : null);
        record.orderID = orderID;
        record.eventType = "shoot";
        break;
      default:
        break;
    }

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
function clearFeed(){
  $("#feedPage #feedBall").fadeOut("slow");
  $("#feedPage #feedGear").fadeOut("slow");
  $("#feedPage #feedSVG").css("visibility", "hidden");
  $("#feedPage #feedGearSuccess, #feedPage #feedGearFailure").animate({ backgroundColor: "transparent"}, 'slow').removeAttr("selected");
  $("#feedPage #feedGearDropped, #feedPage #feedGearGround").animate({ backgroundColor: "transparent"}, 'slow').removeAttr("selected");
  $("#feedPage #feedBallOption, #feedPage #feedGearOption").animate({ backgroundColor: "transparent"}, 'slow').removeAttr("selected");


  d3.select(feedSVGDoc.getElementById(FEED_BALL_LOCATION)).transition().duration(400).style("opacity", 0).each("end", function(){
    d3.select(shootSVGDoc.getElementById(FEED_BALL_LOCATION)).remove();
  });
  $("#feedPage #feedBallPercentageBefore input, " +
  "#feedPage #feedBallPercentageAfter input, " +
  "#feedPage #feedBallAmount input ")
    .val("");

  FEED_BALL_LOCATION = null;
  FEED_BALL_PERCENT_START = null;
  FEED_BALL_PERCENT_END = null;
  FEED_BALL_COUNT = null;
  FEED_GEAR_RESULT = null;
  FEED_GEAR_METHOD = null;
}
document.onkeyup = handleKeypress;
function handleKeypress(e){
  var char = String.fromCharCode(e.keyCode).toUpperCase();
  if (e.ctrlKey && char == "M"){
    $("#modeHeading").trigger("click");
  }
  if(e.target.tagName == "INPUT" || e.target.tagName == "TEXTAREA"){
      return;
    }
    if(e.code.indexOf("Digit") > -1){
      handleTabSwitchKeyPress(e);
    }
    else  if (e.keyCode == 27) { // escape key maps to keycode `27`
      handleEscapeKeyPress(e);
    }
    else if (e.keyCode == 90 && e.ctrlKey){ //Ctrl-Z!
      $("#historyList div.historyItem").eq(0).find(".deleteHistoryItem").trigger("click")
    }

    else if($("#feedPage").css("flex-basis") != "0%"){
      handleFeedPageKeyPress(e);
    }
    else if($("#gearPage").css("flex-basis") != "0%"){
      handleGearPageKeyPress(e);
    }
    else if($("#shootPage").css("flex-basis") != "0%"){
      handleShootPageKeyPress(e);
    }
}
function handleTabSwitchKeyPress(e){
  var index = parseInt(e.code.substring(e.code.length -1)) -1;
  $("#taskSwitcher > div").eq(index).trigger("click");
}
function handleEscapeKeyPress(){
  if($("#gearPage").is(":visible")){
    $("#cancelGear").trigger("click");
  }
  else if($("#feedPage").is(":visible")){
    $("#cancelFeed").trigger("click");
  }
  else if($("#shootPage").is(":visible")){
    $("#cancelShoot").trigger("click");
  }
}
function handleFeedPageKeyPress(e){
  var char = String.fromCharCode(e.keyCode).toUpperCase();
  if(char == "B"){
    $("#feedPage #feedBallOption").trigger("click");
  }
  else if(char == "G"){
    $("#feedPage #feedGearOption").trigger("click");
  }
  if($("#feedPage #feedGear").is(":visible")){
    if(char == "S"){
      $("#feedPage #feedGearSuccess").trigger("click");
    }
    else if(char == "F"){
      $("#feedPage #feedGearFailure").trigger("click");
    }
    else if(char == "D"){
      $("#feedPage #feedGearDropped").trigger("click");
    }
    else if(char == "R"){
      $("#feedPage #feedGearGround").trigger("click");
    }
  }
  else if($("#feedPage #feedBall").is(":visible")){
    if(char == "P" && e.altKey){
     $("#feedPage #feedBallSwitchToPercentage").trigger("click")
    }
    else if(char == "C" && e.altKey){
      $("#feedPage #feedBallSwitchToCount").trigger("click")

    }
  }
}
function handleGearPageKeyPress(e){
  var char = String.fromCharCode(e.keyCode).toUpperCase();
  if(char == "S"){
    $("#gearPage #gearScore").trigger("click");
  }
  else if(char == "M"){
    $("#gearPage #gearMiss").trigger("click");
  }
  else if(char == "Q"){
    if(SCOUTING_TEAM_COLOR == "red"){
      var elem = (gearSVGDoc.getElementById("leftGearHolder"));
    }
    else{
      var elem = (gearSVGDoc.getElementById("rightGearHolder"));
    }
    var ev = {target: elem};
    gearSVGDocClick(ev);
  }
  else if(char == "A"){
    var elem = (gearSVGDoc.getElementById("centerGearHolder"));
    var ev = {target: elem};
    gearSVGDocClick(ev);  }
  else if(char == "Z"){
    if(SCOUTING_TEAM_COLOR == "red"){
      var elem = (gearSVGDoc.getElementById("rightGearHolder"));
    }
    else{
      var elem = (gearSVGDoc.getElementById("leftGearHolder"));
    }
    var ev = {target: elem};
    gearSVGDocClick(ev);
  }
}
function handleShootPageKeyPress(e){
  var char = String.fromCharCode(e.keyCode).toUpperCase();
  if(char == "H"){
    $("#shootPage #shootHighOption").trigger("click");
  }
  else if(char == "L"){
    $("#shootPage #shootLowOption").trigger("click");
  }
}
function focusOnAvailableInput(){
  var currentDiv = $("#taskSwitcher div.active").attr("div-id")
  var el = $("#" + currentDiv + " input[autofocus]:visible").get(0);
  if(el == undefined){
    document.body.focus();
  }
  else{
    el.focus();
  }

}
</script>
</html>