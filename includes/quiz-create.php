<?php
// 03/02/2017 Create a new quiz starting with list of questions. 
require ("user-session.php");

// 3/2/2017 Given list of checked pages, add them to a new quiz.
$_POST['pages'] = explode(",",$_POST['pages']);// ensure pages are a JS array, not string.
$data =  str_replace ('\'','\\u0027',json_encode($_POST)); // Encode ' to avoid MySQl escape mess. 
$mysqli->query("INSERT INTO info (lid,uid,data) VALUES ('',$uid,'$data')");
$lid = $mysqli->insert_id;
echo json_encode(array( 'lid'=>$lid,'SQL'=>$SQL,'result'=>$result ));
?>
