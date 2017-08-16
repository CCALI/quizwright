<?php
// 2/28/2017 Return CALI Author Viewer compatible Bookdata XML for this quiz.
// 03/21/2017 Included author bio/description.

require ("./includes/config.php");
require ("./includes/build-xml.php");	// 05/10/2017 Move XML builder to shared library

header('Content-Type: text/xml');
//header('Content-Type: text/plain'); plain text for debugging in case XML gives errors

// Note: user id is ignored since students will need this
$lid = intval($_GET['lid']);
//$SQL="select data from info where lid = $lid";

// Query to get lesson and author data
$SQL="select info.data, people.profile from info,people where lid = $lid and info.uid = people.uid";

if ($result = $mysqli->query($SQL))
{
	while ($row = $result->fetch_assoc())
	{
		$data = json_decode($row['data'], TRUE);
		$profile = json_decode($row['profile'], TRUE);
		echo BuildXML($mysqli,$lid,$data,$profile);
		return;
	}
}

echo '<xml>Need lesson id</xml>';
	
	
?>
