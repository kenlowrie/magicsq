<?php

/*
 * Need to include the class definitions BEFORE I call session_start(), otherwise it doesn't know how to unserialize them.
 */
 
include_once ('cmagicsquare.php');
include_once ('cterms.php');
include_once ('cquizmaker.php');

/*
** This file is included by all other PHP scripts. It fires the session_start(), and defines common things...
*/
session_start();

// define(ROWS_TO_DISPLAY,50);        // how many rows are displayed at one time
// define(VIDS_TO_DISPLAY,100);       // how many videos are displayed at one time
define(EDIT_FORM,1);             // type used to specify edit quiz form
define(EDIT_COMMIT,2);           // type used to specify commit changes to quiz object
define(RESET,3);             	// type used to specify a reset of the session data
define(MAKE_TERMS,4);			// type used to signal creation of default terms for testing
// define(DELETE_FORM,3);             // type used to specify display a delete form
// define(DELETE_COMMIT,4);           // type used to specify commit a delete record     
// define(ADD_FORM,5);                // type used to specify display an add form
// define(ADD_COMMIT,6);              // type used to specify commit an add record
// define(ADD_FORCE,7);               // type used to specify commit a duplicate add record

define(BASE_URL,"");

function forceReset(){
	$_SESSION['quiz'] = NULL;
	$_SESSION['terms'] = NULL;
}

function genSampleTerms(){
	$myTerms = new Terms();
	$myTerms->loadTerms(25);
	$myTerms->setFilename("Sample Terms for Testing");
	$_SESSION['terms'] = $myTerms;
}

function getTerms(){
	return $_SESSION['terms'];
}

function setTerms($newterms){
	//MyLog("Setting new terms object %s:[%d]", $newterms->getFilename(), count($newterms-getTerms()));
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
// $logged_in = $_SESSION['logged_in'];	// This is my session variable that tells if I have logged in
// 
// if (!IsSet($logged_in)){
    // echo "You must be logged in to use this application";
	// exit();
// }

/*
** A function that will tell you whether or not the currently logged in user is an administrator.
*/
// function is_admin()
// {
	// $isadmin = $_SESSION['isadmin'];
	// if (IsSet($isadmin)){
		// return 1;
	// }
	// return 0;
// }
// 
// function is_guest()
// {
	// $isguest = $_SESSION['isguest'];
	// if (IsSet($isguest)){
		// return 1;
	// }
	// return 0;
// }
// 
// require_once '../functions.php';
// require_once '../sqlfuncs.php';
?>
