<?php

namespace func_list;

include_once('tbs_class.php');
include_once('ll_library.php');
_On_Entry('func_list');

function _Main()
{
    global $conID;
    global $CurrentScript;
    global $CurrentTemplate;
    global $IO_STS;
    global $IO_ROWS;
    global $IO_ERR;
    global $_Lists;
    global $SEASON_ID;
    global $PLAYER_ID;
    global $PLAYER_NAME;
    global $NFL_TEAM_ID;
    global $NFL_TEAM_ID3;
    global $POSITION_ID;
    global $LISTCOUNT1;
    global $LIMIT;
    global $STD_BUTTON;
    global $START;
    global $LIST1;
    global $_LIST1_ptr;
    $_Lists[] = array('LIST1' => 'LISTCOUNT1');
    _get_exchange();
    // $LIST1[] = array('SEASON_ID' => 0,'PLAYER_ID' => 1,'PLAYER_NAME' => 2,'NFL_TEAM_ID' => 3,'NFL_TEAM_ID3' => 4,'POSITION_ID' => 5);
    // $LISTCOUNT1=0;

    $LIMIT = 20;
    if ($STD_BUTTON == 'DETAIL') {
        $_sub_parms = array('START' => $START, 'STD_BUTTON' => $STD_BUTTON, 'SEASON_ID' => $SEASON_ID, 'PLAYER_ID' => $PLAYER_ID);
        _exchange($_sub_parms);

        func_call('func_detail');
        _get_exchange();
    }
    if ($STD_BUTTON == 'FIRST') {
        $START = 0;
    }
    if ($STD_BUTTON == 'PREV') {
        $START = ($START - $LIMIT);
    }
    if ($STD_BUTTON == 'NEXT') {
        $START = ($START + $LIMIT);
    }
    if ($STD_BUTTON == 'LAST') {
        $START = 0;
        $query = 'select * from nfl_player_defense' . ' ORDER BY SEASON_ID, PLAYER_ID';
        var_dump($query);
        $stmt = mysqli_prepare($conID, $query);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $IO_ERR = mysqli_stmt_error($stmt);
        $IO_ROWS = mysqli_stmt_affected_rows($stmt);
        while ($record = mysqli_fetch_array($result, MYSQLI_BOTH)) { {
                foreach ($record as $key => $value) {
                    $key = strtoupper($key);
                    ${$key} = $value;
                }

                $START = ($START + 1);
            }
        }

        $START = ($START - $LIMIT);
    }
    if ($START <= 0) {
        $START = 0;
    }
    /* comment -> OK*/

    /* comment ->Select Fields(*all) From_File(nfl_player_defense) limit(#start,#limit)*/

    /* comment ->	add_entry to_list(#list1)*/

    /* comment ->endselect*/

    /* comment -> OK*/

    /* comment ->Select Fields(*all) From_File(nfl_player_defense) limit(#limit)*/

    /* comment ->	add_entry to_list(#list1)*/

    /* comment ->endselect*/

    /* comment -> OK - fixed for SQL injection (input fields could come from client)*/

    /* comment ->#season_id := 0*/

    /* comment ->#position_id := "LB"*/

    /* comment ->Select Fields(*all) From_File(nfl_player_defense) with_key(#season_id #position_id) limit(#start,#limit)*/

    /* comment ->	add_entry to_list(#list1)*/

    /* comment ->endselect*/

    /* comment ->generates currently: select * from nfl_player_defense where SEASON_ID=? and POSITION_ID=? ORDER BY SEASON_ID, PLAYER_ID LIMIT ?,?*/

    /* comment ->FAILS*/

    /* comment ->#dummy_variable := 0*/

    /* comment ->Select Fields(*all) From_File(nfl_player_defense) with_key(#dummy_variable) limit(#start,#limit)*/

    /* comment ->	add_entry to_list(#list1)*/

    /* comment ->endselect*/

    /* comment ->TODO*/

    /* comment ->Case 1 : where(#position_id = "LB")    // sb #position_id -> position_id*/

    /* comment ->Case 2 : where(#position_id = 'LB')	   // any form of LB should transform to 'LB'  (depending on settings "x" can mean column)*/

    /* comment ->Case 3 : where(#position_id = LB)	   // this form of where (not select_sql can not merge in variables, hard code only)*/

    /* comment -> OK ??*/

    $SEASON_ID = 0;
    $NFL_TEAM_ID3 = "CHI";
    $query = 'select * from nfl_player_defense' . ' where ' . ' SEASON_ID=? ' . ' and ' . ' NFL_TEAM_ID3=? ' . ' and ' . '(' . 'position_id = "LB"' . ')' . ' ORDER BY SEASON_ID, PLAYER_ID' . ' LIMIT ' . '?,?';
    var_dump($query);
    $stmt = mysqli_prepare($conID, $query);
    mysqli_stmt_bind_param($stmt, 'dsii', $SEASON_ID, $NFL_TEAM_ID3, $START, $LIMIT);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $IO_ERR = mysqli_stmt_error($stmt);
    $IO_ROWS = mysqli_stmt_affected_rows($stmt);
    while ($record = mysqli_fetch_array($result, MYSQLI_BOTH)) { {
            foreach ($record as $key => $value) {
                $key = strtoupper($key);
                ${$key} = $value;
            }

            $LIST1[] = array('SEASON_ID' => $SEASON_ID, 'PLAYER_ID' => $PLAYER_ID, 'PLAYER_NAME' => $PLAYER_NAME, 'NFL_TEAM_ID' => $NFL_TEAM_ID, 'NFL_TEAM_ID3' => $NFL_TEAM_ID3, 'POSITION_ID' => $POSITION_ID);
            $LISTCOUNT1++;
        }
    }

    /* comment -> FAILS/* this is currently not working*/

    /* comment ->Select Fields(*all) From_File(nfl_player_defense) where(position_id = 'LB') limit(#start,#limit)*/

    /* comment ->	add_entry to_list(#list1)*/

    /* comment ->endselect*/

    /* comment -> OK - this works*/

    /* comment ->#position_id := "LB"*/

    /* comment ->Select Fields(*all) From_File(nfl_player_defense) where(position_id = #position_id) limit(#start,#limit)*/

    /* comment ->	add_entry to_list(#list1)*/

    /* comment ->endselect*/

    $TBS = new \clsTinyButStrong;
    $TBS->SetOption('noerr', false);
    $TBS->LoadTemplate($CurrentTemplate);
    $TBS->MergeBlock('LIST1', $LIST1);
    $TBS->Show();
    exit();
}

function _Get_Post()
{
    global $SEASON_ID;
    global $PLAYER_ID;
    global $STD_BUTTON;
    global $START;
    global $LIST1;
    global $LISTCOUNT1;
    $SEASON_ID = '';
    $PLAYER_ID = '';
    $STD_BUTTON = '';
    $START = '';
    if (isset($_POST['SEASON_ID'])) $SEASON_ID = strtoupper($_POST['SEASON_ID']);
    if (isset($_POST['PLAYER_ID'])) $PLAYER_ID = strtoupper($_POST['PLAYER_ID']);
    if (isset($_POST['STD_BUTTON'])) $STD_BUTTON = strtoupper($_POST['STD_BUTTON']);
    if (isset($_POST['START'])) $START = strtoupper($_POST['START']);
    unset($_field_array);
    $_field_array[] = array('SEASON_ID', '*OUT');
    $_field_array[] = array('PLAYER_ID', '*OUT');
    $_field_array[] = array('PLAYER_NAME', '*OUT');
    $_field_array[] = array('NFL_TEAM_ID', '*OUT');
    $_field_array[] = array('NFL_TEAM_ID3', '*OUT');
    $_field_array[] = array('POSITION_ID', '*OUT');
    list($LIST1, $_LIST1_listcount) = getPostedList('LIST1', $_field_array);
    $LISTCOUNT1 = $_LIST1_listcount;
    echo "<br>";
}
