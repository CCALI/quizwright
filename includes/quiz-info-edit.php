<!-- Revising lesson information  -->
<?php
// 05/19/2017 Revise lesson's information.
require ("user-session.php");

$lid = intval($_REQUEST['lid']);

$sql = "SELECT * FROM `info` WHERE lid = $lid and uid = $uid";
if ($result = $mysqli->query($sql))
{
	if ($row = $result->fetch_assoc())
	{
		$data = json_decode($row['data'], TRUE);
	}
}
?> 


<form class="form-horizontal" id="quiz-info-form" method="post">
<fieldset>

<!-- Form Name -->
<legend>Quiz Information</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-sm-2 control-label" for="title">Title</label>
  <div class="col-sm-8">
    <input id="title" name="title" placeholder="My Quiz" class="form-control" type="text" value="<?=htmlspecialchars($data['title'])?>">
    
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

<!-- Textarea -->
<div class="form-group">
  <label class="col-sm-2 control-label" for="calidescription">Introduction page (optional)</label>
  <div class="col-sm-8">                     
    <textarea id="quiz-intro" name="quiz-intro" class="form-control"><?=$data['quiz-intro']?></textarea>
  </div>
</div>
<!-- Textarea -->
<div class="form-group">
  <label class="col-sm-2 control-label" for="calidescription">Conclusion page (optional)</label>
  <div class="col-sm-8">                     
    <textarea id="quiz-conclusion" name="quiz-conclusion" class="form-control"><?=$data['quiz-conclusion']?></textarea>
  </div>
</div>
<input type=hidden name="lid" value="<?=$lid?>" />

<!-- Button -->
<div class="form-group">
  <label class="col-sm-2 control-label" for="submit">All done?</label>
  <div class="col-sm-8">
    <button id="quiz-update-submit"   class="btn btn-primary">Update</button>
  </div>
</div>

</fieldset>
</form>



<script> 
$("#quiz-update-submit").click(function(){ // Save quiz. 
	$.post( "./includes/quiz-info-update.php", $( "#quiz-info-form" ).serialize() ,function( data ) {
		$("#main-panel").load("./includes/quiz-detail.php?lid="+data.lid); 
	},'json');
	return false;
});

</script>


