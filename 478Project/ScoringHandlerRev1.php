<?php
// scoring settings here, this should be a polymorphic class because eventually we can extend this to other modes of play.


class scoringHandler
{
    protected $userdbinstance;
    protected $nfldbinstance;

    const CURRENT_SEASON_YEAR = 2016;
    const CURRENT_NFL_WEEK = 13;

    //offense constant scoring values
    const OFFENSE_TOUCHDOWN = 6;
    const PASSING_TOUCHDOWN = 4;
    const PASSING_YARDS = .04;
    const RUSHING_YARDS = .1;
    const RECEIVING_YARDS = .1;
    const LOST_FUMBLE = -2;
    const INTERCEPTION = -2;
    const TWO_PT_CONV = 2;

    //defense & special teams scoring constants
    const FUMBLE_REC = 2;
    const CAUGHT_INT = 2;
    const BLOCKED_KICK = 2;
    const SACK = 2;
    const SAFETY = 2;
    const OFFENSE_PTS_ALLD_0 = 13;
    const OFFENSE_PTS_ALLD_1_6 = 10;
    const OFFENSE_PTS_ALLD_7_13 = 7;
    const OFFENSE_PTS_ALLD_14_20 = 5;
    const OFFENSE_PTS_ALLD_21_27 = 0;
    const OFFENSE_PTS_ALLD_28_34 = -1;
    const OFFENSE_PTS_ALLD_35_AND_ABOVE = -4;
    const DEFENSE_ST_TOUCHDOWN = 6;

    //kicking point values
    const  XP_MADE = 1;
    const KICK_MISSED = -1;
    const FG_0_39 = 3;
    const FG_40_49 = 4;
    const FG_50_AND_ABOVE = 5;


    public function __construct(PDO $userdbinstance, PDO $nfldbinstance) // passed a pdo DB file to construct the object
    {
        $this->userdbinstance = $userdbinstance;  //each object's functions should apply to that object and not others
        $this->nfldbinstance = $nfldbinstance;
//        $this->rosterID = $rosterID;
    }

    public function getOffensePlayerScore($playerID, $gameID)
    {
        $gameID = $this->getCurrentGameID($playerID);
        $offPoints = $this->nfldbinstance->prepare("SELECT SUM(passing_yds) AS passing_yds, SUM(passing_int) AS passing_int,SUM(passing_tds) AS passing_tds,SUM(passing_twoptm) AS passing_twoptm, SUM(kickret_tds) AS kickret_tds, SUM(puntret_tds) AS puntret_tds,SUM(receiving_tds) AS receiving_tds, SUM(receiving_twoptm) AS receiving_twoptm, SUM(receiving_yds) AS receiving_yds, SUM(rushing_tds) AS rushing_tds, SUM(rushing_twoptm) AS rushing_twoptm, SUM(rushing_yds) AS rushing_yds FROM play_player WHERE player_id=:player_id AND gsis_id=:gameID");
        $offPoints->bindValue(':playerID', $playerID, PDO::PARAM_STR);
        $offPoints->bindValue(':gameID', $gameID, PDO::PARAM_STR);
        $offPoints->execute();
        $results = $offPoints->fetchAll(PDO::FETCH_ASSOC);
        $totalPoints = ($results[0]['passing_yds'] * (.04)) + ($results[0]['passing_int'] * (-2)) + ($results[0]['passing_tds'] * (6))
            + ($results[0]['passing_twoptm'] * (2)) + ($results[0]['kickret_tds'] * (6)) + ($results[0]['puntret_tds'] * (6))
            + ($results[0]['receiving_tds'] * (6))+ ($results[0]['receiving_twoptm'] * (2))+ ($results[0]['receiving_yds'] * (.1))
            + ($results[0]['rushing_tds'] * (6)) + ($results[0]['rushing_twoptm'] * (2)) + ($results[0]['rushing_yds'] * (.1));
        
        return $totalPoints;

    }
	
	public function getSeasonPlayerStats($playerID)
	{
//		$gameID = $this->getCurrentGameID($playerID);
        $offPoints = $this->nfldbinstance->prepare("SELECT SUM(play_player.passing_yds) AS passing_yds, SUM(play_player.passing_int) AS passing_int,SUM(play_player.passing_tds) AS passing_tds,SUM(play_player.passing_twoptm) AS passing_twoptm, SUM(play_player.kickret_tds) AS kickret_tds, SUM(play_player.puntret_tds) AS puntret_tds,SUM(receiving_tds) AS receiving_tds, SUM(play_player.receiving_twoptm) AS receiving_twoptm, SUM(play_player.receiving_yds) AS receiving_yds, SUM(play_player.rushing_tds) AS rushing_tds, SUM(play_player.rushing_twoptm) AS rushing_twoptm, SUM(play_player.rushing_yds) AS rushing_yds FROM play_player LEFT JOIN game ON play_player.gsis_id = game.gsis_id AND play_player.player_id=:playerID WHERE game.season_type = 'Regular' AND game.season_year = 2016");
        $offPoints->bindValue(':playerID', $playerID, PDO::PARAM_STR);
        $offPoints->execute();
        $results = $offPoints->fetchAll(PDO::FETCH_ASSOC);
        $seasonPoints = ($results[0]['passing_yds'] * (.04)) + ($results[0]['passing_int'] * (-2)) + ($results[0]['passing_tds'] * (6))
            + ($results[0]['passing_twoptm'] * (2)) + ($results[0]['kickret_tds'] * (6)) + ($results[0]['puntret_tds'] * (6))
            + ($results[0]['receiving_tds'] * (6))+ ($results[0]['receiving_twoptm'] * (2))+ ($results[0]['receiving_yds'] * (.1))
            + ($results[0]['rushing_tds'] * (6)) + ($results[0]['rushing_twoptm'] * (2)) + ($results[0]['rushing_yds'] * (.1));
         $seasonPoints = $seasonPoints/13;
        return $seasonPoints;
	}
	
	//for the above function, if you want to hardcode for a specific player, just put: SELECT SUM(play_player.passing_yds) AS passing_yds, SUM(play_player.passing_int) AS passing_int,SUM(play_player.passing_tds) AS passing_tds,SUM(play_player.passing_twoptm) AS passing_twoptm, SUM(play_player.kickret_tds) AS kickret_tds, SUM(play_player.puntret_tds) AS puntret_tds,SUM(receiving_tds) AS receiving_tds, SUM(play_player.receiving_twoptm) AS receiving_twoptm, SUM(play_player.receiving_yds) AS receiving_yds, SUM(play_player.rushing_tds) AS rushing_tds, SUM(play_player.rushing_twoptm) AS rushing_twoptm, SUM(play_player.rushing_yds) AS rushing_yds FROM play_player LEFT JOIN game ON play_player.gsis_id = game.gsis_id AND play_player.player_id='00-0026153' WHERE game.season_type = 'Regular' AND game.season_year = 2016; you can replace '00-0026153' with whatever ID you want. That ID happens to belong to Jonathan Stewart.

    public function getDefenseScore($team, $gameID)
    {
        $defPoints = $this->nfldbinstance->prepare("SELECT defense_fgblk,defense_frec,defense_frec_tds,defense_int,defense_int_tds,defense_misc_tds,defense_puntblk,defense_safe,defense_sk,defense_xpblk, defense_misc_yds  FROM play_player WHERE team=:team AND gsis_id =:gsisID");
        $defPoints->bindValue(':team', $team, PDO::PARAM_STR);
        $defPoints->bindValue(':gsisID', $gameID, PDO::PARAM_STR);
        $defPoints->execute();
        $results = $defPoints->fetchAll(PDO::FETCH_ASSOC);
        $totalPoints = ($results[0]['defense_fgblk'] * BLOCKED_KICK)+($results[0]['defense_frec']* FUMBLE_REC) + ($results[0]['defense_frec_tds'] * DEFENSE_ST_TOUCHDOWN )
            + ($results[0]['defense_int'] * CAUGHT_INT)+ ($results[0]['defense_int_tds'] * DEFENSE_ST_TOUCHDOWN) + ($results[0]['defense_misc_tds'] * DEFENSE_ST_TOUCHDOWN)
            + ($results[0]['defense_puntblk'] * BLOCKED_KICK) + ($results[0]['defense_safe'] * SAFETY) + ($results[0]['defense_sk'] * SACK) + ($results[0]['defense_xpblk'] * BLOCKED_KICK);

        return $totalPoints;


    }

    public function getKickerScore($playerID)
    {

        
        $kpoints = $this->nfldbinstance->prepare("WITH kicker_ffps AS (
    SELECT
      play_player.player_id as player_id,
      play_player.kicking_xpmade as kicking_xpmade,
      (
        CASE WHEN kicking_fgm_yds BETWEEN 1 AND 39  THEN 3.00
             WHEN kicking_fgm_yds BETWEEN 40 AND 49 THEN 4.00
             WHEN kicking_fgm_yds >= 50 THEN 5.00
             ELSE 0.00
        END
      ) AS kicker_fg_ffps
    FROM play_player
    LEFT JOIN game on play_player.gsis_id = game.gsis_id AND play_player.player_id =:playerID
    WHERE game.season_type = 'Regular' AND game.season_year = 2016
)
    SELECT
      kicker_ffps.player_id,
      SUM(kicker_ffps.kicker_fg_ffps) + SUM(kicker_ffps.kicking_xpmade) AS ffps, player.full_name
    FROM kicker_ffps, player
	WHERE kicker_ffps.player_id = player.player_id AND player.position = 'K'
    GROUP BY kicker_ffps.player_id, player.full_name;");
        $kpoints->bindValue(':playerID', $playerID, PDO::PARAM_STR);
        
        $kpoints->execute();
        $results = $kpoints->fetchAll(PDO::FETCH_ASSOC);
		return $results[0]['ffps'];


    }

    public function getCurrentGameID($playerID)
    {
        $gameID = $this->nfldbinstance->prepare("SELECT game.gsis_id FROM game, play_player WHERE week =:CURRENT_NFL_WEEK AND season_year =:CURRENT_SEASON_YEAR AND player_id =:playerID ");
        $gameID->bindValue(':playerID', $playerID, PDO::PARAM_STR);
        $gameID->bindValue(':CURRENT_NFL_WEEK',(10), PDO::PARAM_INT);
        $gameID->bindValue(':CURRENT_SEASON_YEAR',(2016), PDO::PARAM_INT);
        $gameID->execute();
        $results = $gameID->fetchAll(PDO::FETCH_ASSOC);
//        print_r($results);
//        foreach ($results as $row){
//          print 'Made it here';
//          $gsis_id = $results['gsis_id'];
//        }
//        print_r($gsis_id);
////        $gsis_id = $results[0]['gsis_id'];
//        return $gsis_id;
//        foreach ($results as $row){
//          $resultsSingle = $row;
//        }
         return $results;

    }

}