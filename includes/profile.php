<!-- Author profile information - used to populate lesson author info-->

<?php

require ("user-session.php");

$sql = "SELECT * FROM `people` WHERE uid = '$uid'";
if ($result = $mysqli->query($sql)) {
	 while ($row = $result->fetch_assoc()) {
        $data = json_decode($row['profile'], TRUE); 
		  ?> 
 


<form class="form-horizontal" id="profile-form" method="post">
<fieldset>

<!-- Form Name -->
<legend>Author Profile Information</legend>


<!-- Text input-->
<div class="form-group">
  <label class="col-sm-2 control-label" for="authorname">Author Name</label>
  <div class="col-sm-8">
    <input id="authorname" name="fullname" placeholder="Your full name here" class="form-control" type="text" value="<?=$data['author-name']?>">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-sm-2 control-label" for="authortitle">Author title</label>
  <div class="col-sm-8">
    <input id="authortitle" name="title" placeholder="Your title" class="form-control" type="text">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-sm-2 control-label" for="authorschool">School</label>
  <div class="col-sm-8">
    <input id="authorschool" name="school" placeholder="Your school" class="form-control" type="text">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-sm-2 control-label" for="email">Email</label>
  <div class="col-sm-8">
    <input id="email" name="email" placeholder="Your email address" class="form-control" type="text">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-sm-2 control-label" for="phone">Phone</label>
  <div class="col-sm-8">
    <input id="phone" name="phone" placeholder="234-456-7890" class="form-control" type="text">
    
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
  <div class="col-sm-8">
    <button id="profile-submit"  class="btn btn-primary">Update</button>
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
	$.post( "./includes/profile-update.php", $( "#profile-form" ).serialize() ,function( data ) {
      
		//$("#main-panel").load("./includes/lesson-new-auto.php?lid=" + data.lid); 
	});
	return false;
});

</script>

