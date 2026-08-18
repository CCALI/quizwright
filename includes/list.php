<?php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/config.php';
$uid = (int) ($_SESSION['uid'] ?? 0);

$stmt = $mysqli->prepare("SELECT data FROM `info` WHERE uid = ?");
$stmt->bind_param("i", $uid);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
	$data = json_decode($row['data'], TRUE);
	echo "<li>" . htmlspecialchars($data['title'] ?? '') . "</li>";
}
$stmt->close();

?>
