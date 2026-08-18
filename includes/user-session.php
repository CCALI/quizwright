<?php
	// Included by .php pages that need database and user information.
	// session.php must come first: it configures the shared session store and
	// opens the session. Loading config.php first would be too late.
	require_once __DIR__ . '/session.php';
	require_once __DIR__ . '/config.php';
	$uid = (int) ($_SESSION['uid'] ?? 0);
?>
