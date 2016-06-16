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
include_once ('datautil.php');

function encodeHTML($htmlStr){
	$mystr = trim(json_encode(htmlentities($htmlStr),JSON_HEX_APOS),'"');		//JSON_HEX_TAG doesn't seem to matter...
	//echo "[$mystr]\n\n";
	return $mystr;
//	return str_replace("\n","\\n",htmlspecialchars($htmlStr,ENT_QUOTES|ENT_HTML401,null,false));
}

header('Content-Type: application/json');

$fmt = new cHTMLFormatter;
$fmt->justPrint = false;		// Do not print anything, we need to encode it.

$variant = $_GET['variant'];
$item = $_GET['item'];
$quiz = getQuiz();
$loadedTerms = getTerms();

// go ahead and regen based on what is passed...
$notes = handleRegen($fmt, $quiz, $loadedTerms, $variant, $item);

// Now tack on the notes about this new data...
$notes .= getNotes($fmt, $quiz, $loadedTerms,$variant);

//echo "json_encode: " . json_encode($notes) . "<br /><br />\n\n";

$notes = encodeHTML($notes);
$newNotes = "'notes' : '" . $notes . "'";

if($item=='1') {
	$square = $quiz->magicSquares[$variant]->prettySquare($fmt);
	$newSquare = "'square' : '" . encodeHTML($square) . "'";
	$termSet = $loadedTerms->output($quiz->magicSquares[$variant],$variant,$fmt);
	$newTermSet = "'puzzle' : '" . encodeHTML($termSet) . "'";
    echo $_GET['callback'] . '({' . $newNotes . "," . $newSquare . "," . $newTermSet . '})';
} else if ($item == '2') {
	$termSet = $loadedTerms->output($quiz->magicSquares[$variant],$variant,$fmt);
	$newTermSet = "'puzzle' : '" . encodeHTML($termSet) . "'";
    echo $_GET['callback'] . '({' . $newNotes . "," . $newTermSet . '})';	
} else {
	echo $_GET['callback'] . '({' . "'notes' : 'Internal Error: Invalid State:(".$variant.",".$item.")'" . '})';		
}

?>