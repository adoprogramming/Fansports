<?php
ob_start(); // Turn on output buffering:
/*
  File Name: Register.php

  Version 1.0
  CSC 478 Group Project
  Group#8: FanSports
  Wesley Elliot, Jeremy Jones, Ann Oesterle
  Last Updated: 12/7/2016
 */
define('TITLE', 'Register');
define('CSS', 'Forms');
include('templates/Header2.php'); // Include the header.
//
//-----Begin Changeable Content-----
//
//include necessary files
include_once "UserDBConnection.php";
require_once "UserDBHandler.php";
$userHandler = new userHandler($pdo); //create an instance of the user DB handler class

if ($_SERVER['REQUEST_METHOD'] == 'POST') {//Check if form has been submitted
  if ((!empty($_POST['userName'])) && (!empty($_POST['userPass'])) && (!empty($_POST['userPass2']))) { //check to make suer all fields have been entered
    //sanitize the input for security
    $userName = htmlspecialchars(strip_tags(trim($_POST['userName'])));
    //check to make sure that the user ID entered is unique
    $userNameDupeCheck = $userHandler->checkLogin($userName);
    if (strcasecmp($_POST['userName'], $userNameDupeCheck[0]['userName']) == 0) { //check for unique username, mb_strcasecmp returns 0 if the strings are the same.
      print '
        <div class = "formCard2">
          <p class = "errorMsg2">Your User ID is already taken! Please try another.<br> <br>Go <a class = "backLink" href = "Register.php">back</a> and try again.</p>
        </div>
      ';
    } else {//no duplications in the userName
      if ($_POST['userPass'] != $_POST['userPass2']) {//check that the password and confirmed password match
        print '
          <div class = "formCard2">
            <p class = "errorMsg2">Your password did not match your confirmed password!<br> <br>Go <a class = "backLink" href = "Register.php">back</a> and try again.</p>
          </div>
        ';
      } else { //userPass and userPass2 match
        // clean user inputs to prevent sql injections
        $userPass = htmlspecialchars(strip_tags(trim($_POST['userPass'])));
        $userPass = password_hash($userPass, PASSWORD_DEFAULT); //hash the password
        //add new values (created by registration) for userID and userPass to userinfo2 table
        $userHandler->registerUser($userName, $userPass);
        //set the session variables
        $_SESSION['userName'] = $userName;
        $_SESSION['loggedIn'] = time(); //set that the user has been logged in...time() is just a placeholder any value but NULL works
        ob_end_clean(); //clean buffer
        header('Location: UserHome.php'); //once logged in, redirect to user home
      }//END if - password and confirmed password match
    }//END if - no duplications in user ID
  } else { // Forgot a field.
    print '
      <div class = "formCard">
        <form action="Register.php" method="post" id="registerForm">
        <h1 class = "legend">Register</h1>
          <ul>
            <li class = "formLabel"><label for = "userName">User ID: </label></li>
            <li><input type="text" name="userName" id = "userName" maxlength="30" required></li>
            <li class = "formLabel"><label for = "userPass">Password: </label></li>
            <li><input type="password" name="userPass" id = "userPass" maxlength="30" required></li>
            <li class = "formLabel"><label for = "userPass2">Confirm Password: </label></li>
            <li><input type="password" name="userPass2" id = "userPass2"maxlength="30"  required></li>
            <li><p class = "errorMsg" id = "errorPass">All fields are required!</p></li>
          </ul>
          <input type="submit" value="Submit" class = "formButton" id = "formButton">
        </form>
      </div>
    ';
  }//END if - all fields have been entered
} else { //if form hasn't been printed yet
  print '
      <div class = "formCard">
        <form action="Register.php" method="post" id="registerForm">
        <h1 class = "legend">Register</h1>
          <ul>
            <li class = "formLabel"><label for = "userName">User ID: </label></li>
            <li><input type="text" name="userName" id = "userName" maxlength="30" required></li>
            <li class = "formLabel"><label for = "userPass">Password: </label></li>
            <li><input type="password" name="userPass" id = "userPass" maxlength="30" required></li>
            <li class = "formLabel"><label for = "userPass2">Confirm Password: </label></li>
            <li><input type="password" name="userPass2" id = "userPass2"maxlength="30"  required></li>
            <li><p class = "errorMsg" id = "errorPass">&nbsp;</p></li>
          </ul>
          <input type="submit" value="Submit" class = "formButton" id = "formButton">
        </form>
      </div>
    ';
}
//include footer
include('templates/Footer.php');
?>