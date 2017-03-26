<?php
ob_start();
define('TITLE', 'Quick Match Scoring');
define('CSS', 'Quick');
include('templates/Header.php');  // Include the header.
include_once "NFLDBConnection.php";
include_once "UserDBConnection.php";
require_once "RosterHandler.php";
require_once "ScoringHandlerRev1.php";
require_once "UserDBHandler.php";

try {
  $rosterPDO = new PDO("mysql:host=localhost; dbname=userdb; charset=utf8mb4", $usrDBName, $usrDBPass); //connect to userdb
} catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}
try {
  $scoringPDO = new PDO("mysql:host=localhost; dbname=userdb; charset=utf8mb4", $usrDBName, $usrDBPass); //connect to userdb
} catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}
$rosterHandler = new rosterHandler($rosterPDO, $pgsqlpdo);
$draftHandler = new userHandler($pdo);
$scoreHandler = new scoringHandler($scoringPDO, $pgsqlpdo);


$currentUserID = $_SESSION['userName'];
$currentLeagueName = $_SESSION['leagueName'];
$playerCounter = 1;
$scoreTotal1 = 0;
$scoreTotal2 = 0;

$printErrorMsg = "&nbsp;";
$ownerNum = $_SESSION['ownerNum'];
?>

<!--DIV containing both teams calculations-->
<h1 class ="matchTitle">Quick Match!</h1>
<div class ="bothTeams"><!--DIV containing both teams-->

      <!--<h1 align = "center" class = "teamTitle" ><?php print_r($team1Name) ?></h1>-->
  <table class ="resultsTeam1">
    <tr class = "header">
      <th>Roster</th>
      <th>Player Name</th>
      <th>Score</th>
    </tr>
<!--        <tr>
      <td class = "scoreData"></td>
      <td class = "scoreData"></td>
      <td class = "scoreData"></td>
    </tr>-->
<?php
$teamData1 = $draftHandler->currentTeam1Info($currentLeagueName);
foreach ($teamData1 as $row2) {
  $playerName = $row2['QB'];
  $findPlayerID = $rosterHandler->getPlayerID($playerName);
  foreach ($findPlayerID as $playerID) {
//          print 'Player ID: '.$playerID['player_id'].' ! ';
    $scoreHandler->getCurrentGameID($playerID['player_id']);
    $scoreData = $scoreHandler->getSeasonPlayerStats($playerID['player_id']);
    $scoreDataCalc = $draftHandler->calcPlayerScore($scoreData);
//    $scoreDataCalc = number_format($scoreDataCalc,2);
    print '<tr><td class = "rosterName">QB</td><td class = "name">' . $playerName . '</td><td class = "score">' . number_format($scoreDataCalc,2) . '</td></tr>';
    $scoreTotal1 = $scoreTotal1 + $scoreDataCalc;
  }

  $playerName = $row2['RB1'];
  $findPlayerID = $rosterHandler->getPlayerID($playerName);
  foreach ($findPlayerID as $playerID) {
//          print 'Player ID: '.$playerID['player_id'].' ! ';
    $scoreHandler->getCurrentGameID($playerID['player_id']);
    $scoreData = $scoreHandler->getSeasonPlayerStats($playerID['player_id']);
    $scoreDataCalc = $draftHandler->calcPlayerScore($scoreData);
    print '<tr><td class = "rosterName">RB1</td><td class = "name">' . $playerName . '</td><td class = "score">' . number_format($scoreDataCalc,2) . '</td></tr>';
    $scoreTotal1 = $scoreTotal1 + $scoreDataCalc;
  }

  $playerName = $row2['RB2'];
  $findPlayerID = $rosterHandler->getPlayerID($playerName);
  foreach ($findPlayerID as $playerID) {
//          print 'Player ID: '.$playerID['player_id'].' ! ';
    $scoreHandler->getCurrentGameID($playerID['player_id']);
    $scoreData = $scoreHandler->getSeasonPlayerStats($playerID['player_id']);
    $scoreDataCalc = $draftHandler->calcPlayerScore($scoreData);
    print '<tr><td class = "rosterName">RB2</td><td class = "name">' . $playerName . '</td><td class = "score">' . number_format($scoreDataCalc,2) . '</td></tr>';
    $scoreTotal1 = $scoreTotal1 + $scoreDataCalc;
  }

  $playerName = $row2['WR1'];
  $findPlayerID = $rosterHandler->getPlayerID($playerName);
  foreach ($findPlayerID as $playerID) {
//          print 'Player ID: '.$playerID['player_id'].' ! ';
    $scoreHandler->getCurrentGameID($playerID['player_id']);
    $scoreData = $scoreHandler->getSeasonPlayerStats($playerID['player_id']);
    $scoreDataCalc = $draftHandler->calcPlayerScore($scoreData);
    print '<tr><td class = "rosterName">WR1</td><td class = "name">' . $playerName . '</td><td class = "score">' . number_format($scoreDataCalc,2) . '</td></tr>';
    $scoreTotal1 = $scoreTotal1 + $scoreDataCalc;
  }

  $playerName = $row2['WR2'];
  $findPlayerID = $rosterHandler->getPlayerID($playerName);
  foreach ($findPlayerID as $playerID) {
//          print 'Player ID: '.$playerID['player_id'].' ! ';
    $scoreHandler->getCurrentGameID($playerID['player_id']);
    $scoreData = $scoreHandler->getSeasonPlayerStats($playerID['player_id']);
    $scoreDataCalc = $draftHandler->calcPlayerScore($scoreData);
    print '<tr><td class = "rosterName">WR2</td><td class = "name">' . $playerName . '</td><td class = "score">' . number_format($scoreDataCalc,2) . '</td></tr>';
    $scoreTotal1 = $scoreTotal1 + $scoreDataCalc;
  }

  $playerName = $row2['TE'];
  $findPlayerID = $rosterHandler->getPlayerID($playerName);
  foreach ($findPlayerID as $playerID) {
//          print 'Player ID: '.$playerID['player_id'].' ! ';
    $scoreHandler->getCurrentGameID($playerID['player_id']);
    $scoreData = $scoreHandler->getSeasonPlayerStats($playerID['player_id']);
    $scoreDataCalc = $draftHandler->calcPlayerScore($scoreData);
//          print 'Score Data: '.$scoreData.'!';
    print '<tr><td class = "rosterName">TE</td><td class = "name">' . $playerName . '</td><td class = "score">' . number_format($scoreDataCalc,2) . '</td></tr>';
    $scoreTotal1 = $scoreTotal1 + $scoreDataCalc;
  }

  $playerName = $row2['K'];
  $findPlayerID = $rosterHandler->getPlayerID($playerName);
  foreach ($findPlayerID as $playerID) {
//          print 'Player ID: '.$playerID['player_id'].' ! ';
    $scoreHandler->getCurrentGameID($playerID['player_id']);
    $scoreDataK = $scoreHandler->getKickerScore($playerID['player_id']);
    $scoreDataCalcK = $draftHandler->calcPlayerScore($scoreDataK);
    $scoreDataCalcK = $scoreDataCalcK/13;
    print '<tr><td class = "rosterName">K</td><td class = "name">' . $playerName . '</td><td class = "score">' . number_format($scoreDataCalcK,2) . '</td></tr>';
    $scoreTotal1 = $scoreTotal1 + $scoreDataCalcK;
  }

  $playerName = $row2['DEF'];
  $scoreData = $draftHandler->getDEFScore($playerName);
//        print_r($scoreData);
  foreach ($scoreData as $rowScore1) {
    foreach ($rowScore1 as $rowScore11) {
      $scoreDataCalc = $draftHandler->calcPlayerScore($rowScore11);
      print '<tr><td class = "rosterName">DEF</td><td class = "name">' . $playerName . '</td><td class = "score">' . number_format($scoreDataCalc,2) . '</td></tr>';
      $scoreTotal1 = $scoreTotal1 + $scoreDataCalc;
    }
  }
  print '<tr><td class = "scoreTotal" colspan = "2">ScoreTotal: </td><td class = "score">' . number_format($scoreTotal1,2) . '</td></tr>';
}// end record loop
?>

  </table><!-- END draftTeam1 table-->
  <div class ="scoreSpacer"></div>

  <table class ="resultsTeam2">
    <tr class = "header">
      <th>Position</th>
      <th>Player Name</th>
      <th>Score</th>
    </tr>

<?php
$teamData2 = $draftHandler->currentTeam2Info($currentLeagueName);
// print_r($teamData1);
foreach ($teamData2 as $row3) {
  $playerName = $row3['QB'];
  $findPlayerID = $rosterHandler->getPlayerID($playerName);
  foreach ($findPlayerID as $playerID) {
//          print 'Player ID: '.$playerID['player_id'].' ! ';
    $scoreHandler->getCurrentGameID($playerID['player_id']);
    $scoreData = $scoreHandler->getSeasonPlayerStats($playerID['player_id']);
    $scoreDataCalc = $draftHandler->calcPlayerScore($scoreData);
    print '<tr><td class = "rosterName">QB</td><td class = "name">' . $playerName . '</td><td class = "score">' . number_format($scoreDataCalc,2) . '</td></tr>';
    $scoreTotal2 = $scoreTotal2 + $scoreDataCalc;
  }
  $playerName = $row3['RB1'];
  $findPlayerID = $rosterHandler->getPlayerID($playerName);
  foreach ($findPlayerID as $playerID) {
//          print 'Player ID: '.$playerID['player_id'].' ! ';
    $scoreHandler->getCurrentGameID($playerID['player_id']);
    $scoreData = $scoreHandler->getSeasonPlayerStats($playerID['player_id']);
    $scoreDataCalc = $draftHandler->calcPlayerScore($scoreData);
    print '<tr><td class = "rosterName">RB1</td><td class = "name">' . $playerName . '</td><td class = "score">' . number_format($scoreDataCalc,2) . '</td></tr>';
    $scoreTotal2 = $scoreTotal2 + $scoreDataCalc;
  }
  $playerName = $row3['RB2'];
  $findPlayerID = $rosterHandler->getPlayerID($playerName);
  foreach ($findPlayerID as $playerID) {
//          print 'Player ID: '.$playerID['player_id'].' ! ';
    $scoreHandler->getCurrentGameID($playerID['player_id']);
    $scoreData = $scoreHandler->getSeasonPlayerStats($playerID['player_id']);
    $scoreDataCalc = $draftHandler->calcPlayerScore($scoreData);
    print '<tr><td class = "rosterName">RB2</td><td class = "name">' . $playerName . '</td><td class = "score">' . number_format($scoreDataCalc,2) . '</td></tr>';
    $scoreTotal2 = $scoreTotal2 + $scoreDataCalc;
  }
  $playerName = $row3['WR1'];
  $findPlayerID = $rosterHandler->getPlayerID($playerName);
  foreach ($findPlayerID as $playerID) {
//          print 'Player ID: '.$playerID['player_id'].' ! ';
    $scoreHandler->getCurrentGameID($playerID['player_id']);
    $scoreData = $scoreHandler->getSeasonPlayerStats($playerID['player_id']);
    $scoreDataCalc = $draftHandler->calcPlayerScore($scoreData);
    print '<tr><td class = "rosterName">WR1</td><td class = "name">' . $playerName . '</td><td class = "score">' . number_format($scoreDataCalc,2) . '</td></tr>';
    $scoreTotal2 = $scoreTotal2 + $scoreDataCalc;
  }
  $playerName = $row3['WR2'];
  $findPlayerID = $rosterHandler->getPlayerID($playerName);
  foreach ($findPlayerID as $playerID) {
//          print 'Player ID: '.$playerID['player_id'].' ! ';
    $scoreHandler->getCurrentGameID($playerID['player_id']);
    $scoreData = $scoreHandler->getSeasonPlayerStats($playerID['player_id']);
    $scoreDataCalc = $draftHandler->calcPlayerScore($scoreData);
    print '<tr><td class = "rosterName">WR2</td><td class = "name">' . $playerName . '</td><td class = "score">' . number_format($scoreDataCalc,2) . '</td></tr>';
    $scoreTotal2 = $scoreTotal2 + $scoreDataCalc;
  }
  $playerName = $row3['TE'];
  $findPlayerID = $rosterHandler->getPlayerID($playerName);
  foreach ($findPlayerID as $playerID) {
//          print 'Player ID: '.$playerID['player_id'].' ! ';
    $scoreHandler->getCurrentGameID($playerID['player_id']);
    $scoreData = $scoreHandler->getSeasonPlayerStats($playerID['player_id']);
    $scoreDataCalc = $draftHandler->calcPlayerScore($scoreData);
    print '<tr><td class = "rosterName">TE</td><td class = "name">' . $playerName . '</td><td class = "score">' . number_format($scoreDataCalc,2) . '</td></tr>';
    $scoreTotal2 = $scoreTotal2 + $scoreDataCalc;
  }
  $playerName = $row3['K'];
  $findPlayerID = $rosterHandler->getPlayerID($playerName);
  foreach ($findPlayerID as $playerID) {
//          print 'Player ID: '.$playerID['player_id'].' ! ';
    $scoreHandler->getCurrentGameID($playerID['player_id']);
    $scoreDataK = $scoreHandler->getKickerScore($playerID['player_id']);
    $scoreDataCalcK = $draftHandler->calcPlayerScore($scoreDataK);
    $scoreDataCalcK = $scoreDataCalcK/13;
    print '<tr><td class = "rosterName">K</td><td class = "name">' . $playerName . '</td><td class = "score">' . number_format($scoreDataCalcK,2) . '</td></tr>';
    $scoreTotal2 = $scoreTotal2 + $scoreDataCalcK;
  }
  $playerName = $row3['DEF'];
  $scoreData2 = $draftHandler->getDEFScore($playerName);
//        print_r($scoreData2);
  foreach ($scoreData2 as $rowScore2) {
    foreach ($rowScore2 as $rowScore22) {
      $scoreDataCalc = $draftHandler->calcPlayerScore($rowScore22);
      print '<tr><td class = "rosterName">DEF</td><td class = "name">' . $playerName . '</td><td class = "score">' . number_format($scoreDataCalc,2) . '</td></tr>';
      $scoreTotal2 = $scoreTotal2 + $scoreDataCalc;
    }
  }
  print '<tr><td class = "scoreTotal" colspan = "2">ScoreTotal: </td><td class = "score">' . number_format($scoreTotal2,2) . '</td></tr>';
}// end record loop
?>
  </table><!-- END resultsTeam1 table-->
</div><!-- END bothTeams div-->
<div class ="winnerInfo">
  <?php
  if ($ownerNum == "1") {//owner number one
    if ($scoreTotal1 > $scoreTotal2){
      print '<p>You are the winner!</p>';
    }else if ($scoreTotal2 > $scoreTotal1){
      print '<p>You are the loser!</p>';
    }
  }else if ($ownerNum == "2"){//owner number two
    if ($scoreTotal2 > $scoreTotal1){
      print '<p>You are the winner!</p>';
    }else if ($scoreTotal1 > $scoreTotal2){
      print '<p>You are the loser!</p>';
    }
  }
  ?>
 
</div>

<!-- END CHANGEABLE CONTENT. -->

<?php
// Return to PHP.
include('templates/Footer.php'); // Include the footer.
?>