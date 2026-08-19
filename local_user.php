<?php
/**
 * this file contains user management stuff
 * for default local user control
 * It should serve as a model for other user
 * management schemes like Drupal control
 *
 * Loaded via USER_MGMNT from index.php, which has already required
 * includes/session.php (so the session is open and qw_session_destroy()
 * is available) and includes/config.php (so $mysqli and SITE_URL exist).
 */

$user = htmlspecialchars($_GET['u'] ?? '');

switch($user) {
	case "login":
		if (isset($_POST['username']) and isset($_POST['password'])){
		$username = $_POST['username'];
		$password = $_POST['password'];
		$stmt = $mysqli->prepare("SELECT * FROM `people` WHERE username = ?");
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$result = $stmt->get_result();
		$count = $result->num_rows;
		if ($count == 1){
			$row = $result->fetch_array(MYSQLI_ASSOC);
			$stored = (string) $row['password'];
			// Accounts predating password hashing still hold the password in
			// plaintext. Accept those once, then transparently upgrade the row.
			$isHashed = password_get_info($stored)['algo'] ? true : false;
			if ($isHashed) {
				$ok = password_verify($password, $stored);
			} else {
				$ok = hash_equals($stored, $password);
				if ($ok) {
					$rehash = password_hash($password, PASSWORD_DEFAULT);
					$up = $mysqli->prepare("UPDATE `people` SET password = ? WHERE uid = ?");
					$up->bind_param("si", $rehash, $row['uid']);
					$up->execute();
					$up->close();
				}
			}
			if ($ok) {
				// New privilege level for this session — reissue the id so a
				// pre-login session id cannot be replayed as an authenticated one.
				session_regenerate_id(true);
				$_SESSION['username'] = $row['username'];
				$_SESSION['uid'] = $row['uid'];
			} else {
				$fmsg = "Invalid Login Credentials.";
			}
		}else{
		$fmsg = "Invalid Login Credentials.";
			}
		$stmt->close();
		}
		if (isset($_SESSION['username'])){
			$username = $_SESSION['username'];
			include('./includes/home.php');
		}else{
			$regalert = $_SESSION['regalert'] ?? null;
			include('./includes/login.php');
		}
		break;
	case "register":
		$result = null;
		if (isset($_POST['r_username']) && isset($_POST['r_password'])){
        $username = $_POST['r_username'];
		$email = $_POST['r_email'] ?? '';
        $password = password_hash($_POST['r_password'], PASSWORD_DEFAULT);

        $stmt = $mysqli->prepare("INSERT INTO `people` (username, email, password) VALUES (?, ?, ?)");
		$stmt->bind_param("sss", $username, $email, $password);
        $result = $stmt->execute();
		$stmt->close();
		}
        if($result){
            $_SESSION['regalert'] = "You've registered successfully. Enjoy the site.";
			header('Location:'.SITE_URL);
			exit;
        }else{
            $fmsg ="User Registration Failed";
			qw_session_destroy();
			include('./includes/register.php');
			}

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
