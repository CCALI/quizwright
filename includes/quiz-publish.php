<?php

require ("user-session.php");
require ("build-xml.php");	// 05/10/2017 Move XML builder to shared library

//header('Content-Type: text/plain'); plain text for debugging in case XML gives errors

// Note: user id is ignored since students will need this
$lid = intval($_GET['lid']);


// Query to get lesson and author data
$SQL="select info.data, people.profile from info,people where lid = $lid and info.uid = people.uid";

if ($result = $mysqli->query($SQL))
	{
		while ($row = $result->fetch_assoc())
		{
			$data = json_decode($row['data'], TRUE);
			$profile = json_decode($row['profile'], TRUE);
			$jqbookdata = BuildXML($mysqli,$lid,$data,$profile);
			// Create quiz folder and add/update jqBookData.
			$dir = "../quizzes/$lid";
			if (!is_dir($dir))
			{
				mkdir($dir);
			}
			file_put_contents($dir.'/jqBookData.xml',$jqbookdata);
			//some CURL stuff
			//setting the curl parameters.
			$ch = curl_init();
			$upload_url = PUBLISH_URL."/".$lid;
			$cfile = curl_file_create($dir.'/jqBookData.xml','application/xml','jqBookData.xml');			
			$data = array('quiz_files' => $cfile);
			$fp = fopen(dirname(__FILE__).'/errorlog.txt', 'w');
			curl_setopt($ch, CURLOPT_VERBOSE, 1);
			curl_setopt($ch, CURLOPT_STDERR, $fp);
			curl_setopt($ch, CURLOPT_URL,$upload_url);
			curl_setopt($ch, CURLOPT_VERBOSE, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			
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
				//die;
				$xml = new SimpleXMLElement($response);
				// var_dump($xml);
				 $apURL = $xml->URL[0];
				 header("Location: $apURL");
				 //echo $response;
				 //echo "<script type='text/javascript'>window.open('$apURL', '_blank')</script>";
				} 
			curl_close($ch);
				 
		}
			
		return;
	}


echo '<xml>Need lesson id</xml>';
	

?>