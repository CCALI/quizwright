<?php
// 05/19/2017 Update quiz's list of pages (and order) and return quiz-detail page with new order
require ("user-session.php");

$lid = intval($_POST['lid']);

// Build list of desired pages.
$pages=array();
foreach ($_POST as $key => $value)
{
	$pid = intval($key);
	if ($value=='on' && $pid>0)
	{
		 array_push($pages,$pid);
	} 
}

$SQL = "SELECT data from `info` WHERE lid = $lid and uid = $uid";
if ($result = $mysqli->query($SQL))
{
	if ($row = $result->fetch_assoc())
	{
		$data = json_decode($row['data'],TRUE);
		$data['pages'] = $pages; // replace the Pages lists
		$data =  json_encode($data); 
		
		$stmt = mysqli_prepare($mysqli, "UPDATE info set data = ? where lid = ?"); 
		mysqli_stmt_bind_param($stmt, "si", $data, $lid);
		mysqli_stmt_execute($stmt);
		$result=mysqli_connect_error();

		//$SQL="UPDATE info set data = '$data' where lid = $lid and uid = $uid";
		//$result = $mysqli->query($SQL);
	}
}

//include "quiz-detail.php";
echo json_encode(array('lid'=>$lid, 'result'=>$result ));
?>
