<?php
// 3/2/2017 Update quiz's meta info.
require ("user-session.php");
//require ("utility.php");

// 3/2/2017 Given list of checked pages, add them to a new quiz.
$_POST['pages'] = explode(",",$_POST['pages']);// ensure pages are a JS array, not string.
$data= json_encode($_POST);
$mysqli->query("INSERT INTO info (lid,uid,data) VALUES ('',$uid,'$data')");

//$data = json_encode(mergeObjects($data,$_POST)); 

$lid = $mysqli->insert_id;

echo json_encode(array( 'lid'=>$lid,'SQL'=>$SQL,'result'=>$result ));
?>
