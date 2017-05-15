<?php
// 2/28/2017 Update given page id - if id is blank, it's a new page. 
// Result is just json info. 

require ("user-session.php");

$data =  str_replace ('\'','\\u0027',json_encode($_POST)); // Encode ' to avoid MySQl escape mess. 
$pid = $_POST['pid'];
if (!isset($pid))
{	// No ID? Must be a brand new page.
	if ($_POST['page-question'] != '')
	{
		//$stmt = mysqli_prepare($mysqli, "INSERT INTO page (uid,data) VALUES (?,?)"); 
		//mysqli_stmt_bind_param($stmt, "is", $uid, $data);
		//mysqli_stmt_execute($stmt);
		$SQL="INSERT INTO page (uid,data) VALUES ( $uid,'$data')";
		$mysqli->query($SQL);
	}
}
else
{	// Override our page data.
	// WARNING: We may need to merge instead if we add elements to data that are NOT on the form. 
	$pid = intval($pid);
	$SQL="UPDATE page set data = '$data' where pid = $pid and uid = $uid";
	$mysqli->query($SQL);
}

echo json_encode(array('SQL'=>$SQL, "POST"=>$_POST));
?>
