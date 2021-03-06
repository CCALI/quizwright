<!-- List one page's details  -->
<?php
/*
	05/12/2017 Return full detail report of this question to embed in list of questions.
	TODO include filter to allow other author's questions displayed to this author if PUBLIC.
	
	07/14/2017
		Added: Yes/No question type
*/

require ("user-session.php");
require "utility.php";
$pid = intval($_REQUEST['pid']);
$sql = "SELECT uid,data FROM `page` WHERE pid = $pid";
if ($result = $mysqli->query($sql))
{
	if ($row = $result->fetch_assoc())
	{
		// Check page type so we get accurate detail (but as of 3/2017 there are all quiz type)
		$page = json_decode($row['data'] , TRUE );
		if ($page['public'] || $row['uid']==$uid)
		{
	//		var_dump($page);
			echo pageDetailHTML($page);
		}
		else
		{
			echo "Page $pid not accessible to user $uid";
		}
	}
	else
	{
		echo "Page $pid not found";
	}
}
function pageDetailHTML($page)
{	// Return pleasant HTML markup description of page.
	// TODO: move to shared library so we can display this in other places.
	$pagetype = $page['page-type'];
	$html='<table class="table">';
	
	$topic=niceTopicAndTags($page);
	if ($topic!='') $html.='<tr><td>Topic</td><td>'.$topic.'</td></tr>';

	if ($page['page-question']!='') $html.='<tr><td>Question</td><td>'.$page['page-question'].'</td></tr>';
	switch ($pagetype)
	{
		
		case 'quiz-yn':	// Yes/No
			$isyes = $page['yes-is-correct']=='true';
			$html.='<tr><td>Type</td><td>'
				. ($isyes ?  '<span class="correct">Yes</span>/No' : 'Yes/<span class="correct">No</span>')
				.'</td></tr>';
			break;
		
		case 'quiz-tf':	// True/false 
			$istrue = $page['true-is-correct']=='true';
			$html.='<tr><td>Type</td><td>'
				. ($istrue ?  '<span class="correct">True</span>/False' : 'True/<span class="correct">False</span>')
				.'</td></tr>';
			break;
		
		case 'Quiz':	// Multiple choice: 1 correct, 1-N wrong.
		case 'quiz-mc':
		case '':
			$html.='<tr><td class="correct">Correct</td><td>'.$page['page-choice-correct-text'].'</td></tr>';
			for ($wrong=1;$wrong<=7;$wrong++)
			{
				$wrongText = $page['page-choice-wrong-'.$wrong.'-text'];
				if ($wrongText!='') {
					$html.='<tr><td class="wrong">Wrong</td><td>'.$wrongText.'</td></tr>';
				}
			}
			break;
		default:
			// Should never get here.
	}
	if ($page['page-feedback']!='') $html.='<tr><td>Feedback</td><td>'.$page['page-feedback'].'</td></tr>';
	if ($page['page-attribution']!='') $html.='<tr><td>Attribution</td><td>'.$page['page-attribution'].'</td></tr>';
	if ($page['page-notes']!='') $html.='<tr><td>Notes</td><td>'.$page['page-notes'].'</td></tr>';
	return $html;
}

?>
