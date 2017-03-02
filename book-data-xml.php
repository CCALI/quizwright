<?php
	// 2/28/2017 Return CALI Author Viewer compatible Bookdata XML for this quiz.
	require ("./includes/config.php");

	header('Content-Type: text/xml');

	// Note: user id is ignored since students will need this
	$lid = intval($_GET['lid']);
	$SQL="select data from info where lid = $lid";
	if ($result = $mysqli->query($SQL))
	{
		while ($row = $result->fetch_assoc())
		{
			$data = json_decode($row['data'], TRUE);
			echo BuildXML($data);
			return;
		}
	}

	echo '<xml>Need lesson id</xml>';
	
	
	
function BuildXML($data)
{	// 3/2/2017 SJG 
	$xml='';
	$info=array(
		'TITLE'=>$data['title'],
		'SUBJECTAREA'=>$data['subjectarea']);
	//foreach ($info as $key=>$value) $xml.='<'.$key.'>'.$value.'</'
		
	
	
	//<PAGE ID="Contents" TYPE="Topics" STYLE="0" NEXTPAGEDISABLED="True" SORTNAME="Contents"><TOC><UL><LI><A HREF="Introduction">Introduction</A></LI><LI><A HREF="Question 1">Questions</A></LI><LI><A HREF="Conclusion">Conclusion</A></LI></UL></TOC> </PAGE> 

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
