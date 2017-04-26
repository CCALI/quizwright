<h3>Hi there!</h3>
<?php
require ("user-session.php");
$_POST['pages'] = explode(",",$_POST['pages']);// ensure pages are a JS array, not string.
$data= json_encode($_POST);

echo "This will set up the data for a quiz to be shipped to Drupal for further fun<br/>";
echo $data."<br />";
var_dump($_SESSION);

?>