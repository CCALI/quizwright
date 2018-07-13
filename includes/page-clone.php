<?php
// 04/30/2018 Clone a page. Clone page pid to user but only if shared. Attach a cloned-from tag.

//error_reporting(E_ALL); 
require ("user-session.php");
$pid = intval($_REQUEST['pid']);
$SQL = "SELECT data,uid from page WHERE pid = $pid";
if ($result = $mysqli->query($SQL))
{
	if ($row = $result->fetch_assoc())
	{
		$data = $row['data']; // Only cloning data, not auto publish info.
		$page = json_decode($row['data'], TRUE);
		$public= $page['public']=='true';
		if ($public  || $row['uid'] == $uid) 
		{	// Cloning a page, ensure the clone isn't shared, from the bank. Remember src page id and if a copy from the bank.
			$page['bank-copy']=$page['bank'];
			$page['bank']=false;
			$page['public']=false;
			$page['page-src-id']=$pid;
			$page['page-notes'] .= "<br/>" . date("m/d/Y").' Copied from Question Bank';
			$data=json_encode($page);
			$pid=0;
			$stmt = mysqli_prepare($mysqli, "INSERT INTO page (pid,uid,data) VALUES (?,?,?)"); 
			mysqli_stmt_bind_param($stmt, "iis", $pid, $uid, $data);
			mysqli_stmt_execute($stmt);
			$result=mysqli_connect_error();
			$pid = $mysqli->insert_id;

			?>
				<!--<div class="alert alert-success" role="alert">Copied</div>-->
				<span class="glyphicon glyphicon-ok" role="alert"></span>
			<?php
		}
		else
		{
			// cannot duplicate an unshared or unowned page
		}
	}
}
//echo "<!--".json_encode(array('pid'=>$pid, 'result'=>$result )).'-->';
?>
<?php
//$data = json_decode($data, TRUE);
//echo ($data);
//include "quiz-info-edit.inc";
?>
