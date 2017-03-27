<!-- List unlinked (TODO, currently all) pages for author to assign to quiz -->
<form class="form-horizontal" id="pages-add-form" method="post">
    <fieldset>
    <legend>Quiz Questions</legend>
	 <p>Select the questions to include in your new quiz. </p>
<ul>
<?php
require ("user-session.php");

// List author's pages that are not assigned to a lesson
$sql = "SELECT * FROM `page` WHERE uid = '$uid' and lid = 0";
if ($result = $mysqli->query($sql)) {
	while ($row = $result->fetch_assoc())
	{
		$data = json_decode($row['data'], TRUE);
		$id = $row['pid'];
		?>
			<li>
				<label class="btn btn-primary active">
					<input type="checkbox" autocomplete="off" name="<?=$id?>" checked> <?=$data['page-question']?>
				</label>
			</li>			 
	  <?php 
	}
}
?>
</ul>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="page-submit">  </label>
            <div class="col-sm-3">
                <button id="pages-add-submit"  class="btn btn-primary">Add to new quiz</button>
            </div>
				 
        </div>

	 </fieldset>
	 
	 
</form>



<script> 
$('#pages-add-submit').click(function(){ // Save page selection, let author prepare quiz
	$.post( "./includes/quiz-new-auto.php", $( "#pages-add-form" ).serialize() ,function( data ) {
		$("#main-panel").html(data); 
	});
	
	/*$.post( "./includes/quiz-create-pages.php", $( "#pages-add-form" ).serialize() ,function( data ) {
		$("#main-panel").load("./includes/quiz-new-auto.php?lid=" + data.lid); 
	},'json');
	*/
	return false;
});

</script>


