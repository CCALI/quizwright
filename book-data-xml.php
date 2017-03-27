<?php
// 2/28/2017 Return CALI Author Viewer compatible Bookdata XML for this quiz.
require ("./includes/config.php");

header('Content-Type: text/xml');
//header('Content-Type: text/plain'); plain text for debugging in case XML gives errors

// Note: user id is ignored since students will need this
$lid = intval($_GET['lid']);
$SQL="select data from info where lid = $lid";
if ($result = $mysqli->query($SQL))
{
	while ($row = $result->fetch_assoc())
	{
		$data = json_decode($row['data'], TRUE);
		echo BuildXML($mysqli,$data);
		return;
	}
}

echo '<xml>Need lesson id</xml>';
	
	
	
function BuildXML($mysqli,$data)
{	// 3/2/2017 SJG $data is lesson $data block with meta info and page list.
	$xml='';
	$info=array(
		'TITLE'=>$data['title'],
		'SUBJECTAREA'=>$data['subjectarea']);
	$xml.='<INFO>';
	foreach ($info as $key=>$value) $xml.='<'.$key.'>'.htmlspecialchars( $value).'</'.$key.'>';
	$xml.='</INFO>';	
	
	$firstPage='Question 1';
	$toc='<PAGE ID="Contents" TYPE="Topics" STYLE="0" NEXTPAGEDISABLED="True" SORTNAME="Contents"><TOC><UL><LI><A HREF="Question 1">Questions</A></LI>'
		//.'<LI><A HREF="Conclusion">Conclusion</A></LI>
		.'</UL></TOC> </PAGE>';
	$xml .= $toc;
	$numPages = count($data['pages']);
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
						case 'quiz-tf':
/* Sample True/False question XML from CALI Lesson
<PAGE ID="Erie Origins 3" TYPE="Multiple Choice" STYLE="Choose Buttons" NEXTPAGE="Erie Origins: SWIFT FALSE" NEXTPAGEDISABLED="True">
	<QUESTION ALIGN="AUTO">
		<P>
			<b>TRUE OR FALSE: Under</b>
			<b>
				<i>Swift v. Tyson</i>
			</b>
			<b>, state common law did not count as "law" that could apply in federal court through the Rules of Decision Act.</b>
		</P>
	</QUESTION>
	<BUTTON>TRUE</BUTTON>
	<BUTTON>FALSE</BUTTON>
	<FEEDBACK BUTTON="1" DETAIL="1" GRADE="RIGHT" NEXTPAGE="Erie Origins: SWIFT TRUE"></FEEDBACK>
	<FEEDBACK BUTTON="2" DETAIL="1" GRADE="WRONG" NEXTPAGE="Erie Origins: SWIFT FALSE"></FEEDBACK>
</PAGE>
*/							
							$istrue = $page['true-is-correct']=='true';
							$pageXML = '<BUTTON>True</BUTTON><BUTTON>False</BUTTON>'
								.'<FEEDBACK BUTTON="1" DETAIL="1" GRADE="'.(($istrue)?'RIGHT':'WRONG').'" NEXTPAGE="'.$nextPage.'"></FEEDBACK>'
								.'<FEEDBACK BUTTON="2" DETAIL="1" GRADE="'.((!$istrue)?'RIGHT':'WRONG').'" NEXTPAGE="'.$nextPage.'"></FEEDBACK>';
							break;
						
						case 'Quiz':
						case 'quiz-mc':
						case '':
/*	Sample multiple choice question XML from CALI Lesson
 *<PAGE ID="Question 20.2" TYPE="Multiple Choice" STYLE="Choose List" NEXTPAGE="Question 21" NEXTPAGEDISABLED="False" SCORING="Totals" SORTNAME="Question 20. 2">
	<QUESTION ALIGN="AUTO">
		<P>If parties do not agree on a place of delivery, the place of delivery is:</P>
	</QUESTION>
	<DETAIL>
		<P>seller's place of business (or residence if none).</P>
	</DETAIL>
	<DETAIL>
		<P>buyer's place of business (or residence if none).</P>
	</DETAIL>
	<DETAIL>
		<P>a point halfway between seller's and buyer's place of business.</P>
	</DETAIL>
	<DETAIL>
		<P>Hoboken, New Jersey.</P>
	</DETAIL>
	<FEEDBACK BUTTON="1" DETAIL="1" GRADE="RIGHT">
		<P>Right! The authority for this answer is UCC &#167;&#160;2-308.</P>
	</FEEDBACK>
	<FEEDBACK BUTTON="1" DETAIL="2" GRADE="WRONG">
		<P>Sorry.</P>
	</FEEDBACK>
	<FEEDBACK BUTTON="1" DETAIL="3" GRADE="WRONG">
		<P>Sorry.</P>
	</FEEDBACK>
	<FEEDBACK BUTTON="1" DETAIL="4" GRADE="WRONG">
		<P>I'm glad you have a sense of humor, but I hope you get it right on the next try.</P>
	</FEEDBACK>
</PAGE>
*/							$choices=array(); // assemble feedbacks, which we will shuffle
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
							break;
						default:
							// Should not get here.
					}
					$pageXML='<PAGE ID="'.$pageName.'" TYPE="Multiple Choice" STYLE="Choose List" NEXTPAGE="'.$nextPage.'" NEXTPAGEDISABLED="False" SCORING="Totals" SORTNAME="'.$pageName.'">'
						.'<QUESTION ALIGN="AUTO">'.$pageText.'</QUESTION>'.$pageXML.'</PAGE>';
					$xml .= $pageXML;
				}
			}
		}				
	}

	$xml = '<?xml version="1.0" ?><BOOK>'.$xml.'</BOOK>';
	return $xml;
}
/*
 <?xml version="1.0" ?>
<BOOK>
<INFO><TITLE>A Copyright Quiz</TITLE>
<LESSON>A COPYRIGHT QUIZ</LESSON>
<SUBJECTAREA>Copyright</SUBJECTAREA>
<EMAILCONTACT></EMAILCONTACT>
<VERSION>04/11/2015</VERSION>
<CAVERSIONREQUIRED>4.2.1</CAVERSIONREQUIRED>
<DISTRIBUTION>Personal</DISTRIBUTION>
<COMPLETIONTIME>10 minutes</COMPLETIONTIME>
<CREATEDATE>2015-04-11 08:19:43</CREATEDATE>
<MODIFYDATE>2015-04-11 08:57:56</MODIFYDATE>
<COPYRIGHTS>Copyright 2015</COPYRIGHTS>
<CREDITS></CREDITS>
<CALIDESCRIPTION><P>How well do you know basic copyright law? This will show you.</P></CALIDESCRIPTION>
<AUTHORS><AUTHOR><NAME>Elmer R Masters</NAME>
<TITLE>Director of Technology</TITLE>
<SCHOOL>CALI</SCHOOL>
<EMAIL>emasters@cali.org</EMAIL>
<PHONE>773-332-7508</PHONE>
<WEBADDRESS>http://www.cali.org</WEBADDRESS>
</AUTHOR>
</AUTHORS>
<NOTES>04/11/2015 08:19:43: Book was created</NOTES>
<DESCRIPTION><P>How well do you know basic copyright law? This will show you.</P>
<P>Approximate Completion Time: 10 minutes</P>
<BR /><BR /><BR />
<div style='font-size: .8em'>A Copyright Quiz<BR />by
<P>Elmer R Masters<BR />Director of Technology<BR />CALI<BR /><A HREF="mailto:emasters@cali.org">emasters@cali.org</A> <A HREF="http://www.cali.org">http://www.cali.org</A> </P>
<P>Copyright 2015<BR />CALI Author Copyright 1999-2012 Center for Computer-Assisted Legal Instruction.</P>
<P>Version 04/11/2015</P>
</div>
</DESCRIPTION>
<CBKLOCATION>C:\CCALI\my books\A Copyright Quiz.CBK</CBKLOCATION>
</INFO>

<PAGE ID="Contents" TYPE="Topics" STYLE="0" NEXTPAGEDISABLED="True" SORTNAME="Contents"><TOC><UL><LI><A HREF="Introduction">Introduction</A></LI><LI><A HREF="Question 1">Questions</A></LI><LI><A HREF="Conclusion">Conclusion</A></LI></UL></TOC> </PAGE> 
<PAGE ID="Introduction" TYPE="Book Page" NEXTPAGE="Contents" NEXTPAGEDISABLED="False" SORTNAME="Introduction"><TEXT ALIGN="AUTO"><P>This short quiz on the basics of copyright will test your understanding of general topics in copyright law.</P></TEXT> </PAGE> 
<PAGE ID="Question 1" TYPE="Multiple Choice" STYLE="Choose Buttons" NEXTPAGE="Question 2" NEXTPAGEDISABLED="False" SCORING="Totals" SORTNAME="Question    1"><QUESTION ALIGN="AUTO"><P>You must register your copyright otherwise published materials are copyright free.</P></QUESTION> <BUTTON>True</BUTTON> <BUTTON>False</BUTTON> <FEEDBACK BUTTON="1" DETAIL="1" GRADE="WRONG"><P><B>No</B>. The default position is that all works are copyrighted as &quot;all rights reserved&quot;. You should generally assume that all works are protected by copyright. </P></FEEDBACK> <FEEDBACK BUTTON="2" DETAIL="1" GRADE="RIGHT"><P><B>Correct</B>. There is no requirement to &quot;register&quot; copyright. The default position is &quot;all rights reserved&quot; copyright. You should generally assume that all works are protected by copyright.</P></FEEDBACK> </PAGE> 
<PAGE ID="Conclusion" TYPE="Book Page" STYLE="Choose Buttons" NEXTPAGE="Contents" NEXTPAGEDISABLED="False" SORTNAME="Conclusion"><TEXT ALIGN="AUTO"><P><SMALL>Acknowledgement: Some questions in this quiz were inspired by &quot;<A HREF="[http://www.smartcopying.edu.au/scw/go/pid/657 ">Some common misconceptions</A>&quot; by <A HREF="[http://www.smartcopying.edu.au/scw/go/pid/1">smartcopying</A>.</SMALL></P></TEXT> </PAGE> 
<PAGE ID="Question 2" TYPE="Multiple Choice" STYLE="Choose Buttons" NEXTPAGE="Question 3" NEXTPAGEDISABLED="False" SCORING="Totals" SORTNAME="Question    2"><QUESTION ALIGN="AUTO"><P>You are free to repackage and sell content sourced from <A HREF="[http://en.wikipedia.org/wiki/English_Wikipedia">Wikipedia</A>, the free encyclopedia.</P></QUESTION> <BUTTON>True</BUTTON> <BUTTON>False</BUTTON> <FEEDBACK BUTTON="1" DETAIL="1" GRADE="RIGHT"><P><B>Correct</B>. There are no commercial restrictions on repackaging Wikipedia content, as long as your products are licensed under the same license used by Wikipedia.</P></FEEDBACK> <FEEDBACK BUTTON="2" DETAIL="1" GRADE="WRONG"><P><B>Incorrect</B>. The copyright license used by Wikipedia does not restrict commercial activity on condition that you share your products under the same license. </P></FEEDBACK> </PAGE> 
<PAGE ID="Question 3" TYPE="Multiple Choice" STYLE="Choose Buttons" NEXTPAGE="Question 4" NEXTPAGEDISABLED="False" SCORING="Totals" SORTNAME="Question    3"><QUESTION ALIGN="AUTO"><P>You are free to copy and reuse content which can be openly accessed on the web for educational purposes.</P></QUESTION> <BUTTON>True</BUTTON> <BUTTON>False</BUTTON> <FEEDBACK BUTTON="1" DETAIL="1" GRADE="WRONG"><P><B>No</B>. The fact that a resource is accessible on a public website does not necessarily change the copyright protections.</P></FEEDBACK> <FEEDBACK BUTTON="2" DETAIL="1" GRADE="RIGHT"><P><B>Correct</B>. Public access on a website does not necessarily grant permissions for reusing and copying materials for educational purposes.</P></FEEDBACK> </PAGE> 
<PAGE ID="Question 4" TYPE="Multiple Choice" STYLE="Choose Buttons" NEXTPAGE="Question 5" NEXTPAGEDISABLED="False" SCORING="Totals" SORTNAME="Question    4"><QUESTION ALIGN="AUTO"><P>You can reuse, adapt and modify content published in the Public Domain without attributing the source.</P></QUESTION> <BUTTON>True</BUTTON> <BUTTON>False</BUTTON> <FEEDBACK BUTTON="1" DETAIL="1" GRADE="RIGHT"><P><B>Correct</B>. The public domain means that the holder has waived all copyrights including the requirement for attribution. However, from an ethical perspective, attributing your sources is the right thing to do.</P></FEEDBACK> <FEEDBACK BUTTON="2" DETAIL="1" GRADE="WRONG"><P>Incorrect. While attributing your sources is the right thing to do -- there is no legal requirement to attribute the source of works published in the public domain.</P></FEEDBACK> </PAGE> 
<PAGE ID="Question 5" TYPE="Multiple Choice" STYLE="Choose Buttons" NEXTPAGE="Question 6" NEXTPAGEDISABLED="False" SCORING="Totals" SORTNAME="Question    5"><QUESTION ALIGN="AUTO"><P>You will not infringe copyright as long as you don't make money from the use of the materials.</P></QUESTION> <BUTTON>True</BUTTON> <BUTTON>False</BUTTON> <FEEDBACK BUTTON="1" DETAIL="1" GRADE="WRONG"><P><B>Incorrect</B>. Generally speaking, copyright protections apply irrespective of whether money changes hands.</P></FEEDBACK> <FEEDBACK BUTTON="2" DETAIL="1" GRADE="RIGHT"><P><B>Correct</B>. The reuse of all rights reserved materials without permission of the copyright holder for non-profit purposes would constitute a breach of copyright.</P></FEEDBACK> </PAGE> 
<PAGE ID="Question 6" TYPE="Multiple Choice" STYLE="Choose Buttons" NEXTPAGE="Question 7" NEXTPAGEDISABLED="False" SCORING="Totals" SORTNAME="Question    6"><QUESTION ALIGN="AUTO"><P>If there is no copyright symbol or notice, then you can assume the work is copyright free.</P></QUESTION> <BUTTON>True</BUTTON> <BUTTON>False</BUTTON> <FEEDBACK BUTTON="1" DETAIL="1" GRADE="WRONG"><P><B>Incorrect</B>. The absence of a copyright notice does not mean that holder has abandoned copyright. You should assume the default position of &quot;all rights reserved&quot; copyright.</P></FEEDBACK> <FEEDBACK BUTTON="2" DETAIL="1" GRADE="RIGHT"><P><B>Correct</B>. The default position when the author has not otherwise indicated copyright is that the work is copyrighted as &quot;all rights reserved&quot;. You should generally assume that all works are protected by copyright.</P></FEEDBACK> </PAGE> 
<PAGE ID="Question 7" TYPE="Multiple Choice" STYLE="Choose Buttons" NEXTPAGE="Conclusion" NEXTPAGEDISABLED="False" SCORING="Totals" SORTNAME="Question    7"><QUESTION ALIGN="AUTO"><P>If the materials use a <A HREF="http://creativecommons.org/ ">Creative Commons</A> license, you are free to reuse, adapt, mix and modify these resources for educational purposes.</P></QUESTION> <BUTTON>True</BUTTON> <BUTTON>False</BUTTON> <FEEDBACK BUTTON="1" DETAIL="1" GRADE="WRONG"><P><B>Incorrect</B>. The permissions and restrictions associated with a Creative Commons license depends on the type of Creative Commons license used.</P></FEEDBACK> <FEEDBACK BUTTON="2" DETAIL="1" GRADE="RIGHT"><P>Yes. You must confirm which type of Creative Commons license is used in order to determine the permissions for reuse. </P></FEEDBACK> </PAGE> 
</BOOK>

*/
?>
