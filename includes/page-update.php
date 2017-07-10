<?php
// 2/28/2017 Update given page id - if id is blank, it's a new page. 
// Result is just json info. 
// Must use parameterized query to preserve json quotes/line breaks.

//error_reporting(E_ALL); 
require ("user-session.php");
$data =  json_encode($_POST);
$pid = $_POST['pid'];
if (!isset($pid))
{	// No ID? Must be a brand new page.
	if ($_POST['page-type'] != '')
	{
		$stmt = mysqli_prepare($mysqli, "INSERT INTO page (uid,data) VALUES (?,?)"); 
		mysqli_stmt_bind_param($stmt, "is", $uid, $data);
		mysqli_stmt_execute($stmt);
		printf("Error: %s.\n", mysqli_stmt_error($stmt));
		$result=mysqli_connect_error();
		//$SQL="INSERT INTO page (uid,data) VALUES ( $uid,'$data')";
		//$mysqli->query($SQL);
	}
}
else
{	// Override our page data.
	// WARNING: We may need to merge instead if we add elements to data that are NOT on the form.
	$pid=intval($pid);
	$stmt = mysqli_prepare($mysqli, "UPDATE page set data = ? where pid = ?"); 
	mysqli_stmt_bind_param($stmt, "si", $data, $pid);
	mysqli_stmt_execute($stmt);
	$result=mysqli_connect_error();
	//$SQL="UPDATE page set data = '$data' where pid = $pid and uid = $uid";
	//$mysqli->query($SQL);
}

echo json_encode(array('result'=>$result, "POST"=>$_POST));
?>
