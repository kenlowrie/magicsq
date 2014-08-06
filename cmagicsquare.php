<?php

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
    
	public function getN() {
		return $this->N;
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
    
/*
    public function makeMagicALT($startRow, $startCol){
        $newRow  = 0;
		$newCol = 0;
		
        // Set the indices for the middle of the bottom i
        $row = $startRow;
        $col = $startCol;
        
        // Fill each element of the array using the magic array
        for ( $value = 1; $value <= $this->N*$this->N; $value++ ) {
            $this->magicSquare[$row][$col] = $value;
            // Find the next cell, wrapping around if necessary.
            $newRow = ($row + 1) % $this->N;
            $newCol = ($col + 1) % $this->N;
            
            // If the cell is empty, remember those indices for the next assignment.
            if ( $this->magicSquare[$newRow][$newCol] == 0 ) {
                $row = $newRow;
                $col = $newCol;
            }
            else {
                // The cell was full. Use the cell above the previous one.
                $row = ($row - 1 + $this->N) % $this->N;
            }
            
        }
    }

	 
    public function makeMagic2($startRow, $startCol){
        $magicNumber = 1;
        $row = $startRow;
        $col = $startCol;
        
        for ($A = 0; $A < ($this->N*$this->N); ++$A ) {
            $this->magicSquare[$row][$col]=$magicNumber++;
            
            if (--$row < 0) {			// MOVE LEFT
                $row = $this->N-1;		// If past 0, then go to FAR RIGHT
            }
            if (--$col < 0) {			// MOVE UP
                $col = $this->N-1;		// If past 0, then go to BOTTOM
            }
            
			// Check to see if the new position is occupado
            if ($this->magicSquare[$row][$col] != 0) {
            	// MOVE BACK TO RIGHT, 1 PAST WHERE WE STARTED
                for ($B = 0; $B < 2; ++$B) {
                	// MOVE RIGHT
                    if (++$row > ($this->N-1)) {
                        $row=0;			// If past END, then go to FAR LEFT 
                    }
                }
				// MOVE DOWN
                if (++$col > ($this->N-1)) {
                    $col=0;				// If past BOTTOM, then go to TOP
                }
            }
        }
        
    }
*/

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

/*
    public function makeMagic3($startRow, $startCol){
        $magicNumber = 1;
        $row = $startRow;
        $col = $startCol;
        
        for ($A = 0; $A < ($this->N*$this->N); ++$A ) {
            $this->magicSquare[$row][$col]=$magicNumber++;
            
            $lastRow = $row+1 == $this->N ? 0 : $row+1;
            $lastCol = $col;
            
            if (--$row < 0) {
                $row = $this->N-1;
            }
            if (++$col == $this->N) {
                $col=0;
            }
            
            if ($this->magicSquare[$row][$col] != 0) {
                $row = $lastRow;
                $col = $lastCol;
            }
        }
        
    }
*/
	
	public function getElement($row, $col) {
		if ($row < $this->N && $col < $this->N) {
			return $this->magicSquare[$row][$col];
		}
		return -1;
	}
	
	protected function checkSum($type, $id, $sum, $magicSum){
        if ($sum != $magicSum) {
            MyLog("$type $id does not add to magic sum. Expected $magicSum, got $sum.");
        }		
	}
    
    public function dump() {

		// Compute the magic sum        
        $T = ((($this->N*$this->N*$this->N)+$this->N)/2);
        
        print("Magic sum is: [$T]\n<br />");
        
        for ( $X = 0; $X < $this->N; ++$X ) {
            for ($Y = 0; $Y < $this->N; ++$Y ) {
            	$item = $this->magicSquare[$X][$Y];
                MyPrint("%4s", $item);
            }
            print("\n<br />");
        }
        print("\n<br />");
        
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
    }
}

?>
