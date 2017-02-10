<?php
require ("./config.php");
//Start the Session
//session_start();
    // If the values are posted, insert them into the database.
    if (isset($_POST['r_username']) && isset($_POST['r_password'])){
        $username = $_POST['r_username'];
		$email = $_POST['r_email'];
        $password = $_POST['r_password'];
 
        $query = "INSERT INTO `people` (uid, username, email, password) VALUES ('','$username', '$email', '$password')";
		
        $result = $mysqli->query($query);
        if($result){
            $_SESSION['regalert'] = "You've registered successfully. Please login.";
			header('Location:'.SITE_URL);
        }else{
            $fmsg ="User Registration Failed";
        }
    }
    ?>
<div class="container">
	  <div id="userforms">
      <form class="form-signin" method="POST" action="includes/register.php">
	  <?php if(isset($smsg)){ ?><div class="alert alert-success" role="alert"> <?php echo $smsg; ?> </div><?php } ?>
      <?php if(isset($fmsg)){ ?><div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
        <h2 class="form-signin-heading">Please Register</h2>
        <div class="input-group">
	  <span class="input-group-addon" id="basic-addon1">@</span>
	  <input type="text" name="r_username" class="form-control" placeholder="Username" required>
	</div>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" name="r_email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="r_password" id="inputPassword" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
		
      </form>
	  </div>
</div>