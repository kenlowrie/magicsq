<?php

include_once ('session.php');
include ('header1.inc');
include_once ('utility.php');

$fmt = new cHTMLFormatter;

$fmt->startHeader("mainHeader");
$fmt->h1("Magic Squares Help");
$fmt->endHeader();

$fmt->startSection("helpPage");
$fmt->startDivClass("helpInfo");

$fmt->addLink("mkms.php","Click Me to Return to the Magic Square Maker Page");
$fmt->brk(2);

include ('msdocs.html');

$fmt->brk();
$fmt->addLink("mkms.php","Click Me to Return to the Magic Square Maker Page");

$fmt->endDiv();	// close div.helpInfo

$fmt->endSection();

include('footer1.inc');


?>