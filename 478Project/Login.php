<?php
ob_start(); // Turn on output buffering:
/*
  File Name: Login.php

  Version 1.0
  CSC 478 Group Project
  Group#8: FanSports
  Wesley Elliot, Jeremy Jones, Ann Oesterle
  Last Updated: 12/7/2016
 */
define('TITLE', 'Login');
define('CSS', 'Forms');
include('templates/Header2.php'); // Include the header.
//
//-----Begin Changeable Content-----
//
//include all necessary files
include_once "UserDBConnection.php";
require_once "UserDBHandler.php";

$userHandler = new userHandler($pdo); //create an instance of the user DB handler class

if ($_SERVER['REQUEST_METHOD'] == 'POST') {//Check if form has been submitted
  if ((!empty($_POST['userName'])) && (!empty($_POST['userPass']))) { //Check that both fields have been entered
    //sanitize the input for security
    $userNameInput = htmlspecialchars(strip_tags(trim($_POST['userName'])));
    $userTestPass = htmlspecialchars(strip_tags(trim($_POST['userPass'])));
    //call the necessary functions from the includes files
    $numResults = count($userHandler->checkLogin($userNameInput)); //check if username is found by number of results.
    $userResults = $userHandler->checkLogin($userNameInput); //store result in an array
    if ($numResults > 0) { //userID exists
      $passResult = $userHandler->checkPass($userNameInput); //get the user password that matches the userID from the db
      if (password_verify($userTestPass, $passResult['userPass'])) { //test that the userPass matches the userID
        $_SESSION['userName'] = $_POST['userName']; //set the session variable userID to valid userID
        $_SESSION['loggedIn'] = time(); //basically sets loggedIn = TRUE, time() is a placeholder
        ob_end_clean(); //clean buffer
        header('Location: UserHome.php'); //once logged in, redirect to user home
        exit();
      } else { //incorrect password
        print '
          <div class = "formCard2">
            <p class = "errorMsg2">Credentials submitted are not valid.<br> <br>Go <a class = "backLink" href = "Login.php">back</a> and try again.</p>
          </div>
        ';
      }
    } else { //no result found with that userID, same output as line above for security reasons
      print '
        <div class = "formCard2">
          <p class = "errorMsg2">Credentials submitted are not valid.<br> <br>Go <a class = "backLink" href = "Login.php">back</a> and try again.</p>
        </div>
      ';
    }
  } else { //if one of the fields is missing
    print '
      <div class = "formCard">
        <form action="Login.php" method="post" id="loginForm">
        <h1 class = "legend">Login</h1>
          <ul>
            <li class = "formLabel"><label for = "userName">User ID: </label></li>
            <li><input type="text" name="userName" id = "userName"  maxlength="30" required></li>
            <li class = "formLabel"><label for = "userPass">Password: </label></li>
            <li><input type="password" name="userPass" id = "userPass"  maxlength="30" required></li>
            <li><p class = "errorMsg" id = "errorPass">All Fields are Required!</p></li>
          </ul>
          <input type="submit" value="Submit" class = "formButton" id = "formButton">
        </form>
      </div>
    ';
  }
} else { //if form hasn't been printed yet
  print '
    <div class = "formCard">
      <form action="Login.php" method="post" id="loginForm">
      <h1 class = "legend">Login</h1>
        <ul>
          <li class = "formLabel"><label for = "userName">User ID: </label></li>
          <li><input type="text" name="userName" id = "userName"  maxlength="30" required></li>
          <li class = "formLabel"><label for = "userPass">Password: </label></li>
          <li><input type="password" name="userPass" id = "userPass"  maxlength="30" required></li>
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