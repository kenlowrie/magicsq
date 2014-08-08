<?php

include_once ('session.php');
include ('header1.inc');
include_once ('utility.php');

$fmt = new cHTMLFormatter;

$fmt->startDiv("outertext");	// IS THIS OKAY?? SHOULD WE HAVE SPECIFIC VERSION?

MyLog("Display Terms and Definitions");

$fmt->hr();

$fmt->startDiv("outertext");
$loadedTerms = getTerms();

if ($loadedTerms->numVariants() == 0){
	$loadedTerms->randomizeTerms();
	$loadedTerms->randomizeTerms();	
}

MyLog("There are %d variants", $loadedTerms->numVariants());

$loadedTerms->dumpTermObject();

$fmt->endDiv();

$fmt->addLink("mkms.php","Click me to return to the Magic Square Maker Page");

?>