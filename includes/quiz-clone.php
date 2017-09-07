<?php
// 08/28/2017 Clone a quiz 
//error_reporting(E_ALL); 
require ("user-session.php");
$lid = intval($_REQUEST['lid']);
// Rule: Only owning user may clone a quiz. 
$SQL = "SELECT data from `info` WHERE lid = $lid and uid = $uid";
if ($result = $mysqli->query($SQL))
{
	if ($row = $result->fetch_assoc())
	{
		$data = $row['data']; // Only cloning data, not auto publish info.
		$stmt = mysqli_prepare($mysqli, "INSERT INTO info (lid,uid,data) VALUES (?,?,?)"); 
		$lid=0;
		mysqli_stmt_bind_param($stmt, "iis", $lid, $uid, $data);
		mysqli_stmt_execute($stmt);
		$result=mysqli_connect_error();
		$lid = $mysqli->insert_id;
	}
}
echo "<!--".json_encode(array('lid'=>$lid, 'result'=>$result )).'-->';
?>

<div class="alert alert-success" role="alert">Quiz duplicated</div>

<?php
$data = json_decode($data, TRUE);
include "quiz-info-edit.inc";
?>
