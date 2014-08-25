<?php

include_once ('session.php');
include ('header1.inc');
include_once ('utility.php');

$fmt = new cHTMLFormatter;

$fmt->startHeader("mainHeader");
$fmt->h1("Input Data Manually");
$fmt->endHeader();

$fmt->startSection("manualInput");

$fmt->startDivClass("manualInfo");

$fmt->h3("Enter your input data below");

$fmt->startP();
$fmt->write("The format of the data in the text area needs to follow standard CSV rules. If you need to use commas in your input, be sure to surround the data with quotation marks.");
$fmt->endP();

$fmt->textArea(getQuiz()->textArea,"textarea", "manual", 30, 100);

$fmt->write("<form method=\"POST\" action=\"mkms.php\" id=\"manual\">");
$fmt->write("<input class=\"fancyButton genQuizButton\" type=\"submit\" value=\"Load These Terms\">");
$fmt->write("<input type=\"hidden\" name=\"type\" value=\"". PROCESS_DATA . "\">");
$fmt->write("</form>");

$fmt->linkbutton("mkms.php", "Cancel and Return",NULL,"fancyButton genQuizButton");

$fmt->endDiv();			// textareaForm end div

$fmt->endSection();

include('footer1.inc');

?>