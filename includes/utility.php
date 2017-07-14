<?php
/*
	3/2017 Utility function used by more than one page.
	07/14/2017
		Added: Yes/No question type
*/

function mergeObjects($old,$new)
{	//	3/2017 SJG Combine elements from new into old, overwriting if needed.
	// Allows one to update different elements in a single JSON data.
	//	This will be replaced when we switch to JSON native storage.
	foreach ($new as $key=>$value){ $old[$key]=$value;}
	return $old;
}

function oneLinerHTML($html)
{	// 06/28/2017 SJG Strip html of HTML tags and shorten to MAXCHARS characters for use in lists of questions/quizzes.
	//	Ideally, use CSS markup for wrapping. This hack works for now and reduces traffic a bit. 
	define('MAXCHARS',60);
	$text = trim(strip_tags($html));
	if (strlen($text)>MAXCHARS)
	{
		$text = substr($text,0,MAXCHARS).'…';
	}
	return $text;
}

function compactQuestionDescription($page)
{	// 06/28/2017 SJG Given $page JSON object, return compact question description including question text and some type/choice info.
	$text = oneLinerHTML($page['page-question']);
	switch ( $page['page-type'] )
	{
		case 'quiz-yn':
			$type='Y/N';
			break;
		case 'quiz-tf':
			$type='T/F';
			break;
		default:
			$choices=1;
			for ($wrong=1;$wrong<=7;$wrong++)
			{
				$wrongText = $page['page-choice-wrong-'.$wrong.'-text'];
				if ($wrongText!='') {
					$choices ++;
				}
			}
			$type=$choices.' choices';
			break;
	}
	return $text. ' ('.$type.')';
}

?>
