<?php
ob_start(); // Turn on output buffering:
/*
  File Name: Logout.php

  Version 1.0
  CSC 478 Group Project
  Group#8: FanSports
  Wesley Elliot, Jeremy Jones, Ann Oesterle
  Last Updated: 12/7/2016
 */
define('TITLE', 'Logout');
define('CSS', 'Forms');
include('templates/Header2.php'); // Include the header.
//
//-----Begin Changeable Content-----
//
// Reset the session array:
$_SESSION = array();

// Destroy the session data on the server:
session_destroy();

//reset all session variables
$_SESSION['loggedIn'] = NULL; //make sure the user is logged out
$_SESSION['userID'] = NULL;
$_SESSION['userName'] = NULL;
$_SESSION['leagueName'] = NULL;
$_SESSION['ownerNum'] = NULL;
//print feedback to the user confirming that they have logged out
print '
  <div class ="formCard2">
    <p>You are now logged out.</p>
    <p>Thank you for using this site. We hope that you liked it.</p>
    <a href = "index.php"><div class = "returnButton">Done</div></a>
  </div>
 ';
//include the footer
include('templates/Footer.php'); //include footer
?>


