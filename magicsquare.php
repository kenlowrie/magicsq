<?php

//include ('session.php');
include ('header1.inc');

print("Magic Square v1.0");

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
