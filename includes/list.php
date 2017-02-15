<?php
require ("./config.php");
session_start();
$uid = $_SESSION['uid'];

$sql = "SELECT * FROM `info` WHERE uid = '$uid'";
if ($result = $mysqli->query($sql)) {
	 while ($row = $result->fetch_assoc()) {
        $data = json_decode($row['data'], TRUE);
		echo "<li>".$data['title']."</li>";
    }
}

?>