<?php
session_start();
ob_start();


//error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);

include_once "UserDBConnection.php";
require_once "UserDBHandler.php";
$leagueInfoHandler = new userHandler($pdo);
$rosterID = $_GET['rosterID'];

if(isset( $_SESSION['userName'] ))
{
    if(isset($_POST['startingRoster']))
    {
        $postedValues = array($_POST['Player1'], $_POST['Player2'], $_POST['Player3'], $_POST['Player4'] ,$_POST['Player5'], $_POST['Player6'], $_POST['Player7'], $_POST['Player8'], $_POST['Player9'], $_POST['Player10'], $_POST['Player12'], $_POST['Player13'] ,$_POST['Player14']);
        $selectedPlayers = array_filter($postedValues);

        $leagueInfoHandler->setStartingPlayers($rosterID,$selectedPlayers[0], $selectedPlayers[1], $selectedPlayers[2], $selectedPlayers[3], $selectedPlayers[4],$selectedPlayers[5],$selectedPlayers[6]);

    }
    else
    {
        header('Location: LeagueHome.php'); //roster not set. redirect to league home.
    }
}
else
{
    header('Location: Login.php'); //not logged in. redirect to login.
}