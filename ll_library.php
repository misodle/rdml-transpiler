<?php

function _Create_OAM_PHP($table) {
	$table = strtolower($table);
	$query = 'SHOW COLUMNS FROM '.$table;
	$result = mysql_query($query);
	if (!$result) {
			echo 'Could not run query: ' . mysql_error();
			exit;
	}
	
	$response = "<?php \n";
	$response .= "unset(\$_fields); \n";
	if (mysql_num_rows($result) > 0) 
	{
		while ($row = mysql_fetch_assoc($result)) 
		{
			$response .=  "unset(\$_attrs); \n";
			$fld_name = strtoupper($row["Field"]);
			$fld_type = strtoupper($row["Type"]);
			$fld_key  = strtoupper($row["Key"]);
			$response .= "\$_attrs['FIELD'] = '$fld_name'; \n";
			$response .= "\$_attrs['TYPE'] = '$fld_type'; \n";
			$response .= "\$_attrs['KEY']  = '$fld_key'; \n";
			$response .= "\$_fields['$fld_name'] = \$_attrs; \n";
		}
	}
	$response .= "\$_tables['$table'] = \$_fields; \n";
	$response .= "?> \n";
	$table = "oam_" . $table . ".php";
	
	var_dump($table);
	var_dump($response);
	
	file_put_contents($table,$response);
}

function _Create_OAM_JS($table) {
	$table = strtolower($table);
	$query = 'SHOW COLUMNS FROM '.$table;
	$result = mysql_query($query);
	if (!$result) {
			echo 'Could not run query: ' . mysql_error();
			exit;
	}
	
	$response = "// " . $table . "; \n";
	$response .= "tfields.length = 0; \n";
	if (mysql_num_rows($result) > 0) 
	{
		while ($row = mysql_fetch_assoc($result)) 
		{
			$fld_name = strtoupper($row["Field"]);
			$fld_type = strtoupper($row["Type"]);
			$fld_key  = strtoupper($row["Key"]);
			$response .= "tfields.push('$fld_name'); \n";
			$response .= "tfields.push('$fld_type'); \n";
			$response .= "tfields.push('$fld_key'); \n";
		}
	}
	$response .= "tables.push('$table'); \n";
	$response .= "tables.push(tfields); \n";
	$table = "oam_" . $table . ".txt";
	
	var_dump($table);
	var_dump($response);
	
	file_put_contents($table,$response);
}


function _On_Entry($pgm) {
	global $First_Time;
	global $CurrentScript; 
	global $CurrentTemplate; 
	$CurrentScript = $pgm . '.php';
	$CurrentTemplate = $pgm . '.html';
	if ($First_Time != "N") {
		$First_Time = "N";
		$_ExecPost = '\\' . $pgm . '\\_Get_Post';
		$_ExecPost();
		$_ExecMain = '\\' . $pgm . '\\_Main';
		$_ExecMain();
	}
	// this no longer works because current script this include file
	//$CurrentScript = basename(__FILE__); 
	//$CurrentTemplate = basename(__FILE__,".php").".html"; 
}

function _exchange($_sub_parms) 
{
	global $_Exchange_List;
	foreach($_sub_parms as $field => $value) { 
		$_Exchange_List[$field] = $value;
	}
}

function _get_exchange() 
{
	global $_Exchange_List;
	global $_Lists;
	if (count($_Exchange_List) <= 0)
	{
		return;
	}
	foreach($_Exchange_List as $field => $value) 
	{ 
		$GLOBALS[$field] = $value;										//restore variable to this program
		if(is_array($GLOBALS[$field]))									// is the value restored an array?
		{
			foreach($_Lists as $listname => $listcount) 				// if its an array look up the listcounter for it
			{				
				if($field == $listname) 								// found the right entry?
				{
					if($listcount != 'N/A') 							// does it have a listcounter?
					{
						$GLOBALS[$listcount]=count($GLOBALS[$field]);	// set the listcounter
					}	
				break;
				}
		}	}
	}
	unset($GLOBALS['_Exchange_List']);
}

// this destroys the global reference, however the reference in main() is not destoryed....arghhhhh
// the following illustrates the problem, we use NULL instead
// <?php
// main();
// echo "<br>";echo "foo=";echo $bar;
// function main() {
// 	global $bar;$bar = "something";foo();echo "foo=";echo $bar;
// }

// function foo() {
// $var='bar';unset($GLOBALS[$var]);
// }
// ? >

function func_call($pgm)
{
// backup variables from GLOBALS
// remove variables from GLOBALS 
	foreach($GLOBALS as $GLOBALS_ptr => $GLOBALS_row) { 
		if ($GLOBALS_ptr == '_GET' || $GLOBALS_ptr == '_COOKIE' || 
			$GLOBALS_ptr == '_FILES' || $GLOBALS_ptr == '_SERVER') 
		{	
			unset($GLOBALS[$GLOBALS_ptr]);
		}
		if ($GLOBALS_ptr != 'GLOBALS' && $GLOBALS_ptr != '_GET' && $GLOBALS_ptr != '_POST' && $GLOBALS_ptr != '_COOKIE' && 
			$GLOBALS_ptr != '_FILES' && $GLOBALS_ptr != '_SERVER' && $GLOBALS_ptr != 'First_Time' && $GLOBALS_ptr != '_Exchange_List' ) 
		{	
			$_SAVE[$GLOBALS_ptr] = $GLOBALS_row;
			$GLOBALS[$GLOBALS_ptr] = NULL;
		}
	}

// call next function	
	$pname = $pgm . '.php';
	$fname = '\\' . $pgm . '\\_Main';
	include_once($pname);
	$fname();
	
// restore variables to GLOBALS
	foreach($_SAVE as $_SAVE_ptr => $_SAVE_row) {
		$GLOBALS[$_SAVE_ptr] = $_SAVE_row;
	}
}

function array_orderby()
// sort a list (version 5.3 compatible)
{
    $args = func_get_args();
	$data = array_shift($args);
	foreach ($args as $n => $field) {
        if (is_string($field)) {
				$tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
            }
    }
	// must pass by reference now (https://bugs.php.net/bug.php?id=49069)
	$c = count($args);
	for ($i=0; $i<$c; $i+=1) {
		$pass_args[] = &$args[$i];
	}
	$pass_args[] = &$data;	
	call_user_func_array('array_multisort', $pass_args);
    return array_pop($pass_args);
}

function clearList($_listname)
// Clear a List
{
	$ctr=count($_listname);
	for ($X=1; $X<=$ctr; $X+=1) {
		array_pop($_listname);
	}
	return $_listname;
}

function getPostedList($_listname,$_field_array)
// process posted lists
{
	$_return_list[] = array();													// create 1 row emptry array
	array_pop($_return_list);													// remove first entry in array, now array is empty
	$_listcount = 0;															// default listcount = 0
	
	$_first = 'Y';	
	
	foreach ($_POST as $_postvar => $_postvalue) {
		if(strpos($_postvar,':') > 0) {   										// field format = "list:row:variable"
			list($_list,$_row,$_var)=explode(':',$_postvar);
			if ($_listname == $_list) {
				if ($_first == 'Y') {											// first time prime the loop
					$_work_row = $_row;
					$_first = 'N';
				}
				if ($_work_row != $_row) {								   		// process break
					$_return_list[] = $_work_array;								// save row
					$_work_array = array();										// clear working row
					$_listcount++;										
					$_work_row = $_row;									
				}
				foreach ($_field_array as $_fld_array) {						// only get defined fields to prevent data forging
					$varName = $_fld_array[0];
					$attrArray = $_fld_array;
					if ($varName == $_var) {									// found a matching field
						$inpField = true;									
						$lowerCase = false;
						if (in_array("*IN",$attrArray))   $inpField = true;
						if (in_array("*HIDE",$attrArray)) $inpField = true;
						if (in_array("*OUT",$attrArray))  $inpField = false;
						if (in_array("*LC",$attrArray))   $lowerCase = true;
						if (inpField == true) {									// only accept input fields
							if ($lowerCase == false) $_work_array[$_var] = strtoupper($_postvalue);
							if ($lowerCase == true)  $_work_array[$_var] = $_postvalue;
						}
						break;													// found match so exit loop
					}
				}
			}
		}	
	}
	if ($_first == 'N') {												// capture the last list row break
		$_return_list[] = $_work_array;
		$_listcount++;		
	}
    return array($_return_list,$_listcount);
}

function DBConnect()
{

	global $body;

	// setup database connection info
	
	/* $host = "localhost";
	$user = "root";
	$pwd = "sql413";
	$dbase = "ffdev"; */

	// load .env.local (key=value format) into $_ENV
	foreach (parse_ini_file(__DIR__ . '/.env.local') as $k => $v) {
    	$_ENV[$k] = $v;
	}

	// use
	$host  = $_ENV['DB_HOST'];
	$user  = $_ENV['DB_USER'];
	$pwd   = $_ENV['DB_PASS'];
	$dbase = $_ENV['DB_NAME'];
	
	// connect to mysql server (persistent)
	$conID = mysql_pconnect($host,$user,$pwd);   // this is not possible in mysli extension
	//$conID = mysql_connect($host,$user,$pwd);
	if ($conID == FALSE)
	{
		$body .= "Failed to connect to database server. This is a fatal error.<br>\n";
		return FALSE;
	}

	// connect to database
	//$curDB = mysql_select_db($dbase);
	$curDB = mysql_select_db($dbase,$conID);
	if ($curDB == FALSE)	
	{
		$body .= "Failed to connect to named database. This is a fatal error.<br>\n";
		return FALSE;
	}

	return TRUE;
}

$conn = DBConnect();
if ($conn == FALSE) echo "<BR>DB connection failed.\n";

?>