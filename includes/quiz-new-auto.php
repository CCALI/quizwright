<!-- Lesson Information form filled from recent existing lesson -->

<?php
// 3/2/2017 Build final form with initial pages and quiz info to fill in. 
require ("user-session.php");

// Build list of desired pages, include on the final publish form.
$pages=array();
foreach ($_POST as $key => $value)
{
	$pid = intval($key);
	if ($value=='on' && $pid>0)
	{
		 array_push($pages,$pid);
	} 
}
$pages=join(',',$pages);

  
// Default quiz info (overridden with author's most recent quiz)
$data['title']='My Quiz';
$data['subjectarea']='';
$data['calidescription']='';
$data['completiontime']='20 minutes';

// Grab author's most recent lesson info for this new lesson.
$sql = "SELECT * FROM `info` WHERE uid = '$uid' order by lid desc limit 1";
if ($result = $mysqli->query($sql))
{
	while ($row = $result->fetch_assoc())
	{
		$data = json_decode($row['data'], TRUE);
	}
}
?> 
 


<form class="form-horizontal" id="quiz-info-form" method="post">
<fieldset>

<!-- Form Name -->
<legend>Quiz Information</legend>
<input type="hidden" name="pages" value="<?=$pages?>" />

<!-- Text input-->
<div class="form-group">
  <label class="col-sm-2 control-label" for="title">Title</label>
  <div class="col-sm-8">
    <input id="title" name="title" placeholder="My Quiz" class="form-control" type="text" value="<?=$data['title']?>">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-sm-2 control-label" for="subjectarea">Subject Area</label>
  <div class="col-sm-8">
    <input id="subjectarea" name="subjectarea" placeholder="Contracts" class="form-control" type="text" value="<?=$data['subjectarea']?>">
    
  </div>
</div>

<!-- Textarea -->
<div class="form-group">
  <label class="col-sm-2 control-label" for="calidescription">Brief Description</label>
  <div class="col-sm-8">                     
    <textarea id="calidescription" name="calidescription" class="form-control"><?=$data['calidescription']?></textarea>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-sm-2 control-label" for="completiontime">Est. Completion Time</label>
  <div class="col-sm-8">
    <input id="completiontime" name="completiontime" placeholder="20 minutes" class="form-control" type="text" value="<?=$data['completiontime']?>">
    
  </div>
</div>



<!-- Button -->
<div class="form-group">
  <label class="col-sm-2 control-label" for="submit">All done?</label>
  <div class="col-sm-8">
    <button id="quiz-update-submit"   class="btn btn-primary">Save Quiz</button>
  </div>
</div>

</fieldset>
</form>







<script> 
$("#quiz-update-submit").click(function(){ // Save quiz. 
	$.post( "./includes/quiz-create.php", $( "#quiz-info-form" ).serialize() ,function( data ) {
		$("#main-panel").load("./includes/quiz-detail.php?lid="+data.lid); 
	},'json');
	return false;
});

</script>


