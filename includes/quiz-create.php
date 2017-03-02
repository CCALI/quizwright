<?php
	// 3/2/2017 Update quiz's meta info.

	require ("user-session.php");
	$lid = intval($_REQUEST['lid']);
	$sql = "SELECT * FROM `info` WHERE uid = '$uid' and lid = $lid";
	if ($result = $mysqli->query($sql))
	{
		 while ($row = $result->fetch_assoc()) {
	        $data = json_decode($row['data'], TRUE);
		 }
		foreach ($_POST as $key=>$value){ $data[$key]=$value;}
		$data = json_encode($data); 
		$SQL="UPDATE info set data='$data' where lid = $lid and uid = $uid";
		$result = $mysqli->query($SQL);
	}
	
	echo json_encode(array( 'lid'=>$lid,'SQL'=>$SQL,'result'=>$result ));
?>
