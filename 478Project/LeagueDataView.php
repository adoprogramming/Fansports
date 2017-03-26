<?php

    foreach($leagueData as $row):
        $leagueIDPath = 'LeagueHome.php?leagueID=' . $row['leagueID'];
        $leagueDraftPath = 'Draft.php?leagueID='. $row['leagueID'];

        if($row['leagueStatus']=='DRAFT')
        {
        $leagueIDPath = 'LeagueHome.php?leagueID=' . $row['leagueID'];


        ?>
            <tr class = "data" id = "data">
                <td class = "leagueData" id = "leagueData1"><a href="<?= $leagueIDPath ?>"><?php print $row['leagueName']; ?></a></td>
                <td class = "leagueData" id = "leagueData2"><a href="<?= $leagueIDPath ?>"><?php print $row['leagueID']  ;   ?></td>
                <td class = "leagueData" id = "leagueData2"><a href="<?= $leagueIDPath ?>"><?php print $row['teamName']  ;   ?></td>
                <td class = "leagueData" id = "leagueData3"><a href="<?= $leagueDraftPath ?>"><?php print $row['leagueStatus']; ?></td>
            </tr>

<?php }
    else
    {
        ?>


        <tr class = "data" id = "data">
            <td class = "leagueData" id = "leagueData1"><a href="<?= $leagueIDPath ?>"><?php print $row['leagueName']; ?></a></td>
            <td class = "leagueData" id = "leagueData2"><a href="<?= $leagueIDPath ?>"><?php print $row['leagueID']  ;   ?></td>
            <td class = "leagueData" id = "leagueData2"><a href="<?= $leagueIDPath ?>"><?php print $row['teamName']  ;   ?></td>
            <td class = "leagueData" id = "leagueData3"><a href="<?= $leagueDraftPath ?>"><?php print "Draft complete."; ?></td>
        </tr>

        <?php
    } endforeach ?>

