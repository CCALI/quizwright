<!-- Lesson Information form filled from recent existing lesson -->

<?php
// Grab author's most recent lesson info for this new lesson.
require ("user-session.php");

$lid = intval($_GET['lid']);

$sql = "SELECT * FROM `info` WHERE uid = '$uid' order by lid desc limit 1";
if ($result = $mysqli->query($sql)) {
	 while ($row = $result->fetch_assoc()) {
        $data = json_decode($row['data'], TRUE);
		  ?> 
 


<form class="form-horizontal" id="quiz-info-form" method="post">
<fieldset>

<!-- Form Name -->
<legend>Quiz Information</legend>
<input type="hidden" name="lid" value="<?=$lid?>" />

<!-- Text input-->
<div class="form-group">
  <label class="col-sm-2 control-label" for="title">Title</label>
  <div class="col-sm-8">
    <input id="title" name="title" placeholder="My Lesson" class="form-control" type="text" value="<?=$data['title']?>">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-sm-2 control-label" for="subjectarea">Subject Area</label>
  <div class="col-sm-8">
    <input id="subjectarea" name="subjectarea" placeholder="Contracts" class="form-control" type="text">
    
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
    <input id="completiontime" name="completion-time" placeholder="20 minutes" class="form-control" type="text">
    
  </div>
</div>



<!-- Button -->
<div class="form-group">
  <label class="col-sm-2 control-label" for="submit">All done?</label>
  <div class="col-sm-8">
    <button id="quiz-update-submit"   class="btn btn-primary">Publish Quiz!</button>
  </div>
</div>

</fieldset>
</form>




		<?php 
    }
}

?>




<script> 
$('#quiz-update-submit').click(function(){ // Save quiz, publish. 
	$.post( "./includes/quiz-create.php", $( "#quiz-info-form" ).serialize() ,function( data ) {
		$("#main-panel").load("./includes/quiz-detail.php?lid="+data.lid); 
	},'json');
	return false;
});

</script>


