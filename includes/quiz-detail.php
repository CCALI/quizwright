<!-- List one quiz's details  -->
<form class="form-horizontal" method="post">
    <fieldset>
    <legend>Quiz Details</legend>
	 <p>  </p>
<div class="panel panel-default">
	 

	 
<?php
require ("user-session.php");
$lid = intval($_REQUEST['lid']);
$sql = "SELECT * FROM `info` WHERE uid = '$uid' and lid = $lid";
if ($result = $mysqli->query($sql))
{
	if ($row = $result->fetch_assoc())
	{
		$data = json_decode($row['data'], TRUE);
		$numPages = count($data['pages']); // .pages is array of pid's, order matches lesson order.
		?>
		<ul>
			<li>Title: <?=$data['title']?></li>
			<li><?=$numPages?> Questions</li>
			
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
							$page = json_decode($row['data'], TRUE);
							//var_dump($page);
							$pagetype = $page['page-type'];
							echo '<li>#'.$pid.': '.$page['page-question'];
							switch ($pagetype)
							{
								
								case 'quiz-tf':	// True/false 
									$istrue = $page['true-is-correct']=='true';
									echo '(T/F)';
									break;
								
								case 'Quiz':	// Multiple choice: 1 correct, 1-N wrong.
								case 'quiz-mc':
								case '':
									echo '<ul>';
									echo '<li class="correct">'.$page['page-choice-correct-text'];
									for ($wrong=1;$wrong<=7;$wrong++)
									{
										$wrongText = $page['page-choice-wrong-'.$wrong.'-text'];
										if ($wrongText!='') echo '<li class="wrong">'.$wrongText;
									}
									echo '</ul>';
									break;
							}
						}
					}
				}
			}
			?>
		</ul>
		<?php 
	}
}
?>
<!-- Button -->
<div class="form-group">
  <label class="col-sm-3 control-label" for="submit">Ready to give the quiz?</label>
  <div class="col-sm-8">
    <button id="quiz-publish-submit"   class="btn btn-primary">Publish Quiz</button>
  </div>
</div>

</fieldset>
</form>
 
</div>
</div>