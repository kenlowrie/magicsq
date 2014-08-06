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
	
    
    public function addTerm($term){
        $this->terms[] = $term;
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
		
		for ($X = 0; $X < $N; ++$X) {
			for ($Y = 0; $Y < $N; ++$Y) {
				$item = $ms->getElement($X,$Y);
				$t1 = $this->terms[$item-1]->getTerm();
				$d1 = $this->terms[$count-1]->getDefinition();
				//$lines[] = "x. $t1 -- $count. $d1";
				MyLog("%c. %s&nbsp;&nbsp; -- %d. %s", $letter, $t1, $count, $d1 );		//$lines[$count]);
				++$count;
				++$letter;
			}
		}
	}
    
    public function dumpTerms() {
        for ($i = 0; $i < count($this->terms); ++$i) {

            MyLog("%s -> %s", $this->terms[$i]->getTerm(), $this->terms[$i]->getDefinition());
        }
    }
}

?>