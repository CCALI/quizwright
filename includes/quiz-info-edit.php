<!-- Revising lesson information  -->
<?php
// 05/19/2017 Revise lesson's information.
require ("user-session.php");

$lid = intval($_REQUEST['lid']);

$sql = "SELECT * FROM `info` WHERE lid = $lid and uid = $uid";
if ($result = $mysqli->query($sql))
{
	if ($row = $result->fetch_assoc())
	{
		$data = json_decode($row['data'], TRUE);
	}
}
include "quiz-info-edit.inc";
?> 

