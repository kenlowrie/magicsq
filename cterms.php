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
	
    
    public function addTerm($term){
        $this->terms[] = $term;
    }
	
	public function getTerms(){
		return $this->terms;
	}

	public function setFilename($filename) {
		$this->filename = $filename;
	}
	public function getFilename() {
		return $this->filename;
	}
	
    public function randomizeTerms() {
        
        //MyLog("There are %d terms in the array", count($this->terms));
        for ($i = count($this->terms)-1; $i > 0; --$i) {
            
            $j = rand(0,count($this->terms)) % $i;
            
            $tmpTerm = $this->terms[$i];
            $this->terms[$i] = $this->terms[$j];
            $this->terms[$j] = $tmpTerm;
        }
    }
    
    public function loadTerms($howMany) {

        for ($i = 0; $i < $howMany; ++$i) {

            $tmpTerm = new Term("Term$i", "Definition$i");
            // seems unnecessary to assign to tmpTerm...
            $this->terms[] = $tmpTerm;
        }
    }
	
	public function output($ms){
		$N = $ms->getN();
		$lines = array();
		$count = 1;
		$letter = 65;
		$fmt = new cHTMLFormatter;
		
		$fmt->startDiv("termtable");
		$fmt->startTable();
		
		for ($X = 0; $X < $N; ++$X) {
			for ($Y = 0; $Y < $N; ++$Y) {
				$fmt->startRow();
				$item = $ms->getElement($X,$Y);
				$t1 = $this->terms[$item-1]->getTerm();
				$d1 = $this->terms[$count-1]->getDefinition();

				$fmt->writeClassData("tdterm", "%c. %s", $letter, $t1);
				$fmt->writeClassData("tddef", "%d. %s", $count, $d1);
				//MyLog("%c. %s&nbsp;&nbsp; -- %d. %s", $letter, $t1, $count, $d1 );
				++$count;
				++$letter;
				$fmt->endRow();
			}
		}
		$fmt->endTable();
		$fmt->endDiv();
	}
    
    public function dumpTerms() {
        for ($i = 0; $i < count($this->terms); ++$i) {

            MyLog("%s -> %s", $this->terms[$i]->getTerm(), $this->terms[$i]->getDefinition());
        }
    }
}

?>