<?php

include_once('session.php');
include_once ('utility.php');
include_once ('formatters.php');

function uploadinputfile($fmt){
	$allowedExts = array("txt", "csv");
	$temp = explode(".", $_FILES["file"]["name"]);
	$extension = strtolower(end($temp));
	
	$fmt->startDiv("statusArea");
	
	$fmt->h4("Processing upload of " . $_FILES["file"]["name"] . "...");
	// Make sure that the file is recognized...
	if (( ($_FILES["file"]["type"] == "text/csv") || ($_FILES["file"]["type"] == "text/plain") || ($_FILES["file"]["type"] == "application/vnd.ms-excel")) 
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
			setTerms($myterms);
			$fmt->writeRawData("Parsing has completed... Found <".strval($row-1-$headers)."> terms.<br />");
		}
	} else {
		$fmt->writeRawData("Invalid file [%s] passed to uploaded. Must be .CSV or .TXT",$_FILES["file"]["name"],true,true);
		$fmt->writeAndBreak("Filetype: " . $_FILES["file"]["type"]);
		$fmt->writeAndBreak("Filesize: " . ($_FILES["file"]["size"] / 1024) . " kB");
		$fmt->writeAndBreak("Tempname: " . $_FILES["file"]["tmp_name"]);
	}
	
	//$fmt->addLink("mkms.php","Click me to return to the Magic Square Maker Page");
	
	$fmt->endDiv();
}

?>