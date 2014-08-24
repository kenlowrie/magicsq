<?php

function MyLog($format_string)
{
	if( func_num_args() == 1){
		printf("%s\n<br />",$format_string);
		return;
	}
	
	$args = func_get_args();
	array_shift($args);
	$tmp = vsprintf($format_string, $args);
	
	printf("%s\n<br />",$tmp);
}

function MyPrint($format_string)
{
	if( func_num_args() == 1){
		printf("%s",$format_string);
		return;
	}
	
	$args = func_get_args();
	array_shift($args);
	$tmp = vsprintf($format_string, $args);
	
	printf("%s",$tmp);
}

function getMobileStrings(){
	return array("iPhone", "iPad", "Android", "iPod", "webOS");
}

function getAppleStrings(){
	return array("iPhone", "iPad", "iPod");
}

function isHTTPUserAgent($osStrings){
	foreach ($osStrings as $agent) {
		if( stripos($_SERVER['HTTP_USER_AGENT'],$agent) ){
			return true;
		}		
	}
	return false;
}

function isAppleDevice(){
	return isHTTPUserAgent(getAppleStrings());
}

function isMobileDevice(){
	return isHTTPUserAgent(getMobileStrings());
}

function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, strlen($needle)) === $needle);
}

function endsWith($haystack, $needle)
{
    return (substr($haystack, -strlen($needle)) === $needle);
}

?>
