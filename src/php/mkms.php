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

include_once ('session.php');	// session handling code
include ('record.php');       // the forms for display/edit of a record

$alias = 'mkms.php';        // avoid hard-coding the script name
$type  = $_POST['type'];       // my edit type as a POST parameter
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
		$size  = $_POST['size'];  // get the size of the square
		
		if( !IsSet($size) || !in_array($size, array(3,5,7)) ){
			$size = 5;
		} 
		genSampleTerms($size);		// create sample terms
		$type = EDIT_FORM;
		break;
				
	case PROCESS_DATA:
		// The user entered the data manually from the form.
		getQuiz()->textArea = trim($_POST['textarea']);
		$splitTerms = explode("\n", getQuiz()->textArea);
		parseTerms($fmt,$splitTerms);
		$type = EDIT_FORM;
		break;
				
	case EDIT_FORM:
		break;		// This case is handled below, it's the same for all types...
		
	default:
		$fmt->write("$alias: Unexpected type passed: $type");
		break;
}

// This function displays the main form so the user can enter data...
display_quiz_form($fmt, $alias, getQuiz(), EDIT_COMMIT);

addJS($fmt,"mkms");
include('footer1.inc');
?>
