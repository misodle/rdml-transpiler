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
        $result = mysqli_query($conID, $query);
        $IO_ERR = mysqli_error($conID);
        $IO_ROWS = mysqli_affected_rows($conID);
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
    $query = 'select * from nfl_player_defense' . ' ORDER BY SEASON_ID, PLAYER_ID' . ' LIMIT ' . $START . ',' . $LIMIT;
    var_dump($query);
    $result = mysqli_query($conID, $query);
    $IO_ERR = mysqli_error($conID);
    $IO_ROWS = mysqli_affected_rows($conID);
    while ($record = mysqli_fetch_array($result, MYSQLI_BOTH)) { {
            foreach ($record as $key => $value) {
                $key = strtoupper($key);
                ${$key} = $value;
            }

            $LIST1[] = array('SEASON_ID' => $SEASON_ID, 'PLAYER_ID' => $PLAYER_ID, 'PLAYER_NAME' => $PLAYER_NAME, 'NFL_TEAM_ID' => $NFL_TEAM_ID, 'NFL_TEAM_ID3' => $NFL_TEAM_ID3, 'POSITION_ID' => $POSITION_ID);
            $LISTCOUNT1++;
        }
    }

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
