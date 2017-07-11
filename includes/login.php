<!-- header -->
<div id="top-nav" class="navbar navbar-inverse navbar-static-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">CALI QuizWright</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#"><i class="glyphicon glyphicon-user"></i> <?php echo $username; ?> <span class="caret"></span></a>
                    <ul id="g-account-menu" class="dropdown-menu" role="menu">
                        <li><a href="#">My Profile</a></li>
                    </ul>
                </li>
                <li><a href="?u=logout"><i class="glyphicon glyphicon-lock"></i> Logout</a></li>
            </ul>
        </div>
    </div>
    <!-- /container -->
</div>
<!-- /Header -->
<!-- Main -->
<div class="container-fluid">
    <div class="row">
		<div class="panel panel-default">
            <div class="panel-heading">
                <h4>Welcome to QuizWright</h4>
			</div>
            <div class="panel-body">
                <p>QuizWright is a web app that lets teachers write individual MC, T/F, Y/N questions,
				saves the questions in a personal question bank, allows teachers to bundle the questions
				into quizzes, turns the quizzes into AutoPublish Lessons that are published to the CALI
				website and run by students either as LessonLive or LessonLink assessments.</p>
				<p>All you need to use QuizWright is a CALI member faculty/staff account.
				Just enter your CALI username and password below. </p>
            </div>
        </div>
	</div>
	<div class="row">
	   <div id="userforms">
	  <form class="form-signin" method="POST" action="?u=login">
		<?php if (isset($regalert)){ ?><div class="alert alert-success" role="alert"><?php echo $regalert; ?></div><?php } ?>
        <h2 class="form-signin-heading">Please Login</h2>
		<h4 class="form-signin-heading"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>Need an account? <a href="?u=register">Register here.</a></h4>
        <div class="input-group">
	  <span class="input-group-addon" id="basic-addon1">@</span>
	  <input type="text" name="username" class="form-control" placeholder="Username" required>
	</div>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
      </form>
	   </div>
	</div>
</div>
<!-- /Main -->