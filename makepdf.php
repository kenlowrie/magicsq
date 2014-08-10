<?php

include_once ('session.php');
include ('header1.inc');
include_once ('utility.php');

$fmt = new cHTMLFormatter;

$fmt->startDiv("outertext");

$quiz = getQuiz();
$loadedTerms = getTerms();

if (!IsSet($loadedTerms)){
	$fmt->p("You need to load a terms file or generate sample terms first...");
} else {
	
	$fmt->h2("Create PDF output");
	
	$fmt->hr();
	
	$fmt->startDiv("outertext");
	
	$fmt->p("This is where the generate PDF code will live. When this is completed, it will:");

	$fmt->writeRawData("1. Generate a PDF with %d version%s of the terms and definitions and the blank magic square.", $quiz->variants, $quiz->variants == 1 ? "" : "s");
	$fmt->brk();
	$fmt->write("2. Generate a PDF that has a key for each variant of the output.");
	$fmt->brk();
	$fmt->brk();
		
	$fmt->endDiv();
}

$fmt->addLink("mkms.php","Click me to return to the Magic Square Maker Page");

$fmt->endDiv();


?>