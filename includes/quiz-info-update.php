<?php
// 05/19/2017 Update quiz  info.
error_reporting(E_ALL); 
require ("user-session.php");
$lid = intval($_REQUEST['lid']);
$SQL = "SELECT data from `info` WHERE lid = $lid and uid = $uid";
if ($result = $mysqli->query($SQL))
{
	if ($row = $result->fetch_assoc())
	{
		// Merge rather than overwrite. Use array_merge?
		$fieldsToUpdate=array("title","subjectarea","calidescription","completiontime","quiz-intro","quiz-conclusion");
		$data = json_decode($row['data'],TRUE);
		foreach ($fieldsToUpdate as $field)
		{
			$data[$field] = $_POST[$field]; //str_replace ('\'','\\u0027',$_POST[$field]);
		}
		$data =  json_encode($data);
		$stmt = mysqli_prepare($mysqli, "UPDATE info set data = ? where lid = ? and uid = ?"); 
		mysqli_stmt_bind_param($stmt, "sii", $data, $lid, $uid);
		mysqli_stmt_execute($stmt);
		$result=mysqli_connect_error();
		//$SQL="UPDATE info set data = '$data' where lid = $lid and uid = $uid";
		//$result = $mysqli->query($SQL);
	}
}
echo json_encode(array('lid'=>$lid, 'result'=>$result )); // , 'SQL'=>$SQL,
?>
