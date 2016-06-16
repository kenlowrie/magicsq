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

include_once('session.php');
include_once ('utility.php');
include_once ('formatters.php');

function uploadinputfile($fmt){
	$allowedExts = array("txt", "csv");
	$allowedTypes = array("text/csv","text/plain","application/vnd.ms-excel","text/comma-separated-values", "application/octet-stream");
	$temp = explode(".", $_FILES["file"]["name"]);
	$extension = strtolower(end($temp));
	
	$fmt->startDiv("statusArea");
	
	$fmt->h4("Processing upload of " . $_FILES["file"]["name"] . "...");
	// Make sure that the file is recognized...
	if ( in_array($_FILES["file"]["type"],$allowedTypes) 
	   && ($_FILES["file"]["size"] < 32768)
	   && in_array($extension, $allowedExts)) {
	   	
		if ($_FILES["file"]["error"] > 0) {
		  	$fmt->write("Error: " . $_FILES["file"]["error"] . "<br />");
		} else {
			$fmt->writeAndBreak("Completed upload process...");
		  	$fmt->writeAndBreak("Filename: " . $_FILES["file"]["name"]);
		  	$fmt->writeAndBreak("Filetype: " . $_FILES["file"]["type"]);
		  	$fmt->writeAndBreak("Filesize: " . ($_FILES["file"]["size"] / 1024) . " kB");
		  	$fmt->writeAndBreak("Tempname: " . $_FILES["file"]["tmp_name"]);
	
			$fmt->writeAndBreak("<br />Parsing the CSV file into terms...");	
			$row = 1;
			$headers = 0;		// ignore header rows in the final count
			$myterms = new Terms;
			$myterms->setFilename($_FILES["file"]["name"]);
			
			if (($handle = fopen($_FILES["file"]["tmp_name"], "r")) !== FALSE) {
			    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			        $num = count($data);
					
					if ($num != 2){
						$fmt->p("Error on line $row: Found $num terms, expected 2.");
					} else {
						if (startsWith($data[0],"+++")){
							$myterms->setHeaders(substr($data[0],3),$data[1]);
							++$headers;
							$fmt->p("New column headers defined: Column 1: \"".$myterms->getHeader(1)."\" Column 2: \"".$myterms->getHeader(2)."\"");
						} else{
							$myterms->addTerm(new Term($data[0], iconv('UTF-8', 'ASCII//TRANSLIT', $data[1])));
						}
					}
			        $row++;
			    }
			    fclose($handle);
			}
			$myterms->setTermsType(TERMS_FILE);
			setTerms($myterms);
			$fmt->writeRawData("Parsing has completed... Found <".strval($row-1-$headers)."> terms.<br />");
		}
	} else {
		$fmt->writeRawData("Invalid file [%s] passed to uploaded. Must be .CSV or .TXT",$_FILES["file"]["name"],true,true);
		$fmt->writeAndBreak("Filetype: " . $_FILES["file"]["type"]);
		$fmt->writeAndBreak("Filesize: " . ($_FILES["file"]["size"] / 1024) . " kB");
		$fmt->writeAndBreak("Tempname: " . $_FILES["file"]["tmp_name"]);
		$fmt->p("<br />A common issue is that the filetype passed in by the browser is not recognized. Currently, I recognize the following filetypes:");
		$fmt->brk();
		$fmt->StartList();
		foreach($allowedTypes as $mytype){
			$fmt->writeListItem($mytype);
		}
		$fmt->EndList();
	}
	
	//$fmt->addLink("mkms.php","Click me to return to the Magic Square Maker Page");
	
	$fmt->endDiv();
}

?>