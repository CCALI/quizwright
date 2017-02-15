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
			include('./includes/home.php'); 
		}else{
			$regalert = $_SESSION['regalert'];
			include('./includes/login.php');
		}
		break;
	case "register":
		if (isset($_POST['r_username']) && isset($_POST['r_password'])){
        $username = $_POST['r_username'];
		$email = $_POST['r_email'];
        $password = $_POST['r_password'];
 
        $query = "INSERT INTO `people` (uid, username, email, password) VALUES ('','$username', '$email', '$password')";
		
        $result = $mysqli->query($query);
		}
        if($result){
            $_SESSION['regalert'] = "You've registered successfully. Enjoy the site.";
			header('Location:'.SITE_URL);
        }else{
            $fmsg ="User Registration Failed";
			session_destroy();
			include('./includes/register.php');
			}
		
		break;
	case "logout":	
		session_destroy();
		header('Location:'.SITE_URL.'?u=login');
		break;
	default:
		header('Location:'.SITE_URL.'?u=login');
}

?>