<?php

namespace COMPILE_FILE;

include_once('tbs_class.php');
include_once('ll_library.php');
_On_Entry(COMPILE_FILE);
function _Main()
{
	global $CurrentScript;
	global $CurrentTemplate;
	global $IO_STS;
	global $_Lists;
	global $COMPILE;
	global $FILE_NAME;
	$_Lists = clearList($_Lists);
	_get_exchange();
	if ($COMPILE == "YES") {
		if ($FILE_NAME == " ") {
			echo "Must ENTER file name", "<br>\n";
		} else {
			echo "Creating OAM", "<br>\n";
			$_sub_parms = array($FILE_NAME);
			list($FILE_NAME) = CREATE_OAM($_sub_parms);
		}
	} else {
		echo "ENTER FILE NAME and YES to compile", "<br>\n";
	}
	$TBS = new \clsTinyButStrong;
	$TBS->SetOption('noerr', false);
	$TBS->LoadTemplate($CurrentTemplate);
	$TBS->Show();
	exit();
}
function CREATE_OAM($_sub_parms)
{
	global $IO_STS;
	global $FILE_NAME;
	list($FILE_NAME) = $_sub_parms;

	_Create_OAM_JS($FILE_NAME);
	_Create_OAM_PHP($FILE_NAME);

	return array($FILE_NAME);
}


function _Get_Post()
{
	global $FILE_NAME;
	global $COMPILE;
	$FILE_NAME = '';
	$COMPILE = '';
	if (isset($_POST['FILE_NAME'])) $FILE_NAME = strtoupper($_POST['FILE_NAME']);
	if (isset($_POST['COMPILE'])) $COMPILE = strtoupper($_POST['COMPILE']);
	echo "<br>";
}
