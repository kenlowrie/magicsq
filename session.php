<?php

/*
 * Need to include the class definitions BEFORE I call session_start(), otherwise it doesn't know how to unserialize them.
 */
 
include_once ('cmagicsquare.php');
include_once ('cterms.php');
include_once ('cquizmaker.php');
include_once ('csymbol.php');

/*
** This file is included by all other PHP scripts. It fires the session_start(), and defines common things...
*/
session_start();

define(EDIT_FORM,1);             // type used to specify edit quiz form
define(EDIT_COMMIT,2);           // type used to specify commit changes to quiz object
define(RESET,3);             	// type used to specify a reset of the session data
define(MAKE_TERMS,4);			// type used to signal creation of default terms for testing
define(LOAD_FILE,5);				// type used to signal that we need to load the input file
define(PROCESS_DATA,6);			// type used to signal that we need to process the textarea

define(BASE_URL,"");

function forceReset(){
	$_SESSION['quiz'] = NULL;
	$_SESSION['terms'] = NULL;
}

function genSampleTerms($size=5){
	$myTerms = new Terms();
	$myTerms->loadTerms($size * $size);
	$myTerms->setFilename("Sample ".$size."x".$size." Terms.csv");
	$_SESSION['terms'] = $myTerms;
}

function getTerms(){
	return $_SESSION['terms'];
}

function setTerms($newterms){
	$_SESSION['terms'] = $newterms;
}

function getQuiz(){
	if (!IsSet($_SESSION['quiz'])){
		$_SESSION['quiz'] = new cQuizMaker;
	}
	return $_SESSION['quiz'];
}

function setQuiz($newquiz){
	$_SESSION['quiz'] = $newquiz;
}

?>
