<?php
	
    // If the values are posted, insert them into the database.
    if (isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
		$email = $_POST['email'];
        $password = $_POST['password'];
 
        $query = "INSERT INTO `people` (uid, username, email, password) VALUES ('','$username', '$email', '$password')";
		
        $result = $mysqli->query($query);
        if($result){
            $smsg = "User Created Successfully.";
        }else{
            $fmsg ="User Registration Failed";
        }
    }
    ?>
<div class="container">
	  <div id="userforms">
      <form class="form-signin" method="POST">
	  <?php if(isset($smsg)){ ?><div class="alert alert-success" role="alert"> <?php echo $smsg; ?> </div><?php } ?>
      <?php if(isset($fmsg)){ ?><div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
        <h2 class="form-signin-heading">Please Register</h2>
        <div class="input-group">
	  <span class="input-group-addon" id="basic-addon1">@</span>
	  <input type="text" name="username" class="form-control" placeholder="Username" required>
	</div>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
		<div class="well well-sm">Already registered? Please login</div>
        <a id="login" class="btn btn-lg btn-primary btn-block" href="#">Login</a>
      </form>
	  </div>
</div>