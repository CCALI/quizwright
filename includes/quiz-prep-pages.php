<?php
	// TODO return JSON package and let client do filtering. echo '<script>var pages='.json_encode($pages).'</script>';
	require ("user-session.php");
	require ("utility.php");
?>
<!-- List all pages for author to assign to quiz, option to filter -->
<form class="form-horizontal" id="pages-add-form" method="post">
    <fieldset>
    <legend>Quiz Questions</legend> 
            
	 <p>Select from my existing questions to include in a new quiz. </p> 
	 
	 <!--
		   <div class="form-group">
            <label class="col-sm-4 control-label" for="page-status">Question filter:</label>
 
            <div class="col-sm-4">
					<select name="page-status" id="page-status" class="form-control">
						<option value="created">Created today</option>
						<option value="edited">Edited today</option>
						<option value="unassigned">Unassigned to a quiz</option>
						<option value="" >All</option?></select>
            </div>
        </div>
				-->
		
			<div class="form-group">
				<label class="col-sm-6 control-label" for="page-status-filter">Filter Questions: </label>
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-default "> <input type="radio" name="page-status-filter" value="unassigned" autocomplete="off" > Unassigned   </label>
						<label class="btn btn-default"> 	<input type="radio" name="page-status-filter" value="all" autocomplete="off"> All   </label>
					</div>
			</div>
		
		
<ul>
<?php

$pages=array(); // key is page id, quizzes using the page

// Gather pages of author
$sql = "SELECT * FROM `page` where uid = $uid";
if ($result = $mysqli->query($sql))
{
	while ($row = $result->fetch_assoc())
	{
		$pid =intval($row['pid']);
		$page = json_decode($row['data'], TRUE);
		$pages[$pid]=array('text'=>compactQuestionDescription($page) ,'lessons'=>array());
	}
}

// Gather quiz usage of the pages.
$sql = "SELECT * FROM `info` where uid = $uid";
if ($result = $mysqli->query($sql))
{
	while ($row = $result->fetch_assoc())
	{
		$lid = $row['lid'];
		$lesson = json_decode($row['data'],  TRUE);
		if ($lesson['pages']){
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

foreach ($pages as $pid => &$page)
{	// List all author pages, checks turned off, include lesson count as info.
	// Classify if page is used by lesson or not so our filter can work.
	$page['lessons']= count($page['lessons']);
	$assigned  = $page['lessons'] == 0 ? 'unassigned' : 'assigned';
?>
	 <li class="question <?=$assigned?>">
		 <label class="btn btn-primary active">
			 <input type="checkbox" autocomplete="off" name="<?=$pid?>"  > <?=$page['text']?> (Used by <?=$page['lessons']?> quizzes)
		 </label>
	 </li>			 
 <?php 
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

    <fieldset>
    <legend>New Question</legend> 
            
	 <p>Create a new question for a quiz. </p> 
	 
			<div class="form-group"> <label class="col-sm-2 control-label" for="page-new">  </label>
            <div class="col-sm-3">
                <button id="page-new"  class="btn btn-primary">Add a new Question</button>
            </div>
				</div>
				
	 </fieldset>
	 
	 
</form>



<script>
$('.assigned').hide();
cawSetRB('page-status-filter','unassigned');
updateForm();
function updateForm()
{
	var count = $('[name=page-status-filter]:checked').val()=='unassigned'  ? $('.unassigned').size() : $('.question').size();
	if (count==0) $('#pages-add-submit').hide(); else $('#pages-add-submit').show();
}
$('[name=page-status-filter]').change(function(){
	 var count;
	 switch (this.value){
		  case 'all':
				$('.assigned').show();
				break;
		  case 'unassigned':
				$('.assigned').hide();
				break;
	}
	updateForm();

});
$('#page-new').click(function(){  
	$("#main-panel").load("./includes/page-quiz.php"); 
	return false;
});
$('#pages-add-submit').click(function(){ // Save page selection, let author prepare quiz
	$.post( "./includes/quiz-new-auto.php", $( "#pages-add-form" ).serialize() ,function( data ) {
		$("#main-panel").html(data); 
	});
	return false;
});

</script>


