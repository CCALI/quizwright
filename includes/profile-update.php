<?php
	// 3/3/2017 Update author profile info.
	require ("user-session.php");
	$profile = json_encode($_POST); 
	$SQL="UPDATE people set profile='$profile' where uid = $uid";
	$result = $mysqli->query($SQL);
	echo json_encode(array( 'SQL'=>$SQL,'result'=>$result ));
?>
