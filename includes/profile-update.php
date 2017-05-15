<?php
	// 3/3/2017 Update author profile info.
	// 05/12/2017 Data field seems broken. Using profile instead.
	require ("user-session.php");
	$sql = "SELECT profile FROM `people` WHERE uid = '$uid'";
	if ($result = $mysqli->query($sql))
	{
		$row = $result->fetch_assoc();
		// TODO merge instead of overwrite?
		
		$profile = json_encode($_POST); 
		$SQL="UPDATE people set profile='$profile' where uid = $uid";
		$result = $mysqli->query($SQL);
	}
	echo json_encode(array( 'result'=>$result )); // , 'SQL'=>$SQL,
?>
