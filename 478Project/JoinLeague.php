<?php
ob_start(); // Turn on output buffering:
/*
  File Name: JoinLeague.php

  Version 1.0
  CSC 478 Group Project
  Group#8: FanSports
  Wesley Elliot, Jeremy Jones, Ann Oesterle
  Last Updated: 12/7/2016
 */
define('TITLE', 'Join a League'); //create the title of the page that will show in browser
define('CSS', 'Forms'); //create the link to the stylesheet
include('templates/Header.php'); // Include the header.
//
//-----Begin Changeable Content-----
//
//include the necessary files
include_once "UserDBConnection.php";
require_once "UserDBHandler.php";
$leagueHandler = new userHandler($pdo); //create an instance of the user DB handler class

if ($_SERVER['REQUEST_METHOD'] == 'POST') {//Check if form has been submitted
  if ((!empty($_POST['leagueName'])) && (!empty($_POST['leaguePass'])) && (!empty($_POST['teamName']))) { //Check to make sure that all fields have been entered
    //sanitize the input for security
    $leagueNameInput = htmlspecialchars(strip_tags(trim($_POST['leagueName'])));
    $leagueTestPass = htmlspecialchars(strip_tags(trim($_POST['leaguePass'])));
    $teamName = htmlspecialchars(strip_tags(trim($_POST['teamName'])));
    //call the necessary functions from the includes files
    $numResults = count($leagueHandler->checkLeague($leagueNameInput)); //check if leagueName is found by the number of results.
    $userResults = $leagueHandler->checkLeague($leagueNameInput); //store result in an array
    if ($numResults > 0) { //league name exists
      //check that the team name entered is not already in use
      $teamNameDupeCheck = $leagueHandler->checkTeam2Name($leagueNameInput); //is this name found in the db already?
      if (strcasecmp($_POST['teamName'], $teamNameDupeCheck[0]['team1']) == 0) { //check for unique league name, mb_strcasecmp returns 0 if the strings are the same.
        print '
          <div class = "formCard2">
            <p class = "errorMsg2">That team name is already taken! Please try another.<br> <br>Go <a class = "backLink" href = "JoinLeague.php">back</a> and try again.</p>
          </div>
        ';
      } else {//no duplications in the team Name
        $leaguePassResult = $leagueHandler->checkLeaguePass($leagueNameInput); //get the league password that matches the league name from the db
        if (password_verify($leagueTestPass, $leaguePassResult['leaguePass'])) { //test that the leaguePass matches the league name found in the db
          //check for duplicates
          //for updating the league info, set the variables to the correct values
          $leagueName = $leagueNameInput;
          $currentUserID = $_SESSION['userName']; //get the userID for the current session
          $leagueStatus = 1; //increment the leagueStatus to indicate that the second player has joined the league
          //call the necessary functions from the includes files
          $leagueHandler->updateStatus($leagueName, $leagueStatus); //update the status number in the leagueinfo db table
          $leagueHandler->updateLeagueforT2($leagueName, $leagueStatus, $teamName, $currentUserID); //add the team2 information to the leagueinfo2 db table
          $leagueHandler->createTeam2($leagueName, $teamName, $currentUserID); //create a team in the team2 db table
          //print a message if the league is successfully created and then allow user to return to user home page
          print '
            <div class = "formCard2">
              <p>Your team:  ' . $teamName . ', in league ' . $leagueName . ' has been created. Enjoy!</p>
              <a href = "UserHome.php"><div class = "returnButton">Continue</div></a>
            </div>
          ';
          //END if - no duplications in team name
        } else { //incorrect password
          print '
            <div class = "formCard2">
              <p class = "errorMsg2">Credentials submitted are not valid.<br> <br>Go <a class = "backLink" href = "JoinLeague.php">back</a> and try again.</p>
            </div>
          ';
        } //END if - does the password match the pass in the db
      }//END if - no duplications in team name
    } else { //no result found with that leagueName, same output as line above for security reasons
      print '
        <div class = "formCard2">
          <p class = "errorMsg2">Credentials submitted are not valid.<br> <br>Go <a class = "backLink" href = "JoinLeague.php">back</a> and try again.</p>
        </div>
      ';
    }//END if - that league name is not found in the db
  } else { //if one of the fields is missing
    print '
      <div class = "formCard">
        <form action="JoinLeague.php" method="post" id="joinForm">
        <h1 class = "legend">Join a League</h1>
          <ul>
            <li class = "formLabel"><label for = "leagueName">League Name: </label></li>
            <li><input type="text" name="leagueName" id = "leagueName"  maxlength="30" required></li>
            <li class = "formLabel"><label for = "leaguePass">League Password: </label></li>
            <li><input type="password" name="leaguePass" id = "leaguePass"  maxlength="30" required></li>
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
      <form action="JoinLeague.php" method="post" id="joinForm">
      <h1 class = "legend">Join a League</h1>
        <ul>
          <li class = "formLabel"><label for = "leagueName">League Name: </label></li>
          <li><input type="text" name="leagueName" id = "leagueName" maxlength="30" required></li>
          <li class = "formLabel"><label for = "leaguePass">League Password: </label></li>
          <li><input type="password" name="leaguePass" id = "leaguePass" maxlength="30" required></li>
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