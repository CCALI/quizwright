<!-- List all author's pages -->
<?php
require ("user-session.php");


$sql = "SELECT * FROM `pages` WHERE uid = '$uid'";
if ($result = $mysqli->query($sql)) {
	 while ($row = $result->fetch_assoc()) {
        $data = json_decode($row['data'], TRUE);
			echo "<li>".$data['name']."</li>";
    }
}

?>