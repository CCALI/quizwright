<?php
/**
 * this file contains user management stuff
 * for default local user control
 * It should serve as a model for other user
 * management schemes like Drupal control
 * 
 */
//require ("./includes/config.php");

$user = htmlspecialchars($_GET['u']);

switch($user) {
	case "login":
				
		if (isset($_POST['username']) and isset($_POST['password'])){
		$username = $_POST['username'];
		$password = $_POST['password'];
		$query = "SELECT * FROM `people` WHERE username='$username' and password='$password'";
		$result = $mysqli->query($query);
		$count = mysqli_num_rows($result);
		if ($count == 1){
			$row = $result->fetch_array(MYSQLI_ASSOC);	
			$_SESSION['username'] = $row['username'];
			$_SESSION['uid'] = $row['uid'];
		}else{
		$fmsg = "Invalid Login Credentials.";
		}
		}
		if (isset($_SESSION['username'])){
		$username = $_SESSION['username'];
		var_dump($_SESSION);
		include('./includes/home.php');
		//header('Location:'.SITE_URL); 
		}else{
		include('./includes/login.php');
		}
		break;
	case "register":
		break;
	case "logout":
		
		session_destroy();
		header('Location:'.SITE_URL.'?u=login');
		break;
	default:
		header('Location:'.SITE_URL.'?u=login');
}

?>