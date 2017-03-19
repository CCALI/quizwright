<!-- List quiz details  -->
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
							// check page type so we get accurate detail (but as of 3/2017 there are all quiz type)
							$page = json_decode($row['data'], TRUE);
							echo '<li>'.$page['pid'].':'.$page['page-question'];
							echo '<ul>';
							echo '<li>'.$page['page-choice-correct-text'];
							for ($wrong=1;$wrong<=7;$wrong++)
							{
								$wrongText = $page['page-choice-wrong-'.$wrong.'-text'];
								if ($wrongText!='') echo '<li>'.$wrongText;
							}
							echo '</ul>';
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

 
</div>
</div>