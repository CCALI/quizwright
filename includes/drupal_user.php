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
 
$u = htmlspecialchars($_GET['u']);
 
 switch($u) {
	case "login":				
		if (isset($_POST['username']) and isset($_POST['password'])){
		$name = $_POST['username'];
		$password = $_POST['password'];
		$query = "SELECT * FROM `users` WHERE name='$name'";
		$result = $umysqli->query($query);
		$count = mysqli_num_rows($result);
		if ($count == 1){
			$account = $result->fetch_object();	
			// check roles, needs CALI Staff or facstaff to proceed
			$userid = $account->uid;
			$query = "SELECT * FROM `users_roles` WHERE uid = $userid and rid in (5,6)";
			$result = $umysqli->query($query);
			$count = mysqli_num_rows($result);
			if ($count >= 1) {
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
					$query = "INSERT INTO `people` (username, email, password, data) VALUES ('$name', '$email', '$password', '$data')";
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
		}	
			
		}else{
		$fmsg = "Invalid Login Credentials.";
			}
		}
		if (isset($_SESSION['username'])){
			$username = $_SESSION['username'];
			include('./includes/home.php'); 
		}else{
			// 10/30/2017 SJG Check if user logged into Drupal.
			// Load Drupal boostrap to get Drupal user info. If found, we have user name that we can auto-login (or auto-create). 
			// If user not signed in, userid will be 0.
			if ( 1  && DRUPAL_ROOT_DIR)
			{	// Get user's Drupal info from Drupal session, if it exists. 
				$qwd=getcwd();
				chdir(DRUPAL_ROOT_DIR);
				define('DRUPAL_ROOT', getcwd());
				// Require the bootstrap include
				require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
				// Minimum bootstrap to get user's session info is DRUPAL_BOOTSTRAP_SESSION.
				drupal_bootstrap(DRUPAL_BOOTSTRAP_SESSION);
				//echo ' user=';var_dump($user); 
				$userid =intval($user->uid);

				// ### The code below similar to 'login' section (should be shared function?)
				// lookup drupal creds in QW user table, create QW use record if none found.
	 			// check roles, needs CALI Staff or facstaff to proceed
	 			$query = "SELECT * FROM `users_roles` WHERE uid = $userid and rid in (5,6)";
	 			$result = $umysqli->query($query);
				$count = mysqli_num_rows($result);
	 			if ($count >= 1)
				{	// User logged in to Drupal, get their user id, etc.

				  $account=$user;// Not sure if this is identical to above's account.
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
					  $query = "INSERT INTO `people` (username, email, password, data) VALUES ('$name', '$email', '$password', '$data')";
					  if($result = $mysqli->query($query)){
						  $uid = $mysqli->insert_id;
						  $_SESSION['username'] = $name;
						  $_SESSION['uid'] = $uid;
					  } else {
							printf("Error: %s\n", $mysqli->error);
					  }
  
				  }
				}
				else
				{
					$fmsg = "Invalid Login Credentials."; //? where does this appear (for a student jumping to quizwright)
				}
				//###
				chdir($qwd);
			}
			
		  if (isset($_SESSION['username'])){
			 $username = $_SESSION['username'];
			 include('./includes/home.php');
			}
			if ($userid==0) {
			 // Not in Drupal, do login prompt:
			  include('./includes/login.php');
			 }
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