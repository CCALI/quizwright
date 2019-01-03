<!-- List quiz questions written by author -->
<form class="form-horizontal" id="pages-add-form" method="post">
    <fieldset>
    <legend>Question Bank</legend> 

	<div class="panel panel-default">

	<!-- Default panel contents -->
	<div class="panel-heading">These are questions publicly shared by the QuizWright community and extracted from CALI Lessons. To use one of these questions click the copy icon to add it to your My Questions list.</div>
	<!-- Table -->
	<table class="table table-striped table-condensed">
<?php
require ("user-session.php");
require "utility.php";

$showdev=( 0  ==1);

echo '<tr><th>Question / Topic</th><th>Copy</th><th>Author</th><!--<th>Quizzes</th><th>Shares</th>-->'.($showdev?'<th class=devinfo>Details</th>':'').'</tr>';

// TODO List all publicly shared pages

$qs=array();
$sql = "select page.pid,people.uid,page.data, people.profile from page, people where page.data like '%public\":true%' and page.uid = people.uid";
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
			$pageAuthor = $author['authorfullname'];
			if ($bank)
			{
				$pageAuthor='<div class=calibank> </div>';
			}
			$trace=$pid.' '.$bank.','.$page['public'];
			$row='<tr>
				<td><span class="summary"><a  class=" ellipsis page-detail" href="./includes/page-detail.php?pid='.$pid.'">'.compactQuestionDescription($page).'</a><span class="page-topic">'.niceTopicAndTags($page).'</span></span><div class="details"></div></td>
				<td><a title="Copy to my questions" class="page-clone glyphicon glyphicon-duplicate" href="./includes/page-clone.php?pid='.$pid.'"> </a></td>
				<td>'.$pageAuthor.' </td>
				<!--<td> - </td>
				<td> - </td>-->
				'.($showdev?'<td class=devinfo>'.$trace.'</td>':'').'
			</tr>';
			// Sort by CALI Bank, Topic area. Unspecified topics sort to bottom.
			$sort=$bank.(in_array($pageTopic,array('Not specified','')) ? '0':'').$pageTopic.$pid;
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
$('.page-clone').click(function(){ // Edit inline 
	$(this).closest('td').load($(this).attr('href'));
	return false;
});
$('.page-detail').click(function(){ // Load details of question into row
	var details=$(this).closest('td').find('.details');
	$.post($(this).attr('href'),'' ,function( html ) {
		$(details).closest('.summary').hide();
		$(details).html('').append($('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>')
		.click(function(){$(this).closest('.details').html('');$(this).closest('td').find('.summary').show();}));
		$(details).append(html);
	
	});
	return false;
});

</script>


