<?php

namespace func_detail;

include_once('tbs_class.php');
include_once('ll_library.php');
_On_Entry('func_detail');

function _Main()
{
    global $conID;
    global $CurrentScript;
    global $CurrentTemplate;
    global $IO_STS;
    global $IO_ROWS;
    global $IO_ERR;
    global $_Lists;
    global $STD_BUTTON;
    global $SEASON_ID;
    global $PLAYER_ID;
    global $PLAYER_NAME;
    global $NFL_TEAM_ID;
    global $NFL_TEAM_ID3;
    global $POSITION_ID;
    global $START;
    global $IO_STS;
    global $IO_ERR;
    global $IO_ROWS;
    _get_exchange();
    if ($STD_BUTTON == 'DETAIL') {
        $query = 'select SEASON_ID,PLAYER_ID,PLAYER_NAME,NFL_TEAM_ID,NFL_TEAM_ID3,POSITION_ID from nfl_player_defense' . ' where ' . ' SEASON_ID=? ' . ' and ' . ' PLAYER_ID=? ' . ' LIMIT 1';
        var_dump($query);
        $stmt = mysqli_prepare($conID, $query);
        mysqli_stmt_bind_param($stmt, 'di', $SEASON_ID, $PLAYER_ID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $IO_ERR = mysqli_stmt_error($stmt);
        $IO_ROWS = mysqli_stmt_affected_rows($stmt);
        while ($record = mysqli_fetch_array($result, MYSQLI_BOTH)) {
            foreach ($record as $key => $value) {
                $key = strtoupper($key);
                ${$key} = $value;
            }
        }
        if ($result == FALSE) {
            $IO_STS = 'ER';
        } else {
            $IO_STS = 'OK';
        }
    }
    if ($STD_BUTTON == 'UPDATE') {
        $query = 'update nfl_player_defense set ' . 'SEASON_ID=?' . ', ' . 'PLAYER_ID=?' . ', ' . 'PLAYER_NAME=?' . ', ' . 'NFL_TEAM_ID=?' . ', ' . 'NFL_TEAM_ID3=?' . ', ' . 'POSITION_ID=?' . ' where ' . ' SEASON_ID=? ' . ' and ' . ' PLAYER_ID=? ' . ' LIMIT 1';
        var_dump($query);
        $stmt = mysqli_prepare($conID, $query);
        mysqli_stmt_bind_param($stmt, 'disissdi', $SEASON_ID, $PLAYER_ID, $PLAYER_NAME, $NFL_TEAM_ID, $NFL_TEAM_ID3, $POSITION_ID, $SEASON_ID, $PLAYER_ID);
        mysqli_stmt_execute($stmt);
        $IO_ERR = mysqli_stmt_error($stmt);
        $IO_ROWS = mysqli_stmt_affected_rows($stmt);
        if ($IO_ERR !== '' or $IO_ROWS == 0 or $IO_ROWS == -1) {
            $IO_STS = 'ER';
        } else {
            $IO_STS = 'OK';
        }
    }
    if ($STD_BUTTON == 'INSERT') {
        $query = 'insert INTO nfl_player_defense (SEASON_ID,PLAYER_ID,PLAYER_NAME,NFL_TEAM_ID,NFL_TEAM_ID3,POSITION_ID) VALUES (?,?,?,?,?,?)';
        var_dump($query);
        $stmt = mysqli_prepare($conID, $query);
        mysqli_stmt_bind_param($stmt, 'disiss', $SEASON_ID, $PLAYER_ID, $PLAYER_NAME, $NFL_TEAM_ID, $NFL_TEAM_ID3, $POSITION_ID);
        try 
        { 
            mysqli_stmt_execute($stmt);
            $IO_ERR = mysqli_stmt_error($stmt);
            $IO_ROWS = mysqli_stmt_affected_rows($stmt);
            if ($IO_ERR !== '' or $IO_ROWS == 0 or $IO_ROWS == -1) {
                $IO_STS = 'ER';
            } else {
                $IO_STS = 'OK';
            } 
        }
        catch (\mysqli_sql_exception $e) 
        { 
            $IO_ERR = $e->getMessage();
            $IO_ROWS = 0; 
        }


    }
    if ($STD_BUTTON == 'DELETE') {
        $query = 'delete  from nfl_player_defense' . ' where ' . ' SEASON_ID=? ' . ' and ' . ' PLAYER_ID=? ' . ' LIMIT 1';
        var_dump($query);
        $stmt = mysqli_prepare($conID, $query);
        mysqli_stmt_bind_param($stmt, 'di', $SEASON_ID, $PLAYER_ID);
        mysqli_stmt_execute($stmt);
        $IO_ERR = mysqli_stmt_error($stmt);
        $IO_ROWS = mysqli_stmt_affected_rows($stmt);
        if ($IO_ERR !== '' or $IO_ROWS == 0 or $IO_ROWS == -1) {
            $IO_STS = 'ER';
        } else {
            $IO_STS = 'OK';
        }
    }
    if ($STD_BUTTON == 'GOBACK') {
        $_sub_parms = array('START' => $START, 'STD_BUTTON' => $STD_BUTTON);
        _exchange($_sub_parms);

        func_call('func_list');
        _get_exchange();
    }
    $STD_BUTTON = ' ';
    $TBS = new \clsTinyButStrong;
    $TBS->SetOption('noerr', false);
    $TBS->LoadTemplate($CurrentTemplate);
    $TBS->Show();
    exit();
}

function _Get_Post()
{
    global $START;
    global $STD_BUTTON;
    global $IO_STS;
    global $IO_ERR;
    global $IO_ROWS;
    global $SEASON_ID;
    global $PLAYER_ID;
    global $PLAYER_NAME;
    global $NFL_TEAM_ID;
    global $NFL_TEAM_ID3;
    global $POSITION_ID;
    $START = '';
    $STD_BUTTON = '';
    $IO_STS = '';
    $IO_ERR = '';
    $IO_ROWS = '';
    $SEASON_ID = '';
    $PLAYER_ID = '';
    $PLAYER_NAME = '';
    $NFL_TEAM_ID = '';
    $NFL_TEAM_ID3 = '';
    $POSITION_ID = '';
    if (isset($_POST['START'])) $START = strtoupper($_POST['START']);
    if (isset($_POST['STD_BUTTON'])) $STD_BUTTON = strtoupper($_POST['STD_BUTTON']);
    if (isset($_POST['SEASON_ID'])) $SEASON_ID = strtoupper($_POST['SEASON_ID']);
    if (isset($_POST['PLAYER_ID'])) $PLAYER_ID = strtoupper($_POST['PLAYER_ID']);
    if (isset($_POST['PLAYER_NAME'])) $PLAYER_NAME = $_POST['PLAYER_NAME'];
    if (isset($_POST['NFL_TEAM_ID'])) $NFL_TEAM_ID = strtoupper($_POST['NFL_TEAM_ID']);
    if (isset($_POST['NFL_TEAM_ID3'])) $NFL_TEAM_ID3 = strtoupper($_POST['NFL_TEAM_ID3']);
    if (isset($_POST['POSITION_ID'])) $POSITION_ID = strtoupper($_POST['POSITION_ID']);
    echo "<br>";
}
