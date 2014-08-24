<?php

include_once ('session.php');
include ('header1.inc');
include_once ('utility.php');

//var_dump($_POST);

$fmt = new cHTMLFormatter;

$fmt->startHeader("mainHeader");
$fmt->h1("Generate Magic Squares");
$fmt->endHeader();

$fmt->startSection("makeQuiz");
$fmt->startDivClass("quizInfo");

$fmt->addLink("mkms.php","Click Me to Return to the Magic Square Maker Page");
$fmt->brk(2);

$quiz = getQuiz();

$regen = $_POST['regen'];       	// check to see if we are in regen mode
$object  = $_POST['object'];		// which object to regen

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
	$alignFT = $_POST['alignft'];
	$quiz->mapFTtoAlignedTD = ($alignFT == 1) ? true : false;
	$quiz->magicSquares = array();
	for($X = 0; $X < $quiz->variants; ++$X){
		$tmpSquare = new cMagicSquare($quiz->magicSquareSize);
		$tmpSquare->makeMagic();
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
			$tmpSquare = new cMagicSquare($quiz->magicSquareSize);
			$tmpSquare->makeMagic();
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

if (!isAppleDevice()){
	$fmt->p("Review the puzzles and jumbled term lists below, regenerate as needed, and then display or download each PDF by clicking the appropriate button.");
} else {
	$fmt->p("Review the puzzles and jumbled term lists below, and regenerate as needed. The current version of this app does not support downloading or displaying PDFs on iOS, so you'll need to run this program on a different computer in order to get the PDF output.");
}
$fmt->endDiv();

$optionCounter = 1;		
$divOptionOddEven = array("puzzleOdd","puzzleEven");

$fmt->startDivClass("puzzle");

for($X = 0; $X < $quiz->variants; ++$X){
	$fmt->startDivClass($divOptionOddEven[++$optionCounter % 2]);		// wrap with an odd or even puzzle class
	$fmt->startDivClass("puzzleInfo");		// wrap the puzzle info and buttons
	$fmt->h4("Magic Square Set #".strval($optionCounter-1));

	$sqType = $quiz->magicSquares[$X]->getSquareType();
	if (!isAppleDevice()){
		$fmt->linkbutton("makepdf.php", "Display PDF",null,"fancyButton puzzleButton",array("variant" => $X),"POST","submit","name",true);
		$fmt->linkbutton("makepdf.php", "Download PDF",null,"fancyButton puzzleButton",array("variant" => $X,"download" => 1),"POST","submit","name",true);
	}
	$fmt->linkbutton("makequiz.php","New square [$sqType]", null, "fancyButton puzzleButton", array("regen" => $X,"object" => 1));
	$fmt->linkbutton("makequiz.php","Jumble terms",null, "fancyButton puzzleButton", array("regen" => $X,"object" => 2));

	$fmt->startDivClass("squareInfo");
	$quiz->magicSquares[$X]->validate($fmt);
	$alignedRow = $loadedTerms->checkAlignment($quiz->magicSquares[$X],$X,$fmt);
	if ($alignedRow != -1 && $quiz->mapFTtoAlignedTD){
		$quiz->magicSquares[$X]->setAlignedRow($alignedRow);	// This will be used later
	}
	$fmt->endDiv(2);		// close div.statusArea and div.puzzleInfo

	$quiz->magicSquares[$X]->prettySquare($fmt);
	$loadedTerms->output($quiz->magicSquares[$X],$X,$fmt);
	$fmt->endDiv();		// close div.odd or div.even
}

$fmt->endDiv();	// close div.puzzle

$fmt->endSection();

include('footer1.inc');

?>