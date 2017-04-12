<!-- List quiz questions (TODO, currently all) pages for author to assign to quiz -->
<form class="form-horizontal" id="pages-add-form" method="post">
    <fieldset>
    <legend>My Questions</legend> 

	<div class="panel panel-default">

	<!-- Default panel contents -->
	<div class="panel-heading">Questions</div>
	<!-- Table -->
	<table class="table">
		<tr><th>ID#</th><th>Title</th><th>Description</th><th> - </th></tr>
<?php
require ("user-session.php");

// List author's pages that are not assigned to a lesson
$sql = "SELECT * FROM `page` WHERE uid = '$uid' ";
if ($result = $mysqli->query($sql)) {
	while ($row = $result->fetch_assoc())
	{
		$page = json_decode($row['data'], TRUE);
		$id = $row['pid'];
		$pageText = $page['page-question'];
					
		
		
		?> 
			
		<tr>
			<td><?=$id?></td>
			<td> <?=$pageText?></td>
			<td><a href="./includes/quiz-detail.php?lid=<?=$lid?>">Details</td>
			<td>Edit</td>
		</tr>
			
	  <?php 
	}
}
?>
		</table> 
	</div>
</div>


        <div class="form-group">
            <label class="col-sm-2 control-label" for="page-submit">  </label>
            <div class="col-sm-3">
                <button id="pages-add-submit"  class="btn btn-primary">-</button>
            </div>
				 
        </div>

	 </fieldset>
	 
	 
</form>



<script> 
$('x#pages-add-submit').click(function(){ // Save page selection, let author prepare quiz
	$.post( "./includes/quiz-new-auto.php", $( "#pages-add-form" ).serialize() ,function( data ) {
		$("#main-panel").html(data); 
	});
	return false;
});

</script>


