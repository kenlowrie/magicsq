<?php

include_once ('session.php');
include ('header1.inc');
include_once ('utility.php');
include_once ('datautil.php');

//var_dump($_POST);

$fmt = new cHTMLFormatter;

$fmt->startHeader("mainHeader");
$fmt->h1("Generate Magic Squares");
$fmt->endHeader();

$fmt->startSection("makeQuiz");
$fmt->startDivClass("quizInfo");

addNeedJavaScript($fmt);
$fmt->addLink("mkms.php","Click Me to Return to the Magic Square Maker Page");
$fmt->brk(2);

$quiz = getQuiz();

$regen = $_POST['regen'];       	// check to see if we are in regen mode
$object  = $_POST['object'];		// which object to regen

//TODO: This may be temporary, after implementing json properly...
if( !IsSet($regen)){
	$regen=$_GET['regen'];
	$object=$_GET['object'];
}

$loadedTerms = getTerms();

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
		$loadedTerms->randomizeTerms($fmt);
	}
} else {
	handleRegen($fmt, $quiz, $loadedTerms, $regen, $object);			// This will do the regen in a jsonp safe way...
}

$fmt->p("Review the puzzles and jumbled term lists below, regenerate as needed, and then display or download each PDF by clicking the appropriate button.");
if($quiz->variants > 1){
	$fmt->startP();
	$fmt->write("<br /><a onClick=\"disableAccordion()\" title=\"Click to show all the puzzle sets\" class=\"fancyButton puzzleButton\">\n");
	$fmt->write(" Show All ",false,false);
	$fmt->write("</a>&nbsp;<a onClick=\"enableAccordion(false)\" title=\"Click to collapse all the puzzle sets\" class=\"fancyButton puzzleButton\">",false,false);
	$fmt->write(" Collapse ");
	$fmt->write("</a>");
	$fmt->endP();
}
$fmt->endDiv();

$optionCounter = 1;		
$divOptionOddEven = array("quizPuzzleOdd","quizPuzzleEven");

$fmt->startDivClass("quizPuzzle");

for($X = 0; $X < $quiz->variants; ++$X){
	$puzzleVariant = "puzzleVariant".$optionCounter;
	$pvID = $puzzleVariant."ID";
	$fmt->h3("Magic Square Set #".strval($optionCounter-1));
	$fmt->startDiv($pvID);								// give myself a unique ID so I can find this section with JavaScript
	$fmt->startDivClass($divOptionOddEven[++$optionCounter % 2]);		// wrap with an odd or even puzzle class
	$fmt->startDivClass("puzzleInfo");		// wrap the puzzle info and buttons
	$fmt->anchor($puzzleVariant);
	//$fmt->h4("Magic Square Set #".strval($optionCounter-1));

	$sqType = $quiz->magicSquares[$X]->getSquareType();
	$fmt->linkbutton("makepdf.php", "Display PDF",null,"fancyButton puzzleButton",array("variant" => $X),"POST","submit","name",true);
	$fmt->linkbutton("makepdf.php", "Download PDF",null,"fancyButton puzzleButton",array("variant" => $X,"download" => 1),"POST","submit","name",true);
	$fmt->startDivClass("hideMe");
	$fmt->linkbutton("makequiz.php#$puzzleVariant","New square [$sqType]", null, "fancyButton puzzleButton", array("regen" => $X,"object" => 1));
	$fmt->linkbutton("makequiz.php#$puzzleVariant","Jumble terms",null, "fancyButton puzzleButton", array("regen" => $X,"object" => 2));
	$fmt->endDiv();

	$fmt->startDivClass("magicSquareNotes");
	getNotes($fmt, $quiz, $loadedTerms,$X);		// This will write the messages to the $fmt object if print is ON
	$fmt->endDiv(2);		// close div.statusArea and div.puzzleInfo

	$fmt->write("<a onClick=\"regenPuzzleObject($X,1,'#$pvID')\" title=\"Click me to generate a new square\">\n");
	$fmt->startDivClass("puzzleSquare");
	$quiz->magicSquares[$X]->prettySquare($fmt);
	$fmt->endDiv();
	$fmt->write("</a>");
	$fmt->write("<a onClick=\"regenPuzzleObject($X,2,'#$pvID')\" title=\"Click me to jumble this term set\">\n");
	$fmt->startDivClass("puzzleTerms");
	$loadedTerms->output($quiz->magicSquares[$X],$X,$fmt);
	$fmt->endDiv();
	$fmt->write("</a>");
	
	$fmt->endDiv();		// close div.odd or div.even
	$fmt->endDiv();		// close puzzleVariant#ID
}

$fmt->endDiv();	// close div.puzzle
$fmt->endSection();

addJSspinner($fmt);
addJS($fmt,"makequiz");
include('footer1.inc');

?>