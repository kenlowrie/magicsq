<?php

include_once('utility.php');

class Term {
    protected $lSide;	// to make these protected need to have getters and setters...
    protected $rSide;
	
    public function __construct($leftSide, $rightSide){
        $this->lSide = $leftSide;
        $this->rSide = $rightSide;
    }
    
	public function getTerm(){
		return $this->lSide;
	}
	
	public function getDefinition(){
		return $this->rSide;
	}
}

class Terms{
	protected $terms = array();
	protected $filename = "<>";
	protected $jumbled = array();
	protected $leftColumn = "Term";
	protected $rightColumn = "Definition";
	
    
    public function addTerm($term){
    		//MyLog("ADDTERM: %s : %s", $term->getTerm(), $term->getDefinition());
        $this->terms[] = $term;
    }
	
	public function getTerms(){
		return $this->terms;
	}

	public function getJumbledTerms(){
		return $this->jumbled;
	}
	
	public function resetJumbledTerms(){
		$this->jumbled = array();
	}
	
	public function setHeaders($left,$right){
		$this->leftColumn = $left;
		$this->rightColumn = $right;
	}
	
	public function getHeader($which=1){
		switch($which){
			default:
			case 1:
				return $this->leftColumn;
			case 2:
				return $this->rightColumn;
		}
	}

	public function numVariants(){
		return count($this->jumbled);
	}

	public function setFilename($filename) {
		$this->filename = $filename;
	}
	public function getFilename() {
		return $this->filename;
	}
	
	public function getBaseFilename(){
		$baseName = pathinfo($this->filename,PATHINFO_FILENAME);
		
		return IsSet($baseName) ? $baseName : "magic_square";
	}
	
    public function randomizeTerms($replaceSet=-1) {

		$copy = $this->terms;		// Make a copy of the current terms

		shuffle($copy);
		if( $replaceSet == -1){
			$this->jumbled[] = $copy;		// Save this copy of the jumbled terms into the array
		} elseif ($replace >= count($this->jumbled)){
			MyLog("randomizeTerms($replaceSet) called with invalid item number");
		} else {
			$this->jumbled[$replaceSet] = $copy;
		}		
	}
    
    public function loadTerms($howMany) {

        for ($i = 1; $i <= $howMany; ++$i) {

            $tmpTerm = new Term("Term$i", "Definition$i");
            // seems unnecessary to assign to tmpTerm...
            $this->terms[] = $tmpTerm;
        }
    }
		
	public function findItemInSquare($item,$ms){
		$N = $ms->getN();
		for ($X = 0; $X < $N; ++$X) {
			for ($Y = 0; $Y < $N; ++$Y) {
				if($ms->getElement($X,$Y) == $item){
					return ($X * $N) + $Y;
				}
			}
		}
		return -1;
	}

	public function checkAlignment($ms,$jumbleset=0,$fmt=NULL){
		$N = $ms->getN();
		$count = 1;
		$letter = new cSymbol;
		$alignedRow = -1;
		if($fmt == NULL){
			$fmt = new cHTMLFormatter;		
		}
		if( $this->numVariants() > 0){
			$js = $this->jumbled[$jumbleset];
			
			for ($X = 0; $X < $N; ++$X) {
				for ($Y = 0; $Y < $N; ++$Y) {

					$location = $this->findItemInSquare($count, $ms);
					if ($location != -1 && $letter->me() == $letter->getSymbol($location)){
						$msg = sprintf("NOTE: %s and %s on row [%s] are aligned",$this->getHeader(1),$this->getHeader(2),$letter->me());
						$fmt->write($msg,true,true);
						$alignedRow = $location + 1;		// Bump the row so it is 1-based because that's what the lookup expects later...
					}	
					++$count;
					$letter->increment();
				}
			}
		} else {
			MyLog("output called with no variants defined...");
		}
		
		return $alignedRow;
	}
	
	public function output($ms,$jumbleset=0,$fmt=NULL){
		$N = $ms->getN();
		$count = 1;
		$letter = new cSymbol;
		if($fmt == NULL){
			$fmt = new cHTMLFormatter;		
		}
		
		$fmt->startDivClass("puzzleTerms puzzleTermsSize".strval($N));
		$fmt->startTable();

		if( $this->numVariants() > 0){
			$js = $this->jumbled[$jumbleset];
			
			$fmt->startRow();
			$fmt->writeCellData($this->getHeader(1));
			$fmt->writeCellData("Correct Answer");
			$fmt->writeCellData($this->getHeader(2));
			$fmt->endRow();
			for ($X = 0; $X < $N; ++$X) {
				for ($Y = 0; $Y < $N; ++$Y) {
					$fmt->startRow();
					$item = $ms->getElement($X,$Y);
					$t1 = $js[$item-1]->getTerm();
					$d1 = $js[$count-1]->getDefinition();
	
					$fmt->writeCellData("%s. %s", $letter->me(), $t1);
					$location = $this->findItemInSquare($count, $ms);
					if ($location != -1){
						$fmt->writeClassData("answer", "%s", $letter->getSymbol($location));
					} else {
						$fmt->writeClassData("answer", "?");
					}
					$fmt->writeCellData("%d. %s", $count, $d1);
					++$count;
					$letter->increment();
					$fmt->endRow();
				}
			}
		} else {
			MyLog("output called with no variants defined...");
		}
		$fmt->endTable();
		$fmt->endDiv();
	}
    
	public function dumpTermSet($which, $termSet, $fmt = NULL){
		$count = 1;
		$letter = new cSymbol;
		if($fmt == NULL){
			$fmt = new cHTMLFormatter;		
		}
		
		$output = $fmt->startDiv("termtable");
		$output .= $fmt->h3($which);
		$output .= $fmt->startTable();
		
		for ($X = 0; $X < count($termSet); ++$X) {
			$output .= $fmt->startRow();
			$t1 = $termSet[$X]->getTerm();
			$d1 = $termSet[$X]->getDefinition();

			$output .= $fmt->writeClassData("tdterm", "%s. %s", $letter->me(), $t1);
			$output .= $fmt->writeClassData("tddef", "%d. %s", $count, $d1);
			++$count;
			$letter->increment();
			$output .= $fmt->endRow();
		}
		if ($X == 0) {
			$output .= $fmt->p("No terms in term set");
		}
		$output .= $fmt->endTable();
		$output .= $fmt->endDiv();
		
		return $output;
	}
	
	public function dumpTermObject(){
		$this->dumpTermSet("Primary Term Set", $this->terms);
		
		for ($X = 0; $X < $this->numVariants(); ++$X){
			$this->dumpTermSet("Jumbled Term Set #$X", $this->jumbled[$X]);
		}
	}
	
	public function getFreeTerm($freeTerm, $termSet){
		if ($freeTerm != 0){	
			if (($freeTerm-1) < count($termSet)){
				return $this->terms[$freeTerm-1]->getTerm();	// Terms are zero based internally...
			}
		}
		return "";
	}
	
	public function mapFreeTermToRow($ftstr,$termSet){
		for ($index = 0; $index < count($termSet); ++$index){
			if ($ftstr == $termSet[$index]->getTerm()){
				return $index+1;		// Since terms are zero based, but we will lookup in square using one based...
			}		
		}
		return -1;
	}
	
	public function printTermSet($ms, $freeTerm, $alignFT, $termSet, $fmt = NULL){
		$N = $ms->getN();
		$count = 1;
		$letter = new cSymbol;
		if($fmt == NULL){
			$fmt = new cHTMLFormatter;		
		}
				
		if ($freeTerm == 0) {
			// if freeTerm is zero, that has precedent...
			$ftrow = -1;		// everything below will just ignore the free term...
			$ftsid = -1;
		} else {
			// If the user wants to set the free term to the aligned term/definition ...
			if ($alignFT){
				$ftrow = $ms->getAlignedRow();
			} else {
				// Okay, we need to find the free term in the jumbled set
				$ftstr = $this->getFreeTerm($freeTerm,$termSet);
				$ftrow = $this->mapFreeTermToRow($ftstr,$termSet);		
			}
			// now find the free term in the magic square
			$ftsid = $this->findItemInSquare($ftrow, $ms);
		}
		
		$output = $fmt->startDiv("ptermtable");
		$output .= $fmt->startTable();

		//$output .= $fmt->startTHead();
		$output .= $fmt->startRow();
		$output .= $fmt->writeClassData("ptdhterm", $this->getHeader(1));
		$output .= $fmt->writeClassData("ptdhansr", "Correct Answer");
		$output .= $fmt->writeClassData("ptdhdefi", $this->getHeader(2));
		$output .= $fmt->endRow();
		//$output .= $fmt->endTHead();
		$output .= $fmt->startTBody();

		for ($X = 0; $X < $N; ++$X) {
			for ($Y = 0; $Y < $N; ++$Y) {
				$output .= $fmt->startRow();

				$item = $ms->getElement($X,$Y);
				$t1 = $termSet[$item-1]->getTerm();
				$d1 = $termSet[$count-1]->getDefinition();


				$output .= $fmt->writeClassData("ptdterm", "%s. %s", $letter->me(), $t1);
				if($count == $ftrow){
					// If the lookup failed, this will print '@' ... Should be obvious... :)
					$output .= $fmt->writeClassData("ptdanswer", "%s", $letter->getSymbol($ftsid));
					// remember this for later...
					$ms->setFreeTermSolution($letter->getSymbol($ftsid),$count);
				} else {
					$output .= $fmt->writeClassData("ptdanswer", "%s", "&nbsp;");									
				}
				$output .= $fmt->writeClassData("ptddef", "%d. %s", $count, $d1);
				++$count;
				$letter->increment();
				$output .= $fmt->endRow();		
			}
		}
		$output .= $fmt->endTBody();
		$output .= $fmt->endTable();
		$output .= $fmt->endDiv();
		
		return $output;
    }
}

?>