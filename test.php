<?php

namespace test;

include_once('tbs_class.php');
include_once('ll_library.php');
_On_Entry(test);

function _Main()
{
    global $CurrentScript;
    global $CurrentTemplate;
    global $IO_STS;
    global $IO_ROWS;
    global $IO_ERR;
    global $_Lists;
    global $ANSWER;
    _get_exchange();
    echo 'what is 1/4 * 8?', "<br>\n";
    if ($ANSWER != '') {
        if ($ANSWER == ((1 / 4) * 8)) {
            echo 'THAT WAS THE ANSWER', "<br>\n";
        } else {
            echo "THAT'S NOT IT DUMMY", "<br>\n";
        }
    }
    $TBS = new \clsTinyButStrong;
    $TBS->SetOption('noerr', false);
    $TBS->LoadTemplate($CurrentTemplate);
    $TBS->Show();
    exit();
}

function _Get_Post()
{
    global $ANSWER;
    $ANSWER = '';
    if (isset($_POST['ANSWER'])) $ANSWER = strtoupper($_POST['ANSWER']);
    echo "<br>";
}
