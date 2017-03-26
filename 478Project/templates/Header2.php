<?php
session_start(); //start session on each page
//error reporting
//ini_set('display_errors', 1);
//error_reporting(E_ALL | E_STRICT);
/*
  File Name: Header.php - this is the header for not logged in users

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
    <title>
      <?php
      // Print the page title.
      if (defined('TITLE')) { // Is the title defined?
        print TITLE;
      } else { // The title is not defined.
        print 'FanSports Website';
      }
      ?>
    </title>

    <!--Backwards compatible with browsers-->
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->


    <?php
    $css = "css/" . CSS . ".css";
    print ' <link rel="stylesheet" href=" ' . $css . ' "> ';
    ?>
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
        print '<a href = "UserHome.php"><img class = "logo" src = "pictures/logo_square.jpg"> </a>';
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