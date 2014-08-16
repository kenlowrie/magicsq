<?php

class cSymbol{
	protected $offset;

	public function __construct($offset=0){
		$this->offset = $offset;
	}
	
	public function me(){
		return $this->getSymbol($this->offset);		
	}
	
	public function increment(){
		return $this->getSymbol(++$this->offset);
	}
	
	public function incSymbol($id){
		return $this->getSymbol($id+1);
	}
	
	public function getSymbol($id){
		
		if ($id < 26){
			$prefix = '';
		}
		elseif ($id < 52){
			$prefix = 'A';
		}
		elseif ($id < 78){
			$prefix = 'B';
		}
		
		return sprintf("$prefix%c", ($id%26)+65);
	}	
	
}

?>