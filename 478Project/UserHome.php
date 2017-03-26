<?php
ob_start(); // Turn on output buffering:
/*
  File Name: UserHome.php

  Version 1.0
  CSC 478 Group Project
  Group#8: FanSports
  Wesley Elliot, Jeremy Jones, Ann Oesterle
  Last Updated: 12/7/2016
 */
define('TITLE', 'User Home'); //create the title of the page that will show in browser
define('CSS', 'UserHome'); //create the link to the stylesheet
include('templates/Header.php');  // Include the header.
///
//-----Begin Changeable Content-----
//
//include all necessary files
include_once "UserDBConnection.php";
?>
<!--One Card on this page with all user info.-->
<div class ="userCard">
  <!--Top of the card, contains the user profile-->
  <div class ="userProfile">
    <?php
    if (!isset($_SESSION['loggedIn'])) {
      header('Location: Login.php'); //user isn't logged in if session variable 'loggedin' isn't set.
    } else {
      $name = $_SESSION['userName'];
      print "<h1 class ='userWelcome'>Hello $name</h1>";
    }
    ?>

  </div>
  <!--Middle of the card, contains the info about any leagues the user is in-->
  <?php
  //uses a form to determine which link is clicked on.
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {////Check if the form has been submitted
    //SET values for what league and team was clicked
    //if one of the buttons in the row was clicked, the value of the league name is given by the hidden input dataNameDefault1   
    if (!empty($_POST['dataNameDefault1'])) {// if one of the buttons in the team1 rows was clicked
      //set the values for league name, team name, league status, and what link the status button should point to
      $leagueNameInput = $_POST['dataNameDefault1'];
      $teamNameInput = $_POST['dataTeamDefault1'];
      $statusInput = $_POST['dataStatusDefault1'];
      $statusLinkInput = $_POST['dataLinkDefault1'];//if the status button is clicked it should point to this league

      $_SESSION['ownerNum'] = "1";//since a team1 was clicked, set ownerNum to 1
      $_SESSION['leagueName'] = $leagueNameInput;//set session value of leagueName
      // CHECK to see what was clicked and REDIRECT
      if (!empty($_POST['dataName1'])) {//the league's name was clicked
        ob_end_clean(); //clean buffer
        header('Location: LeagueHome.php'); //redirect to league home
        exit();
      }//END if - league's name was clicked
      if (!empty($_POST['dataTeam1'])) {//the user's team name was clicked
        ob_end_clean(); //clean buffer
        header('Location: LeagueHome.php'); //redirect to league home
        exit();
      }//END if - team's name was clicked
      if (!empty($_POST['dataStatus1'])) {//the status button was clicked
        ob_end_clean(); //clean buffer
        header('Location: ' . $statusLinkInput . ''); //redirect to draft page or quick match page as necessary
        exit();
      }//END if - status link button was clicked
    }//END if - a row in team1's area was clicked

    if (isset($_POST['dataNameDefault2'])) {// if one of the buttons in the team2 rows was clicked
      //set the values for league name, team name, league status, and what link the status button should point to
      $leagueNameInput = $_POST['dataNameDefault2'];
      $teamNameInput = $_POST['dataTeamDefault2'];
      $statusInput = $_POST['dataStatusDefault2'];
      $statusLinkInput = $_POST['dataLinkDefault2'];//if the status button is clicked it should point to this league

      $_SESSION['ownerNum'] = "2";//since a team2 was clicked, set ownerNum to 2
      $_SESSION['leagueName'] = $leagueNameInput;//set session value of leagueName
      // CHECK to see what was clicked and REDIRECT
      if (!empty($_POST['dataName2'])) {//the league's name was clicked
        ob_end_clean(); //clean buffer
        header('Location: LeagueHome.php'); //redirect to league home
        exit();
      }//END if - league's name was clicked
      if (!empty($_POST['dataTeam2'])) {//the user's team name was clicked
        ob_end_clean(); //clean buffer
        header('Location: LeagueHome.php'); //redirect to league home
        exit();
      }//END if - team's name was clicked
      if (!empty($_POST['dataStatus2'])) {//the status button was clicked
        ob_end_clean(); //clean buffer
        header('Location: ' . $statusLinkInput . ''); //redirect to draft page or quick match page as necessary
        exit();
      }//END if - status link button was clicked
    }//END if - a row in team2's area was clicked
    
  } else { //if form hasn't been printed yet
    //print the table containing the leagues, teams, and league status
    print'
    <div class ="currentLeagues">
    <h1 class = "clTitle">My Leagues</h1>
    <table class ="myLeagues">
      <tr class = "categoriesHeader">
        <th >League Name</th>
        <th >Team Name</th>
        <th >Status</th>
      </tr>';
    //include all necessary files
    require_once "UserDBHandler.php";
    $userHomeHandler = new userHandler($pdo); //create an instance of the user DB handler class
    $currentUserID = $_SESSION['userName']; //get the userID for the current session
    //FIRST get the leagues where the user is owner1
    //set the label and link for the league Status
    $leagueData = $userHomeHandler->userHomeInfo1($currentUserID); //get the league status from the leagueinfo db table
    foreach ($leagueData as $row) {
      $printStatus = $row['leagueStatus'];
      if ($printStatus == 0) {//Before second player has joined the league
        $printStatus = "Waiting for Opponent";
        $statusLink = "UserHome.php";
      } else if (($printStatus > 0) && ($printStatus < 17)) {//during the draft
        $printStatus = "Draft";
        $statusLink = "Draft.php";
      } else if ($printStatus > 16) {//after draft is finished, a quick match game is available
        $printStatus = "Quick Match";
        $statusLink = "QuickMatch.php";
      }
      //print the leagues where the user is owner1
      print ' 
        <tr class = "data" id = "data">
          <form action="UserHome.php" method="post" id="uHomeForm">
            <input type="hidden" name = "dataNameDefault1" class = "dataNameDefault1" id = "dataNameDefault1" value = "' . $row['leagueName'] . '" />
            <input type="hidden" name = "dataTeamDefault1" class = "dataTeamDefault1" id = "dataTeamDefault1" value = "' . $row['team1'] . '" />
            <input type="hidden" name = "dataStatusDefault1" class = "dataStatusDefault1" id = "dataStatusDefault1" value = "' . $printStatus . '" />
            <input type="hidden" name = "dataLinkDefault1" class = "dataLinkDefault1" id = "dataLinkDefault1" value = "' . $statusLink . '" />
            <td class = "leagueData" id = "leagueData3"><input type = "submit" name = "dataName1" class = "dataName1" id = "dataName1" value = "' . $row['leagueName'] . '"></td>
            <td class = "leagueData" id = "leagueData3"><input type = "submit" name = "dataTeam1" class = "dataTeam1" id = "dataTeam1" value = "' . $row['team1'] . '"></td>
            <td class = "leagueData" id = "leagueData3"><input type = "submit" name = "dataStatus1" class = "dataStatus1" id = "dataStatus1" value = "' . $printStatus . '"></td>    
          </form>
        </tr>
      ';
    }//END $leagueData foreach
    //
    //SECOND get the leagues where the user is owner2
    //set the label and link for the league Status
    $leagueData2 = $userHomeHandler->userHomeInfo2($currentUserID); //get the league status from the leagueinfo db table
    foreach ($leagueData2 as $row2) {
      $printStatus = $row2['leagueStatus'];
      if ($printStatus == 0) {//Before second player has joined the league
        $printStatus = "Waiting for Opponent";
        $statusLink = "UserHome.php";
      } else if (($printStatus > 0) && ($printStatus < 17)) {//during the draft
        $printStatus = "Draft";
        $statusLink = "Draft.php";
      } else if ($printStatus > 16) {//after draft is finished, a quick match game is available
        $printStatus = "Quick Match";
        $statusLink = "QuickMatch.php";
      }
      print '
        <tr class = "data" id = "data">
          <form action="UserHome.php" method="post" id="uHomeForm">
            <input type="hidden" name = "dataNameDefault2" class = "dataNameDefault2" id = "dataNameDefault2" value = "' . $row2['leagueName'] . '" />
            <input type="hidden" name = "dataTeamDefault2" class = "dataTeamDefault2" id = "dataTeamDefault2" value = "' . $row2['team2'] . '" />
            <input type="hidden" name = "dataStatusDefault2" class = "dataStatusDefault2" id = "dataStatusDefault2" value = "' . $printStatus . '" />
            <input type="hidden" name = "dataLinkDefault2" class = "dataLinkDefault2" id = "dataLinkDefault2" value = "' . $statusLink . '" />
            <td class = "leagueData" id = "leagueData3"><input type = "submit" name = "dataName2" class = "dataName2" id = "dataName2" value = "' . $row2['leagueName'] . '"></td>
            <td class = "leagueData" id = "leagueData3"><input type = "submit" name = "dataTeam2" class = "dataTeam2" id = "dataTeam2" value = "' . $row2['team2'] . '"></td>
            <td class = "leagueData" id = "leagueData3"><input type = "submit" name = "dataStatus2" class = "dataStatus2" id = "dataStatus2" value = "' . $printStatus . '"></td>
          </form>
        </tr>
      ';
    } //END $leagueData2 foreach
    print '
      <tr class = "mlSpacer"><td colspan = "3"><td></tr>
    </table>
  </div>';
  }//END if form hasn't been printed
  ?> <!--END of league form-->

  <!--Bottom of card, contains buttons for joining or creating a league-->
  <div class ="leagueButtons">
  <a href = "CreateLeague.php"><div class = "createLeague" id = "createLeague">Create a League</div></a>
  <div class ="leagueSpacer"></div>
  <a href = "JoinLeague.php"><div class = "joinLeague" id = "joinLeague">Join a League</div></a>
</div>
  </div><!-- End userCard div  -->
<?php
//
//-----END Changeable Content-----
//
//include the footer
include('templates/Footer.php'); // Include the footer.
?>
