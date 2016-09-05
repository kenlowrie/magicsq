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