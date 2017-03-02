<!-- List user's lessons/quizzes  -->
<form class="form-horizontal" method="post">
    <fieldset>
    <legend>Your Quizzes</legend>
	 <p>  </p>
<div class="panel panel-default">
	 

  <!-- Default panel contents -->
  <div class="panel-heading">Quizzes</div>
  <!-- Table -->
  <table class="table">
	 <tr><th>ID#</th><th>Title</th><th>Description</th></tr>
<?php
require ("user-session.php");

$sql = "SELECT * FROM `info` WHERE uid = '$uid'";
if ($result = $mysqli->query($sql)) {
	 while ($row = $result->fetch_assoc()) {
        $data = json_decode($row['data'], TRUE);
		  ?>
<tr><td><?=$row['lid']?></td><td> <?=$data['title']?></td> <td> <?=$data['calidescription']?></td> </tr>
 


		<?php 
    }
}

?>
  </table>

 
</div>
</div>