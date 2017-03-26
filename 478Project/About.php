<?php
session_start(); //start session on each page
//error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);
/*
  File Name: About.php

  Version 1.0.2
  CSC 478 Group Project
  Group: FanSports
  Wesley Elliot, Jeremy Jones, Ann Oesterle
  Last Updated: 11/13/2016
 */

//Checks if the user is logged in
if (!isset($_SESSION['loggedIn'])) {//user is not logged in
  $session = FALSE;
} else {//Logged in
  $session = TRUE;
}
?>

<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"/>

<head>
  <meta charset="utf-8">
    <title>About FanSports</title>
<link rel="stylesheet" href="css/About.css">
</head>
<body>

  <header role = "header" class = "verticalHeader">
     <div class ="verticalHeader_top">
      <?php
      //Check if the user is logged in
      if ($session === FALSE) {//not logged in
        print '<a href = "index.php"><img class = "logo" src = "pictures/logo_square.jpg"> </a>';

      } else {//logged in
        //clicking on the logo sends them to the User Home page
        print '<a href = "UserHome.php"><img class = "logo" src = "pictures/logo_square.jpg"> </a><a href = "UserHome.php"><p class ="homeLink">Home</p></a><a href = "Logout.php"><p class ="logoutLink">logout</p></a>';
      }
      ?>
      
    </div>

    
    <div class ="verticalHeader_info"><!--Contains informatino about app-->
      <p>Created By: Wesley Elliot, Jeremy Jones, Ann Oesterle</p>
      <a href = "About.php" class ="aboutLink"><p >about</p></a>
      <small><a rel="license" class = "license" href="http://creativecommons.org/licenses/by/4.0/">This work is licensed under a Creative Commons Attribution 4.0 International License</a></small>
      <a rel="license"href="http://creativecommons.org/licenses/by/4.0/"><img alt="Creative Commons License" class = "ccimage" src="pictures/ccimage2.jpg" /></a>
    </div><!--END appinfo div-->
  </header>
  <div class ="verticalHeader_spacer"></div>
  <main>
    <!-- BEGIN CHANGEABLE CONTENT. -->

<div class = "aboutInfo">
  <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We here at FanSports are a small team of UIS computer science students that came together to create this application as a result of Professor Roger West’s Software Engineering course project assignment. Each of us live in separate regions of the country and have never formally met so this project came together remotely using various web-based tools for group communication and file sharing. The impetus behind the Fansports application was two-fold. First, being a fantasy sports application we clearly wanted it to be entertaining and secondly, we also wanted FanSports to be an educational resource for other developers hence the open source nature of the application.</p>
  <p>We very much hope that you enjoy your visit to our site!
    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The FanSports Team (Ann, Wes, and Jeremy)
  </p>
</div>

<!-- END CHANGEABLE CONTENT. -->

<?php
include('templates/Footer.php'); // Include the footer.
?>
