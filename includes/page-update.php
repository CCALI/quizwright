<?php
// 2/28/2017 Update given page id - if id is blank, it's a new page. 
// Result is just json info. 
// Must use parameterized query to preserve json quotes/line breaks.

//error_reporting(E_ALL); 
require ("user-session.php");
require ("utility.php");
$pid = $_POST['pid'];
if (!isset($pid))
{	// No ID? Must be a brand new page.
	if ($_POST['page-type'] != '')
	{
		$data =  json_encode($_POST);
		$stmt = mysqli_prepare($mysqli, "INSERT INTO page (uid,data) VALUES (?,?)"); 
		mysqli_stmt_bind_param($stmt, "is", $uid, $data);
		mysqli_stmt_execute($stmt);
		//printf("Error: %s.\n", mysqli_stmt_error($stmt));
		$result=mysqli_stmt_error($stmt);
		//$SQL="INSERT INTO page (uid,data) VALUES ( $uid,'$data')";
		//$mysqli->query($SQL);
	}
}
else
{	// Override our page data.
	// WARNING: We may need to merge instead if we add elements to data that are NOT on the form.
	$pid=intval($pid);
	$sql = "SELECT data FROM `page` WHERE pid=$pid and uid = $uid";
	if ($result = $mysqli->query($sql))
	{
		if ($row = $result->fetch_assoc())
		{
			$data=json_encode(mergeObjects(json_decode($row['data'],TRUE),$_POST));
			$stmt = mysqli_prepare($mysqli, "UPDATE page set data = ? where pid = ?"); 
			mysqli_stmt_bind_param($stmt, "si", $data, $pid);
			mysqli_stmt_execute($stmt);
			$result=mysqli_connect_error();
		}
	}


	
}

echo json_encode(array('result'=>$result, "POST"=>$_POST));
?>
