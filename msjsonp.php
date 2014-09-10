<?php

include_once ('session.php');	// session handling code
include_once ('datautil.php');

function encodeHTML($htmlStr){
	return trim(json_encode($htmlStr),'"');
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