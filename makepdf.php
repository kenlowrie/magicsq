<?php

include_once ('session.php');
//include ('header1.inc');
include_once ('utility.php');
require_once('tcpdf/tcpdf.php');

function genhtml($fmt,$quiz,$loadedTerms,$pdf){
	
	$output = '<style>'.file_get_contents('stylesregular.css').'</style>';
	
	$output .= $fmt->startTable();
	$output .= $fmt->startRow();
	$output .= $fmt->writeClassData("tdtitle","Magic Square");	
	$output .= $fmt->writeClassData("tdtitle","Name: __________________________");
	$output .= $fmt->endRow();
	$output .= $fmt->endTable();
	
	$output .= $fmt->brk();
	//$output .= $fmt->h3("Magic Square");
	//$output .= $fmt->h4($quiz->quizTitle);
	//$output .= $fmt->writeAndBreak("");
	$dirTxt = <<<EOD
Directions: Match the correct terms with the correct definition or information. On the last page, 
place the number of the definition in the box with the letter of the term it matches. 
When terms and definitions are correctly matched, all of the rows or columns 
will add up to equal the same number.
EOD;
	
	//$pdf->SetFont('helvetica', '', 10, '', true);
	$output .= $fmt->p($dirTxt,"directions");
	$output .= $fmt->hr();
	
	$output .= $fmt->startDiv("outertext");
	
	$output .= $fmt->p("For each variant of the review terms...");

	$output .= $fmt->writeRawData("1. Add page(s) with table of jumbled terms and a blank magic square.");
	$output .= $fmt->brk();
	$output .= $fmt->write("2. Add a page with the solution for each set of jumbled terms.");
	$output .= $fmt->brk();
	$output .= $fmt->brk();	
	
	$output .= $fmt->p("This is some formatted text...","tdterm");
	
	return $output;
}

function createPDF($fmt, $quiz, $loadedTerms)
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
	
	// Set some content to print
// $html = <<<EOD
// <h1>Welcome to <a href="http://www.tcpdf.org" style="text-decoration:none;background-color:#CC0000;color:black;">&nbsp;<span style="color:black;">TC</span><span style="color:white;">PDF</span>&nbsp;</a>!</h1>
// <i>This is the first example of TCPDF library.</i>
// <p>This text is printed using the <i>writeHTMLCell()</i> method but you can also use: <i>Multicell(), writeHTML(), Write(), Cell() and Text()</i>.</p>
// <p>Please check the source code documentation and other examples for further information.</p>
// <p style="color:#CC0000;">TO IMPROVE AND EXPAND TCPDF I NEED YOUR SUPPORT, PLEASE <a href="http://sourceforge.net/donate/index.php?group_id=128076">MAKE A DONATION!</a></p>
//EOD;
	
	// Print text using writeHTMLCell()
//	$pdf->writeHTMLCell(0, 0, '', '', genhtml($fmt,$quiz,$loadedTerms,$pdf), 0, 1, 0, true, '', true);
	$pdf->writeHTML(genhtml($fmt,$quiz,$loadedTerms,$pdf), true, false, true, false, '');
	
	// ---------------------------------------------------------
	
	$pdf->AddPage();
	
	$pdf->writeHTML(genhtml($fmt,$quiz,$loadedTerms,$pdf), true, false, true, false, '');
//	$pdf->writeHTMLCell(0,0,'','', genhtml($fmt,$quiz,$loadedTerms,$pdf), 0,1,0,true,'',true);
	
	// Close and output PDF document
	// This method has several options, check the source code documentation for more information.
	$pdf->Output('example_001.pdf', 'I');
	
}

$fmt = new cHTMLFormatter;
$fmt->justPrint = false;

//$fmt->startDiv("outertext");

$quiz = getQuiz();
$loadedTerms = getTerms();

createPDF($fmt, $quiz, $loadedTerms);

/*
if (!IsSet($loadedTerms)){
	$fmt->p("You need to load a terms file or generate sample terms first...");
} else {
	
	$fmt->h2("Create PDF output");
	
	$fmt->hr();
	
	$fmt->startDiv("outertext");
	
	$fmt->p("This is where the generate PDF code will live. When this is completed, it will:");

	$fmt->writeRawData("1. Generate a PDF with %d version%s of the terms and definitions and the blank magic square.", $quiz->variants, $quiz->variants == 1 ? "" : "s");
	$fmt->brk();
	$fmt->write("2. Generate a PDF that has a key for each variant of the output.");
	$fmt->brk();
	$fmt->brk();
		
	$fmt->endDiv();
}

$fmt->addLink("mkms.php","Click me to return to the Magic Square Maker Page");

$fmt->endDiv();
*/

?>