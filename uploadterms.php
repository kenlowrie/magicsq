<?php

include_once('session.php');
include ('header1.inc');
include_once ('utility.php');
include_once ('formatters.php');

$alias = "Upload Terms";
$allowedExts = array("txt", "csv");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
$fmt = new cHTMLFormatter;

$fmt->startDiv("outertext");

MyLog("$alias: Processing upload of %s...", $_FILES["file"]["name"]);
// Make sure that the file is recognized...
if (( ($_FILES["file"]["type"] == "text/csv") || ($_FILES["file"]["type"] == "text/plain")) 
   && ($_FILES["file"]["size"] < 32768)
   && in_array($extension, $allowedExts)) {
   	
	if ($_FILES["file"]["error"] > 0) {
	  	echo "Error: " . $_FILES["file"]["error"] . "<br>";
	} else {
		MyLog("Completed upload process...");
	  	MyLog("Filename: " . $_FILES["file"]["name"]);
	  	MyLog("Filetype: " . $_FILES["file"]["type"]);
	  	MyLog("Filesize: " . ($_FILES["file"]["size"] / 1024) . " kB");
	  	MyLog("Tempname: " . $_FILES["file"]["tmp_name"]);

		MyLog("<p>Parsing the CSV file into terms...</p>");	
		$row = 1;
		$myterms = new Terms;
		$myterms->setFilename($_FILES["file"]["name"]);
		
		if (($handle = fopen($_FILES["file"]["tmp_name"], "r")) !== FALSE) {
		    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		        $num = count($data);
				if ($num != 2){
					echo "<p>Error on line $row: Found $num terms, expected 2.</p>\n";
				} else {
					$myterms->addTerm(new Term(iconv('UTF-8', 'ASCII/TRANSLIT', $data[0]), iconv('UTF-8', 'ASCII//TRANSLIT', $data[1])));
				}
		        $row++;
		    }
		    fclose($handle);
		}
		setTerms($myterms);
		MyLog("<p>Parsing has completed... Found <%d> terms.",$row-1);
	}
} else {
	MyLog("Invalid file [%s] passed to uploaded. Must be .CSV or .TXT",$_FILES["file"]["name"]);
}

$fmt->addLink("mkms.php","Click me to return to the Magic Square Maker Page");

$fmt->endDiv();

include ('footer1.inc');

?>