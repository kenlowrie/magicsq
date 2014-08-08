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
	
	public function numVariants(){
		return count($this->jumbled);
	}

	public function setFilename($filename) {
		$this->filename = $filename;
	}
	public function getFilename() {
		return $this->filename;
	}
	
    public function randomizeTerms() {
        
		$copy = $this->terms;		// Make a copy of the current terms

        MyLog("RANDOMIZE: There are %d terms in the array", count($copy));
		MyPrint("Randomization in progress: ");
        for ($i = count($copy)-1; $i > 0; --$i) {
        		MyPrint(".");
            
            $j = rand(0,count($copy)) % $i;
            
            $tmpTerm = $copy[$i];
            $copy[$i] = $copy[$j];
            $copy[$j] = $tmpTerm;
        }
		MyLog(" completed.<br />\r\nRANDOMIZE: Saving jumbled copy<br />");
		$this->jumbled[] = $copy;		// Save this copy of the jumbled terms into the array
    }
    
    public function loadTerms($howMany) {

        for ($i = 0; $i < $howMany; ++$i) {

            $tmpTerm = new Term("Term$i", "Definition$i");
            // seems unnecessary to assign to tmpTerm...
            $this->terms[] = $tmpTerm;
        }
    }
	
	public function output($ms,$jumbleset=0){
		$N = $ms->getN();
		$lines = array();
		$count = 1;
		$letter = 65;
		$fmt = new cHTMLFormatter;
		
		$fmt->startDiv("termtable");
		$fmt->startTable();

		if( $this->numVariants() > 0){
			$js = $this->jumbled[$jumbleset];
			
			for ($X = 0; $X < $N; ++$X) {
				for ($Y = 0; $Y < $N; ++$Y) {
					$fmt->startRow();
					$item = $ms->getElement($X,$Y);
					$t1 = $js[$item-1]->getTerm();
					$d1 = $js[$count-1]->getDefinition();
	
					$fmt->writeClassData("tdterm", "%c. %s", $letter, $t1);
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
    
	public function dumpTermSet($which, $termSet){
		$count = 1;
		$letter = 65;
		$fmt = new cHTMLFormatter;
		
		$fmt->startDiv("termtable");
		$fmt->h3($which);
		$fmt->startTable();
		
		for ($X = 0; $X < count($termSet); ++$X) {
			$fmt->startRow();
			$t1 = $termSet[$X]->getTerm();
			$d1 = $termSet[$X]->getDefinition();

			$fmt->writeClassData("tdterm", "%c. %s", $letter, $t1);
			$fmt->writeClassData("tddef", "%d. %s", $count, $d1);
			++$count;
			++$letter;
			$fmt->endRow();
		}
		$fmt->endTable();
		$fmt->endDiv();
	}
	
	public function dumpTermObject(){
		$this->dumpTermSet("Primary Term Set", $this->terms);
		
		for ($X = 0; $X < $this->numVariants(); ++$X){
			$this->dumpTermSet("Jumbled Term Set #$X", $this->jumbled[$X]);
		}
	}

    public function dumpTerms() {
        for ($i = 0; $i < count($this->terms); ++$i) {

            MyLog("%s -> %s", $this->terms[$i]->getTerm(), $this->terms[$i]->getDefinition());
        }
    }
}

?>