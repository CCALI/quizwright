<!-- List question questions imported by CALI staff from CALI Lessons -->
<form class="form-horizontal" id="pages-add-form" method="post">
    <fieldset>
    <legend>Question Bank</legend> 

	<div class="panel panel-default">

	<!-- Default panel contents -->
	<div class="panel-heading">These are questions publicly shared by the QuizWright community and extracted from CALI Lessons. </div>
	<!-- Table -->

<?php
require ("user-session.php");
require "utility.php";

$lessons=array();
$lessonPages=array();
$areaTotals=array();//array of CALI Topic with count.
$counter=0;
$qs=array();
$sql = "select page.pid,page.data   from page where page.data like '%public\":true%'"	;	// limit 25";
if ($result = $mysqli->query($sql))
{
	while ($row = $result->fetch_assoc())
	{
		$page = json_decode($row['data'], TRUE);
		$counter++;
		//if ($counter==20) var_dump($page);
		$public= $page['public']=='true';
		$bank= $page['bank']=='true';
		$added=$page['bank-date-added'];
		if ($public && $bank)
		{
			$pid = $row['pid'];
			$topic=$page['page-topic'];
			$lsn=$page['bank-src-lesson'];
			$attr=$page['page-attribution'];
			$pagename=$page['bank-src-page'];
			$pageTopic = $page['page-topic'];
			$nicetags=niceTopicAndTags($page);
			// Question information
			$row="<tr valign=top><td nowrap>$topic</td><td>$lsn</td><td nowrap>$pagename</td><td>$nicetags</td><td>$attr</td><td>$added</td><td align=right>#$pid</td></tr>";
			$sort=$topic.' '.$lsn.' '.$pagename;
			$qs[$sort]=$row;
			if (!isset($areaTotals[$topic]))
				$areaTotals[$topic]=1;
			else
				$areaTotals[$topic]++;
			// Lesson summary
			$lessonPages[$lsn]++;
			$count=$lessonPages[$lsn];//++$lessons[$lsn]['count'];
			$row="<tr valign=top><td>$topic</td><td>$lsn</td><td>$added</td><td align=right>$count</td><td>$attr</td></tr>";
			$sort=$topic.' '.$lsn;
			$lessons[$lsn]=$row;

		}
		
	}
}
ksort($qs);
ksort($lessons);
$lessons=implode($lessons);
$list=implode($qs);

ksort($areaTotals);
$topics='';


foreach ($areaTotals as $area=>$total)
{
	$topics.='<tr><td>'.$area.'</td><td align=right>'.$total.'</td></tr>';
}
	$topics.='<tr><td>&nbsp;</td><td align=right>'.$counter.'</td></tr>';
?>

	
<p>Index current as of <?php echo date("m/d/Y");?> </p>
<h2>CALI Topics</h2>
<table>
	<?php echo $topics;?>
</table>

<h2>Imported CALI Lesson index</h2>
	<table cellpadding=5 class="table table-striped table-condensed">
		<tr><th>CALI Topic</th><th>CALI Lesson</th><th>Date added</th><th>Pages</th><th>CALI Attribution</th></tr>
		<?php echo $lessons;?>
	</table> 
<h2>Imported CALI Lesson question index</h2>
	<table class="table table-striped table-condensed">
		<tr><th>CALI Topic</th><th>CALI Lesson</th><th>CALI Lesson Page</th><th>Topic tags</th><th>CALI Attribution</th><th>Date added</th><th>ID</th></tr>
		<?php echo $list;?>
	</table> 
</div>
</div>
	 </fieldset>
</form>
