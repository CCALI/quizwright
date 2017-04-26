<?php
require ("user-session.php");

//header('Content-Type: text/plain'); plain text for debugging in case XML gives errors

// Note: user id is ignored since students will need this
$lid = intval($_GET['lid']);
//$SQL="select data from info where lid = $lid";

// Query to get lesson and author data
$SQL="select info.data, people.profile from info,people where lid = $lid and info.uid = people.uid";

if ($result = $mysqli->query($SQL))
{
	while ($row = $result->fetch_assoc())
	{
		$data = json_decode($row['data'], TRUE);
		$profile = json_decode($row['profile'], TRUE);
		$jqbookdata = BuildXML($mysqli,$data,$profile);
		
		//some CURL stuff
		$URL = "https://d7.calidev.org/autopublish/upload";

    //setting the curl parameters.
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,$URL);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jqbookdata);

        if (curl_errno($ch)) 
    {
        // moving to display page to display curl errors
          echo curl_errno($ch) ;
          echo curl_error($ch);
    } 
    else 
    {
        //getting response from server
        $response = curl_exec($ch);
		// $response is a bit of XML
         //echo $response;
		 $xml = new SimpleXMLElement($response);
		// var_dump($xml);
		 $apURL = $xml->URL[0];
		 header("Location: $apURL");
         curl_close($ch);
		 
    }
		
		return;
	}
}

echo '<xml>Need lesson id</xml>';
	
	
	
function BuildXML($mysqli,$data,$author)
{	// 3/2/2017 SJG $data is lesson $data block with meta info and page list.
	$xml='';
	
	$description=
		'<DIV>'.$data['calidescription'].'</DIV>'
		.'<P>Approximate Completion Time: '. $data['completiontime']. '</P>'
		.'<BR /><BR />'
		.'<DIV style="font-size: .8em">'. $data['title']
		.'<BR />by '
		.'<BR />'.$author['authorfullname']
		.'<BR />'.$author['authortitle']
		.'<BR />'.$author['authorschool']
		.'</DIV>';
		
	$info=array(
		'TITLE'=>htmlspecialchars($data['title']),
		'SUBJECTAREA'=>htmlspecialchars($data['subjectarea']),
		'DESCRIPTION'=> ( $description),
		'COMPLETIONTIME'=>htmlspecialchars($data['completiontime']),
		'NOTES'=>'Book automatically created by QuizWright'
		
		);
	$xml.='<INFO>';
	foreach ($info as $key=>$value) $xml.='<'.$key.'>'. $value .'</'.$key.'>';
	$xml.='</INFO>';	
	
	$numPages = count($data['pages']);
	$firstPage='Question 1';
	$toc='<PAGE ID="Contents" TYPE="Topics" STYLE="0" NEXTPAGEDISABLED="True" SORTNAME="Contents"><TOC><UL><LI><A HREF="Question 1">'.$numPages.' Questions</A></LI>'
		//.'<LI><A HREF="Conclusion">Conclusion</A></LI>
		.'</UL></TOC> </PAGE>';
	$xml .= $toc;
	$pageNum=0;
	if ($numPages>0)
	{ 
		foreach ($data['pages'] as $pid)
		{
			$pid=intval($pid);
			$sql = "SELECT data FROM `page` WHERE pid = $pid";
			if ($result = $mysqli->query($sql))
			{
				if ($row = $result->fetch_assoc())
				{
					// check page type so we get accurate detail (but as of 3/2017 there are all quiz type) which translates to Multiple Choice type.
					$page = json_decode($row['data'], TRUE);
					$pagetype = $page['page-type'];
					$pageNum++;
					$pageName = 'Question '.$pageNum;
					$nextPage = ($pageNum < $numPages) ? ('Question '.($pageNum+1)) : ('Contents');
					$pageText = $page['page-question'];
					$pageXML='';
					switch ($pagetype)
					{
						
						case 'quiz-tf': // This will be a CA Buttons-only thype.
						
							$istrue = $page['true-is-correct']=='true';
							$pageXML = '<BUTTON>True</BUTTON><BUTTON>False</BUTTON>'
								.'<FEEDBACK BUTTON="1" DETAIL="1" GRADE="'.(($istrue)?'RIGHT':'WRONG').'" NEXTPAGE="'.$nextPage.'"></FEEDBACK>'
								.'<FEEDBACK BUTTON="2" DETAIL="1" GRADE="'.((!$istrue)?'RIGHT':'WRONG').'" NEXTPAGE="'.$nextPage.'"></FEEDBACK>';
						$pageXML='<PAGE ID="'.$pageName.'" TYPE="Multiple Choice" STYLE="Choose Buttons" NEXTPAGE="'.$nextPage.'" NEXTPAGEDISABLED="False" SCORING="Totals" SORTNAME="'.$pageName.'">'
							.'<QUESTION ALIGN="AUTO">'.$pageText.'</QUESTION>'.$pageXML.'</PAGE>';
							break;
						
						case 'Quiz':
						case 'quiz-mc':
						case '':
							$choices=array(); // assemble feedbacks, which we will shuffle
							$choices[] = array("DETAIL"=>$page['page-choice-correct-text'],"GRADE"=>"RIGHT");
							for ($wrong=1;$wrong<=7;$wrong++)
							{
								$wrongText = $page['page-choice-wrong-'.$wrong.'-text'];
								if ($wrongText!=''){							
									$choices[] = array("DETAIL"=>$wrongText,"GRADE"=>"WRONG");
									}
							} 
							shuffle($choices);							
							$choicei=0;
							$details='';
							$feedbacks='';
							foreach($choices as $choice)
							{
								$choicei++;
								$details .= '<DETAIL>'.$choice['DETAIL'].'</DETAIL>';
								$feedbacks.='<FEEDBACK BUTTON="1" DETAIL="'.$choicei.'" GRADE="'.$choice['GRADE'].'" NEXTPAGE="'.$nextPage.'"></FEEDBACK>';
							}
							$pageXML = $details.$feedbacks;
						$pageXML='<PAGE ID="'.$pageName.'" TYPE="Multiple Choice" STYLE="Choose List" NEXTPAGE="'.$nextPage.'" NEXTPAGEDISABLED="False" SCORING="Totals" SORTNAME="'.$pageName.'">'
							.'<QUESTION ALIGN="AUTO">'.$pageText.'</QUESTION>'.$pageXML.'</PAGE>';
							break;
						default:
							// Should not get here.
					}
					$xml .= $pageXML;
				}
			}
		}				
	}

	$xml = '<?xml version="1.0" ?><BOOK>'.$xml.'</BOOK>';
	return $xml;
}

?>