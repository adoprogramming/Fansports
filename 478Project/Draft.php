<?php
ob_start();
/*
  File Name: Draft.php

  Version 1.0
  CSC 478 Group Project
  Group#8: FanSports
  Wesley Elliot, Jeremy Jones, Ann Oesterle
  Last Updated: 12/7/2016
 */
define('TITLE', 'Draft Page'); //create the title of the page that will show in browser
define('CSS', 'Draft'); //create the link to the stylesheet
include('templates/Header.php');  // Include the header.
//
//-----Begin Changeable Content-----
//
//include all necessary files
include_once "NFLDBConnection.php";
include_once "UserDBConnection.php";
require_once "RosterHandler.php";
require_once "UserDBHandler.php";
//connect to nfldb on server
try {
  $rosterPDO = new PDO("mysql:host=localhost; dbname=userdb; charset=utf8mb4", $usrDBName, $usrDBPass); //connect to userdb
} catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}
$rosterHandler = new rosterHandler($rosterPDO, $pgsqlpdo); //create an instance of the roster handler DB handler class
$draftHandler = new userHandler($pdo); //create an instance of the user DB handler class

$currentUserID = $_SESSION['userName']; //get the current user name for this session
$currentLeagueName = $_SESSION['leagueName']; //get the current league name
$roundCounter = 1;
$ownerNum = $_SESSION['ownerNum']; //figure out if the user is owner1 or owner2
// Decide if it is this player's turn
$statusResults = $draftHandler->currentleagueStatus($currentLeagueName); //return the current league status as array
foreach ($statusResults as $rowStatus) {//even though there is only one result it is returned as an array, so you have to parse the array
  $leagueStatus = $rowStatus['leagueStatus']; //return the current league status as single variable
}//END foreach $statusResults
//go through each possible league Status

if ($leagueStatus < 1) {//Still waiting for second player to join
} else if ($leagueStatus > 16) {//Draft is over
  $myTurn = 3;
} else if ($leagueStatus & 1) {// League Status is on an ODD number between 1 and 16 inclusive
  //team1 goes on odd
  if ($ownerNum == "1") {//user is owner1
    $myTurn = 1; //it IS the user's turn
  } else if ($ownerNum == "2") {//user is owner2
    //team2 goes on even
    $myTurn = 0; //it is NOT the user's turn
  }//END if - owner num is 1 or 2
} else {// League Status is on an EVEN number between 1 and 16 inclusive
  //team 1 goes on odd
  if ($ownerNum == "1") {//user is owner1
    $myTurn = 0; //it is NOT the user's turn
  } else if ($ownerNum == "2") {//user is owner2
    //team2 goes on even
    $myTurn = 1; //it IS the user's turn
  }//END if - owner num is 1 or 2
  $roundCounter = $roundCounter + 1;
}//END if - draft hasn't started if

/* TOP of page, contains information on draft round and whose turn it is */

// SET team1Name, team2Name, and printStatus
$team1Name = $draftHandler->getTeam1Name($currentLeagueName); //return team1's name as an array
foreach ($team1Name as $row) {//even though there is only one result it is returned as an array, so you have to parse the array
  $team1Name = $row['team1']; //return  team1's name as single variable
}//END foreach $team1Name
$team2Name = $draftHandler->getTeam2Name($currentLeagueName);
foreach ($team2Name as $row) {//even though there is only one result it is returned as an array, so you have to parse the array
  $team2Name = $row['team2']; //return team2's name as single variable
}//END foreach $team2Name
//Print the top of page, with information on league name, draft round, and whose turn it is
print '
  <div class ="draftInfoTop">
    <h1 class ="draftLeagueName">League: ' . $currentLeagueName . '</h1>
    <h1 class ="draftTitle">The Draft</h1>';

    if ($myTurn == 1) {//if it IS the user's turn
      //do nothing
    } else if ($myTurn == 0) {//if it is NOT the user's turn
      print'<h1 class ="turnInfo" id = "turnInfo">Not Your Turn!</h1>';
    }//END if - is it the user's turn?
    print '</div><div class = "selectAPlayer">';

/* IT IS MY TURN */
if ($myTurn == 1) {//it is my turn
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {//form has been submitted
    $playerSelected = $_POST['playerSelection']; //get the value of playerSelection from the dropdown menu submission. Value =  the player's name
    $playerPosRoster = $_POST['playerPosition']; //get the value of playerPosition from the dropdown menu submission. Value = the player's positon.
    if ($ownerNum == "1") {//if owner is owner 1, we are adding the players to team #1
      if ($playerPosRoster == "QB") {//if the player's position that was selected is QB
        $draftHandler->addQBTeam1($playerSelected, $currentLeagueName); //add playerName (playerSelected) into QB slot of team1
      } else if ($playerPosRoster == "RB1") {//if the player's position that was selected is RB1
        $draftHandler->addRB1Team1($playerSelected, $currentLeagueName); //add playerName (playerSelected) into RB1 slot of team1
      } else if ($playerPosRoster == "RB2") {//if the player's position that was selected is RB2, note...fill RB1 first
        $draftHandler->addRB2Team1($playerSelected, $currentLeagueName); //add playerName (playerSelected) into RB2 slot of team1
      } else if ($playerPosRoster == "WR1") {//if the player's position that was selected is WR1
        $draftHandler->addWR1Team1($playerSelected, $currentLeagueName); //add playerName (playerSelected) into WR1 slot of team1
      } else if ($playerPosRoster == "WR2") {//if the player's position that was selected is WR2, note...fill WR1 first
        $draftHandler->addWR2Team1($playerSelected, $currentLeagueName); //add playerName (playerSelected) into WR2 slot of team1
      } else if ($playerPosRoster == "TE") {//if the player's position that was selected is TE
        $draftHandler->addTETeam1($playerSelected, $currentLeagueName); //add playerName (playerSelected) into TE slot of team1
      } else if ($playerPosRoster == "K") {//if the player's position that was selected is K
        $draftHandler->addKTeam1($playerSelected, $currentLeagueName); //add playerName (playerSelected) into K slot of team1
      } else if ($playerPosRoster == "DEF") {//if the player's position that was selected is DEF
        $draftHandler->addDEFTeam1($playerSelected, $currentLeagueName); //add playerName (playerSelected) into DEF slot of team1
      }//END if - find playerPosRoster
      $myTurn == 0;
    } else if ($ownerNum == "2") {//if owner is owner2, we are adding the players to team #2
      if ($playerPosRoster == "QB") {//if the player's position that was selected is QB
        $draftHandler->addQBTeam2($playerSelected, $currentLeagueName); //add playerName (playerSelected) into QB slot of team2
      } else if ($playerPosRoster == "RB1") {//if the player's position that was selected is RB1
        $draftHandler->addRB1Team2($playerSelected, $currentLeagueName); //add playerName (playerSelected) into RB1 slot of team2
      } else if ($playerPosRoster == "RB2") {//if the player's position that was selected is RB2, note...fill RB1 first
        $draftHandler->addRB2Team2($playerSelected, $currentLeagueName); //add playerName (playerSelected) into RB2 slot of team2
      } else if ($playerPosRoster == "WR1") {//if the player's position that was selected is WR1
        $draftHandler->addWR1Team2($playerSelected, $currentLeagueName); //add playerName (playerSelected) into WR1 slot of team2
      } else if ($playerPosRoster == "WR2") {//if the player's position that was selected is WR2, note...fill WR1 first
        $draftHandler->addWR2Team2($playerSelected, $currentLeagueName); //add playerName (playerSelected) into WR2 slot of team2
      } else if ($playerPosRoster == "TE") {//if the player's position that was selected is TE
        $draftHandler->addTETeam2($playerSelected, $currentLeagueName); //add playerName (playerSelected) into TE slot of team2
      } else if ($playerPosRoster == "K") {//if the player's position that was selected is K
        $draftHandler->addKTeam2($playerSelected, $currentLeagueName); //add playerName (playerSelected) into K slot of team2
      } else if ($playerPosRoster == "DEF") {//if the player's position that was selected is DEF
        $draftHandler->addDEFTeam2($playerSelected, $currentLeagueName); //add playerName (playerSelected) into DEF slot of team2
      }//END if - find playerPosRoster
      $myTurn == 0;
    }//END if - is it owner1 or owner2
    //increment leagueStatus by one to keep track of the draft rounds
    $leagueStatus = $leagueStatus + 1;
    $draftHandler->updateLeagueStatus($currentLeagueName, $leagueStatus); //SET the new leagueStatus in the leagueinfo2 table in the db
  } else { //if form hasn't been printed yet
    //print the position selector drop down menu
    print '
      <h1 class ="turnInfo" id = "turnInfo">Your Turn!</h1>
      <div class = "selectDIV">
        <select class = "posSelectorMenu" name="posSelection" id="posSelectorMenu">
          <option selected = "selected" disabled>Find Player by Position:</option>
          <option value="QB">QB</option>
          <option value="RB">RB</option>
          <option value="WR">WR</option>
          <option value="TE">TE</option>
          <option value="K">K</option>
          <option value="DEF">DEf</option>
          </option>
        </select>
        <button id ="posSelectorButton" class = "posSelectorButton">Choose</button>
      </div>
    ';

    if ($ownerNum == "1") {//user is playing as owner1 of team1
      $dataPosTeam1 = $draftHandler->currentTeam1Info($currentLeagueName); //return the complete roster of team1 as an array
      foreach ($dataPosTeam1 as $rowdata1) {
        $playerPos = "QB";
        if ($rowdata1['QB'] === "0") {//make sure roster QB position is empty
          $availablePlayers = $rosterHandler->getAvailablePlayers($playerPos); //return list of quarterbacks from nfldb as array
          //print the QB drop down menu
          print '
            <form action="Draft.php" method="post" id="draftForm">
              <input type="hidden" name = "playerPosition" class = "playerPosition" id = "playerPosition" value = "QB" />
              <select name="playerSelection" class = "draftMenuQB" id = "draftMenuQB">
                <option selected="selected" disabled>Quarter Backs:</option>';
          //print every available QB listed in nfldb
          foreach ($availablePlayers as $player) {
            print '
                <option value="' . $player['full_name'] . '" id ="playerSelection">' . $player['full_name'] . '</option>';
            //value of playerSelection will equal $player['full_name'] when submit button clicked and value of playerPosition will equal QB
          }//END foreach $availablePlayers
          print '
                </option>
              </select>
              <input type="submit" value="Draft this Player?" class ="buttonQB" id = "buttonQB">
            </form>
          ';
        }//END if - make sure roster QB position is empty
        $playerPos = "RB";
        if ($rowdata1['RB1'] === "0") { //make sure roster RB1 position is empty
          $availablePlayers = $rosterHandler->getAvailablePlayers($playerPos); //return list of running backs from nfldb as array
          //print the RB drop down menu
          print '
            <form action="Draft.php" method="post" id="draftForm">
              <input type="hidden" name = "playerPosition" class = "playerPosition" id = "playerPosition" value = "RB1" />
              <select name="playerSelection" class = "draftMenuRB" id = "draftMenuRB">
                <option selected="selected" disabled>Running Backs:</option>';
          //print every available RB listed in nfldb
          foreach ($availablePlayers as $player) {
            print '
                <option value="' . $player['full_name'] . '" id ="playerSelection">' . $player['full_name'] . '</option>';
            //value of playerSelection will equal $player['full_name'] when submit button clicked and value of playerPosition will equal RB1
          }//END foreach $availablePlayers
          print '
                </option>
              </select>
              <input type="submit" value="Draft this Player?" class ="buttonRB" id = "buttonRB">
            </form>
          ';
        } //END if - make sure roster RB1 position is empty
        $playerPos = "RB";
        if ($rowdata1['RB2'] === "0") { //make sure roster RB2 position is empty
          $availablePlayers = $rosterHandler->getAvailablePlayers($playerPos); //return list of running backs from nfldb as array
          //print the RB drop down menu
          print '
            <form action="Draft.php" method="post" id="draftForm">
              <input type="hidden" name = "playerPosition" class = "playerPosition" id = "playerPosition" value = "RB2" />
              <select name="playerSelection" class = "draftMenuRB" id = "draftMenuRB">
                <option selected="selected" disabled>Running Backs:</option>';
          //print every available RB listed in nfldb
          foreach ($availablePlayers as $player) {
            print '
                <option value="' . $player['full_name'] . '" id ="playerSelection">' . $player['full_name'] . '</option>';
            //value of playerSelection will equal $player['full_name'] when submit button clicked and value of playerPosition will equal RB2
          }//END foreach $availablePlayers
          print '
                </option>
              </select>
              <input type="submit" value="Draft this Player?" class ="buttonRB" id = "buttonRB">
            </form>
          ';
        }//END if - make sure roster RB2 position is empty
        $playerPos = "WR";
        if ($rowdata1['WR1'] === "0") {  //make sure roster WR1 position is empty
          $availablePlayers = $rosterHandler->getAvailablePlayers($playerPos); //return list of wide recievers from nfldb as array
          //print the WR drop down menu
          print '
            <form action="Draft.php" method="post" id="draftForm">
              <input type="hidden" name = "playerPosition" class = "playerPosition" id = "playerPosition" value = "WR1" />
              <select name="playerSelection" class = "draftMenuWR" id = "draftMenuWR">
                <option selected="selected" disabled>Wide Recievers:</option>';
          //print every available WR listed in nfldb
          foreach ($availablePlayers as $player) {
            print '
                <option value="' . $player['full_name'] . '" id ="playerSelection">' . $player['full_name'] . '</option>';
            //value of playerSelection will equal $player['full_name'] when submit button clicked and value of playerPosition will equal WR1
          }//END foreach $availablePlayers
          print '
                </option>
              </select>
              <input type="submit" value="Draft this Player?" class ="buttonWR" id = "buttonWR">
            </form>
          ';
        }//END if - make sure roster WR1 position is empty
        $playerPos = "WR";
        if ($rowdata1['WR2'] === "0") { //make sure roster WR2 position is empty
          $availablePlayers = $rosterHandler->getAvailablePlayers($playerPos); //return list of wide recievers from nfldb as array
          //print the WR drop down menu
          print '
            <form action="Draft.php" method="post" id="draftForm">
              <input type="hidden" name = "playerPosition" class = "playerPosition" id = "playerPosition" value = "WR2" />
              <select name="playerSelection" class = "draftMenuWR" id = "draftMenuWR">
                <option selected="selected" disabled>Wide Recievers:</option>';
          //print every available WR listed in nfldb
          foreach ($availablePlayers as $player) {
            print '
                <option value="' . $player['full_name'] . '" id ="playerSelection">' . $player['full_name'] . '</option>';
            //value of playerSelection will equal $player['full_name'] when submit button clicked and value of playerPosition will equal WR2
          }//END foreach $availablePlayers
          print '
                </option>
              </select>
              <input type="submit" value="Draft this Player?" class ="buttonWR" id = "buttonWR">
            </form>
          ';
        }//END if - make sure roster WR2 position is empty
        $playerPos = "TE";
        if ($rowdata1['TE'] === "0") {  //make sure roster TE position is empty
          $availablePlayers = $rosterHandler->getAvailablePlayers($playerPos); //return list of tight ends from nfldb as array
          //print the TE drop down menu
          print '
            <form action="Draft.php" method="post" id="draftForm">
              <input type="hidden" name = "playerPosition" class = "playerPosition" id = "playerPosition" value = "TE" />
              <select name="playerSelection" class = "draftMenuTE" id = "draftMenuTE">
                <option selected="selected" disabled>Tight Ends:</option>';
          //print every available TE listed in nfldb
          foreach ($availablePlayers as $player) {
            print '
            <option value="' . $player['full_name'] . '" id ="playerSelection">' . $player['full_name'] . '</option>';
            //value of playerSelection will equal $player['full_name'] when submit button clicked and value of playerPosition will equal TE
          }//END foreach $availablePlayers
          print '
                </option>
              </select>
              <input type="submit" value="Draft this Player?" class ="buttonTE" id = "buttonTE">
            </form>
          ';
        }//END if - make sure roster TE position is empty
        $playerPos = "K";
        if ($rowdata1['K'] === "0") {  //make sure roster K position is empty
          $availablePlayers = $rosterHandler->getAvailablePlayers($playerPos); //return list of kickers from nfldb as array
          //print the K drop down menu
          print '
            <form action="Draft.php" method="post" id="draftForm">
              <input type="hidden" name = "playerPosition" class = "playerPosition" id = "playerPosition" value = "K" />
              <select name="playerSelection" class = "draftMenuK" id = "draftMenuK">
                <option selected="selected" disabled>Kickers:</option>';
          //print every available K listed in nfldb
          foreach ($availablePlayers as $player) {
            print '
                <option value="' . $player['full_name'] . '" id ="playerSelection">' . $player['full_name'] . '</option>';
            //value of playerSelection will equal $player['full_name'] when submit button clicked and value of playerPosition will equal K
          }//END foreach $availablePlayers
          print '
                </option>
              </select>
              <input type="submit" value="Draft this Player?" class ="buttonK" id = "buttonK">
            </form>
          ';
        }//END if - make sure roster K position is empty
        $playerPos = "DEF";
        if ($rowdata1['DEF'] === "0") {  //make sure roster DEF position is empty
          $drafted = "0"; //DEF teams are listed as drafted = 0 until they are drafted by one team or the other
          $availablePlayers = $draftHandler->getAvailablePlayersTEMPDEF($drafted); //return list of Defensive teams from the teamDEF table of the db  as array
          //print the DEF drop down menu
          print '
            <form action="Draft.php" method="post" id="draftForm">
              <input type="hidden" name = "playerPosition" class = "playerPosition" id = "playerPosition" value = "DEF" />
              <select name="playerSelection" class = "draftMenuDEF" id = "draftMenuDEF">
                <option selected="selected" disabled>Defensive Teams:</option>';
          //print every available DEF team listed in the teamDEF table of the db
          foreach ($availablePlayers as $player) {
            print '
                <option value="' . $player['defName'] . '" id ="playerSelection">' . $player['defName'] . '</option>';
            //value of playerSelection will equal $player['full_name'] when submit button clicked and value of playerPosition will equal DEF
          }//END foreach $availablePlayers
          print '
                </option>
              </select>
              <input type="submit" value="Draft this Player?" class ="buttonDEF" id = "buttonDEF">
            </form>
          ';
        }//END if - make sure roster DEF position is empty
        //END selectAPlayer div
        print '
        </div>
    ';
      }//END foreach $dataPosTeam1 as $rowdata1
    } else if ($ownerNum == "2") {//playing as owner2 of team2
      $dataPosTeam2 = $draftHandler->currentTeam2Info($currentLeagueName); //return the complete roster of team2 as an array
      foreach ($dataPosTeam2 as $rowdata2) {
        $playerPos = "QB";
        if ($rowdata2['QB'] === "0") {//make sure roster QB position is empty
          $availablePlayers = $rosterHandler->getAvailablePlayers($playerPos); //return list of quarterbacks from nfldb as array
          //print the QB drop down menu
          print '
            <form action="Draft.php" method="post" id="draftForm">
              <input type="hidden" name = "playerPosition" class = "playerPosition" id = "playerPosition" value = "QB" />
              <select name="playerSelection" class = "draftMenuQB" id = "draftMenuQB">
                <option selected="selected" disabled>Quarter Backs:</option>';
          //print every available QB listed in nfldb
          foreach ($availablePlayers as $player) {
            print '
                <option value="' . $player['full_name'] . '" id ="playerSelection">' . $player['full_name'] . '</option>';
            //value of playerSelection will equal $player['full_name'] when submit button clicked and value of playerPosition will equal QB
          }//END foreach $availablePlayers
          print '
                </option>
              </select>
              <input type="submit" value="Draft this Player?" class ="buttonQB" id = "buttonQB">
            </form>
          ';
        }//END if - make sure roster QB position is empty
        $playerPos = "RB";
        if ($rowdata2['RB1'] === "0") { //make sure roster RB1 position is empty
          $availablePlayers = $rosterHandler->getAvailablePlayers($playerPos); //return list of running backs from nfldb as array
          //print the RB drop down menu
          print '
            <form action="Draft.php" method="post" id="draftForm">
              <input type="hidden" name = "playerPosition" class = "playerPosition" id = "playerPosition" value = "RB1" />
              <select name="playerSelection" class = "draftMenuRB" id = "draftMenuRB">
                <option selected="selected" disabled>Running Backs:</option>';
          //print every available RB listed in nfldb
          foreach ($availablePlayers as $player) {
            print '
                <option value="' . $player['full_name'] . '" id ="playerSelection">' . $player['full_name'] . '</option>';
            //value of playerSelection will equal $player['full_name'] when submit button clicked and value of playerPosition will equal RB1
          }//END foreach $availablePlayers
          print '
                </option>
              </select>
              <input type="submit" value="Draft this Player?" class ="buttonRB" id = "buttonRB">
            </form>
          ';
        } //END if - make sure roster RB1 position is empty
        $playerPos = "RB";
        if ($rowdata2['RB2'] === "0") { //make sure roster RB2 position is empty
          $availablePlayers = $rosterHandler->getAvailablePlayers($playerPos); //return list of running backs from nfldb as array
          //print the RB drop down menu
          print '
            <form action="Draft.php" method="post" id="draftForm">
              <input type="hidden" name = "playerPosition" class = "playerPosition" id = "playerPosition" value = "RB2" />
              <select name="playerSelection" class = "draftMenuRB" id = "draftMenuRB">
                <option selected="selected" disabled>Running Backs:</option>';
          //print every available RB listed in nfldb
          foreach ($availablePlayers as $player) {
            print '
                <option value="' . $player['full_name'] . '" id ="playerSelection">' . $player['full_name'] . '</option>';
            //value of playerSelection will equal $player['full_name'] when submit button clicked and value of playerPosition will equal RB2
          }//END foreach $availablePlayers
          print '
                </option>
              </select>
              <input type="submit" value="Draft this Player?" class ="buttonRB" id = "buttonRB">
            </form>
          ';
        }//END if - make sure roster RB2 position is empty
        $playerPos = "WR";
        if ($rowdata2['WR1'] === "0") {  //make sure roster WR1 position is empty
          $availablePlayers = $rosterHandler->getAvailablePlayers($playerPos); //return list of wide recievers from nfldb as array
          //print the WR drop down menu
          print '
            <form action="Draft.php" method="post" id="draftForm">
              <input type="hidden" name = "playerPosition" class = "playerPosition" id = "playerPosition" value = "WR1" />
              <select name="playerSelection" class = "draftMenuWR" id = "draftMenuWR">
                <option selected="selected" disabled>Wide Recievers:</option>';
          //print every available WR listed in nfldb
          foreach ($availablePlayers as $player) {
            print '
                <option value="' . $player['full_name'] . '" id ="playerSelection">' . $player['full_name'] . '</option>';
            //value of playerSelection will equal $player['full_name'] when submit button clicked and value of playerPosition will equal WR1
          }//END foreach $availablePlayers
          print '
                </option>
              </select>
              <input type="submit" value="Draft this Player?" class ="buttonWR" id = "buttonWR">
            </form>
          ';
        }//END if - make sure roster WR1 position is empty
        $playerPos = "WR";
        if ($rowdata2['WR2'] === "0") { //make sure roster WR2 position is empty
          $availablePlayers = $rosterHandler->getAvailablePlayers($playerPos); //return list of wide recievers from nfldb as array
          //print the WR drop down menu
          print '
            <form action="Draft.php" method="post" id="draftForm">
              <input type="hidden" name = "playerPosition" class = "playerPosition" id = "playerPosition" value = "WR2" />
              <select name="playerSelection" class = "draftMenuWR" id = "draftMenuWR">
                <option selected="selected" disabled>Wide Recievers:</option>';
          //print every available WR listed in nfldb
          foreach ($availablePlayers as $player) {
            print '
                <option value="' . $player['full_name'] . '" id ="playerSelection">' . $player['full_name'] . '</option>';
            //value of playerSelection will equal $player['full_name'] when submit button clicked and value of playerPosition will equal WR2
          }//END foreach $availablePlayers
          print '
                </option>
              </select>
              <input type="submit" value="Draft this Player?" class ="buttonWR" id = "buttonWR">
            </form>
          ';
        }//END if - make sure roster WR2 position is empty
        $playerPos = "TE";
        if ($rowdata2['TE'] === "0") {  //make sure roster TE position is empty
          $availablePlayers = $rosterHandler->getAvailablePlayers($playerPos); //return list of tight ends from nfldb as array
          //print the TE drop down menu
          print '
            <form action="Draft.php" method="post" id="draftForm">
              <input type="hidden" name = "playerPosition" class = "playerPosition" id = "playerPosition" value = "TE" />
              <select name="playerSelection" class = "draftMenuTE" id = "draftMenuTE">
                <option selected="selected" disabled>Tight Ends:</option>';
          //print every available TE listed in nfldb
          foreach ($availablePlayers as $player) {
            print '
            <option value="' . $player['full_name'] . '" id ="playerSelection">' . $player['full_name'] . '</option>';
            //value of playerSelection will equal $player['full_name'] when submit button clicked and value of playerPosition will equal TE
          }//END foreach $availablePlayers
          print '
                </option>
              </select>
              <input type="submit" value="Draft this Player?" class ="buttonTE" id = "buttonTE">
            </form>
          ';
        }//END if - make sure roster TE position is empty
        $playerPos = "K";
        if ($rowdata2['K'] === "0") {  //make sure roster K position is empty
          $availablePlayers = $rosterHandler->getAvailablePlayers($playerPos); //return list of kickers from nfldb as array
          //print the K drop down menu
          print '
            <form action="Draft.php" method="post" id="draftForm">
              <input type="hidden" name = "playerPosition" class = "playerPosition" id = "playerPosition" value = "K" />
              <select name="playerSelection" class = "draftMenuK" id = "draftMenuK">
                <option selected="selected" disabled>Kickers:</option>';
          //print every available K listed in nfldb
          foreach ($availablePlayers as $player) {
            print '
                <option value="' . $player['full_name'] . '" id ="playerSelection">' . $player['full_name'] . '</option>';
            //value of playerSelection will equal $player['full_name'] when submit button clicked and value of playerPosition will equal K
          }//END foreach $availablePlayers
          print '
                </option>
              </select>
              <input type="submit" value="Draft this Player?" class ="buttonK" id = "buttonK">
            </form>
          ';
        }//END if - make sure roster K position is empty
        $playerPos = "DEF";
        if ($rowdata2['DEF'] === "0") {  //make sure roster DEF position is empty
          $drafted = "0"; //DEF teams are listed as drafted = 0 until they are drafted by one team or the other
          $availablePlayers = $draftHandler->getAvailablePlayersTEMPDEF($drafted); //return list of Defensive teams from the teamDEF table of the db  as array
          //print the DEF drop down menu
          print '
            <form action="Draft.php" method="post" id="draftForm">
              <input type="hidden" name = "playerPosition" class = "playerPosition" id = "playerPosition" value = "DEF" />
              <select name="playerSelection" class = "draftMenuDEF" id = "draftMenuDEF">
                <option selected="selected" disabled>Defensive Teams:</option>';
          //print every available DEF team listed in the teamDEF table of the db
          foreach ($availablePlayers as $player) {
            print '
                <option value="' . $player['defName'] . '" id ="playerSelection">' . $player['defName'] . '</option>';
            //value of playerSelection will equal $player['full_name'] when submit button clicked and value of playerPosition will equal DEF
          }//END foreach $availablePlayers
          print '
                </option>
              </select>
              <input type="submit" value="Draft this Player?" class ="buttonDEF" id = "buttonDEF">
            </form>
          ';
        }//END if - make sure roster DEF position is empty
      }//END foreach $dataPosTeam2 as $rowdata2
    }//END if playing as owner2 on team2
    print '</div>';
  }//END if form hasn't been printed yet
} else if ($myTurn == "0") {//it is NOT my turn
  //do nothing
} else if ($myTurn == "3") {//draft is over
  //do nothing
}//END if - is it my turn
?>
<!--DIV containing both teams-->
<div class ="draftTeams"><!--DIV containing both teams-->
  <div class ="draftTeam1"><!--DIV containing team1, blue background-->
    <div class ="draftTeam1Top">
      <?php
      if ($ownerNum == "1") {//if user is owner1 of team1
        print '<h1 align = "left">My Team</h1>'; //team on left is team1, user's team
      } else {//if user is owner2 of team2
        print '<h1 align = "left">My Opponent</h1>'; //team on left is team1, user's opponent
      }//END if ownerNum = 1 or 2
      ?>
    </div> <!-- END draftTeamsTop div-->
    <!--Print this team's name-->
    <h1 align = "center" class = "teamTitle" ><?php print_r($team1Name) ?></h1>
    <table class ="draftTeam1">
      <tr class = "header">
        <th>Roster</th>
        <th>Player Name</th>
        <th>Position</th>
      </tr>
      <tr class = "padding">
        <td class = "rosterName"></td>
        <td class = "name"></td>
        <td class = "pos"></td>
      </tr>
      <?php
      $teamData1 = $draftHandler->currentTeam1Info($currentLeagueName); //return the complete roster of team1 in this league as an array
      foreach ($teamData1 as $rowData1) {//go through the array and pull each individual player from each position
        if ($rowData1['QB'] !== "0") {//check to make sure this position is not empty
          print '<tr><td class = "rosterName">QB</td><td class = "name">' . $rowData1['QB'] . '</td><td class = "pos">&nbsp;</td></tr>';
        }
        if ($rowData1['RB1'] !== "0") {//check to make sure this position is not empty
          print '<tr><td class = "rosterName">RB1</td><td class = "name">' . $rowData1['RB1'] . '</td><td class = "pos">&nbsp;</td></tr>';
        }
        if ($rowData1['RB2'] !== "0") {//check to make sure this position is not empty
          print '<tr><td class = "rosterName">RB2</td><td class = "name">' . $rowData1['RB2'] . '</td><td class = "pos">&nbsp;</td></tr>';
        }
        if ($rowData1['WR1'] !== "0") {//check to make sure this position is not empty
          print '<tr><td class = "rosterName">WR1</td><td class = "name">' . $rowData1['WR1'] . '</td><td class = "pos">&nbsp;</td></tr>';
        }
        if ($rowData1['WR2'] !== "0") {//check to make sure this position is not empty
          print '<tr><td class = "rosterName">WR2</td><td class = "name">' . $rowData1['WR2'] . '</td><td class = "pos">&nbsp;</td></tr>';
        }
        if ($rowData1['TE'] !== "0") {//check to make sure this position is not empty
          print '<tr><td class = "rosterName">TE</td><td class = "name">' . $rowData1['TE'] . '</td><td class = "pos">&nbsp;</td></tr>';
        }
        if ($rowData1['K'] !== "0") {//check to make sure this position is not empty
          print '<tr><td class = "rosterName">K</td><td class = "name">' . $rowData1['K'] . '</td><td class = "pos">&nbsp;</td></tr>';
        }
        if ($rowData1['DEF'] !== "0") {//check to make sure this position is not empty
          print '<tr><td class = "rosterName">DEF</td><td class = "name">' . $rowData1['DEF'] . '</td><td class = "pos">&nbsp;</td></tr>';
        }
      }//END foreach $teamData1 as $rowData1
      ?>
      <tr class = "padding">
        <td class = "rosterName"></td>
        <td class = "name"></td>
        <td class = "pos"></td>
      </tr>
    </table><!-- END draftTeam1 table-->
  </div><!-- END draftTeam1 div-->
  <div class ="draftSpacer"></div>

  <div class ="draftTeam2"><!--DIV containing team2, white background-->
    <div class ="draftTeam2Top">
      <?php
      if ($ownerNum == "2") {//if user is owner2 of team2
        print '<h1 align = "left">My Team</h1>'; //team on left is team2, user's team
      } else {//if user is owner1 of team1
        print '<h1 align = "left">My Opponent</h1>'; //team on right is team2, user's opponent
      }//END if ownerNum = 1 or 2
      ?>
    </div><!-- END draftTeam2Top div-->
    <!--Print this team's name-->
    <h1 align = "center" class = "teamTitle2" ><?php print_r($team2Name) ?></h1>
    <table class ="draftTeam2">
      <tr class = "header">
        <th>Roster</th>
        <th>Player Name</th>
        <th>Position</th>
      </tr>
      <tr class = "padding">
        <td class = "rosterName"></td>
        <td class = "name"></td>
        <td class = "pos"></td>
      </tr>
      <?php
      $teamData2 = $draftHandler->currentTeam2Info($currentLeagueName); //return the complete roster of team2 in this league as an array
      foreach ($teamData2 as $rowData2) {//go through the array and pull each individual player from each position
        if ($rowData2['QB'] !== "0") {//check to make sure this position is not empty
          print '<tr><td class = "rosterName">QB</td><td class = "name">' . $rowData2['QB'] . '</td><td class = "pos">&nbsp;</td></tr>';
        }
        if ($rowData2['RB1'] !== "0") {//check to make sure this position is not empty
          print '<tr><td class = "rosterName">RB1</td><td class = "name">' . $rowData2['RB1'] . '</td><td class = "pos">&nbsp;</td></tr>';
        }
        if ($rowData2['RB2'] !== "0") {//check to make sure this position is not empty
          print '<tr><td class = "rosterName">RB2</td><td class = "name">' . $rowData2['RB2'] . '</td><td class = "pos">&nbsp;</td></tr>';
        }
        if ($rowData2['WR1'] !== "0") {//check to make sure this position is not empty
          print '<tr><td class = "rosterName">WR1</td><td class = "name">' . $rowData2['WR1'] . '</td><td class = "pos">&nbsp;</td></tr>';
        }
        if ($rowData2['WR2'] !== "0") {//check to make sure this position is not empty
          print '<tr><td class = "rosterName">WR2</td><td class = "name">' . $rowData2['WR2'] . '</td><td class = "pos">&nbsp;</td></tr>';
        }
        if ($rowData2['TE'] !== "0") {//check to make sure this position is not empty
          print '<tr><td class = "rosterName">TE</td><td class = "name">' . $rowData2['TE'] . '</td><td class = "pos">&nbsp;</td></tr>';
        }
        if ($rowData2['K'] !== "0") {//check to make sure this position is not empty
          print '<tr><td class = "rosterName">K</td><td class = "name">' . $rowData2['K'] . '</td><td class = "pos">&nbsp;</td></tr>';
        }
        if ($rowData2['DEF'] !== "0") {//check to make sure this position is not empty
          print '<tr><td class = "rosterName">DEF</td><td class = "name">' . $rowData2['DEF'] . '</td><td class = "pos">&nbsp;</td></tr>';
        }
      }//END foreach $teamData2 as $rowData2
      ?>
      <tr class = "padding">
        <td class = "rosterName"></td>
        <td class = "name"></td>
        <td class = "pos"></td>
      </tr>
    </table><!-- END draftTeams2 table-->
  </div><!-- END draftTeams2 div-->
</div><!-- END draftTeamss div-->

<?php
//
//-----END Changeable Content-----
//
//include the footer
include('templates/Footer.php'); // Include the footer.
?>