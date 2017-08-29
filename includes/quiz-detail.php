<!-- List one quiz's details  -->

    <H3>Quiz Details</H3>
<div class="panel panel-default">
	 	 
<?php
require ("user-session.php");
require ("utility.php");
//error_reporting(E_ALL); 
$lid = intval($_REQUEST['lid']);
$sql = "SELECT * FROM `info` WHERE uid = '$uid' and lid = $lid";
if ($result = $mysqli->query($sql))
{
	if ($row = $result->fetch_assoc())
	{
		$data = json_decode($row['data'], TRUE);
		$published=$row['location'];
		$publishdate=$row['publishdate'];
		$numPages = count($data['pages']); // .pages is array of pid's, order matches lesson order.
		?>
		<ul>
			<li>Title: <?=$data['title']?></li>
			<li>Description: <?=$data['calidescription']?></li>
			<li><?=$numPages?> Questions</li>
			<ol>
			<?php
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
							$page = json_decode($row['data'] , TRUE);
							echo '<li>'.compactQuestionDescription($page);
							/*
							$pagetype = $page['page-type'];
							echo '<li>#'.$pid.': '.$page['page-question'];
							switch ($pagetype)
							{
								
								case 'quiz-yn':	// Yes/No 
									$isyes = $page['yes-is-correct']=='true';
									echo '(Y/N)';
									break;
								
								case 'quiz-tf':	// True/false 
									$istrue = $page['true-is-correct']=='true';
									echo '(T/F)';
									break;
								
								case 'Quiz':	// Multiple choice: 1 correct, 1-N wrong.
								case 'quiz-mc':
								case '':
									//echo '<ul>';
									//echo '<li class="correct">'.$page['page-choice-correct-text'];
									$count=1;
									for ($wrong=1;$wrong<=7;$wrong++)
									{
										$wrongText = $page['page-choice-wrong-'.$wrong.'-text'];
										if ($wrongText!='') echo $count ++; // '<li class="wrong">'.$wrongText;
									}
									//echo '</ul>';
									echo $count;
									break;
							}
							*/
						}
					}
				}
			}
			?>
			</ol>
		</ul>
		<?php 
	}
}
?>
<!-- Button -->

<?php if ($published) { ?>
	<div class="col-sm-3 control-label" for="submit">This quiz was published <?=$publishdate?></div>
	<div class="col-sm-8">
		<a id="quiz-run"   class="btn btn-primary" target=_blank href="<?=$published?>">Run the Quiz</a>
		<a id="quiz-clone"   class="btn btn-default">Duplicate Quiz</a>
	</div>
	</div>
  <?php } else { ?>
 
  <div class="col-sm-3 control-label" for="submit">Ready to give the quiz?</div>
  <div class="col-sm-8">
    <a id="quiz-publish"   class="btn btn-primary" target=_blank href="./includes/quiz-publish.php?lid=<?php echo $lid;?>">Publish Quiz</a>
    <a id="quiz-page-order"   class="btn btn-default">Change Questions</a>
    <a id="quiz-info-edit"   class="btn btn-default">Edit Description</a>
    <a id="quiz-cancel"   class="btn btn-default">Publish Later</a>
  </div>
</div>
  
  <?php } ?>



<script>
	$("#quiz-page-order").click(function(){$("#main-panel").load("./includes/quiz-page-order.php?lid=<?php echo $lid;?>");});
	$("#quiz-info-edit").click(function(){$("#main-panel").load("./includes/quiz-info-edit.php?lid=<?php echo $lid;?>");});
	$("#quiz-cancel").click(function(){$("#main-panel").load("./includes/quiz-list.php");});
	$("#quiz-clone").click(function(){$("#main-panel").load("./includes/quiz-clone.php?lid=<?php echo $lid;?>");});
</script>
