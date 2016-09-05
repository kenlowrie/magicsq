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