<?php
// 03/02/2017 Create a new quiz starting with list of questions. 
require ("user-session.php");

// 3/2/2017 Given list of checked pages, add them to a new quiz.
$_POST['pages'] = explode(",",$_POST['pages']);// ensure pages are a JS array, not string.
$data = json_encode($_POST);

// Old, bad quotes: $mysqli->query("INSERT INTO info (lid,uid,data) VALUES ('',$uid,'$data')");
$stmt = mysqli_prepare($mysqli, "INSERT INTO info (lid,uid,data) VALUES (?,?,?)"); 
mysqli_stmt_bind_param($stmt, "iis", $lid, $uid, $data);
mysqli_stmt_execute($stmt);
$result=mysqli_connect_error();
$lid = $mysqli->insert_id;
echo json_encode(array( 'lid'=>$lid,'SQL'=>$SQL,'result'=>$result ));
?>
