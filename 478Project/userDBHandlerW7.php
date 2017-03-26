<?php

class userHandler
{
    protected $dbinstance;



    public function __construct(PDO $dbinstance) // passed a pdo DB file to construct the object
    {
        $this->dbinstance = $dbinstance;  //each object's functions should apply to that object and not others
    }

    public function checkLogin($userNameInput)
    {


        $query = $this->dbinstance->prepare("SELECT userName FROM userinfo WHERE userName=:userNameInput"); //build the query
        $query->bindValue(':userNameInput', $userNameInput, PDO::PARAM_STR); //bind the value to a string to help protect against SQL injection
        $query->execute(); //execute query
        $results = $query->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
        return $results; //return result from the query

    }

    public function checkPass($userNameInput)
    {


        $query = $this->dbinstance->prepare("SELECT userPass FROM userinfo WHERE userName=:userNameInput"); //build the query
        $query->bindValue(':userNameInput', $userNameInput, PDO::PARAM_STR); //bind the value to a string to help protect against SQL injection
        $query->execute(); //execute query
        $results = $query->fetch(PDO::FETCH_ASSOC); //get resulting matches
        return $results; //return result from the query

    }

    public function checkLeaguePassID($leagueID)
    {


        $query = $this->dbinstance->prepare("SELECT leaguePass FROM leagueinfo WHERE leagueID=:leagueID"); //build the query
        $query->bindValue(':leagueID', $leagueID, PDO::PARAM_INT); //bind the value to a string to help protect against SQL injection
        $query->execute(); //execute query
        $results = $query->fetch(PDO::FETCH_ASSOC); //get resulting matches
        return $results; //return result from the query

    }

    public function checkLeaguePassName($leagueName)
    {


        $query = $this->dbinstance->prepare("SELECT leaguePass FROM leagueinfo WHERE leagueName=:leagueName"); //build the query
        $query->bindValue(':leagueName', $leagueName, PDO::PARAM_INT); //bind the value to a string to help protect against SQL injection
        $query->execute(); //execute query
        $results = $query->fetch(PDO::FETCH_ASSOC); //get resulting matches
        return $results; //return result from the query

    }

    public function registerUser($userName,$hashedPass)
    {
        $stmt = $this->dbinstance->prepare("INSERT INTO userinfo(userName,userPass)VALUES(:userName,:hashedPass)");
        $stmt->bindValue(':userName',$userName,PDO::PARAM_STR);
        $stmt->bindValue(':hashedPass',$hashedPass,PDO::PARAM_STR);
        $stmt->execute();



    }

    public function checkLeagueIDExists($leagueID)
    {
        $checkLeagueIDLogin = $this->dbinstance->prepare("SELECT leagueID FROM leagueinfo WHERE leagueID=:leagueID"); //build the query
        $checkLeagueIDLogin->bindValue(':leagueID', $leagueID, PDO::PARAM_INT); //bind the value to a string to help protect against SQL injection

        $checkLeagueIDLogin->execute(); //execute query
        $results = $checkLeagueIDLogin->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
        return $results; //return result from the query
    }

    public function checkLeagueNameExists($leagueName)
    {
        $checkLeagueIDLogin = $this->dbinstance->prepare("SELECT leagueName FROM leagueinfo WHERE leagueName=:leagueName"); //build the query
        $checkLeagueIDLogin->bindValue(':leagueName', $leagueName, PDO::PARAM_STR); //bind the value to a string to help protect against SQL injection

        $checkLeagueIDLogin->execute(); //execute query
        $results = $checkLeagueIDLogin->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
        return $results; //return result from the query
    }

    public function joinLeague($rosterID, $leagueID)
    {
        $stmt = $this->dbinstance->prepare("INSERT INTO leaguerosters(rosterID,leagueID)VALUES(:rosterID,:leagueID)");
        $stmt->bindValue(':rosterID',$rosterID,PDO::PARAM_INT);
        $stmt->bindValue(':leagueID',$leagueID,PDO::PARAM_INT);
        $stmt->execute();
		
		$stmt2 = $this->dbinstance->prepare("INSERT INTO startingrosters(rosterID)VALUES(:rosterID)");
        $stmt2->bindValue(':rosterID',$leagueID,PDO::PARAM_INT);
        $stmt2->execute();
		
		
    }

    public function getUserIDs() //return all userIDs, this will likely be changed as we add more users and more than one league
    {


        $userIDList = $this->dbinstance->prepare("SELECT userID FROM userinfo"); //build the query
        $userIDList->execute(); //execute query
        $results = $userIDList->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
        return $results; //return result from the query

    }

    public function getRostersInLeague($leagueID) //return all rosters in a given league
    {


        $rostersInGivenLeague = $this->dbinstance->prepare("SELECT userName, userinfo.userID, teamName, rosterinfo.rosterID from userinfo, rosterinfo, leagueinfo, leaguerosters WHERE (leagueinfo.leagueID = :leagueID) AND (leagueinfo.leagueID = leaguerosters.leagueID) AND (rosterinfo.rosterID = leaguerosters.rosterID) AND (rosterinfo.userID = userinfo.userID)"); //build the query
        $rostersInGivenLeague->bindValue(':leagueID',$leagueID,PDO::PARAM_INT);
        $rostersInGivenLeague->execute(); //execute query
        $results = $rostersInGivenLeague->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
        return $results; //return result from the query

    }
    public function getLeagueName($leagueID) //return a league's name given it's ID
    {


        $leagueName = $this->dbinstance->prepare("SELECT leagueName FROM leagueinfo WHERE leagueID =:leagueID"); //build the query
        $leagueName->bindValue(':leagueID',$leagueID,PDO::PARAM_INT);
        $leagueName->execute(); //execute query
        $results = $leagueName->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
        return $results[0]['leagueName']; //return result from the query

    }

    public function getLeagueID($leagueName) //return a league's name given it's ID
    {


        $leagueID = $this->dbinstance->prepare("SELECT leagueID FROM leagueinfo WHERE leagueName =:leagueName"); //build the query
        $leagueID->bindValue(':leagueName',$leagueName,PDO::PARAM_STR);
        $leagueID->execute(); //execute query
        $results = $leagueID->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
        return $results[0]['leagueID']; //return result from the query

    }


    public function getSingleUserID($userName) //return one specific userID
    {


        $userIDQuery = $this->dbinstance->prepare("SELECT userID FROM userinfo WHERE userName=:userName"); //build the query
        $userIDQuery->bindValue(':userName',$userName,PDO::PARAM_STR);
        $userIDQuery->execute(); //execute query
        $results = $userIDQuery->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
        return $results[0]['userID']; //return result from the query

    }

    public function getUserName($userID) //gets username from userID parameter
    {


        $userFromID = $this->dbinstance->prepare("SELECT userName FROM userinfo WHERE userID=:userID"); //build the query
        $userFromID->bindValue(':userID', $userID, PDO::PARAM_INT); //bind the value to a string to help protect against SQL injection
        $userFromID->execute(); //execute query
        $results = $userFromID->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
        return $results; //return result from the query
    }

    public function getLeagueStatus($leagueID)
    {
        $draftStatusQuery = $this->dbinstance->prepare("SELECT leagueStatus FROM leagueinfo WHERE leagueID=:leagueID"); //build the query
        $draftStatusQuery->bindValue(':leagueID', $leagueID, PDO::PARAM_INT); //bind the value to a string to help protect against SQL injection
        $draftStatusQuery->execute(); //execute query
        $results = $draftStatusQuery->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
        return $results; //return result from the query

    }

    public function getDraftStatus($userID)
    {
        $draftStatusQuery = $this->dbinstance->prepare("SELECT draftStatus FROM userinfo WHERE userID=:userID"); //build the query
        $draftStatusQuery->bindValue(':userID', $userID, PDO::PARAM_INT); //bind the value to a string to help protect against SQL injection
        $draftStatusQuery->execute(); //execute query
        $results = $draftStatusQuery->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
        return $results; //return result from the query

    }

    public function setDraftStatus($userID,$draftStatus) //set draftStatus to 1(TRUE) if it's their turn and 0 (FALSE) if it's not.
    {
        $updateDraftStatus = $this->dbinstance->prepare("UPDATE userinfo SET draftStatus=:draftStatus WHERE userID=:userID"); //build the query
        $updateDraftStatus->bindValue(':userID', $userID, PDO::PARAM_INT); //bind the value to a string to help protect against SQL injection
        $updateDraftStatus->bindValue(':draftStatus', $draftStatus, PDO::PARAM_BOOL); //bind the value to a string to help protect against SQL injection
        $updateDraftStatus->execute(); //execute query


    }

    public function getAvailableSlots($userID, $rosterID) //gets number of open roster spots, adjust for DST when that's figured out. For now leaving out. Used mainly for drafting to determine when it is complete but helpful.
    {
        $rosterQuery = $this->dbinstance->prepare("SELECT QB,RB1,RB2,WR1,WR2,TE,K,DEF,bench1,bench2,bench3,bench4,bench5,bench6,
                                                        CASE WHEN QB IS NOT NULL THEN 0 ELSE 1 END + 
                                                        CASE WHEN RB1 IS NOT NULL THEN 0 ELSE 1 END +
                                                        CASE WHEN RB2 IS NOT NULL THEN 0 ELSE 1 END + 
                                                        CASE WHEN WR1 IS NOT NULL THEN 0 ELSE 1 END +
                                                        CASE WHEN WR2 IS NOT NULL THEN 0 ELSE 1 END +
                                                        CASE WHEN TE IS NOT NULL THEN 0 ELSE 1 END + 
                                                        CASE WHEN K IS NOT NULL THEN 0 ELSE 1 END + 
                                                        CASE WHEN bench1 IS NOT NULL THEN 0 ELSE 1 END +
                                                        CASE WHEN bench2 IS NOT NULL THEN 0 ELSE 1 END + 
                                                        CASE WHEN bench3 IS NOT NULL THEN 0 ELSE 1 END +
                                                        CASE WHEN bench4 IS NOT NULL THEN 0 ELSE 1 END +
                                                        CASE WHEN bench5 IS NOT NULL THEN 0 ELSE 1 END + 
                                                        CASE WHEN bench6 IS NOT NULL THEN 0 ELSE 2 END AS numSlots
														FROM rosterinfo WHERE userID=:userID AND rosterID=:rosterID"); //last line is 2 instead of 1 because DST isn't in yet. should give an even number so that last drafter as determined by order is indeed last so logic holds in program.
        $rosterQuery->bindValue(':userID', $userID, PDO::PARAM_INT);
		$rosterQuery->bindValue(':rosterID', $rosterID, PDO::PARAM_INT);
        $rosterQuery->execute(); //execute query
        $results = $rosterQuery->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
        return $results[0]['numSlots']; //return result from the query
    }
	
	public function getAvailableBench($userID, $rosterID)
	{
		$rosterQuery = $this->dbinstance->prepare("SELECT bench1,bench2,bench3,bench4,bench5,bench6,
														CASE WHEN bench1 IS NOT NULL THEN 0 ELSE 1 END +
                                                        CASE WHEN bench2 IS NOT NULL THEN 0 ELSE 1 END + 
                                                        CASE WHEN bench3 IS NOT NULL THEN 0 ELSE 1 END +
                                                        CASE WHEN bench4 IS NOT NULL THEN 0 ELSE 1 END +
                                                        CASE WHEN bench5 IS NOT NULL THEN 0 ELSE 1 END + 
                                                        CASE WHEN bench6 IS NOT NULL THEN 0 ELSE 1 END AS numSlots
														FROM rosterinfo WHERE userID=:userID AND rosterID=:rosterID");
		$rosterQuery->bindValue(':userID', $userID, PDO::PARAM_INT);
		$rosterQuery->bindValue(':rosterID', $rosterID, PDO::PARAM_INT);
        $rosterQuery->execute(); //execute query
        $results = $rosterQuery->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
		return $results[0]['numSlots']; //return result from the query 
	}
	
	public function getAvailableQB($userID, $rosterID)
	{
		$rosterQuery = $this->dbinstance->prepare("SELECT QB, 
												   CASE WHEN QB IS NOT NULL THEN 0 ELSE 1 END AS numSlots
												   FROM rosterinfo WHERE userID=:userID AND rosterID=:rosterID");
		$rosterQuery->bindValue(':userID', $userID, PDO::PARAM_INT);
		$rosterQuery->bindValue(':rosterID', $rosterID, PDO::PARAM_INT);
        $rosterQuery->execute(); //execute query
        $results = $rosterQuery->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
        return $results[0]['numSlots'];
	}
	
	public function getAvailableRB1($userID,$rosterID)
	{
		$rosterQuery = $this->dbinstance->prepare("SELECT RB1, 
												   CASE WHEN RB1 IS NOT NULL THEN 0 ELSE 1 END AS numSlots
												   FROM rosterinfo WHERE userID=:userID AND rosterID=:rosterID");
		$rosterQuery->bindValue(':userID', $userID, PDO::PARAM_INT);
		$rosterQuery->bindValue(':rosterID', $rosterID, PDO::PARAM_INT);
        $rosterQuery->execute(); //execute query
        $results = $rosterQuery->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
        return $results[0]['numSlots'];
	}
	
	public function getAvailableRB2($userID, $rosterID)
	{
		$rosterQuery = $this->dbinstance->prepare("SELECT RB2, 
												   CASE WHEN RB2 IS NOT NULL THEN 0 ELSE 1 END AS numSlots
												   FROM rosterinfo WHERE userID=:userID AND rosterID=:rosterID");
		$rosterQuery->bindValue(':userID', $userID, PDO::PARAM_INT);
		$rosterQuery->bindValue(':rosterID', $rosterID, PDO::PARAM_INT);
        $rosterQuery->execute(); //execute query
        $results = $rosterQuery->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
        return $results[0]['numSlots'];
	}
	
	public function getAvailableWR1($userID, $rosterID)
	{
		$rosterQuery = $this->dbinstance->prepare("SELECT WR1, 
												   CASE WHEN WR1 IS NOT NULL THEN 0 ELSE 1 END AS numSlots
												   FROM rosterinfo WHERE userID=:userID AND rosterID=:rosterID");
		$rosterQuery->bindValue(':userID', $userID, PDO::PARAM_INT);
		$rosterQuery->bindValue(':rosterID', $rosterID, PDO::PARAM_INT);
        $rosterQuery->execute(); //execute query
        $results = $rosterQuery->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
        return $results[0]['numSlots'];
	}
	
	public function getAvailableWR2($userID, $rosterID)
	{
		$rosterQuery = $this->dbinstance->prepare("SELECT WR2, 
												   CASE WHEN WR2 IS NOT NULL THEN 0 ELSE 1 END AS numSlots
												   FROM rosterinfo WHERE userID=:userID AND rosterID=:rosterID");
		$rosterQuery->bindValue(':userID', $userID, PDO::PARAM_INT);
		$rosterQuery->bindValue(':rosterID', $rosterID, PDO::PARAM_INT);
        $rosterQuery->execute(); //execute query
        $results = $rosterQuery->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
        return $results[0]['numSlots'];
	}
	
	public function getAvailableTE($userID, $rosterID)
	{
		$rosterQuery = $this->dbinstance->prepare("SELECT TE, 
												   CASE WHEN TE IS NOT NULL THEN 0 ELSE 1 END AS numSlots
												   FROM rosterinfo WHERE userID=:userID AND rosterID=:rosterID");
		$rosterQuery->bindValue(':userID', $userID, PDO::PARAM_INT);
		$rosterQuery->bindValue(':rosterID', $rosterID, PDO::PARAM_INT);
        $rosterQuery->execute(); //execute query
        $results = $rosterQuery->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
        return $results[0]['numSlots'];
	}
	
	public function getAvailableK($userID, $rosterID)
	{
		$rosterQuery = $this->dbinstance->prepare("SELECT K, 
												   CASE WHEN K IS NOT NULL THEN 0 ELSE 1 END AS numSlots
												   FROM rosterinfo WHERE userID=:userID AND rosterID=:rosterID");
		$rosterQuery->bindValue(':userID', $userID, PDO::PARAM_INT);
		$rosterQuery->bindValue(':rosterID', $rosterID, PDO::PARAM_INT);
        $rosterQuery->execute(); //execute query
        $results = $rosterQuery->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
        return $results[0]['numSlots'];
	}
    public function userHomeInfo($userID) //custom query that takes in userID from session and gets league name, user's team name in that league,
    {
        $userHomeQuery = $this->dbinstance->prepare("SELECT leagueName, teamName, leagueStatus, leagueinfo.leagueID FROM userinfo, rosterinfo, leagueinfo, leaguerosters WHERE (userinfo.userID=:userID) AND (userinfo.userID = rosterinfo.userID) AND(rosterinfo.rosterID = leaguerosters.rosterID)AND (leagueinfo.leagueID = leaguerosters.leagueID)");
        $userHomeQuery->bindValue(':userID', $userID, PDO::PARAM_INT); //bind the value to a string to help protect against SQL injection
        $userHomeQuery->execute();
        $userHomeResults = $userHomeQuery->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
        return $userHomeResults;
    }

    public function getUserRoster($userID, $leagueID) //gets a given user's players on a roster, in a given league
    {
        $rosterQuery = $this->dbinstance->prepare("SELECT QB,RB1,RB2,WR1,WR2,TE,K,DEF,bench1,bench2,bench3,bench4,bench5,bench6,rosterinfo.rosterID FROM rosterinfo, leaguerosters WHERE userID=:userID AND leagueID=:leagueID AND rosterinfo.rosterID = leaguerosters.rosterID "); //last line is 2 instead of 1 because DST isn't in yet. should give an even number so that last drafter as determined by order is indeed last so logic holds in program.
        $rosterQuery->bindValue(':userID', $userID, PDO::PARAM_INT);
        $rosterQuery->bindValue(':leagueID', $leagueID, PDO::PARAM_INT);
        $rosterQuery->execute(); //execute query
        $results = $rosterQuery->fetchAll(PDO::FETCH_ASSOC); //get resulting matches
        return $results; //return result from the query
    }

    public function createRoster($userID,$teamName)
    {
        $stmt = $this->dbinstance->prepare("INSERT INTO rosterinfo(userID,teamName)VALUES(:userID, :teamName)");
        $stmt->bindValue(':userID',$userID,PDO::PARAM_INT);
        $stmt->bindValue(':teamName', $teamName, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function getRosterID($userID,$teamName)
    {
        $stmt = $this->dbinstance->prepare("SELECT rosterID FROM rosterinfo WHERE userID=:userID AND teamName=:teamName");
        $stmt->bindValue(':userID',$userID,PDO::PARAM_INT);
        $stmt->bindValue(':teamName', $teamName, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results[0]['rosterID'];
    }

    public function createLeague($leagueName, $leaguePass)
    {
        $stmt = $this->dbinstance->prepare("INSERT INTO leagueinfo(leagueName,leaguePass)VALUES(:leagueName, :leaguePass)");
        $stmt->bindValue(':leagueName',$leagueName,PDO::PARAM_STR);
        $stmt->bindValue(':leaguePass', $leaguePass, PDO::PARAM_STR);
        $stmt->execute();

    }
	
	public function setStartingPlayers($rosterID, $qbID, $rb1ID, $rb2ID, $wr1ID, $wr2ID, $teID, $kID)
	{
		$stmt = $this->dbinstance->prepare("UPDATE startingrosters SET StartingQBid =:qbID, StartingRB1id=:rb1ID, StartingRB2id=:rb2ID, StartingWR1id=:wr1ID, StartingWR2id=:wr2ID, StartingTEid=:teID, StartingKid=:kID WHERE rosterID=:rosterID");
        $stmt->bindValue(':qbID',$qbID,PDO::PARAM_STR);
        $stmt->bindValue(':rb1ID', $rb1ID, PDO::PARAM_STR);
		$stmt->bindValue(':rb2ID', $rb2ID, PDO::PARAM_STR);
		$stmt->bindValue(':wr1ID', $wr1ID, PDO::PARAM_STR);
		$stmt->bindValue(':wr2ID', $wr2ID, PDO::PARAM_STR);
		$stmt->bindValue(':teID', $teID, PDO::PARAM_STR);
		$stmt->bindValue(':kID', $kID, PDO::PARAM_STR);
        $stmt->execute();
	}
	
	public function setUserRoster($availableSlots, $playerPosition, $playerID, $rosterID, $userID)
	{
		if($availableSlots > 0)
		{
			switch($playerPosition)
			{
				case 'QB':
				if($this->getAvailableQB($userID, $rosterID) > 0)
				{
				$updateRoster = $this->dbinstance->prepare("UPDATE rosterinfo SET 'QB' = :playerID WHERE rosterID = :rosterID");
				$updateRoster->bindValue(':playerID',$playerID, PDO::PARAM_STR);
				$updateRoster->bindValue(':rosterID',$rosterID, PDO::PARAM_INT);
				$updateRoster->execute();
				}
				elseif($this->getAvailableBench($userID, $rosterID) > 0)
				{
				$updateRoster = $this->dbinstance->prepare("UPDATE rosterinfo SET COALESCE(bench1,bench2,bench3,bench4,bench5,bench6) = :playerID WHERE rosterID = :rosterID");
				$updateRoster->bindValue(':playerID',$playerID, PDO::PARAM_STR);
				$updateRoster->bindValue(':rosterID',$rosterID, PDO::PARAM_INT);
				$updateRoster->execute();
				}
				else
				{
					return "No available slots for QB.";
				}
				break;
				
				case 'RB':
				if($this->getAvailableRB1($userID, $rosterID) > 0)
				{
				$updateRoster = $this->dbinstance->prepare("UPDATE rosterinfo SET 'RB1' = :playerID WHERE rosterID = :rosterID");
				$updateRoster->bindValue(':playerID',$playerID, PDO::PARAM_STR);
				$updateRoster->bindValue(':rosterID',$rosterID, PDO::PARAM_INT);
				$updateRoster->execute();
				}
				elseif($this->getAvailableRB2($userID, $rosterID) > 0)
				{
				$updateRoster = $this->dbinstance->prepare("UPDATE rosterinfo SET 'RB2' = :playerID WHERE rosterID = :rosterID");
				$updateRoster->bindValue(':playerID',$playerID, PDO::PARAM_STR);
				$updateRoster->bindValue(':rosterID',$rosterID, PDO::PARAM_INT);
				$updateRoster->execute();
				}
				elseif($this->getAvailableBench($userID, $rosterID) > 0)
				{
				$updateRoster = $this->dbinstance->prepare("UPDATE rosterinfo SET COALESCE(bench1,bench2,bench3,bench4,bench5,bench6) = :playerID WHERE rosterID = :rosterID");
				$updateRoster->bindValue(':playerID',$playerID, PDO::PARAM_STR);
				$updateRoster->bindValue(':rosterID',$rosterID, PDO::PARAM_INT);
				$updateRoster->execute();
				}
				else
				{
					return "No available slots for RB.";
				}
				break;
				
				case 'WR':
				if($this->getAvailableWR1($userID, $rosterID) > 0)
				{
				$updateRoster = $this->dbinstance->prepare("UPDATE rosterinfo SET 'WR1' = :playerID WHERE rosterID = :rosterID");
				$updateRoster->bindValue(':playerID',$playerID, PDO::PARAM_STR);
				$updateRoster->bindValue(':rosterID',$rosterID, PDO::PARAM_INT);
				$updateRoster->execute();
				}
				elseif($this->getAvailableWR2($userID, $rosterID) > 0)
				{
				$updateRoster = $this->dbinstance->prepare("UPDATE rosterinfo SET 'WR2' = :playerID WHERE rosterID = :rosterID");
				$updateRoster->bindValue(':playerID',$playerID, PDO::PARAM_STR);
				$updateRoster->bindValue(':rosterID',$rosterID, PDO::PARAM_INT);
				$updateRoster->execute();
				}
				elseif($this->getAvailableBench($userID, $rosterID) > 0)
				{
				$updateRoster = $this->dbinstance->prepare("UPDATE rosterinfo SET COALESCE(bench1,bench2,bench3,bench4,bench5,bench6) = :playerID WHERE rosterID = :rosterID");
				$updateRoster->bindValue(':playerID',$playerID, PDO::PARAM_STR);
				$updateRoster->bindValue(':rosterID',$rosterID, PDO::PARAM_INT);
				$updateRoster->execute();
				}
				else
				{
					return "No available slots for WR.";
				}
				break;
				
				case 'TE':
				if($this->getAvailableTE($userID, $rosterID) > 0)
				{
				$updateRoster = $this->dbinstance->prepare("UPDATE rosterinfo SET 'RB1' = :playerID WHERE rosterID = :rosterID");
				$updateRoster->bindValue(':playerID',$playerID, PDO::PARAM_STR);
				$updateRoster->bindValue(':rosterID',$rosterID, PDO::PARAM_INT);
				$updateRoster->execute();
				}
				elseif($this->getAvailableBench($userID, $rosterID) > 0)
				{
				$updateRoster = $this->dbinstance->prepare("UPDATE rosterinfo SET COALESCE(bench1,bench2,bench3,bench4,bench5,bench6) = :playerID WHERE rosterID = :rosterID");
				$updateRoster->bindValue(':playerID',$playerID, PDO::PARAM_STR);
				$updateRoster->bindValue(':rosterID',$rosterID, PDO::PARAM_INT);
				$updateRoster->execute();
				}
				else
				{
					return "No available slots for TE.";
				}
				break;
				
				case 'K':
				if($this->getAvailableK($userID, $rosterID) > 0)
				{
				$updateRoster = $this->dbinstance->prepare("UPDATE rosterinfo SET 'K' = :playerID WHERE rosterID = :rosterID");
				$updateRoster->bindValue(':playerID',$playerID, PDO::PARAM_STR);
				$updateRoster->bindValue(':rosterID',$rosterID, PDO::PARAM_INT);
				$updateRoster->execute();
				}
				elseif($this->getAvailableBench($userID, $rosterID) > 0)
				{
				$updateRoster = $this->dbinstance->prepare("UPDATE rosterinfo SET COALESCE(bench1,bench2,bench3,bench4,bench5,bench6) = :playerID WHERE rosterID = :rosterID");
				$updateRoster->bindValue(':playerID',$playerID, PDO::PARAM_STR);
				$updateRoster->bindValue(':rosterID',$rosterID, PDO::PARAM_INT);
				$updateRoster->execute();
				}
				else
				{
					return "No available slots for K.";
				}
				break;
			}
		}
		else
		{
			return "No available slots!";
		}
		
	}
	
}




?>