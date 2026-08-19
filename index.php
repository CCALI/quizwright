<?php
require_once __DIR__ . '/includes/session.php';   // must be first: opens the shared session
require_once __DIR__ . '/includes/config.php';

if (isset($_POST["lesson-submit"])) {
	$data = json_encode($_POST);
	$uid  = (int) ($_SESSION['uid'] ?? 0);
	if ($uid > 0) {
		$stmt = $mysqli->prepare("INSERT INTO info (lid,uid,data) VALUES (0,?,?)");
		$stmt->bind_param("is", $uid, $data);
		$stmt->execute();
		$stmt->close();
	}
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
// load user control
	include(USER_MGMNT);
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