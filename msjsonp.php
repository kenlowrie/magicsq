<?php

include_once ('session.php');	// session handling code
header('Content-Type: application/json');

$fmt = new cHTMLFormatter;

$variant = $_GET['variant'];
$item = $_GET['item'];

if($item=='ms') {
     //header("Content-Type: application/json");
     echo $_GET['callback'] . '(' . "{'newitem' : '<b>MS".$variant."</b>'}" . ')';
} else if ($item == 'jt') {
     echo $_GET['callback'] . '(' . "{'newitem' : '<b>JT".$variant."</b>'}" . ')';	
} else {
	 echo $_GET['callback'] . '(' . "{'newitem' : '<b>??".$variant."</b>'}" . ')';		
}

?>