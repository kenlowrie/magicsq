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

include_once ('session.php');
include_once ('utility.php');
require_once('tcpdf/tcpdf.php');

function genhtml($fmt,$quiz,$loadedTerms,$variant,$pdf){
	$output = '<style>'.file_get_contents('css/stylespdf.css').'</style>';
	
	if ($variant >= $loadedTerms->numVariants()){
		return $fmt->p("Internal error: Variant [$variant] is out of range...");
	}
	$output .= $fmt->p("Name: __________________________","tdtitle2");
		
	//$output .= $fmt->brk();
	$dirTxt = <<<EOD
Directions: Match the correct terms with the correct definition or information. On the last page, 
place the number of the definition in the box with the letter of the term it matches. 
When terms and definitions are correctly matched, all of the rows or columns 
will add up to equal the same number.
EOD;
	
	//$pdf->SetFont('helvetica', '', 10, '', true);
	$output .= $fmt->p($dirTxt,"directions");
	//$output .= $fmt->brk();
	//$output .= $fmt->hr();
	
	$output .= $fmt->startDiv("maintext");
	
	$jumbledTerms = $loadedTerms->getJumbledTerms();
	$output .= $loadedTerms->printTermSet($quiz->magicSquares[$variant],$quiz->freeTerm, $quiz->mapFTtoAlignedTD,$jumbledTerms[$variant],$fmt);
	
	return $output;
}

function gensquare($fmt,$quiz,$loadedTerms,$variant,$pdf){

	$output = '<style>'.file_get_contents('css/stylespdf.css').'</style>';	
		
	if ($variant >= $loadedTerms->numVariants()){
		return $fmt->p("Internal error: Variant [$variant] is out of range...");
	}
	$output .= $fmt->p("Name: __________________________","tdtitle2");
		
	$output .= $fmt->brk();
	$output .= $quiz->magicSquares[$variant]->prettySquarePDF($fmt,$quiz->freeTerm);
	
	return $output;
}

function gensolution($fmt,$quiz,$loadedTerms,$variant,$pdf){

	$output = '<style>'.file_get_contents('css/stylespdf.css').'</style>';	
		
	$output .= $quiz->magicSquares[$variant]->validate($fmt,"maintext");

	if ($variant >= $loadedTerms->numVariants()){
		return $fmt->p("Internal error: Variant [$variant] is out of range...");
	}
	$output .= $quiz->magicSquares[$variant]->prettySquarePDF($fmt,$quiz->freeTerm, true);
	
	return $output;
}

function createPDF($fmt, $quiz, $loadedTerms, $variant, $pdfDest)
{
	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, "LETTER", true, 'UTF-8', false);
	
	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Magic Square Maker');
	$pdf->SetTitle($quiz->quizTitle);
	$pdf->SetSubject('Magic Square');
	$pdf->SetKeywords('Magic Square, PDF');
	
	// set default header data
	$pdf->SetHeaderData(NULL, 0, $quiz->quizTitle, "Magic Square", array(0,64,255), array(0,64,128));
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
	
	// ---------------------------------------------------------
	
	$pdf->SetHeaderData(NULL, 0, $quiz->quizTitle. " - SOLUTION", "Magic Square", array(0,64,255), array(0,64,128));
	$pdf->ResetHeaderTemplate();
	$pdf->AddPage();
	
	$pdf->writeHTML(gensolution($fmt,$quiz,$loadedTerms,$variant,$pdf), true, false, true, false, '');

	// Close and output PDF document
	// This method has several options, check the source code documentation for more information.
	$pdf->Output($loadedTerms->getBaseFilename()."_$variant.pdf", $pdfDest);	
}

$fmt = new cHTMLFormatter;
$fmt->justPrint = false;

$variant = $_POST['variant'];		// Get the variant # that we are to process
$download = $_POST['download'];	// whether we want to display inline or download

if (!IsSet($variant)){
	include ('header1.inc');
	$fmt->p("Internal error: No variant was passed to the PDF maker");
	include ('footer1.inc');
	return;	
}

if (IsSet($download) && $download == '1'){
	$pdfDest = 'D';
} else {
	$pdfDest = 'I';
}

$quiz = getQuiz();
$loadedTerms = getTerms();

createPDF($fmt, $quiz, $loadedTerms, $variant, $pdfDest);

?>