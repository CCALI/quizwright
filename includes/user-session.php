<?php
	require ("./config.php");
	// Use SimpleSAML session (same as index.php)
	session_name('SimpleSAML');
	session_start();
	$uid = $_SESSION['uid'];
?>
