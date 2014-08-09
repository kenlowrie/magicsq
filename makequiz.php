<?php

include_once ('session.php');
include ('header1.inc');
include_once ('utility.php');

$fmt = new cHTMLFormatter;

$fmt->startDiv("outertext");	// IS THIS OKAY?? SHOULD WE HAVE SPECIFIC VERSION?

MyLog("Generate Quiz Data");

$fmt->hr();

$fmt->startDiv("outertext");

// Generate Quiz Data (the SUBMIT button on the main form), invokes this page, so we need to Extract the
// new values that the user entered, store them in the object, so that we remember them until they finish
// or do a RESET later...
$quiz = getQuiz();
$quiz->quizTitle = $_POST['title'];    // transfer the form variables into
$quiz->variants = $_POST['variants'];	
$quiz->freeTerm = $_POST['freeterm'];

$quiz->magicSquares = array();
for($X = 0; $X < $quiz->variants; ++$X){
	//TODO: Make this better, randomize the square type...
	$tmpSquare = new cMagicSquare;
	$tmpSquare->makeMagicAlgoUPLEFT(rand(0,4), rand(0,4));
	$quiz->magicSquares[] = $tmpSquare;	
}

$loadedTerms = getTerms();

// Should we always throw away what we have and redo with the current number of variants?

if ($loadedTerms->numVariants() == 0){
	for($X = 0; $X < $quiz->variants; ++$X){
		$loadedTerms->randomizeTerms();
	}
}

MyLog("There are %d variants", $loadedTerms->numVariants());

for($X = 0; $X < $quiz->variants; ++$X){
	$fmt->startDiv("outertext");				//TODO: Need a custom div type here
	MyLog("Variation: %d",$X+1);
	$quiz->magicSquares[$X]->prettySquare($fmt);
	$loadedTerms->output($quiz->magicSquares[$X],$X);
	$fmt->endDiv();
}

// 
// 
// $loadedTerms->dumpTermObject();
// 
$fmt->endDiv();

$fmt->addLink("mkms.php","Click me to return to the Magic Square Maker Page");

?>