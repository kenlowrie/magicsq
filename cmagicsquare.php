<?php

class cMagicSquare{
	protected $N;
	protected $magicSquare = array();
	
    // var N: Int = 5
    // var magicSquare: [[Int]] = []
    
    public function __construct() {
    	$this->N = 5;
        $this->initMagicSquare();
    }
    
    // public function __construct($newN) {
        // $N = $newN;
        // initMagicSquare();
    //}
    
    public function initMagicSquare(){
    	$row = array();
        //var row: [Int] = []
        for ($i = 0; $i < $this->N; ++$i) {
            $row[$i] = 0;
        }
		//print_r($row);
        
        for ($i = 0; $i < $this->N; ++$i ) {
            $this->magicSquare[$i] = $row;
        }
    }
    

    public function makeMagic($startRow, $startCol){
        $newRow  = 0;
		$newCol = 0;
		
        // Set the indices for the middle of the bottom i
        $row = $startRow;    //0
        $col = $startCol;    //N / 2;
        
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
        $row = $startRow;  //0;
        $col = $startCol;  //(N/2);
        
        for ($A = 0; $A < ($this->N*$this->N); ++$A ) {
		// in 0..<(N*N) {
            $this->magicSquare[$row][$col]=$magicNumber++;
            
            if (--$row < 0) {
                $row = $this->N-1;
            }
            if (--$col < 0) {
                $col = $this->N-1;
            }
            
            if ($this->magicSquare[$row][$col] != 0) {
                for ($B = 0; $B < 2; ++$B) {
				// in 0..<2 {
                    if (++$row > ($this->N-1)) {
                        $row=0;
                    }
                }
                if (++$col > ($this->N-1)) {
                    $col=0;
                }
            }
        }
        
    }
    
    // make the other variants of these: up/right, up/left, down/left, down/right. I think that cover all four...
    
    public function makeMagic3($startRow, $startCol){
        $magicNumber = 1;
        $row = $startRow;  //0;
        $col = $startCol;  //(N/2);
        
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
    
    public function dump() {

		// Compute the magic sum        
        $T = ((($this->N*$this->N*$this->N)+$this->N)/2);
        
        print("Magic sum is: [$T]\n<br />");
        
        for ( $X = 0; $X < $this->N; ++$X ) {
            for ($Y = 0; $Y < $this->N; ++$Y ) {
            	$item = $this->magicSquare[$X][$Y];
                print("$item&nbsp;&nbsp;");
            }
            print("\n<br />");
        }
        print("\n<br />");
        
        for ($X = 0; $X < $this->N; ++$X ) {
            $rowTot = 0;
            
            for ($Y = 0; $Y < $this->N; ++$Y ) { 
                $rowTot += $this->magicSquare[$X][$Y];
            }
            if ($rowTot != $T) {
                print("Row $X does not add to magic sum. Is $rowTot.\n<br />");
            }
        }
        
        for ( $Y = 0; $Y < $this->N; ++$Y ) {
            $colTot = 0;
            
            for ($X = 0; $X < $this->N; ++$X ) {
                $colTot += $this->magicSquare[$X][$Y];
            }
            if ($colTot != $T) {
                print("Col $Y does not add to magic sum. Is $colTot.\n<br />");
            }
        }

        $diag1 = 0;
        for ( $X = 0; $X < $this->N; ++$X ) {
            $diag1 += $this->magicSquare[$X][$X];
        }
        if ($diag1 != $T) {
            print("Diagonal 1 does not add to magic sum. Is $diag1.\n<br />");
        }

        $diag2 = 0;
        $d2col = $this->N-1;
        for ($X = 0; $X < $this->N; ++$X) {
            $diag2 += $this->magicSquare[$X][$d2col--];
        }
        if ($diag2 != $T) {
            print("Diagonal 2 does not add to magic sum. Is $diag2.\n<br />");
        }
        print("\n<br />");
    }
}

?>
