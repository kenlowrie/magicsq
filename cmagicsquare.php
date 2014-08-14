<?php

include 'formatters.php';

function isValidSquareSize($square){
	$validSizes = array(3.0, 5.0, 7.0, 9.0);
	
	return in_array($square, $validSizes);
}

class cMagicSquare{
	protected $N;
	protected $magicSquare = array();
	
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

	public function getElement($row, $col) {
		if ($row < $this->N && $col < $this->N) {
			return $this->magicSquare[$row][$col];
		}
		return -1;
	}
    

    public function makeMagicAlgoUPLEFT($startRow, $startCol){
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
	
	
	protected function checkSum($type, $id, $sum, $magicSum){
        if ($sum != $magicSum) {
            MyLog("$type $id does not add to magic sum. Expected $magicSum, got $sum.");
        }		
	}
    
	public function prettySquare($fmt){
        	
		$output = $fmt->startDiv("left");
		$output .= $fmt->startTable();
		
        for ( $X = 0; $X < $this->N; ++$X ) {
        		$output .= $fmt->startRow();
            for ($Y = 0; $Y < $this->N; ++$Y ) {
            		$item = $this->magicSquare[$X][$Y];
                $output .= $fmt->writeCellData("%s", $item);
            }
        		$output .= $fmt->endRow();
        }
		$output .= $fmt->endTable();
		$output .= $fmt->endDiv();
		
		return $output;
	}
	
    public function dump() {

		$fmt = new cHTMLFormatter();
		
		$fmt->startDiv("magicsquare");

		// Compute the magic sum        
        $T = ((($this->N*$this->N*$this->N)+$this->N)/2);
        
		$this->prettySquare($fmt);
		
		$fmt->startDiv("right");
        print("Magic sum is: [$T]\n<br />");
        
        for ($X = 0; $X < $this->N; ++$X ) {
            $rowTot = 0;
            
            for ($Y = 0; $Y < $this->N; ++$Y ) { 
                $rowTot += $this->magicSquare[$X][$Y];
            }
			$this->checkSum("Row", $X, $rowTot, $T);
        }
        
        for ( $Y = 0; $Y < $this->N; ++$Y ) {
            $colTot = 0;
            
            for ($X = 0; $X < $this->N; ++$X ) {
                $colTot += $this->magicSquare[$X][$Y];
            }
			$this->checkSum("Col", $Y, $colTot, $T);
        }

        $diag1 = 0;
        for ( $X = 0; $X < $this->N; ++$X ) {
            $diag1 += $this->magicSquare[$X][$X];
        }
		$this->checkSum("Diagonal", 1, $diag1, $T);

        $diag2 = 0;
        $d2col = $this->N-1;
        for ($X = 0; $X < $this->N; ++$X) {
            $diag2 += $this->magicSquare[$X][$d2col--];
        }
		$this->checkSum("Diagonal", 2, $diag2, $T);
        MyLog("");
		
		$fmt->endDiv();
		$fmt->endDiv();
    }
}

?>
