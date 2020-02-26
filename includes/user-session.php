<?php
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
	require ("./config.php");
	session_start(); //should already be started
	$uid = $_SESSION['uid'];
	
	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 64800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
	}
	$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
	
	var_dump($_SESSION);
	echo "<b>".session_id()."</b>";
?>
