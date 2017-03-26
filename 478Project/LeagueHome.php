<?php
ob_start();
/*
  File Name: CreateLeague.php

  Version 1.0
  CSC 478 Group Project
  Group#8: FanSports
  Wesley Elliot, Jeremy Jones, Ann Oesterle
  Last Updated: 12/7/2016
 */
define('TITLE', 'League Page');//create the title of the page that will show in browser
define('CSS', 'LeagueHome');//create the link to the stylesheet
include('templates/Header.php');  // Include the header.
//
//-----Begin Changeable Content-----
//
//include all necessary files
include_once "UserDBConnection.php";
require_once "UserDBHandler.php";

$leagueHomeHandler = new userHandler($pdo);//create an instance of the user DB handler class

$currentUserID = $_SESSION['userName'];//get the current user name for this session
$currentLeagueName = $_SESSION['leagueName'];//get the current league name for this session (which league was clicked on, which is the user trying to view)
$ownerNum = $_SESSION['ownerNum'];//figure out if this player is owner1 or owner2
//call the necessary functions from the includes files
$team1Name = $leagueHomeHandler->getTeam1Name($currentLeagueName);//find the name of team1 for the current league
foreach ($team1Name as $row) {
  $team1Name = $row['team1'];
}//END foreach team1Name
$team2Name = $leagueHomeHandler->getTeam2Name($currentLeagueName);//find the name of team2 for the current league
foreach ($team2Name as $row) {
  $team2Name = $row['team2'];
}//END foreach team2Name
$leagueData = $leagueHomeHandler->userleagueInfo($currentLeagueName);//find the status of  the current league
foreach ($leagueData as $row) {
  $printStatus = $row['leagueStatus'];
  if ($printStatus == 0) {//Before second player has joined the league
    $printStatus = "Waiting for Opponent";
    $statusLink = "UserHome.php";
  } else if (($printStatus > 0) && ($printStatus < 17)) {//during the draft
    $printStatus = "Draft";
    $statusLink = "Draft.php";
  } else if ($printStatus > 16) {//after draft is finished, a quick match game is available
    $printStatus = "Quick Match Available";
    $statusLink = "QuickMatch.php";
  }
  //print the league information including league name and status
  print '
    <div class ="leagueInfoTop"><p class = "leagueNameTop">League Name:   ' . $row['leagueName'] . '</p>
      <p>League Status: &nbsp;<a class = "statusLink" href = "'.$statusLink.'">' . $printStatus . '</a></p>
    </div>';
}//END foreach leagueData
?>
<!--DIV contains 2 tables, one for each team-->
<div class ="leagueTeams">
  <!--TEAM #1-->
  <div class ="leagueTeam1">
    <div class ="leagueTeam1Top">
      <?php
      if ($ownerNum == "1"){//if user is owner1, the table on the left is their team
        print '<h1 align = "left">My Team</h1>';
      }else if ($ownerNum == "2"){
        print '<h1 align = "left">My Opponent</h1>';
      }
      ?>
    </div> <!-- END leagueTeamTop div-->
    <h1 align = "center" class = "teamTitle" ><?php print_r($team1Name) ?></h1>
    <table class ="leagueTeam1">
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
      $teamData1 = $leagueHomeHandler->userTeamInfo1($currentLeagueName);//get all the players of team2
      foreach ($teamData1 as $row2) {
        print '
          <tr><td class = "rosterName">QB</td><td class = "name">' . $row2['QB'] . '</td><td class = "pos">&nbsp;</td></tr>
          <tr><td class = "rosterName">RB1</td><td class = "name">' . $row2['RB1'] . '</td><td class = "pos">&nbsp;</td></tr>
          <tr><td class = "rosterName">RB2</td><td class = "name">' . $row2['RB2'] . '</td><td class = "pos">&nbsp;</td></tr>
          <tr><td class = "rosterName">WR1</td><td class = "name">' . $row2['WR1'] . '</td><td class = "pos">&nbsp;</td></tr>
          <tr><td class = "rosterName">WR2</td><td class = "name">' . $row2['WR2'] . '</td><td class = "pos">&nbsp;</td></tr>
          <tr><td class = "rosterName">TE</td><td class = "name">' . $row2['TE'] . '</td><td class = "pos">&nbsp;</td></tr>
          <tr><td class = "rosterName">K</td><td class = "name">' . $row2['K'] . '</td><td class = "pos">&nbsp;</td></tr>
          <tr><td class = "rosterName">DEF</td><td class = "name">' . $row2['DEF'] . '</td><td class = "pos">&nbsp;</td></tr>
        ';
      } //END foreach teamData1
      ?>
      <tr class = "padding">
        <td class = "rosterName"></td>
        <td class = "name"></td>
        <td class = "pos"></td>
      </tr>
    </table><!-- END leagueTeam1 table-->
  </div><!-- END leagueTeam1 div-->
  <!--SPACE between the two team's tables-->
  <div class ="spacer"></div>
  <!--TEAM #1-->
  <div class ="leagueTeam2">
    <div class ="leagueTeam2Top">
      <?php
      if ($ownerNum == "1"){//
        print '<h1 align = "left">My Opponent</h1>';
      }else if ($ownerNum == "2"){//if the user is owner2, the table on the right is their team
        print '<h1 align = "left">My Team</h1>';
      }
      ?>
    </div><!-- END leagueTeam2Top div-->
    <h1 align = "center" class = "teamTitle2"><?php print_r($team2Name) ?></h1>
    <table class ="leagueTeam2">
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
      $teamData2 = $leagueHomeHandler->userTeamInfo2($currentLeagueName);//get all the players of team2
      foreach ($teamData2 as $row4) {
        print '
          <tr><td class = "rosterName">QB</td><td class = "name">' . $row4['QB'] . '</td><td class = "pos">&nbsp;</td></tr>
          <tr><td class = "rosterName">RB1</td><td class = "name">' . $row4['RB1'] . '</td><td class = "pos">&nbsp;</td></tr>
          <tr><td class = "rosterName">RB2</td><td class = "name">' . $row4['RB2'] . '</td><td class = "pos">&nbsp;</td></tr>
          <tr><td class = "rosterName">WR1</td><td class = "name">' . $row4['WR1'] . '</td><td class = "pos">&nbsp;</td></tr>
          <tr><td class = "rosterName">WR2</td><td class = "name">' . $row4['WR2'] . '</td><td class = "pos">&nbsp;</td></tr>
          <tr><td class = "rosterName">TE</td><td class = "name">' . $row4['TE'] . '</td><td class = "pos">&nbsp;</td></tr>
          <tr><td class = "rosterName">K</td><td class = "name">' . $row4['K'] . '</td><td class = "pos">&nbsp;</td></tr>
          <tr><td class = "rosterName">DEF</td><td class = "name">' . $row4['DEF'] . '</td><td class = "pos">&nbsp;</td></tr>
        ';
      } // END foreach teamData2
      ?>
      <tr class = "padding">
        <td class = "rosterName"></td>
        <td class = "name"></td>
        <td class = "pos"></td>
      </tr>
    </table><!-- END leagueTeam2 table-->
  </div><!-- END leagueTeam2 div-->
</div><!-- END leagueTeams div-->

<?php
//
//-----END Changeable Content-----
//
//include the footer
include('templates/Footer.php'); // Include the footer.
?>
