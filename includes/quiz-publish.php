<?php

require ("user-session.php");
require ("build-xml.php");	// 05/10/2017 Move XML builder to shared library

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
		//$URL = "https://d7.calidev.org/autopublish/upload";

    //setting the curl parameters.
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,PUBLISH_URL);
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
		 //echo $response;
		 //echo "<script type='text/javascript'>window.open('$apURL', '_blank')</script>";
	} 
         curl_close($ch);
		 
    }
		
		return;
	}


echo '<xml>Need lesson id</xml>';
	

?>