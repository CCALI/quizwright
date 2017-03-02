<?php
	// 3/2/2017 Given list of checked pages, add them to a new quiz.
	// Result is just json info. 

	require ("user-session.php");
	
	$pages=array();
	foreach ($_POST as $key => $value)
	{
		$pid = intval($key);
		if ($value=='on' && $pid>0)
		{
			array_push($pages,$pid);
		} 
	}
	
	$data = json_encode(array("pages"=>$pages));
	$mysqli->query("INSERT INTO info (lid,uid,data) VALUES ('',$uid,'$data')");
	$last_id = $mysqli->insert_id;
	echo json_encode(array("lid"=>$last_id, 'SQL'=>$SQL, "POST"=>$_POST));
?>
