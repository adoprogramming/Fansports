<?php

class userHandler
{
    protected $dbinstance;


    public function __construct(PDO $dbinstance) // passed a pdo DB file to construct the object
    {
        $this->dbinstance = $dbinstance;  //each object's functions should apply to that object and not others
    }

    
  /* *****REGISTER******/
    public function registerUser($userName,$hashedPass)
    {
        $stmt = $this->dbinstance->prepare("INSERT INTO userinfo2(userName,userPass)VALUES(:userName,:hashedPass)");
        $stmt->bindValue(':userName',$userName,PDO::PARAM_STR);
        $stmt->bindValue(':hashedPass',$hashedPass,PDO::PARAM_STR);
        $stmt->execute();
    }
    /* *****LOGIN******/
    public function checkLogin($userNameInput)
    {


        $query = $this->dbinstance->prepare("SELECT userName FROM userinfo2 WHERE userName=:userNameInput"); //build the query
        $query->bindValue(':userNameInput', $userNameInput, PDO::PARAM_STR); //bind the value to a string to help protect against SQL injection
        $query->execute(); //execute query
        $results = $query->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
        return $results; //return result from the query

    }

    public function checkPass($userNameInput)
    {


        $query = $this->dbinstance->prepare("SELECT userPass FROM userinfo2 WHERE userName=:userNameInput"); //build the query
        $query->bindValue(':userNameInput', $userNameInput, PDO::PARAM_STR); //bind the value to a string to help protect against SQL injection
        $query->execute(); //execute query
        $results = $query->fetch(PDO::FETCH_ASSOC); //get resulting matches
        return $results; //return result from the query

    }
    /* *****CREATE LEAGUE******/
    public function registerLeague($leagueName,$hashedPass,$teamName,$userID, $leagueStatus)
    {
        $stmt = $this->dbinstance->prepare("INSERT INTO leagueinfo2(leagueName,leaguePass,team1,owner1,leagueStatus)VALUES(:leagueName,:hashedPass,:teamName,:userID,:leagueStatus)");
        $stmt->bindValue(':leagueName',$leagueName,PDO::PARAM_STR);
        $stmt->bindValue(':hashedPass',$hashedPass,PDO::PARAM_STR);
        $stmt->bindValue(':teamName',$teamName,PDO::PARAM_STR);
        $stmt->bindValue(':userID',$userID,PDO::PARAM_STR);
        $stmt->bindValue(':leagueStatus',$leagueStatus,PDO::PARAM_STR);
        $stmt->execute();
    }
    public function registerTeam1($leagueName, $teamName,$userID)
    {
        $stmt = $this->dbinstance->prepare("INSERT INTO team1(leagueName,teamName,ownerName)VALUES(:leagueName,:teamName,:userID)");
        $stmt->bindValue(':teamName',$teamName,PDO::PARAM_STR);
        $stmt->bindValue(':userID',$userID,PDO::PARAM_STR);
        $stmt->bindValue(':leagueName',$leagueName,PDO::PARAM_STR);
        $stmt->execute();

    }
    /* *****JOIN LEAGUE******/
    public function checkLeague($leagueNameInput)
    {


        $query = $this->dbinstance->prepare("SELECT leagueName FROM leagueinfo2 WHERE leagueName=:leagueNameInput"); //build the query
        $query->bindValue(':leagueNameInput', $leagueNameInput, PDO::PARAM_STR); //bind the value to a string to help protect against SQL injection
        $query->execute(); //execute query
        $results = $query->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
        return $results; //return result from the query

    }

    public function checkLeaguePass($leagueNameInput)
    {
        $query = $this->dbinstance->prepare("SELECT leaguePass FROM leagueinfo2 WHERE leagueName=:leagueNameInput"); //build the query
        $query->bindValue(':leagueNameInput', $leagueNameInput, PDO::PARAM_STR); //bind the value to a string to help protect against SQL injection
        $query->execute(); //execute query
        $results = $query->fetch(PDO::FETCH_ASSOC); //get resulting matches
        return $results; //return result from the query

    }
    
    public function registerTeam2($leagueName, $teamName,$userID)
    {
        $stmt = $this->dbinstance->prepare("INSERT INTO team2(leagueName,teamName,ownerName)VALUES(:leagueName,:teamName,:userID)");
        $stmt->bindValue(':teamName',$teamName,PDO::PARAM_STR);
        $stmt->bindValue(':userID',$userID,PDO::PARAM_STR);
        $stmt->bindValue(':leagueName',$leagueName,PDO::PARAM_STR);
        $stmt->execute();

    }
     public function updateLeagueforT2($leagueName, $leagueStatus, $team2Name, $team2owner)
    {
        $stmt = $this->dbinstance->prepare("UPDATE leagueinfo2 SET leagueStatus=:leagueStatus,team2=:team2Name,owner2=:team2owner WHERE leagueName=:leagueName");
        $stmt->bindValue(':leagueStatus',$leagueStatus,PDO::PARAM_STR);
        $stmt->bindValue(':leagueName',$leagueName,PDO::PARAM_STR);
        $stmt->bindValue(':team2Name',$team2Name,PDO::PARAM_STR);
        $stmt->bindValue(':team2owner',$team2owner,PDO::PARAM_STR);
        $stmt->execute();

    }
    public function updateStatus($leagueName, $leagueStatus)
    {
        $stmt = $this->dbinstance->prepare("UPDATE leagueinfo2 SET leagueStatus=:leagueStatus WHERE leagueName=:leagueName");
        $stmt->bindValue(':leagueStatus',$leagueStatus,PDO::PARAM_STR);
        $stmt->bindValue(':leagueName',$leagueName,PDO::PARAM_STR);
        $stmt->execute();

    }
    /* *****USER HOME******/
    public function userHomeInfo1($userID)//for when you are owner1 - the person who created the league
    {

        $query = $this->dbinstance->prepare("SELECT leagueName, team1, leagueStatus FROM leagueinfo2 WHERE owner1=:userID"); //build the query
        $query->bindValue(':userID', $userID, PDO::PARAM_STR); //bind the value to a string to help protect against SQL injection
        $query->execute(); //execute query
        $results = $query->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
        return $results; //return result from the query

    }
    public function userHomeInfo2($userID)//for when you are owner 2 - hte person who joined the league
    {

        $query = $this->dbinstance->prepare("SELECT leagueName, team2, leagueStatus FROM leagueinfo2 WHERE owner2=:userID"); //build the query
        $query->bindValue(':userID', $userID, PDO::PARAM_STR); //bind the value to a string to help protect against SQL injection
        $query->execute(); //execute query
        $results = $query->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
        return $results; //return result from the query

    }
    /* *****LEAGUE HOME******/
    public function userleagueInfo($userID)
    {

        $query = $this->dbinstance->prepare("SELECT leagueName, leagueStatus FROM leagueinfo2 WHERE owner1=:userID"); //build the query
        $query->bindValue(':userID', $userID, PDO::PARAM_STR); //bind the value to a string to help protect against SQL injection
        $query->execute(); //execute query
        $results = $query->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
        return $results; //return result from the query
    }
    public function userteamInfo($userID)
    {

        $query = $this->dbinstance->prepare("SELECT QB, RB1, RB2, WR1, WR2, TE, K, DEF, bench1, bench2, bench3, bench4, bench5, bench6 FROM team1 WHERE ownerName=:userID"); //build the query
        $query->bindValue(':userID', $userID, PDO::PARAM_STR); //bind the value to a string to help protect against SQL injection
        $query->execute(); //execute query
        $results = $query->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
        return $results; //return result from the query
    }
    public function userteamInfo2($leagueName)
    {

        $query = $this->dbinstance->prepare("SELECT QB, RB1, RB2, WR1, WR2, TE, K, DEF, bench1, bench2, bench3, bench4, bench5, bench6 FROM team2 WHERE leagueName=:leagueName"); //build the query
        $query->bindValue(':leagueName', $leagueName, PDO::PARAM_STR); //bind the value to a string to help protect against SQL injection
        $query->execute(); //execute query
        $results = $query->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
        return $results; //return result from the query
    }
    
    /* *****DRAFT******/
    
}
?>