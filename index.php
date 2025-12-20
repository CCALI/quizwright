<?php
date_default_timezone_set("America/Chicago");

require_once(__DIR__ . '/includes/config.php');

// Use SimpleSAML session
session_name('SimpleSAML');
session_start();

// Check if user is authenticated
$isAuthenticated = isset($_SESSION['uid']) && $_SESSION['uid'] > 0;

if (!$isAuthenticated) {
    // Show login page instead of the app
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CALI QuizWright - Login Required</title>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .login-container {
                max-width: 500px;
                margin: 100px auto;
                padding: 30px;
                border: 1px solid #ddd;
                border-radius: 5px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="login-container">
                <h2>CALI QuizWright</h2>
                <p>QuizWright is a web app that lets teachers write individual MC, T/F, Y/N questions,
                saves the questions in a personal question bank, allows teachers to bundle the questions
                into quizzes, turns the quizzes into AutoPublish Lessons that are published to the CALI
                website and run by students either as LessonLive or LessonLink assessments.</p>
                <p><strong>All you need to use QuizWright is a CALI member faculty/staff account.</strong></p>
                <a href="login.php?return=<?php echo urlencode($_SERVER['REQUEST_URI'] ?? '/quizwright/'); ?>" class="btn btn-primary btn-lg">
                    Log in with your CALI Account
                </a>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// User is authenticated, continue with normal functionality
if (isset($_POST["lesson-submit"])){
	$data = json_encode($_POST);
	$uid = $_SESSION['uid'];
	$mysqli->query("INSERT INTO info (lid,uid,data) VALUES ('',$uid,'$data')");
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta http-equiv="pragma" content="no-cache">
		<meta charset="utf-8">
		<title>CALI QuizWright</title>
		<meta name="generator" content="Bootply" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
		<!--[if lt IE 9]>
			<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link href="css/styles.css" rel="stylesheet">
	</head>
	<body>
<?php
// Load the main UI for authenticated users
$username = $_SESSION['username'];
include('./includes/home.php');
?>
<footer class="text-center"><div id="footer-wrapper">
    <div ><img src="images/CALI_LogoTagline_DarkGrayMedium.png" /></div>
	<div class="copyright-text">Copyright &copy; 2017, All Contents Copyright<br>The Center for Computer-Assisted Legal Instruction</div>
</footer>

	<!-- script references -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
      <!--
			<script src="//cdn.ckeditor.com/4.7.0/basic/ckeditor.js"></script>
			<script src="//cdn.ckeditor.com/4.22.1/basic/ckeditor.js"></script>
		-->

<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.css" />
<script type="importmap">
{
    "imports": {
        "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.js",
        "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.0.0/"
    }
}
</script>

<script type="module" >
	//console.log('module');
	import { ClassicEditor, Essentials, Bold, Italic, Paragraph, Underline, Superscript, Subscript, Strikethrough, List , Indent, Undo } from 'ckeditor5';
	import coreTranslations from 'ckeditor5/translations/en.js';
	window.CKEditorConstruct=function(id){
		//console.log('module CKEditorConstruct');
		return ClassicEditor
	    .create( document.querySelector( id ), {
	        plugins: [ Essentials, Bold, Italic, Paragraph, Underline, Superscript, Subscript, Strikethrough, List , Indent, Undo],
			  toolbar: {
                        items: [
                            //'cut','copy','clipboard', '|',
									 'undo', 'redo', '|',
									 'bold', 'italic', 'underline','|', 'strikethrough', 'subscript', 'superscript', '|',
									 'bulletedList', 'numberedList','|',
                            'outdent', 'indent'
                        ]
                    },
	        //licenseKey: '<LICENSE_KEY>',
	        translations: [
	            coreTranslations
	        ]
	    } )
		.then( editor => {
			editors[id]=editor;
			//console.log( 'Editor was initialized', editor );
		} )
		.catch( err => {
			console.error( err.stack );
		} );
	 };
</script>

		
		<script src="js/bootstrap.min.js"></script>
		<script src="js/scripts.js"></script>
	</body>
</html>