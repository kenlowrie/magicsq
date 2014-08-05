<?php

//include ('session.php');
include ('header1.inc');
include_once ('cmagicsquare.php');
include_once ('cterms.php');
include_once ('utility.php');

MyLog("Magic Square v1.0");

MyLog("ALGO1");
$magicSquare = new cMagicSquare();
$magicSquare->makeMagic(0, 2);
$magicSquare->dump();

MyLog("ALGO2");
$ms2 = new cMagicSquare();
$ms2->makeMagic2(0, 2);
$ms2->dump();

MyLog("ALGO3");
$ms3 = new cMagicSquare();
$ms3->makeMagic3(0, 2);
$ms3->dump();

$myTerms = new Terms();
$myTerms->loadTerms(25);
MyLog("1st randomization...");
$myTerms->randomizeTerms();
$myTerms->dumpTerms();
MyLog("2nd randomization...");
$myTerms->randomizeTerms();
$myTerms->dumpTerms();


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

/*
println("ALGO1")
var magicSquare = cMagicSquare()
magicSquare.makeMagic(0, startCol: 2)
magicSquare.dump()

println("ALGO2")
var ms2 = cMagicSquare()
ms2.makeMagic2(1, startCol: 2)
ms2.dump()

println("ALGO3")
var ms3 = cMagicSquare()
ms3.makeMagic3(1, startCol: 2)
ms3.dump()

println("ALGO4")
var ms4 = cMagicSquare()
ms4.makeMagic3(0, startCol: 0)
ms4.dump()


var myTerms = Terms()
myTerms.loadTerms(25)
println("1st randomization...")
myTerms.randomizeTerms()
myTerms.dumpTerms()
println("2nd randomization...")
myTerms.randomizeTerms()
myTerms.dumpTerms()
*/


include ('footer1.inc');
?>
