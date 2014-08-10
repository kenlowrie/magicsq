<?php

include_once('session.php');
include_once ('utility.php');
include_once ('formatters.php');

function uploadinputfile($fmt){
	$allowedExts = array("txt", "csv");
	$temp = explode(".", $_FILES["file"]["name"]);
	$extension = end($temp);
	
	$fmt->startDiv("statusarea");
	
	$fmt->h4("Processing upload of " . $_FILES["file"]["name"] . "...");
	// Make sure that the file is recognized...
	if (( ($_FILES["file"]["type"] == "text/csv") || ($_FILES["file"]["type"] == "text/plain")) 
	   && ($_FILES["file"]["size"] < 32768)
	   && in_array($extension, $allowedExts)) {
	   	
		if ($_FILES["file"]["error"] > 0) {
		  	$fmt->write("Error: " . $_FILES["file"]["error"] . "<br>");
		} else {
			$fmt->write("Completed upload process...");
		  	$fmt->write("Filename: " . $_FILES["file"]["name"]);
		  	$fmt->write("Filetype: " . $_FILES["file"]["type"]);
		  	$fmt->write("Filesize: " . ($_FILES["file"]["size"] / 1024) . " kB");
		  	$fmt->write("Tempname: " . $_FILES["file"]["tmp_name"]);
	
			$fmt->write("<br />Parsing the CSV file into terms...");	
			$row = 1;
			$myterms = new Terms;
			$myterms->setFilename($_FILES["file"]["name"]);
			
			if (($handle = fopen($_FILES["file"]["tmp_name"], "r")) !== FALSE) {
			    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			        $num = count($data);
					
					if ($num != 2){
						$fmt->p("Error on line $row: Found $num terms, expected 2.");
					} else {
						$myterms->addTerm(new Term($data[0], iconv('UTF-8', 'ASCII//TRANSLIT', $data[1])));
					}
			        $row++;
			    }
			    fclose($handle);
			}
			setTerms($myterms);
			$fmt->write("Parsing has completed... Found <%d> terms.",$row-1);
		}
	} else {
		$fmt->write("Invalid file [%s] passed to uploaded. Must be .CSV or .TXT",$_FILES["file"]["name"]);
	}
	
	//$fmt->addLink("mkms.php","Click me to return to the Magic Square Maker Page");
	
	$fmt->endDiv();
}

?>