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

function parseTerms($fmt,$inputData){
	$row = 1;
	$headers = 0;		// ignore header rows in the final count
	$myterms = new Terms;
	$myterms->setFilename("Manual Entry.csv");

	$fmt->startDiv("statusArea");
	
	$fmt->h4("Processing manual entry of terms...");
	// Make sure that the file is recognized...

	foreach ($inputData as $line){
		$data = str_getcsv($line);
		
        $num = count($data);
		
		if ($num == 1){
			// This is usually because of a blank line. Ignore it.
			continue;
		}
		else if ($num != 2){
			$fmt->p("Error on line $row: Found $num terms, expected 2.");
		} else {
			if (startsWith($data[0],"+++")){
				$myterms->setHeaders(substr($data[0],3),$data[1]);
				++$headers;
				$fmt->p("New column headers defined: Column 1: \"".$myterms->getHeader(1)."\" Column 2: \"".$myterms->getHeader(2)."\"");
			} else{
				$myterms->addTerm(new Term($data[0], iconv('UTF-8', 'ASCII//TRANSLIT', $data[1])));
			}
		}
        $row++;
    }
	setTerms($myterms);
	$fmt->writeRawData("Parsing has completed... Found <".strval($row-1-$headers)."> terms.<br />");

	$fmt->endDiv();
}

?>
