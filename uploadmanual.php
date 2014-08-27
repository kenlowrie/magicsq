<?php

include_once ('session.php');
include ('header1.inc');
include_once ('utility.php');

function preloadData(){
	return <<<EOD
+++Problem,Solution
37/37,1
8-6,2
96/32,3
3+1,4
135/27,5
2*3,6
38-31,7
5+3,8
81/9,9
5*2,10
63-52,11
4+8,12
39/3,13
29-15,14
105/7,15
2*8,16
51/3,17
121-103,18
17+2,19
400/20,20
3*7,21
88/4,22
532-509,23
18+6,24
625/25,25
EOD;
}
$fmt = new cHTMLFormatter;

$fmt->startHeader("mainHeader");
$fmt->h1("Enter Data Manually");
$fmt->endHeader();

$fmt->startSection("manualInput");

$fmt->startDivClass("manualInfo");

$fmt->h3("Enter your input data below");

$fmt->startP();
$fmt->write("The format of the data in the text area needs to follow standard CSV rules. If you need to use commas in your input, be sure to surround the data with quotation marks.");
$fmt->write("The default column headers are \"Term\" and \"Definition\", but you can override them by entering a line in your CSV input like this:");
$fmt->brk(2);
$fmt->write("+++Left Column, Right Column");
$fmt->brk(2);
$fmt->write("If you put the previous line in your input data below, then the columns would be named: \"Left Column\" and \"Right Column\".");
$fmt->endP();

$preload = $_POST['preload'];
if(IsSet($preload) && $preload == '1'){
	getQuiz()->textArea = preloadData();
}
$fmt->textArea(getQuiz()->textArea,"textarea", "manual", 30, 100);

$fmt->write("<form method=\"POST\" action=\"mkms.php\" id=\"manual\">");
$fmt->write("<input class=\"fancyButton genQuizButton\" type=\"submit\" value=\"Load These Terms\">");
$fmt->write("<input type=\"hidden\" name=\"type\" value=\"". PROCESS_DATA . "\">");
$fmt->write("</form>");

$fmt->linkbutton("uploadmanual.php", "Create Sample Data",NULL,"fancyButton genQuizButton",array("preload" => "1"));
$fmt->linkbutton("mkms.php", "Cancel and Return",NULL,"fancyButton genQuizButton");

$fmt->endDiv();			// textareaForm end div

$fmt->endSection();

include('footer1.inc');

?>