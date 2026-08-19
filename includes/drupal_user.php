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
 
$user = htmlspecialchars($_GET['u'] ?? '');
 
 switch($user) {
	case "login":				
		if (isset($_GET['token'])){
   // NOTE: this authenticates on a Drupal session id handed over in a query
   // string. It leaks through Referer headers, proxy logs and browser history.
   // It should be replaced with the SAML flow; parameterized here so it is at
   // least not also an injection point.
   $sid = $_GET['token'];
   $stmt = $umysqli->prepare("SELECT uid FROM `sessions` WHERE sid = ?");
   $stmt->bind_param("s", $sid);
   $stmt->execute();
   $row = $stmt->get_result()->fetch_assoc();
   $stmt->close();
   $uid = (int) ($row['uid'] ?? 0);
   $stmt = $umysqli->prepare("SELECT * FROM `users` WHERE uid = ?");
   $stmt->bind_param("i", $uid);
   $stmt->execute();
   $result = $stmt->get_result();
  $count = $result->num_rows;
  $stmt->close();
		if ($count == 1){
			$account = $result->fetch_object();	
			// check roles, needs CALI Staff or facstaff to proceed
			$userid = $account->uid;
			$stmt = $umysqli->prepare("SELECT * FROM `users_roles` WHERE uid = ? and rid in (5,6)");
			$stmt->bind_param("i", $userid);
			$stmt->execute();
			$count = $stmt->get_result()->num_rows;
			$stmt->close();
			if ($count >= 1) {
				$name = $account->name;
				$email = $account->mail;
				$password = $account->pass;
				// 1: check to see if user in people table
				/**
				 * at this point we've verified the passwd in Drupal and
				 * matched it to a username. If that username already exists
				 * in the local people table, let's just carry on
				 */
				$stmt = $mysqli->prepare("SELECT * FROM `people` WHERE username = ?");
				$stmt->bind_param("s", $name);
				$stmt->execute();
				$result = $stmt->get_result();
				$count = $result->num_rows;
				$stmt->close();
				if ($count == 1){
					$row = $result->fetch_array(MYSQLI_ASSOC);
					// New privilege level for this session — reissue the id.
					session_regenerate_id(true);
					$_SESSION['username'] = $row['username'];
					$_SESSION['uid'] = $row['uid'];
				} else {
					// 2: if not add to people table
					// let's stash the drupal user object in the people table.
					$data = json_encode($account);
					$stmt = $mysqli->prepare("INSERT INTO `people` (username, email, password, data) VALUES (?, ?, ?, ?)");
					$stmt->bind_param("ssss", $name, $email, $password, $data);
					if($result = $stmt->execute()){
						$uid = $mysqli->insert_id;
						// New privilege level for this session — reissue the id.
						session_regenerate_id(true);
						$_SESSION['username'] = $name;
						$_SESSION['uid'] = $uid;
					} else {
						 printf("Error: %s\n", $mysqli->error);
					}
					
					
				}
				
				
				// 3: update user in people table if necessary
				
				
			}
   }
   
  }elseif (isset($_POST['username']) and isset($_POST['password'])){
		$name = $_POST['username'];
		$password = $_POST['password'];
		$stmt = $umysqli->prepare("SELECT * FROM `users` WHERE name = ?");
		$stmt->bind_param("s", $name);
		$stmt->execute();
		$result = $stmt->get_result();
		$count = $result->num_rows;
		$stmt->close();
		if ($count == 1){
			$account = $result->fetch_object();	
			// check roles, needs CALI Staff or facstaff to proceed
			$userid = $account->uid;
			$stmt = $umysqli->prepare("SELECT * FROM `users_roles` WHERE uid = ? and rid in (5,6)");
			$stmt->bind_param("i", $userid);
			$stmt->execute();
			$count = $stmt->get_result()->num_rows;
			$stmt->close();
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
				$stmt = $mysqli->prepare("SELECT * FROM `people` WHERE username = ?");
				$stmt->bind_param("s", $name);
				$stmt->execute();
				$result = $stmt->get_result();
				$count = $result->num_rows;
				$stmt->close();
				if ($count == 1){
					$row = $result->fetch_array(MYSQLI_ASSOC);
					// New privilege level for this session — reissue the id.
					session_regenerate_id(true);
					$_SESSION['username'] = $row['username'];
					$_SESSION['uid'] = $row['uid'];
				} else {
					// 2: if not add to people table
					// let's stash the drupal user object in the people table.
					$data = json_encode($account);
					$stmt = $mysqli->prepare("INSERT INTO `people` (username, email, password, data) VALUES (?, ?, ?, ?)");
					$stmt->bind_param("ssss", $name, $email, $password, $data);
					if($result = $stmt->execute()){
						$uid = $mysqli->insert_id;
						// New privilege level for this session — reissue the id.
						session_regenerate_id(true);
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
			
			include('./includes/login.php');
		}
		break;
	case "register":
		
		header('Location:'.DRUPAL_REGISTER);
		
		break;
	case "logout":	
		qw_session_destroy();
		header('Location:'.SITE_URL.'?u=login');
		exit;
	default:
		header('Location:'.SITE_URL.'?u=login');
		exit;
}
 ?>