<!-- List user's quizzes  -->
<form class="form-horizontal" method="post">
	<fieldset>
	<legend>My Quizzes</legend>
	<p>  </p>
	<div class="panel panel-default">

	<!-- Default panel contents -->
	<!--<div class="panel-heading">Quizzes</div>-->
	<!-- Table -->
	<table class="table">
		<tr><th>Title</th><th>Description</th><th>&nbsp;</th><th>Published</th><th>&nbsp;</th><th>&nbsp;</th><th>ID#</th></tr>
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
			<td> <?=$data['title']?></td>
			<td> <?=oneLinerHTML($data['calidescription'])?></td>
			
			
			<?php if ($published) { /* If published include only link to review and run */ ?>
			<td>
				<a class="btn btn-primary glyphicon glyphicon-play" title="Run my published quiz on www.cali.org" id="quiz-run"  target=_blank href="<?=$published?>"></a>
			</td>
			<td>
				<span class="label label-default"  data-toggle="popover" title="Published"  data-placement="left" data-trigger="hover" data-html="true" data-content="Published to CALI website: <?=$publishdate?>"><?=$publishdate?></span>
			</td>
			<td nowrap>
				<a class="btn btn-default" title="Review details of my quiz"  href="./includes/quiz-detail.php?lid=<?=$lid?>">Details</a>
			</td>
			
			<?php } else { ?>
			<td> </td>
			<td>Unpublished</td>
			<td nowrap>
				<a class="btn btn-default" title="Review details of my quiz"  href="./includes/quiz-detail.php?lid=<?=$lid?>">Details</a> 
				<a class="btn btn-default glyphicon glyphicon-pencil" title="Edit quiz information"   href="./includes/quiz-info-edit.php?lid=<?=$lid?>"> </a>
				<a class="btn btn-default glyphicon glyphicon-th-list" title="Change questions in the quiz"   href="./includes/quiz-page-order.php?lid=<?=$lid?>"></a>
				<a class="btn btn-default" title="Preview the quiz in the test viewer" target=_blank href="./cav/web/preview/index.php?quiz=<?=$lid?>">Preview</a> 
				<a class="btn btn-default" title="View the XML data that makes up a quiz" target=_blank href="./book-data-xml.php?lid=<?=$lid?>">XML</a>
			</td>
			<?php } ?>
			<td>
				<span class="badge" data-toggle="popover" data-placement="left" data-trigger="hover" data-html="true" data-content="This quiz has <?=$numPages?> questions"> <?=$numPages?></span>
			</td>
			<td><?=$lid?></td>
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
