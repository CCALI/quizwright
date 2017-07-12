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
                        <li><a id="profile-edit" href="#">My Profile</a></li>
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
        <div class="col-sm-3">
            <!-- Left column -->
            <a href="#"><strong><i class="glyphicon glyphicon-wrench"></i> QuizWright Toolbox</strong></a>

            <hr>

            <ul class="nav nav-stacked">
                <li class="nav-header"> <a href="#" data-toggle="collapse" data-target="#userMenu">Quizzes <i class="glyphicon glyphicon-chevron-down"></i></a>
                    <ul class="nav nav-stacked collapse in" id="userMenu">
								
                        <li><a id="author-edit" href="#"> <i  class="glyphicon glyphicon-user"></i> My Profile</a></li>
                        <li class="active"> <a id="quiz-list" href="#"><i class="glyphicon glyphicon-th-list"></i> My Quizzes</a></li>
                        <li class="active"> <a id="question-list" href="#"><i class="glyphicon glyphicon-th-list"></i> My Questions</a></li>
                        <li><a id="new" href="#" data-toggle="collapse" data-target="#lessonMenu"><i class="glyphicon glyphicon-plus"></i> New</a></li>
						<ul class="nav nav-stacked collapse" id="lessonMenu">
							<!--<li><a id="lesson-info" href="#" ><i class="glyphicon glyphicon-plus"></i>Info</a></li>-->
							<!--<li><a id="page" href="#" ><i class="glyphicon glyphicon-plus"></i>Page</a></li>-->
							<li><a id="question-new" href="#" ><i class="glyphicon glyphicon-plus"></i>Question</a></li>
							<li><a id="quiz-new" href="#" ><i class="glyphicon glyphicon-plus"></i>Quiz</a></li>
						</ul>
                        <li><a href="#"><i class="glyphicon glyphicon-cog"></i> Options</a></li>
                        
                    </ul>
                </li>
                <!-- <li class="nav-header"> <a href="#" data-toggle="collapse" data-target="#menu2"> Reports <i class="glyphicon glyphicon-chevron-right"></i></a>

                    <ul class="nav nav-stacked collapse" id="menu2">
                        <li><a href="#">Information &amp; Stats</a>
                        </li>
                        <li><a href="#">Views</a>
                        </li>
                        <li><a href="#">Requests</a>
                        </li>
                        <li><a href="#">Timetable</a>
                        </li>
                        <li><a href="#">Alerts</a>
                        </li>
                    </ul>
                </li> -->
                
            </ul>

            <hr>

            <a href="#"><strong><i class="glyphicon glyphicon-link"></i> CALI Teaching Resources</strong></a>

            <hr>

            <ul class="nav nav-pills nav-stacked">
                <li class="nav-header"></li>
					 <!--<li  > <a id="question-list-public" href="#"><i class="glyphicon glyphicon-list"></i> Question Library</a></li>-->
					 <li><a href="https://www.cali.org/lessons" target="_blank"><i class="glyphicon glyphicon-list"></i> Lessons</a></li>
					<li><a href="https://www.cali.org/lessonlink" target="_blank"><i class="glyphicon glyphicon-list"></i> LessonLink</a></li>
					<li><a href="https://www.cali.org/autopublish" target="_blank"><i class="glyphicon glyphicon-briefcase"></i> AutoPublish</a></li>
					<li><a href="https://www.cali.org/the-elangdell-bookstore" target="_blank"><i class="glyphicon glyphicon-book"></i> CALI Bookstore</a></li>
					<li><a href="https://www.cali.org/content/cali-tools" target="_blank"><i class="glyphicon glyphicon-wrench"></i> CALI Power Tools</a></li>
            </ul>

           
        </div>
        <!-- /col-3 -->
        <div class="col-sm-9">

            <!-- column 2 -->
            <a href="#"><strong><i class="glyphicon glyphicon-dashboard"></i> QuizWright Builder</strong></a>
            <hr>

            <div class="row">
				<div id="main-panel">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4>Welcome to QuizWright</h4>
						</div>
						<div class="panel-body">
							<p>QuizWright is a web app that lets teachers write individual MC, T/F, Y/N questions,
							saves the questions in a personal question bank, allows teachers to bundle the questions
							into quizzes, turns the quizzes into AutoPublish Lessons that are published to the CALI
							website and run by students either as LessonLive or LessonLink assessments.</p>
							<p>Get started by adding a question or three and then add a quiz.</p>
						</div>
					</div>
					<div class="btn-group btn-group-justified">
                        <a href="#" id="add-question" class="btn btn-primary col-sm-3" title="Start here!">
                            <i class="glyphicon glyphicon-pencil"></i>
                            <br> Add a question
                        </a>
                        <a href="#" id="add-quiz" class="btn btn-primary col-sm-3" title="Remember to add some questions first!">
                            <i class="glyphicon glyphicon-pencil"></i>
                            <br> Add a quiz
                        </a>
                        <a href="#" id="list-questions" class="btn btn-primary col-sm-3">
                            <i class="glyphicon glyphicon-th-list"></i>
                            <br> List my questions
                        </a>
                        <a href="#" id="list-quizzes" class="btn btn-primary col-sm-3">
                            <i class="glyphicon glyphicon-th-list"></i>
                            <br> List my quizzes
                        </a>
                    </div>
				</div>
            </div>
            <!--/row-->

          
        </div>
        <!--/col-span-9-->
    </div>
</div>
<!-- /Main -->

