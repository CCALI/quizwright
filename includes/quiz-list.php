<!-- List user's quizzes  -->
<form class="form-horizontal" method="post">
	<fieldset>
	<legend>Your Quizzes</legend>
	<p>  </p>
	<div class="panel panel-default">

	<!-- Default panel contents -->
	<div class="panel-heading">Quizzes</div>
	<!-- Table -->
	<table class="table">
		<tr><th>ID#</th><th>Title</th><th>Description</th><th>Questions</th></tr>
<?php
	require ("user-session.php");
	$sql = "SELECT * FROM `info` WHERE uid = '$uid'";
	if ($result = $mysqli->query($sql))
	{
		while ($row = $result->fetch_assoc())
		{
			$data = json_decode($row['data'], TRUE);
			$lid=$row['lid'];
			$numPages = count($data['pages']); // .pages is array of pid's, order matches lesson order.
			?>
			<tr>
				<td><?=$lid?></td>
				<td> <?=$data['title']?></td>
				<td> <?=$data['calidescription']?></td>
				<td> <?=$numPages?></td>
				<td><a href="./includes/quiz-detail.php?lid=<?=$lid?>">Details</td>
				<td>Edit</td>
				<td>Preview</td>
				<td><a target=_blank href="./book-data-xml.php?lid=<?=$lid?>">XML</td>
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
