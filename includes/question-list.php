<!-- List quiz questions written by author -->
<form class="form-horizontal" id="pages-add-form" method="post">
    <fieldset>
    <legend>My Questions</legend> 

	<div class="panel panel-default">

	<!-- Default panel contents -->
	<div class="panel-heading">These are all of my questions</div>
	<!-- Table -->
	<table class="table table-striped table-condensed">
		<tr><th>Question</th><th>Edit</th><th>Topic</th><th>Author</th><th>Quizzes</th><th>Shares</th><th>ID#</th></tr>
<?php
require ("user-session.php");

// List author's pages that are not assigned to a lesson
$sql = "SELECT * FROM `page` WHERE uid = '$uid' ";
if ($result = $mysqli->query($sql)) {
	while ($row = $result->fetch_assoc())
	{
		$page = json_decode($row['data'], TRUE);
		$pid = $row['pid'];
		$pageText = $page['page-question'];
		$pageTopic = $page['page-topic'];		
		?> 
			
		<tr  >
			<td ><a  class="ellipsis page-detail" href="./includes/page-detail.php?pid=<?=$pid?>"> <?=$pageText?></a><div class="details"></div></td>
			<td><a title="TODO" xhref="./includes/todo-detail.php?pid=<?=$pid?>">[Edit]</td>
			<td nowrap> <?=$pageTopic?></td>
			<td> Me </td>
			<td> - </td>
			<td> - </td>
			<td><?=$pid?></td>
		</tr>
			
	  <?php 
	}
}
?>
		</table> 
	</div>
</div>
	 </fieldset>
</form>



<script> 
$('.page-detail').click(function(){ // Load details of question into row
	var details=$(this).closest('td').find('.details');
	$.post($(this).attr('href'),'' ,function( html ) {
		$(details).html(html); 
	});
	return false;
});

</script>


