<!-- Author profile information - used to populate lesson author info-->

<?php

require ("user-session.php");

$sql = "SELECT profile FROM `people` WHERE uid = '$uid'";
if ($result = $mysqli->query($sql)) {
	 while ($row = $result->fetch_assoc()) {
        $data = json_decode($row['profile'], TRUE); 
		  ?> 
 


<form class="form-horizontal" id="profile-form" method="post">
<fieldset>

<!-- Form Name -->
<legend>Author Profile Information</legend>

<div class="panel panel-default">
  <div class="panel-body">
    This information will appear to students on the first screen of your quizzes. 
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-sm-2 control-label" for="authorfullname">Author Name</label>
  <div class="col-sm-8">
    <input id="authorfullname" name="authorfullname" placeholder="Your full name here" class="form-control" type="text" value="<?=$data['authorfullname']?>">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-sm-2 control-label" for="authortitle">Author title</label>
  <div class="col-sm-8">
    <input id="authortitle" name="authortitle" placeholder="Your title" class="form-control" type="text" value="<?=$data['authortitle']?>">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-sm-2 control-label" for="authorschool">School</label>
  <div class="col-sm-8">
    <input id="authorschool" name="authorschool" placeholder="Your school" class="form-control" type="text" value="<?=$data['authorschool']?>">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-sm-2 control-label" for="authoremail">Email</label>
  <div class="col-sm-8">
    <input id="authoremail" name="authoremail" placeholder="Your email address" class="form-control" type="text" value="<?=$data['authoremail']?>">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-sm-2 control-label" for="authorphone">Phone</label>
  <div class="col-sm-8">
    <input id="authorphone" name="authorphone" placeholder="234-456-7890" class="form-control" type="text" value="<?=$data['authorphone']?>">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-sm-2 control-label" for="webaddress">Web Site</label>
  <div class="col-sm-8">
    <input id="webaddress" name="webaddress" placeholder="http://www.cali.org/" class="form-control" type="text">
    
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-sm-2 control-label" for="submit">All done?</label>
  <div class="col-sm-4">
    <button id="profile-submit"  class="btn btn-primary">Update</button>
  </div>
  <div class="col-sm-4">
    <div id="success"></div>
  </div>
</div>

</fieldset>
</form>




		<?php 
    }
}

?>






<script> 
$('#profile-submit').click(function(){ // Save profile to people
  $("#success").html('');
	$.post( "./includes/profile-update.php", $( "#profile-form" ).serialize() ,function( data ) {
    $("#success").html('<div class="alert alert-success" role="alert">Updated</div>'); 
	});
	return false;
});

</script>

