<?php

include_once ('session.php');	// session handling code
include ('record.php');       // the forms for display/edit of a record

$alias = 'mkms.php';        // avoid hard-coding the script name
$type  = $_GET['type'];       // my edit type as a GET parameter
$fmt = new cHTMLFormatter;

include('header1.inc');        // display our standard page layout

$fmt->startHeader("mainHeader");
$fmt->h1("Magic Square Quiz Maker");
$fmt->endHeader();

// If this page is invoked with no type= (first time or via the uploader), default it to EDIT mode...
if (!IsSet($type)){
	$type = EDIT_FORM;	
}

// This page is invoked with type=EDIT_FORM, RESET, MAKE_TERMS, EDIT_COMMIT
switch ($type){
	case LOAD_FILE:
		// The Load File type is used to load up the input file.
		uploadinputfile($fmt);
		$type = EDIT_FORM;
		break;
		
	case RESET:
		// The Reset Session Data button sets the type to RESET, which means we want to throw away our session
		// data and pretend this is a new browser session... Do that, and then put the type back to EDIT mode...
		forceReset();			// clear out the applicable session data
		$type = EDIT_FORM;
		break;
		
	case MAKE_TERMS:
		// The Generate Sample Terms button sets the type to MAKE_TERMS, which means we want to generate some sample
		// term data... Do that, and then put the type back to EDIT mode...
		$size  = $_GET['size'];  // get the size of the square
		
		if( !IsSet($size) || !in_array($size, array(3,5,7)) ){
			$size = 5;
		} 
		genSampleTerms($size);		// create sample terms
		$type = EDIT_FORM;
		break;
				
	case EDIT_FORM:
		break;		// This case is handled below, it's the same for all types...
		
	default:
		MyLog("$alias: Unexpected type passed: $type");
		break;
}

// This function displays the main form so the user can enter data...
display_quiz_form($fmt, $alias, getQuiz(), EDIT_COMMIT);

include('footer1.inc');
?>
