<?php
// server should keep session data for AT LEAST 1 hour
ini_set('session.gc_maxlifetime', 64800);

// each client should remember their session id for EXACTLY 1 hour
session_set_cookie_params(64800);
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
require ("./includes/config.php");

if (isset($_POST["lesson-submit"])){
	$data = json_encode($_POST);
	$uid = $_SESSION['uid'];
	$mysqli->query("INSERT INTO info (lid,uid,data) VALUES ('',$uid,'$data')");
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta http-equiv="pragma" content="no-cache">
		<meta charset="utf-8">
		<title>CALI QuizWright</title>
		<meta name="generator" content="Bootply" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
		<!--[if lt IE 9]>
			<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link href="css/styles.css" rel="stylesheet">
	</head>
	<body>		
<?php
// load user control
	include(USER_MGMNT);
?>
<footer class="text-center"><div id="footer-wrapper">
    <div ><img src="images/CALI_LogoTagline_DarkGrayMedium.png" /></div>
	<div class="copyright-text">Copyright &copy; 2017, All Contents Copyright<br>The Center for Computer-Assisted Legal Instruction</div>
<?php
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 64800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
	}
	$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
var_dump($_SESSION);
echo "<b>".session_id()."</b>";
$params = session_get_cookie_params();
var_dump($params);
?>
</footer>

	<!-- script references -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
      <script src="//cdn.ckeditor.com/4.7.0/basic/ckeditor.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/scripts.js"></script>
	</body>
</html>
