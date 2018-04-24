<!-- List quiz questions written by author -->
<form class="form-horizontal" id="pages-add-form" method="post">
    <fieldset>
    <legend>Shared Questions</legend> 

	<div class="panel panel-default">

	<!-- Default panel contents -->
	<div class="panel-heading">These are questions publicly shared by the QuizWright community. </div>
	<!-- Table -->
	<table class="table table-striped table-condensed">
		<tr><th></th><th>Topic / Question</th><th>Copy</th><th>Author</th><th>Quizzes</th><th>Shares</th><th></th></tr>
<?php
require ("user-session.php");

// TODO List all publicly shared pages

$qs=array();
$sql = "select page.pid,people.uid,page.data, people.profile from page, people where page.uid = people.uid and   1=1";
if ($result = $mysqli->query($sql)) {
	while ($row = $result->fetch_assoc())
	{
		$page = json_decode($row['data'], TRUE);
		$public= $page['public']=='true';
		if ($public)
		{
			$bank= $page['bank']=='true';
			$author = json_decode($row['profile'], TRUE);
			$pid = $row['pid'];
			$pageText = $page['page-question'];
			$pageTopic = $page['page-topic'];
			$pageAuthor = $author['authorfullname'];
			$trace=$pid.' '.$bank.','.$page['public'];
			$row='<tr>
				<td>'.$bank.'</td>
				<td>'.$pageTopic.'<br /><a  class="ellipsis page-detail" href="./includes/page-detail.php?pid='.$pid.'">'.substr( strip_tags($pageText),0,50).'</a><div class="details"></div></td>
				<td><a title="TODO" xhref="./includes/todo-detail.php?pid='.$pid.'">[Copy]</td>
				<td>'.$pageAuthor.' </td>
				<td> - </td>
				<td> - </td>
				<td>'.$trace.'</td>
			</tr>';
			// osrt by CALI Bank, Topic area. Unspecified topics sort to bottom.
			$sort=$bank.(in_array($pageTopic,array('','Not specified')) ? '0':'').$pageTopic.$pid;
			$qs[$sort]=$row;
		}
	}
}
ksort($qs);
echo implode($qs);
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


