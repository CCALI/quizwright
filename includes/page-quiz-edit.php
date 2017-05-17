<?php
// 5/12/2017 Load quiz page for editing. Use page type to decide which editing template to use.
error_reporting(E_ALL); 

require ("user-session.php");
$pid=intval($_GET["pid"]);
$sql = "SELECT data FROM `page` WHERE pid = '$pid'";
if ($result = $mysqli->query($sql))
{
	$row = $result->fetch_assoc();
	$page = json_decode($row['data'], TRUE);
?>

<!-- Form for editing existing pages.  -->
<script>
var data=<?=$row['data']?>
</script>
<form class="form-horizontal"  id="page-quiz-form">
    <fieldset>
	<input type="hidden" name="pid" value="<?=$pid?>" />
        <div class="form-group">
            <label class="col-sm-2 control-label" for="page">Text of your question</label>
            <div class="col-sm-8">
                <textarea name="page-question" id="page-question"  class="form-control"></textarea>
            </div>
        </div>

<?php
	// Insert page type specific form 
	$pagetype = isset($page['page-type'])?$page['page-type']:'quiz-mc';
	switch ($pagetype)
	{
		// Is this a fuse? :)
		case "quiz-tf":
			require "page-quiz-tf-edit.inc";
			break;
		
		case "quiz-mc":
		case 'Quiz':
			require "page-quiz-mc-edit.inc";
			break;
		default:
			;
	}
}
	// Insert rest of standard page form elements.
?> 

		
        <div class="form-group">
            <label class="col-sm-2 control-label" for="page">Feedback (Optional)</label>

            <div class="col-sm-8">
                <textarea name="page-feedback"  class="form-control"></textarea>
            </div>
        </div>
		  
		   <div class="form-group">
            <label class="col-sm-2 control-label" for="page-topic">Topic Area</label>
 
            <div class="col-sm-8">
					<select name="page-topic" id="page-topic" class="form-control"></select>
            </div>
        </div>
		   <div class="form-group">
            <label class="col-sm-2 control-label" for="page-topic">Attribution (optional)</label>
 
            <div class="col-sm-8">
		      <input id="page-attribution" name="page-attribution" placeholder="" class="form-control" type="text" value="">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="page">Teacher Notes (Optional)</label>

            <div class="col-sm-8">
                <textarea name="page-notes"  class="form-control"></textarea>
            </div>
        </div>
		  
						  
        
		<div class="xform-inline">
			<div class="form-group">
				<label class="col-sm-2 control-label" for="page">Share?</label>
				<div class="col-sm-4">
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-default "> <input type="radio"  name="public" value="false" autocomplete="off" > Private   </label>
						<label class="btn btn-default"> 	<input type="radio" name="public" value="true" autocomplete="off"> Public   </label>
					</div>
				</div> 
			</div>
		</div>
        
         
        <!-- Button -->

        <div class="form-group">
            <label class="col-sm-2 control-label" for="page-submit">Done?</label>

            <div class="col-sm-3">
                <button id="page-submit" name="page-submit" class="btn btn-primary">Update</button>
            </div> 
        </div>
        
    </fieldset>
</form>
 
<script>
$('[name=page-question]').text(data['page-question']);
$('[name=page-feedback]').text(data['page-feedback']);
cawSetRB('public',data['public']);
$('[name=page-attribution]').val(data['page-attribution']);
$('[name=page-notes]').text(data['page-notes']);
cawLoadCALITopics($('#page-topic'),data['page-topic']);

$('[data-toggle=collapse]').unbind().click(function(){
	// toggle icon
	$(this).find("i").toggleClass("glyphicon-chevron-right glyphicon-chevron-down");
});
$('#page-submit').click(function(){ // Save page let author add new page
	$.post( "./includes/page-update.php", $( "#page-quiz-form" ).serialize() ,function( data ) {
		//console.log(data);
		//$("#main-panel").load("./includes/page-quiz.inc");
		$("#main-panel").load('./includes/question-list.php');//$(this).attr('href'));
	});
	return false;
});
</script>
