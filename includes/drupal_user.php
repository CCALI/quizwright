<?php
/**
 * this file contains user management stuff
 * for Drupal-based user control
 * It should serve as a model for other user
 * management schemes like Drupal control
 *
 * It takes input from the login form and
 * uses that to get info from a Drupal db
 * then it's passed to the local people table
 * for use with CAW.
 *
 **/
 
 /**
 * You need a copy of password.inc from your Drupal install.
 * This is especially important if Wordpress and Drupal are on 
 * separate servers which is likely. 
 */
require "password.inc";
 
$user = htmlspecialchars($_GET['u']);
 
 switch($user) {
	case "login":				
		if (isset($_POST['username']) and isset($_POST['password'])){
		$name = $_POST['username'];
		$password = $_POST['password'];
		$query = "SELECT * FROM `users` WHERE name='$name'";
		$result = $umysqli->query($query);
		$count = mysqli_num_rows($result);
		if ($count == 1){
			$account = $result->fetch_object();	
			
			if(user_check_password($password, $account)){
				$name = $account->name;
				$email = $account->mail;
				$password = $account->pass;
				// 1: check to see if user in people table
				/**
				 * at this point we've verified the passwd in Drupal and
				 * matched it to a username. If that username already exists
				 * in the local people table, let's just carry on
				 */
				$query = "SELECT * FROM `people` WHERE username='$name'";
				$result = $mysqli->query($query);
				$count = mysqli_num_rows($result);
				if ($count == 1){
					$row = $result->fetch_array(MYSQLI_ASSOC);
					$_SESSION['username'] = $row['username'];
					$_SESSION['uid'] = $row['uid'];
				} else {
					// 2: if not add to people table
					// let's stash the drupal user object in the people table.
					$data = json_encode($account);
					$query = "INSERT INTO `people` (uid, username, email, password, data) VALUES ('','$name', '$email', '$password', '$data')";
					if($result = $mysqli->query($query)){
						$uid = $mysqli->insert_id;
						$_SESSION['username'] = $name;
						$_SESSION['uid'] = $uid;
					} else {
						 printf("Error: %s\n", $mysqli->error);
					}
					
					
				}
				
				
				// 3: update user in people table if necessary
				
				
			}
			
			
		}else{
		$fmsg = "Invalid Login Credentials.";
			}
		}
		if (isset($_SESSION['username'])){
			$username = $_SESSION['username'];
			include('./includes/home.php'); 
		}else{
			
			include('./includes/login.php');
		}
		break;
	case "register":
		
		header('Location:'.DRUPAL_REGISTER);
		
		break;
	case "logout":	
		session_destroy();
		header('Location:'.SITE_URL.'?u=login');
		break;
	default:
		header('Location:'.SITE_URL.'?u=login');
}
 ?>