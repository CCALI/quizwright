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
		<tr><th>ID#</th><th>Title</th><th>Description</th><th>Questions</th><th>More</th></tr>
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
		$data = json_decode($row['data'], TRUE);
		$numPages=count($data['pages']);
		$published=$row['location'];/* see if already published */
		$publishdate=$row['publishdate'];
		?>
		<tr>
			<td><?=$lid?></td>
			<td> <?=$data['title']?></td>
			<td> <?=oneLinerHTML($data['calidescription'])?></td>
			
			
			<?php if ($published) { /* If published include only link to review and run */ ?>
			
			<td> <span class="badge"> <?=$numPages?> </span> </td>
			
			<td>
				<a id="quiz-run"   class="btn btn-primary" target=_blank href="<?=$published?>">Run</a>
				<a class="btn btn-default" href="./includes/quiz-detail.php?lid=<?=$lid?>">Details</a>
				 
				<span class="label label-default"  data-toggle="popover" title="Published"  data-placement="left"
							data-trigger="hover" data-html="true" data-content="<?=$publishdate?>"><?=$publishdate?></span>
				
				
				
				
				
				</td>
			<?php } else { ?>
			<td> <span class="badge"> <?=$numPages?> </span> <a class="btn btn-default"  href="./includes/quiz-page-order.php?lid=<?=$lid?>">+/-</a></td>
			<td nowrap><a class="btn btn-primary"  href="./includes/quiz-detail.php?lid=<?=$lid?>">Details</a> 
			<a class="btn btn-default"  href="./includes/quiz-info-edit.php?lid=<?=$lid?>">Desc</a> 
			<a class="btn btn-default" target=_blank href="./cav/web/preview/index.php?quiz=<?=$lid?>">Preview</a> 
			<a class="btn btn-default" target=_blank href="./book-data-xml.php?lid=<?=$lid?>">XML</a></td>
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
$(function () {
  $('[data-toggle="popover"]').popover()
})
</script>
