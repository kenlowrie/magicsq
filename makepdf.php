<?php

include_once ('session.php');
//include ('header1.inc');
include_once ('utility.php');
require_once('tcpdf/tcpdf.php');

function genhtml($fmt,$quiz,$loadedTerms,$variant,$pdf){
	
	$output = '<style>'.file_get_contents('stylespdf.css').'</style>';
	
	if ($variant >= $loadedTerms->numVariants()){
		return $fmt->p("Internal error: Variant [$variant] is out of range...");
	}
	$output .= $fmt->startTable();
	$output .= $fmt->startRow();
	$output .= $fmt->writeClassData("tdtitle1","Magic Square");	
	$output .= $fmt->writeClassData("tdtitle2","Name: __________________________");
	$output .= $fmt->endRow();
	$output .= $fmt->endTable();
	
	$output .= $fmt->brk();
	$dirTxt = <<<EOD
Directions: Match the correct terms with the correct definition or information. On the last page, 
place the number of the definition in the box with the letter of the term it matches. 
When terms and definitions are correctly matched, all of the rows or columns 
will add up to equal the same number.
EOD;
	
	//$pdf->SetFont('helvetica', '', 10, '', true);
	$output .= $fmt->p($dirTxt,"directions");
	$output .= $fmt->brk();
	//$output .= $fmt->hr();
	
	$output .= $fmt->startDiv("maintext");
	
	$jumbledTerms = $loadedTerms->getJumbledTerms();
	$output .= $loadedTerms->printTermSet($quiz->magicSquares[$variant],$quiz->freeTerm, $jumbledTerms[$variant],$fmt);
	
	return $output;
}

function gensquare($fmt,$quiz,$loadedTerms,$variant,$pdf){

	$output = '<style>'.file_get_contents('stylespdf.css').'</style>';	
		
	if ($variant >= $loadedTerms->numVariants()){
		return $fmt->p("Internal error: Variant [$variant] is out of range...");
	}
	$output .= $quiz->magicSquares[$variant]->prettySquare($fmt);
	
	return $output;
}

function createPDF($fmt, $quiz, $loadedTerms, $variant)
{
	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	
	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Magic Square Maker');
	$pdf->SetTitle($quiz->quizTitle);
	$pdf->SetSubject('SUBJECT');				// Should I add this?
	$pdf->SetKeywords('Magic Square, PDF');
	
	// set default header data
	$pdf->SetHeaderData(NULL, 0, $quiz->quizTitle, "SUBJECT", array(0,64,255), array(0,64,128));
	$pdf->setFooterData(array(0,64,0), array(0,64,128));
	
	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	// set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	
	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	
	// set some language-dependent strings (optional)
	// if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
		// require_once(dirname(__FILE__).'/lang/eng.php');
		// $pdf->setLanguageArray($l);
	// }
	
	// ---------------------------------------------------------
	
	// set default font subsetting mode
	$pdf->setFontSubsetting(true);
	
	// Set font
	// dejavusans is a UTF-8 Unicode font, if you only need to
	// print standard ASCII chars, you can use core fonts like
	// helvetica or times to reduce file size.
	$pdf->SetFont('helvetica', '', 14, '', true);
	
	// Add a page
	// This method has several options, check the source code documentation for more information.
	$pdf->AddPage();
	
	// set text shadow effect
	$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
	
	
	// Print text using writeHTML()
	$pdf->writeHTML(genhtml($fmt,$quiz,$loadedTerms,$variant,$pdf), true, false, true, false, '');
	
	// ---------------------------------------------------------
	
	$pdf->AddPage();
	
	$pdf->writeHTML(gensquare($fmt,$quiz,$loadedTerms,$variant,$pdf), true, false, true, false, '');
	
	// Close and output PDF document
	// This method has several options, check the source code documentation for more information.
	$pdf->Output('example_001.pdf', 'I');	
}

$fmt = new cHTMLFormatter;
$fmt->justPrint = false;

$variant = $_GET['variant'];		// Get the variant # that we are to process

if (!IsSet($variant)){
	include ('header1.inc');
	$fmt->p("Internal error: No variant was passed to the PDF maker");
	include ('footer1.inc');
	return;	
}
//$fmt->startDiv("outertext");

$quiz = getQuiz();
$loadedTerms = getTerms();

createPDF($fmt, $quiz, $loadedTerms, $variant);

?>