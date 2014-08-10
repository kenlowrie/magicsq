<?php

include_once ('session.php');
include ('header1.inc');
include_once ('utility.php');

$fmt = new cHTMLFormatter;

$fmt->startDiv("outertext");

$fmt->h2("Display Terms and Definitions");

$fmt->hr();

$fmt->startDiv("outertext");
$loadedTerms = getTerms();

if (IsSet($loadedTerms)){
		
	if ($loadedTerms->numVariants() == 0){
		$fmt->writeRawData("There are currently no jumbled sets defined for the terms.");
	} else {
		$fmt->writeRawData("There are %d variants", $loadedTerms->numVariants());	
	}
	
	$loadedTerms->dumpTermObject();
} else {
	$fmt->p("There are no terms currently loaded...");
}

$fmt->endDiv();

$fmt->addLink("mkms.php","Click me to return to the Magic Square Maker Page");

$fmt->endDiv();

?>