<?php

include_once('utility.php');

class Term {
    public $lSide;	// to make these protected need to have getters and setters...
    public $rSide;
	
    public function __construct($leftSide, $rightSide){
        $this->lSide = $leftSide;
        $this->rSide = $rightSide;
    }
    
}

class Terms{
	protected $terms = array();
	
    
    public function addTerm($term){
        $this->terms[] = $term;
    }

    public function randomizeTerms() {
        
        MyLog("There are %d terms in the array", count($this->terms));
        for ($i = count($this->terms)-1; $i > 0; --$i) {
            
            $j = rand(0,count($this->terms)) % $i;
			// arc4random_uniform(UInt32(terms.count))) % i
            
            $tmpTerm = $this->terms[$i];
            $this->terms[$i] = $this->terms[$j];
            $this->terms[$j] = $tmpTerm;
        }
    }
    
    public function loadTerms($howMany) {

        for ($i = 0; $i < $howMany; ++$i) {
		// in 1...howMany{
            $tmpTerm = new Term("Term$i", "Definition$i");
            // seems unnecessary to assign to tmpTerm...
            $this->terms[] = $tmpTerm;
        }
    }
    
    public function dumpTerms() {
        for ($i = 0; $i < count($this->terms); ++$i) {
        	//in 0..<terms.count {
            MyLog("%s -> %s", $this->terms[$i]->lSide, $this->terms[$i]->rSide);
        }
    }
}

?>