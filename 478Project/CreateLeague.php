<?php
ob_start(); // Turn on output buffering:
/*
  File Name: CreateLeague.php

  Version 1.0
  CSC 478 Group Project
  Group#8: FanSports
  Wesley Elliot, Jeremy Jones, Ann Oesterle
  Last Updated: 12/7/2016
 */
define('TITLE', 'Create League'); //create the title of the page that will show in browser
define('CSS', 'Forms'); //create the link to the stylesheet
include('templates/Header.php'); // Include the header.
//
//-----Begin Changeable Content-----
//
//include all necessary files
include_once "UserDBConnection.php";
require_once "UserDBHandler.php";

$leagueHandler = new userHandler($pdo); //create an instance of the user DB handler class

if ($_SERVER['REQUEST_METHOD'] == 'POST') {//Check if the form has been submitted
  if ((!empty($_POST['leagueName'])) && (!empty($_POST['leaguePass'])) && (!empty($_POST['leaguePass2'])) && (!empty($_POST['teamName']))) { //check to make sure all fields have been entered
    $leagueName = htmlspecialchars(strip_tags(trim($_POST['leagueName']))); //so far so good. sanitize input for the next check.
    //check to make sure that the league name entered is unique
    $leagueNameDupeCheck = $leagueHandler->checkLeague($leagueName);//is this name found in the db already?
    if (strcasecmp($_POST['leagueName'], $leagueNameDupeCheck[0]['leagueName']) == 0) { //check for unique league name, mb_strcasecmp returns 0 if the strings are the same.
      print '
        <div class = "formCard2">
          <p class = "errorMsg2">That league name is already taken! Please try another.<br> <br>Go <a class = "backLink" href = "CreateLeague.php">back</a> and try again.</p>
        </div>
      ';
    } else {//no duplications in the leagueName
      if ($_POST['leaguePass'] != $_POST['leaguePass2']) {//check that the password and confirmed password match
        print '
          <div class = "formCard2">
            <p class = "errorMsg2">Your password did not match your confirmed password!<br> <br>Go <a class = "backLink" href = "CreateLeague.php">back</a> and try again.</p>
          </div>
        ';
      } else { //leaguePass and leaguePass2 match
        // clean user inputs to prevent sql injections
        $leaguePass = htmlspecialchars(strip_tags(trim($_POST['leaguePass'])));
        $leaguePass = password_hash($leaguePass, PASSWORD_DEFAULT); //hash the password - encrypt for security
        //add new values (created by registration) for userID and leaguePass to leagueinfo2 table
        $teamName = htmlspecialchars(strip_tags(trim($_POST['teamName'])));
        $currentUserID = $_SESSION['userName']; //get the userID for the current session
        //for creating the league, set the initial values to zero for all the variables we will not be creating until join league
        $leagueStatus = 0;
        $owner2 = 0;
        $team2 = 0;
        //call the necessary functions from the includes files
        $leagueHandler->registerLeague($leagueName, $leaguePass, $leagueStatus, $teamName, $currentUserID, $team2, $owner2); //create the league inside the leagueinfo2 db table
        $leagueHandler->createTeam1($leagueName, $teamName, $currentUserID); //create team1 in the team1 db table
        //print a message if the league is successfully created and then allow user to return to user home page
        print '
          <div class = "formCard2">
            <p>The league: ' . $leagueName . ' has been created.</p>
            <p>And your team ' . $teamName . ' has been added.</p>
            <a href = "UserHome.php"><div class = "returnButton">Continue</div></a>
          </div>
        ';
      }//END if - does password and confirmed password match
    }//END if - league name duplications
  } else { // Forgot a field.
    //reprint the form with the necessary error message
    print '
      <div class = "formCard">
        <form action="CreateLeague.php" method="post" id="createForm">
        <h1 class = "legend">Create a League</h1>
          <ul>
            <li class = "formLabel"><label for = "leagueName">League Name: </label></li>
            <li><input type="text" name="leagueName" id = "leagueName"  maxlength="30" required></li>
            <li class = "formLabel"><label for = "leaguePass">League Password: </label></li>
            <li><input type="password" name="leaguePass" id = "leaguePass" maxlength="30" required></li>
            <li class = "formLabel"><label for = "leaguePass2">Confirm Password: </label></li>
            <li><input type="password" name="leaguePass2" id = "leaguePass2" maxlength="30" required></li>
            <li class = "formLabel"><label for = "teamName">Your Team Name: </label></li>
            <li><input type="text" name="teamName" id = "teamName" maxlength="30" required></li>
            <li><p class = "errorMsg" id = "errorPass">All fields are required!</p></li>
          </ul>
          <input type="submit" value="Submit" class = "formButton" id = "formButton">
        </form>
      </div>
    ';
  }
} else { //if form hasn't been printed yet
  print '
    <div class = "formCard">
      <form action="CreateLeague.php" method="post" id="createForm">
      <h1 class = "legend">Create a League</h1>
        <ul>
          <li class = "formLabel"><label for = "leagueName">League Name: </label></li>
          <li><input type="text" name="leagueName" id = "leagueName" maxlength="30" required></li>
          <li class = "formLabel"><label for = "leaguePass">League Password: </label></li>
          <li><input type="password" name="leaguePass" id = "leaguePass" maxlength="30" required></li>
          <li class = "formLabel"><label for = "leaguePass2">Confirm Password: </label></li>
          <li><input type="password" name="leaguePass2" id = "leaguePass2" maxlength="30" required></li>
          <li class = "formLabel"><label for = "teamName">Your Team Name: </label></li>
          <li><input type="text" name="teamName" id = "teamName" maxlength="30" required></li>
          <li><p class = "errorMsg" id = "errorPass">&nbsp;</p></li>
        </ul>
        <input type="submit" value="Submit" class = "formButton" id = "formButton">
      </form>
    </div>
  ';
}
//
//-----END Changeable Content-----
//
//include the footer
include('templates/Footer.php');
?>