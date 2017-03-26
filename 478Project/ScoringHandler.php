<?php
// scoring settings here, this should be a polymorphic class because eventually we can extend this to other modes of play.


class scoringHandler
{
    protected $userdbinstance;
    protected $nfldbinstance;

    const CURRENT_SEASON_YEAR = 2016;
    const CURRENT_NFL_WEEK = 10;

    //offense constant scoring values
    const OFFENSE_TOUCHDOWN = 6;
    const PASSING_TOUCHDOWN = 4;
    const PASSING_YARDS = .25;
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


    public function __construct(PDO $userdbinstance, PDO $nfldbinstance, $rosterID) // passed a pdo DB file to construct the object
    {
        $this->userdbinstance = $userdbinstance;  //each object's functions should apply to that object and not others
        $this->nfldbinstance = $nfldbinstance;
        $this->rosterID = $rosterID;
    }

    public function getOffensePlayerScore($playerID, $gameID)
    {
        $gameID = $this->getCurrentGameID($playerID);
        $offPoints = $this->nfldbinstance->prepare("SELECT passing_yds, passing_int,passing_tds,passing_twoptm, kickret_tds, puntret_tds,receiving_tds,receiving_twoptm, receiving_yds, rushing_tds, rushing_twoptm, rushing_yds FROM play_player WHERE player_id=:playerID AND gsis_id =:gsisID");
        $offPoints->bindValue(':playerID', $playerID, PDO::PARAM_STR);
        $offPoints->bindValue(':gsisID', $gameID, PDO::PARAM_STR);
        $offPoints->execute();
        $results = $offPoints->fetchAll(PDO::FETCH_ASSOC);
        $totalPoints = ($results[0]['passing_yds'] * PASSING_YARDS) + ($results[0]['passing_int'] * INTERCEPTION) + ($results[0]['passing_tds'] * OFFENSE_TOUCHDOWN)
            + ($results[0]['passing_twoptm'] * TWO_PT_CONV) + ($results[0]['kickret_tds'] * OFFENSE_TOUCHDOWN) + ($results[0]['puntret_tds'] * OFFENSE_TOUCHDOWN)
            + ($results[0]['receiving_tds'] * OFFENSE_TOUCHDOWN)+ ($results[0]['receiving_twoptm'] * TWO_PT_CONV)+ ($results[0]['receiving_yds'] * RECEIVING_YARDS)
            + ($results[0]['rushing_tds'] * OFFENSE_TOUCHDOWN) + ($results[0]['rushing_twoptm'] * TWO_PT_CONV) + ($results[0]['rushing_yds'] * RUSHING_YARDS);
        return $totalPoints;

    }

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

        $gameID = $this->getCurrentGameID($playerID);
        $kpoints = $this->nfldbinstance->prepare("SELECT kicking_xpmade, kicking_xpmissed, kicking_fgmissed, kicking_fgm_yds FROM play_player WHERE player_id=:playerID AND gsis_id =:gsisID");
        $kpoints->bindValue(':playerID', $playerID, PDO::PARAM_STR);
        $kpoints->bindValue(':gsisID', $gameID, PDO::PARAM_STR);
        $kpoints->execute();
        $results = $kpoints->fetchAll(PDO::FETCH_ASSOC);
        $totalPoints = ($results[0]['kicking_xpmade'] * XP_MADE )+ ($results[0]['kicking_xpmissed'] * KICK_MISSED) + ($results[0]['kicking_fgmissed'] * KICK_MISSED)
            + ($results[0]['kicking_fgm_yards'] * FG_0_39);

        return $totalPoints;


    }

    public function getCurrentGameID($playerID)
    {
        $gameID = $this->nfldbinstance->prepare("SELECT game.gsis_id FROM game, play_player WHERE week =:CURRENT_NFL_WEEK AND season_year =:CURRENT_SEASON_YEAR AND player_id =:playerID ");
        $gameID->bindValue(':playerID', $playerID, PDO::PARAM_STR);
        $gameID->bindValue(':CURRENT_NFL_WEEK',CURRENT_NFL_WEEK, PDO::PARAM_INT);
        $gameID->bindValue(':CURRENT_SEASON_YEAR',CURRENT_SEASON_YEAR, PDO::PARAM_INT);
        $gameID->execute();
        $results = $gameID->fetchAll(PDO::FETCH_ASSOC);
        $gsis_id = $results[0]['gsis_id'];
        return $gsis_id;

    }

}