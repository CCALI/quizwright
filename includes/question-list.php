<!-- List quiz questions written by author independant of quiz -->
<form class="form-horizontal" id="pages-add-form" method="post">
    <fieldset>
    <legend>My Questions</legend> 

	<div class="panel panel-default">

	<!-- Default panel contents -->
	<!--<div class="panel-heading">These are all of my questions</div>-->
	<!-- Table -->
	<table class="table table-striped table-condensed">
		<tr><th>Topic / Question</th><th>Edit</th><!--<th>Author</th>--><th>Used</th><!--<th>Shares</th>--><!--<th>ID#</th>--></tr>
<?php
require ("user-session.php");
require ("utility.php");

$pages=array();// for each page, tally lessons using it. 
$lessons=array();

// List author's pages that are not assigned to a lesson
$sql = "SELECT pid,data FROM `page` WHERE uid = '$uid' order by pid desc";
if ($result = $mysqli->query($sql))
{
	while ($row = $result->fetch_assoc())
	{
		$page = json_decode($row['data'], TRUE);
		$pid = $row['pid'];
		$page['lessons']=array();
		$pages[$pid] = $page;
	}
}


// 7/7/2017 Gather quiz usage of the pages.
$sql = "SELECT lid,data FROM `info` where uid = '$uid'";
if ($result = $mysqli->query($sql))
{
	while ($row = $result->fetch_assoc())
	{
		$lid = $row['lid'];
		$lesson = json_decode($row['data'],  TRUE);
		$lessons[$lid]=$lesson;
		if ($lesson['pages'])
		{
			foreach ($lesson['pages'] as $pid)
			{	
				if (isset($pages[$pid]))
				{
					$pages[$pid]['lessons'][$lid]=1;
					// Later, doing count($pages[$pid]['lesson']) will return how many lessons use this page.
				}
			}
		}
	}
}

// List author's pages
foreach ($pages as $pid => $page)
{
	 $pageTopic = $page['page-topic'];
	 $lesson_count = count($page['lessons']);
	 // Tally lessons using this page.
	 if ($lesson_count==0)
	 {
		  $lesson_info = 'Unused';
	 }
	 else
	 {
		  $lesson_info='<ol>';
		  foreach ($page['lessons'] as $lid => $dummy)
		  {
				$lesson_info .='<li>'. htmlspecialchars($lessons[$lid]['title']);
		  }
	 }
	?> 
		  
	  <tr  >
		  <td ><span class="summary"><a  class="ellipsis page-detail" href="./includes/page-detail.php?pid=<?=$pid?>"> <?=compactQuestionDescription($page) ?></a><span class="page-topic"><?=niceTopicAndTags($page)?></span></span>
		  <div class="details"></div></td>
		  <td><a class="page-edit glyphicon glyphicon-pencil" href="./includes/page-quiz-edit.php?pid=<?=$pid?>"> </a></td>
		  
		 <!-- <td> Me </td>-->
		 
		  <td> <span class="badge" data-toggle="popover" title="Quizzes using this question"  data-placement="left"
							data-trigger="hover" data-html="true" data-content="<?=$lesson_info?>">
		  <?=$lesson_count?></span> </td>
		  <!-- <td> - </td> -->
		  <!--<td><?=$pid?></td>-->
	  </tr>
			
	 <?php 
}
?>
		</table> 
	</div>
</div>
	 </fieldset>
</form>

<div id="myModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Modal title</h4>
      </div>
      <div class="modal-body">
        <p>One fine body&hellip;</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
	 
$('.page-edit').click(function(){ // Edit inline
	$('.details').html('');
	var details=$(this).closest('tr').find('.details');
	$(details).load($(this).attr('href'));
	return false;
});
$(function () {
  $('[data-toggle="popover"]').popover()
})
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


