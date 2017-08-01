<!-- List a quiz's pages with option to reorder or remove.  -->

<form class="form-horizontal" method="post" id="pages-update-form">
	<fieldset>
	<legend>Questions in this Quiz</legend>
	 <p>Uncheck questions to remove them. Click/drag to change the order. </p>
<div class="panel panel-default">
	 	 <ol id="sortable"> 
<?php
require ("user-session.php");
require ("utility.php");
$lid = intval($_REQUEST['lid']);
$sql = "SELECT * FROM `info` WHERE uid = '$uid' and lid = $lid";
if ($result = $mysqli->query($sql))
{
	if ($row = $result->fetch_assoc())
	{
		$data = json_decode($row['data'], TRUE);
		$numPages = count($data['pages']); // .pages is array of pid's, order matches lesson order.
		if ($numPages>0)
		{ 
			foreach ($data['pages'] as $pid)
			{
				$sql = "SELECT * FROM `page` WHERE pid = $pid";
				if ($result = $mysqli->query($sql))
				{
					if ($row = $result->fetch_assoc())
					{
						// Check page type so we get accurate detail (but as of 3/2017 there are all quiz type)
						$page = json_decode($row['data'], TRUE);
						?>
						<li>
							<label class="btn btn-primary active">
								<input type="checkbox" autocomplete="off" name="<?=$pid?>" checked > <?=compactQuestionDescription($page)?>
							</label>
						</li>
						<?php
					}
				}
			}
		}
		?>
		</ol>
		<?php 
	}
}
?>
<input type=hidden name=lid value="<?=$lid?>"/>
<!-- Button -->

  <div class="col-sm-8"> 
		<button id="pages-add-submit"  class="btn btn-primary">Update</button>
  </div>
</div>

	 </fieldset>
	 
	 
</form>


<script>
	$( "#sortable" ).sortable();
	$( "#sortable" ).disableSelection();
	$('#pages-add-submit').click(function(){ // Save page selection, let author prepare quiz
		$.post( "./includes/quiz-page-order-update.php", $( "#pages-update-form" ).serialize() ,
				 function( data ) {$("#main-panel").load('./includes/quiz-detail.php?lid='+data.lid);},'json');
		return false;
	});
</script>
