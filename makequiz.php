<?php

include_once ('session.php');
include ('header1.inc');
include_once ('utility.php');

$fmt = new cHTMLFormatter;

$fmt->startDiv("outertext");

MyLog("Generate Quiz Data");

$fmt->hr();

// $fmt->addLink("makepdf.php","Click me to generate the output PDF - after reviewing everything below",true);
// $fmt->brk();
$fmt->addLink("mkms.php","Click me to return to the Magic Square Maker Page");
$fmt->brk();

$fmt->startDiv("outertext");

$quiz = getQuiz();

$regen = $_GET['regen'];       	// check to see if we are in regen mode
$object  = $_GET['object'];		// which object to regen

$loadedTerms = getTerms();
$numvariants = $loadedTerms->numVariants();

if (!IsSet($regen)){
	// In this case, we are coming in on a POST (should I verify that??)
	// Generate Quiz Data (the SUBMIT button on the main form), invokes this page, so we need to Extract the
	// new values that the user entered, store them in the object, so that we remember them until they finish
	// or do a RESET later...
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
	
	$loadedTerms->resetJumbledTerms();				// Throw out what we had, if any
	for($X = 0; $X < $quiz->variants; ++$X){
		$loadedTerms->randomizeTerms();
	}
} elseif ($regen >= $numvariants){
		$fmt->p("Internal error: regen element is out of variant range [$regen]");	
} else{
	switch ($object){
		case 1:
			$tmpSquare = new cMagicSquare;
			$tmpSquare->makeMagicAlgoUPLEFT(rand(0,4), rand(0,4));
			$quiz->magicSquares[$regen] = $tmpSquare;	
			break;
		case 2:
			$loadedTerms->randomizeTerms($regen);
			break;
		default:
			$fmt->p("Internal error: regen mode with invalid object type $object");
			break;
	}
}

//MyLog("There %s %d variant%s", $numvariants == 1 ? "is" : "are", $numvariants, $numvariants == 1 ? "" : "s");
$fmt->startDiv("statusarea");
$fmt->p("Your generated output is below.");
$fmt->p("Review the puzzles and jumbled term lists, regenerate as needed, and then generate the PDF by clicking the appropriate button for each set.");
$fmt->endDiv();

for($X = 0; $X < $quiz->variants; ++$X){
	$fmt->linkbutton("makepdf.php?variant=$X", "Generate PDF of variant " . strval($X+1),null,"POST","submit","name",true);
	$fmt->linkbutton("makequiz.php?regen=$X&object=1","Regen this square");
	$fmt->linkbutton("makequiz.php?regen=$X&object=2","Jumble this set of terms again");
	$fmt->startDiv("makequiz");
	$quiz->magicSquares[$X]->prettySquare($fmt);
	$fmt->startDiv("right");
	$loadedTerms->output($quiz->magicSquares[$X],$X);
	$fmt->brk();
	$fmt->endDiv(2);
}

// 
// 
// $loadedTerms->dumpTermObject();
// 
$fmt->endDiv();

$fmt->endDiv();

?>