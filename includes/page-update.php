<?php
	// 2/28/2017 Update given page id - if id is blank, it's a new page. 
	// Result is just json info. 

	require ("user-session.php");

	$data = json_encode($_POST);
	$pid = $_POST['pid'];
	if (!isset($pid))
	{	// No ID? Must be a brand new page.
		if ($_POST['page-question'] != '')
		{
			$SQL="INSERT INTO page (uid,data) VALUES ( $uid,'$data')";
			$mysqli->query($SQL);
		}
	}
	else
	{	// Override our page data. 
		$pid = intval($pid);
		$SQL="UPDATE page set data = '$data' where pid = $pid and uid = $uid";
		$mysqli->query($SQL);
	}

	echo json_encode(array('SQL'=>$SQL, "POST"=>$_POST));
?>
