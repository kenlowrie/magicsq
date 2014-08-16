<?php

include 'formatters.php';

function isValidSquareSize($square){
	$validSizes = array(3.0, 5.0, 7.0, 9.0);
	
	return in_array($square, $validSizes);
}

class cMagicSquare{
	protected $N;
	protected $sqType;						// which algorithm made it
	protected $magicSquare = array();
	protected $freeTermLetter = '@';			// means No Free Term Solution has been set
	protected $freeTermSolution = 0;
	protected $alignedRow = -1;				// set during validation of square
	
	public function __construct() { 
        $a = func_get_args(); 
        $i = func_num_args(); 
		if ($i == 0) {
	    	$this->N = 5;
    	    $this->initMagicSquare();
			return;
		}
        if (method_exists($this,$f='__construct'.$i)) { 
            call_user_func_array(array($this,$f),$a); 
			return;
        } 
		MyLog("Constructor called with invalid parameters.");
		die();
    } 
    
    function __construct1($newN) { 
        //echo('__construct with 1 param called: '.$a1.PHP_EOL); 
        $this->N = $newN;
		$this->initMagicSquare();
    } 
    
    
    public function initMagicSquare(){
    		$row = array();
        //var row: [Int] = []
        for ($i = 0; $i < $this->N; ++$i) {
            $row[$i] = 0;
        }
        
        for ($i = 0; $i < $this->N; ++$i ) {
            $this->magicSquare[$i] = $row;
        }
    }

	public function getN() {
		return $this->N;
	}
	
	public function getSquareType(){
		return $this->sqType;
	}

	public function getMagicSum(){
		// Compute the magic sum        
        return ((($this->N*$this->N*$this->N)+$this->N)/2);
	}
	public function getElement($row, $col) {
		if ($row < $this->N && $col < $this->N) {
			return $this->magicSquare[$row][$col];
		}
		return -1;
	}
    
	public function setFreeTermSolution($letter, $solution) {
		$this->freeTermLetter = $letter;
		$this->freeTermSolution = $solution;
	}

	public function hasFreeTermSolution($letter) {
		return $this->freeTermLetter == $letter;
	}

	public function getFreeTermSolution($letter) {
		if ($this->freeTermLetter == $letter){
			return $this->freeTermSolution;
		}
		return -1;
	}

	public function setAlignedRow($alignedRow){
		$this->alignedRow = $alignedRow;
	}
	
	public function getAlignedRow(){
		return $this->alignedRow;
	}
	
    public function makeMagicAlgoUPLEFT($startRow, $startCol){
    		$this->sqType = "UL";
        $magicNumber = 1;
        $row = $startRow;
        $col = $startCol;
        
        for ($A = 0; $A < ($this->N*$this->N); ++$A ) {
            $this->magicSquare[$row][$col]=$magicNumber++;
      
	  		// This is where we will go if the new space is occupied
	  		// NEXT Row, SAME Column
	  		$isOccupiedRow = $row+1 == $this->N ? 0 : $row+1;
            $isOccupiedCol = $col;
			      
			$row = $row == 0 ? $this->N-1 : $row - 1;	// MOVE UP, WRAP AT TOP
			$col = $col == 0 ? $this->N-1 : $col - 1;	// MOVE LEFT, WRAP AT START
			
			// Check to see if the new position is occupado
            if ($this->magicSquare[$row][$col] != 0) {

				$row = $isOccupiedRow;
				$col = $isOccupiedCol;
            }
        }
    }
        
    public function makeMagicAlgoUPRIGHT($startRow, $startCol){
    		$this->sqType = "UR";
        $magicNumber = 1;
        $row = $startRow;
        $col = $startCol;
        
        for ($A = 0; $A < ($this->N*$this->N); ++$A ) {
            $this->magicSquare[$row][$col]=$magicNumber++;
      
	  		// This is where we will go if the new space is occupied
	  		// Next Row, SAME Column
	  		$isOccupiedRow = $row+1 == $this->N ? 0 : $row+1;
            $isOccupiedCol = $col;
			      
			$row = $row == 0 ? $this->N-1 : $row - 1;	// MOVE UP, WRAP AT TOP
			$col = $col == $this->N-1 ? 0 : $col + 1;	// MOVE RIGHT, WRAP AT END
			
			// Check to see if the new position is occupado
            if ($this->magicSquare[$row][$col] != 0) {

				$row = $isOccupiedRow;
				$col = $isOccupiedCol;
            }
        }
    }

    public function makeMagicAlgoDOWNRIGHT($startRow, $startCol){
    		$this->sqType = "DR";
        $magicNumber = 1;
        $row = $startRow;
        $col = $startCol;
        
        for ($A = 0; $A < ($this->N*$this->N); ++$A ) {
            $this->magicSquare[$row][$col]=$magicNumber++;
      
	  		// This is where we will go if the new space is occupied
	  		// Same ROW, PREVIOUS Column
	  		$isOccupiedRow = $row;
            $isOccupiedCol = $col == 0 ? $this->N-1 : $col - 1;
			      
			$row = $row == $this->N-1 ? 0 : $row + 1;	// MOVE DOWN, WRAP AT BOTTOM
			$col = $col == $this->N-1 ? 0 : $col + 1;	// MOVE RIGHT, WRAP AT END
			
			// Check to see if the new position is occupado
            if ($this->magicSquare[$row][$col] != 0) {

				$row = $isOccupiedRow;
				$col = $isOccupiedCol;
            }
        }
	}
    
    public function makeMagicAlgoDOWNLEFT($startRow, $startCol){
    		$this->sqType = "DL";
        $magicNumber = 1;
        $row = $startRow;
        $col = $startCol;
        
        for ($A = 0; $A < ($this->N*$this->N); ++$A ) {
            $this->magicSquare[$row][$col]=$magicNumber++;
      
	  		// This is where we will go if the new space is occupied
	  		// Same ROW, NEXT Column
	  		$isOccupiedRow = $row;
            $isOccupiedCol = $col == $this->N-1 ? 0 : $col + 1;
			      
			$row = $row == $this->N-1 ? 0 : $row + 1;	// MOVE DOWN, WRAP AT BOTTOM
			$col = $col == 0 ? $this->N-1 : $col - 1;	// MOVE LEFT, WRAP AT START
			
			// Check to see if the new position is occupado
            if ($this->magicSquare[$row][$col] != 0) {

				$row = $isOccupiedRow;
				$col = $isOccupiedCol;
            }
        }
	}
	
	public function makeMagic(){
		$N = $this->getN();
		$startRow = rand(0,$N-1);
		$startCol = rand(0,$N-1);
		
		switch(rand(1,4)){
			case 1:
				$this->makeMagicAlgoDOWNLEFT($startRow, $startCol);
				break;
			case 2:
				$this->makeMagicAlgoUPRIGHT($startRow, $startCol);
				break;
			case 3:
				$this->makeMagicAlgoDOWNRIGHT($startRow, $startCol);
				break;
			case 4:
			default:
				$this->makeMagicAlgoUPLEFT($startRow, $startCol);
				break;
		}
	}
	
	protected function checkSum($fmt, $type, $id, $sum, $magicSum){
        if ($sum != $magicSum) {
            return $fmt->write("$type $id does not add to magic sum. Expected $magicSum, got $sum.",true,true);
        }
		return "";
	}
    
	public function prettySquare($fmt){
        	
		$output = $fmt->startDiv("left".strval($this->N));	// adjust the space needed for the square
		$output .= $fmt->startTable();
		$sym = new cSymbol;
		
        for ( $X = 0; $X < $this->N; ++$X ) {
        		$output .= $fmt->startRow();
            for ($Y = 0; $Y < $this->N; ++$Y ) {
				$letter = $sym->getSymbol(($X * $this->N) + $Y);
            		$item = $this->magicSquare[$X][$Y];
                $output .= $fmt->writeCellData("%s:%s", $item,$letter);
            }
        		$output .= $fmt->endRow();
        }
		$output .= $fmt->endTable();
		$output .= $fmt->endDiv();
		
		return $output;
	}
	
	public function prettySquarePDF($fmt,$freeTerm,$solution=false){
        	
		$output = $fmt->startDiv("puzzle");
		$output .= $fmt->startTable();
		$sym = new cSymbol;
		$bsq = $this->N > 5 ? "bigsquare7" : "bigsquare";
		$gsq = $this->N > 5 ? "giant7" : "giant";
		
        for ( $X = 0; $X < $this->N; ++$X ) {
        		$output .= $fmt->startRow();
            for ($Y = 0; $Y < $this->N; ++$Y ) {
				$letter = $sym->getSymbol(($X * $this->N) + $Y);
            		$item = $this->magicSquare[$X][$Y];
            		if ($solution){
            			$output .= $fmt->writeClassData($bsq,"%s.<br /><br /><span class=\"$gsq\">%s</span>", $letter, $item );
				} elseif ($freeTerm != 0 && $this->hasFreeTermSolution($letter)){
            			$output .= $fmt->writeClassData($bsq,"%s.<br /><br /><span class=\"$gsq\">%s</span>", $letter, strval($this->getFreeTermSolution($letter)) );					
            		} else {
            			$output .= $fmt->writeClassData($bsq,"%s.<br /><br /><span class=\"$gsq\">&nbsp;</span>", $letter);
            		}
            }
        		$output .= $fmt->endRow();
        }
		$output .= $fmt->endTable();
		$output .= $fmt->endDiv();
		
		return $output;
	}
	
	public function validate($fmt, $div="right", $checkTrueness=false){
		
		$T = $this->getMagicSum();
		$output = "";
		
		$output .= $fmt->startDiv($div);
        $output .= $fmt->write("Magic sum for this square is: [$T]",true,true);
        
        for ($X = 0; $X < $this->N; ++$X ) {
            $rowTot = 0;
            
            for ($Y = 0; $Y < $this->N; ++$Y ) { 
                $rowTot += $this->magicSquare[$X][$Y];
            }
			$output .= $this->checkSum($fmt, "Row", $X, $rowTot, $T);
        }
        
        for ( $Y = 0; $Y < $this->N; ++$Y ) {
            $colTot = 0;
            
            for ($X = 0; $X < $this->N; ++$X ) {
                $colTot += $this->magicSquare[$X][$Y];
            }
			$output .= $this->checkSum($fmt, "Col", $Y, $colTot, $T);
        }
		
		if($checkTrueness){
	        $diag1 = 0;
	        for ( $X = 0; $X < $this->N; ++$X ) {
	            $diag1 += $this->magicSquare[$X][$X];
	        }
			$output .= $this->checkSum($fmt, "Diagonal", 1, $diag1, $T);
	
	        $diag2 = 0;
	        $d2col = $this->N-1;
	        for ($X = 0; $X < $this->N; ++$X) {
	            $diag2 += $this->magicSquare[$X][$d2col--];
	        }
			$output .= $this->checkSum($fmt, "Diagonal", 2, $diag2, $T);		
		}

        $output .= $fmt->write("");
		
		$output .= $fmt->endDiv();	
		
		return $output;	
	}
	
    public function dump() {

		$fmt = new cHTMLFormatter();
		
		$fmt->startDiv("magicsquare");

		$this->prettySquare($fmt);

		$this->validate($fmt);
		
		$fmt->endDiv();
    }
}

?>
