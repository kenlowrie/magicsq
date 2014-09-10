<?php

function handleRegen($fmt,$quiz,$loadedTerms,$regenID,$objectID){
	
	$message = "";
	
	// This $fmt has to be in the right mode. printing the strings when jsonp invokes me...
		
	if ($regenID >= $loadedTerms->numVariants()){
			$message = $fmt->p("Internal error: regen element is out of variant range [$regenID]");	
	} else {
		switch ($objectID){
			case 1:
				$tmpSquare = new cMagicSquare($quiz->magicSquareSize);
				$tmpSquare->makeMagic();
				$quiz->magicSquares[$regenID] = $tmpSquare;	
				break;
			case 2:
				$message = $loadedTerms->randomizeTerms($fmt,$regenID);
				break;
			default:
				$message = $fmt->p("Internal error: regen mode with invalid object type $objectID");
				break;
		}
	}

	return $message;		// Nothing returned to add to the message stack...
}

function getNotes($fmt,$quiz,$loadedTerms,$which){
	$notes = $quiz->magicSquares[$which]->validate($fmt);
	list($alignedRow,$msg) = $loadedTerms->checkAlignment($quiz->magicSquares[$which],$which,$fmt);
	if ($alignedRow != -1 && $quiz->mapFTtoAlignedTD){
		$quiz->magicSquares[$which]->setAlignedRow($alignedRow);	// This will be used later
	}
	return $notes . $msg;
}


?>