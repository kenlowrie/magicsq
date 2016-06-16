<?php
// This file is part of the Magic Square Quiz Maker (magicsq) application.
// Copyright (C) 2014-2016 Ken Lowrie
//
// magicsq is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// magicsq is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// See LICENSE.TXT for more information.   

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

define(TERMS_UNKNOWN,0);			// unknown terms
define(TERMS_AUTO,1);			// generated terms
define(TERMS_FILE,2);			// terms loaded from a file
define(TERMS_MANUAL,3);			// terms input manually

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
