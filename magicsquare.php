<?php

//include ('session.php');
include ('header1.inc');
include_once ('cmagicsquare.php');
include_once ('cterms.php');
include_once ('utility.php');

$fmt = new cHTMLFormatter;

$fmt->startDiv("outertext");

MyLog("Magic Square v1.0a");

$fmt->hr();
MyLog("Using UP -- LEFT Method");

$fmt->endDiv();

$ms2a = new cMagicSquare();
$ms2a->makeMagicAlgoUPLEFT(0, 2);
$ms2a->dump();

$fmt->startDiv("outertext");
$fmt->hr();
MyLog("Using UP -- RIGHT Method");
$fmt->endDiv();
$ms2b = new cMagicSquare();
$ms2b->makeMagicAlgoUPRIGHT(0, 2);
$ms2b->dump();

$fmt->startDiv("outertext");
$fmt->hr();
MyLog("Using DOWN -- RIGHT Method");
$fmt->endDiv();
$ms3a = new cMagicSquare();
$ms3a->makeMagicAlgoDOWNRIGHT(1, 2);
$ms3a->dump();

$fmt->startDiv("outertext");
$fmt->hr();
MyLog("Using DOWN -- LEFT Method");
$fmt->endDiv();
$ms3b = new cMagicSquare();
$ms3b->makeMagicAlgoDOWNLEFT(0, 2);
$ms3b->dump();


$fmt->startDiv("outertext");
MyLog("Sample Terms & Definitions");
MyLog("Based on DOWN -- LEFT Square above");
$myTerms = new Terms();
$myTerms->loadTerms(25);
$myTerms->randomizeTerms();
$myTerms->randomizeTerms();

$myTerms->output($ms3b);
$fmt->endDiv();

//$uid   = $_GET['uid'];              // my UID (record ID) as a GET parameter
//$video = $_GET['video'];			// read the permalink value
//$ap    = $_GET['ap'];				// autoplay value

//db_count_rows();					// load up the session variable for total count

//if (!IsSet($video) and !IsSet($uid)){
//	$uid = '1';						// if neither UID or VIDEO is specified, default to video 1
//}

// If autoplay specified as URL argument, pick it up now
//if (IsSet($ap)){
//    $_SESSION['def_autoplay'] = $ap;
//}

//$record = db_read_record($uid,$video);		// read the requested record from the DB

// if we found the record, record['ok'] will be set to true
//if (!$record['ok']){
//     print("$alias encountered an error reading record [$uid/$video] from the database");
//	include('footer1.inc');
//	exit();
//}

//include ('dispvids.php');

//display_video($record);			// Display the video window and the requested video
//display_navigation($record, null, null);	// display navigation
//display_standard_videos($record['uid']);	// Display the remaining videos


include ('footer1.inc');
?>
