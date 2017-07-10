<h1>Page Usage Stats</h1>
<?php
// 05/19/2017 Gather usage info for all pages as debugging aid
//	This page is mainly a dev tool to understand how data structures link. 
// For each topic: Pages assigned to the topic, authors using that topic
// For each quiz: Pages assigned to it, authors using it
// For each page: public (shared), # of authors using it, number of quizzes using it

require ("user-session.php");

$topics=array(); // key is topic name ("Torts"), subkey 'pages' is list of pages assigned this topic
$lessons=array(); // key is quiz id, subkeys include 'pages' array
$pages=array(); // key is page id, subkeys include 'public', 'clones' (numberof uses by non-author), quizzes using the page

// Gather page use information
$sql = "SELECT * FROM `page` order by pid";
if ($result = $mysqli->query($sql))
{
	while ($row = $result->fetch_assoc())
	{
		$pid =intval($row['pid']);
		$aid =intval($row['uid']);
		$page = json_decode($row['data'], TRUE);
		$public= ($page['public']=='true') ? 'Public' : '';
		$pages[$pid]=array('public'=>$public,'aid'=>$aid,'lesson'=>array(),'users'=>array());
		// Track topics too:
		$ptopic = $page['page-topic'];
		if ($ptopic=='') $ptopic='Unknown';
		$topics[$ptopic]['pages'][]=$pid;
		$topics[$ptopic]['users'][$aid]=1;
	}
}

// Gather quiz usage
$sql = "SELECT * FROM `info`";
if ($result = $mysqli->query($sql))
{
	while ($row = $result->fetch_assoc())
	{
		$lid = $row['lid'];
		$aid = $row['uid'];
		$lesson = json_decode($row['data'], TRUE);
		$lessons[$lid]=array('public'=>'','aid'=>$aid,'pages'=>array());
		if (isset($lesson['pages']))
		{
			foreach ($lesson['pages'] as $pid)
			{	// It's possible for quiz to refer to deleted page. 
				if (isset($pages[$pid]))
				{
					//$pages[$pid]['public']='deleted';
					$pages[$pid]['lesson'][$lid]=1;
					$pages[$pid]['users'][$aid]=1;
					$lessons[$lid]['pages'][] = $pid;
				}
			}
		}
	}
}

ksort($topics); 
ksort($pages);
ksort($lessons);

// List topics and pages that are assigned to them. Need quizzes here too.
?> <h2>Topic Usage</h2>
<table border=1><tr><th>Topic</th><th>Page Count</th><th>Page IDs</th><th>Author Count</th><th>Author IDs</th></tr> <?php
foreach( $topics as $name => $info)
{
	$subpages = join(', ', $info['pages']);
	$users = join(', ',array_keys( $info['users']));
	echo '<tr><td>'.$name.'</td><td>'.count($info['pages']).'</td><td>'.$subpages.'</td><td>'.count($info['users']).'</td><td>'.$users.'</td></tr>';
}
?> </table> <?php 

// List quizzes and pages that are assigned to them.
?> <h2>Quiz Usage </h2>
<table border=1><tr><th>Quiz ID</th><th>Page Count</th><th>Page IDs</th><th>Author ID</th></tr> <?php
foreach( $lessons as $lid => $info)
{
	$subpages = join(', ', $info['pages']);
	$users = '';//join(', ',array_keys( $info['users']));
	echo '<tr><td>'.$lid.'</td><td>'.count($info['pages']).'</td><td>'.$subpages.'</td><td>'.$info['aid'].'</td></tr>';
}
?> </table> <?php 

// List pages stats including: public shared, quizzes using the page, authors using the page
?> <h2>Page Usage</h2>
<table border=1><tr><th>Page ID</th><th>Shared?</th><th>Quiz Count</th><th>Quiz IDs</th><th>Author Count</th><th>Author IDs</th></tr> <?php
foreach( $pages as $pid => $info)
{
	$lessons = join(', ',array_keys( $info['lesson']));
	$users = join(', ',array_keys( $info['users']));
	echo '<tr><td>'.$pid.'</td><td>'.$info['public'].'</td><td>'.count($info['lesson']).'</td><td>'.$lessons.'</td><td>'.count($info['users']).'</td><td>'.$users.'</td></tr>';
}
?> </table> <?php 



?>




