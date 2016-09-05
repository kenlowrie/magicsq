<?php
// This file is part of the Magic Square Quiz Maker (magicsq) application.
// Copyright (C) 2014-2016 Ken Lowrie
//
// magicsq is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// magicsq is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// See LICENSE.TXT for more information.   

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