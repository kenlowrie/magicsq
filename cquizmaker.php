<?php

class cQuizMaker{
	public $quizTitle;
	public $magicSquareSize;
	public $variants;
	public $freeTerm;
	public $mapFTtoAlignedTD;
	public $textArea;
	
	public function __construct(){
		$this->quizTitle = "Title of Handout";
		$this->magicSquareSize = "5";
		$this->variants = 1;
		$this->freeTerm = 1;
		$this->mapFTtoAlignedTD = false;
		$this->textArea = "";
	}
	
}


?>