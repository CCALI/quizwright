<?php
//	3/2017 Utility function used by more than one page.

function mergeObjects($old,$new)
{	//	combine elements from new into old, overwriting if needed.
	// Allows one to update different elements in a single JSON data.
	foreach ($new as $key=>$value){ $old[$key]=$value;}
	return $old;
}

?>
