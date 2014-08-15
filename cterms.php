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
	
	public function numVariants(){
		return count($this->jumbled);
	}

	public function setFilename($filename) {
		$this->filename = $filename;
	}
	public function getFilename() {
		return $this->filename;
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
		$letter = 65;
		if($fmt == NULL){
			$fmt = new cHTMLFormatter;		
		}
		$fmt->startDiv("statusarea");
		if( $this->numVariants() > 0){
			$js = $this->jumbled[$jumbleset];
			
			for ($X = 0; $X < $N; ++$X) {
				for ($Y = 0; $Y < $N; ++$Y) {

					$location = $this->findItemInSquare($count, $ms);
					if ($location != -1 && $letter == ($location+65)){
						$msg = sprintf("NOTE: Term and Definition on row [%c] are aligned",$location+65);
						$fmt->write($msg,true,true);
					}	
					++$count;
					++$letter;
				}
			}
		} else {
			MyLog("output called with no variants defined...");
		}
		$fmt->endDiv();
	}
	
	public function output($ms,$jumbleset=0,$fmt=NULL){
		$N = $ms->getN();
		$count = 1;
		$letter = 65;
		if($fmt == NULL){
			$fmt = new cHTMLFormatter;		
		}
		
		$fmt->startDiv("termtable");
		$fmt->startTable();

		if( $this->numVariants() > 0){
			$js = $this->jumbled[$jumbleset];
			
			$fmt->startRow();
			$fmt->writeClassData("tdhterm", "Word:");
			$fmt->writeClassData("tdhansr", "Correct Answer:");
			$fmt->writeClassData("tdhdefi", "Definition/Information:");
			$fmt->endRow();
			for ($X = 0; $X < $N; ++$X) {
				for ($Y = 0; $Y < $N; ++$Y) {
					$fmt->startRow();
					$item = $ms->getElement($X,$Y);
					$t1 = $js[$item-1]->getTerm();
					$d1 = $js[$count-1]->getDefinition();
	
					$fmt->writeClassData("tdterm", "%c. %s", $letter, $t1);
					$location = $this->findItemInSquare($count, $ms);
					if ($location != -1){
						$fmt->writeClassData("tdanswer", "%c", $location + 65);
					} else {
						$fmt->writeClassData("tdanswer", "?");
					}
					$fmt->writeClassData("tddef", "%d. %s", $count, $d1);
					++$count;
					++$letter;
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
		$letter = 65;
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

			$output .= $fmt->writeClassData("tdterm", "%c. %s", $letter, $t1);
			$output .= $fmt->writeClassData("tddef", "%d. %s", $count, $d1);
			++$count;
			++$letter;
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
	
	public function printTermSet($ms, $freeTerm, $termSet, $fmt = NULL){
		$N = $ms->getN();
		$count = 1;
		$letter = 65;
		if($fmt == NULL){
			$fmt = new cHTMLFormatter;		
		}
		
		$ftstr = $this->getFreeTerm($freeTerm,$termSet);
		
		$ftrow = $this->mapFreeTermToRow($ftstr,$termSet);
		$ftsid = $this->findItemInSquare($ftrow, $ms);
		
		$output = $fmt->startDiv("ptermtable");
		$output .= $fmt->startTable();

		$fmt->startRow();
		$fmt->writeClassData("ptdhterm", "Word:");
		$fmt->writeClassData("ptdhansr", "Correct Answer:");
		$fmt->writeClassData("ptdhdefi", "Definition/Information:");
		$fmt->endRow();

		for ($X = 0; $X < $N; ++$X) {
			for ($Y = 0; $Y < $N; ++$Y) {
				$output .= $fmt->startRow();

				$item = $ms->getElement($X,$Y);
				$t1 = $termSet[$item-1]->getTerm();
				$d1 = $termSet[$count-1]->getDefinition();


				$output .= $fmt->writeClassData("ptdterm", "%c. %s", $letter, $t1);
				if($count == $ftrow){
					// If the lookup failed, this will print '@' ... Should be obvious... :)
					$output .= $fmt->writeClassData("ptdanswer", "%c", $ftsid+65);
					// remember this for later...
					$ms->setFreeTermSolution($ftsid+65,$count);
				} else {
					$output .= $fmt->writeClassData("ptdanswer", "%s", "&nbsp;");									
				}
				$output .= $fmt->writeClassData("ptddef", "%d. %s", $count, $d1);
				++$count;
				++$letter;
				$output .= $fmt->endRow();		
			}
		}
		$output .= $fmt->endTable();
		$output .= $fmt->endDiv();
		
		return $output;
    }
}

?>