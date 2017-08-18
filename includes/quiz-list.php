<!-- List user's quizzes  -->
<form class="form-horizontal" method="post">
	<fieldset>
	<legend>My Quizzes</legend>
	<p>  </p>
	<div class="panel panel-default">

	<!-- Default panel contents -->
	<div class="panel-heading">Quizzes</div>
	<!-- Table -->
	<table class="table">
		<tr><th>ID#</th><th>Title</th><th>Description</th><th>Questions</th><th>Publish</th><th>Description</th><th></th><th></th></tr>
<?php
//error_reporting(E_ALL); 
require ("user-session.php");
require ("utility.php");

$sql = "SELECT * FROM `info` WHERE uid = '$uid'";
if ($result = $mysqli->query($sql))
{
	while ($row = $result->fetch_assoc())
	{
		$lid=$row['lid'];
		$published=$row['location'];/* see if already published */
		$data = json_decode($row['data'], TRUE);
		$numPages=count($data['pages']); 
		?>
		<tr>
			<td><?=$lid?></td>
			<td> <?=$data['title']?></td>
			<td> <?=oneLinerHTML($data['calidescription'])?></td>
			<?php if ($published) { /* If published include only link to review and run */ ?>
			<td> <?=$numPages?> </td>
			<td><a href="./includes/quiz-detail.php?lid=<?=$lid?>">Details</td>
			<td> <a id="quiz-run"   class="btn btn-primary" target=_blank href="<?=$published?>">Run</a></td>
			<td>-</td>
			<td><a target=_blank href="./book-data-xml.php?lid=<?=$lid?>">XML</td>
			<?php } else { ?>
			<td><a href="./includes/quiz-page-order.php?lid=<?=$lid?>"><?=$numPages?> +/-</td>
			<td><a href="./includes/quiz-detail.php?lid=<?=$lid?>">Details</td>
			<td><a href="./includes/quiz-info-edit.php?lid=<?=$lid?>">Desc</td>
			<td><a target=_blank href="./cav/web/preview/index.php?quiz=<?=$lid?>">Preview</a></td>
			<td><a target=_blank href="./book-data-xml.php?lid=<?=$lid?>">XML</td>
			<?php } ?>
		</tr>
		<?php 
	}
}
?>
		</table> 
	</div>
</div>

<script>
$('#main-panel a[target!="_blank"]').click(function(){  
	$("#main-panel").load($(this).attr('href')); 
	return false;
});
</script>
