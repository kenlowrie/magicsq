<?php

include_once ('session.php');	// session handling code
include ('record.php');       // the forms for display/edit of a record

$alias = 'mkms.php';        // avoid hard-coding the script name
$type  = $_GET['type'];       // my edit type as a GET parameter

include('header1.inc');        // display our standard page layout

// If this page is invoked with no type= (first time or via the uploader), default it to EDIT mode...
if (!IsSet($type)){
	$type = EDIT_FORM;	
}

// The Reset Session Data button sets the type to RESET, which means we want to throw away our session
// data and pretend this is a new browser session... Do that, and then put the type back to EDIT mode...
if ($type == RESET){
	forceReset();			// clear out the applicable session data
	$type = EDIT_FORM;
}

// The Generate Sample Terms button sets the type to MAKE_TERMS, which means we want to generate some sample
// term data... Do that, and then put the type back to EDIT mode...
if ($type == MAKE_TERMS){
	genSampleTerms();		// create sample terms
	$type = EDIT_FORM;
}
// Generate Quiz Data (the SUBMIT button on the main form), invokes the page with this type. Extract the
// new values that the user entered, store them in the object, and then put the type back to EDIT mode...
// At some point, I need to invoke the code here that generates the OUTPUT, or make another PHP script for that,
// and put this code over there, then return back to here (or something)...
if ($type == EDIT_COMMIT){
	$quiz = getQuiz();
	$quiz->quizTitle = $_POST['title'];    // transfer the form variables into
	$quiz->magicSquareSize = $_POST['mssize'];
	$quiz->variants = $_POST['variants'];	
	$quiz->freeTerm = $_POST['freeterm'];
}

// This is the function that puts up the form so the user can enter data...
modify_record($alias, getQuiz(), EDIT_COMMIT);

include('footer1.inc');
?>
