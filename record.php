<?php

include_once ('session.php');
include ('uploadterms.php');

/*
** The common functions for handling the forms are in this file.
*/

/*
** Return the help string for the specified field.
*/
function get_help($key)
{
     switch($key)
     {
     case 'title':
          $str = "Enter the title of the handout in the space above. This title will be printed at the top of the student handout.";
          break;
     case 'mssize':
          $str = "Select the size for your magic square. Currently, only odd sizes of 3x3, 5x5, 7x7 and 9x9 are supported. Make sure that your input file below has exactly (size * size) questions in it. e.g. if you select 5x5, you need 25 questions in your input file.";
          break;
     case 'variants':
          $str = "Enter the number of variations that you want created. For each variant, a different magic square is generated, and the list of terms is jumbled, to help prevent students from simply copying from someone else.";
          break;
     case 'filename':
          $str = "Select the input file from your computer and press submit. This must be a valid CSV file, with either .CSV or .TXT as the file extension. Each line requires two comma separated items, a term and its definition.<br /><br />e.g. \"My Term\", \"My Definition.\"";
          break;
     case 'freeterm':
          $str = "Line number of term in the uploaded file that will be solved automatically. This will assist the student in understanding how to fill out the answer sheet. Zero (0) means <i>\"No Free Term\"</i>.";
          break;
     case 'alignft':
          $str = "Check to automatically align free term (see above description) to the term/definition that is aligned in the output. In each magic square, there is at least one term/definition that will be aligned. By checking here, the aligned term becomes the free term, as long as free term (above) is not 0.";
          break;
	 case 'genterms3':
          $str = "Click this button to generate a sample 3x3 set of terms to see how the app works.";
          break;
	 case 'genterms5':
          $str = "Click this button to generate a sample 5x5 set of terms to see how the app works.";
          break;
	 case 'genterms7':
          $str = "Click this button to generate a sample 7x7 set of terms to see how the app works.";
          break;
	 case 'reset':
          $str = "Click this button to reset all the data and start over.";
          break;
     default:
          $str = NULL;
     }
     return $str;
}


function display_quiz_form($fmt, $alias, $quiz, $commit_id)
{
	$title  = $quiz->quizTitle;
	//$mstype = $quiz->magicSquareSize;
	$variants = $quiz->variants;
	$freeterm = $quiz->freeTerm;

	$fmt->startSection("loadTerms");

	$fmt->h3("Terms and definitions");

	$fmt->startDivClass("termInfo");	
	$terms = getTerms();
	if (IsSet($terms)){
		$numterms = count($terms->getTerms());
		$fmt->h5("Current term file information");
		$fmt->startP();
		$fmt->write("\"" . $terms->getFilename() . "\" is the currently loaded terms file.");
		$squaresize = sqrt($numterms);
		if( isValidSquareSize($squaresize)){
			$quiz->magicSquareSize = intval($squaresize);
			$fmt->write("There are " . $numterms . " terms, which will yield a " . $quiz->magicSquareSize . "x" . $quiz->magicSquareSize . " magic square.");
		} else {
			$quiz->magicSquareSize = 0;
			$fmt->write("Based on this term set, I cannot generate a magic square for you. Only odd sized squares of 3, 5, 7 or 9 are currently supported, therefore I need a terms file with 9, 25, 49 or 81 terms in it.");
		}
		if (!isAppleDevice()){
			$fmt->write("To replace this set of terms, just load another file using the button below.");
		}
		$fmt->endP();
	}
	if (!isAppleDevice()){
		$fmt->h5("Load a new terms file");
		$loadfile = LOAD_FILE;
		$fmt->write("<form method=\"POST\" action=\"$alias?type=$loadfile\" enctype=\"multipart/form-data\">");
		$fmt->write("<input type=\"file\" name=\"file\" class=\"loadTermsButton\">");
		$fmt->brk();
		$fmt->write("<input type=\"submit\" name=\"submit\" value=\"Choose Terms File and Click Here to Upload\" class=\"loadTermsButton\">");
		$fmt->write("</form>");		
	} else {
		$fmt->write("Uploading terms with iOS devices is not supported at this time. Use the Generate buttons below to test.");		
	}

	$fmt->endDiv();
	$fmt->endSection();
	
	if (IsSet($terms)){

		$fmt->startSection("selectOptions");

		$optionCounter = 1;		
		$divOptionOddEven = array("quizOptionOdd","quizOptionEven");
		$divMainLeft = "quizOptionLeft";
		$divMainRight = "quizOptionRight";
		
		$fmt->startDivClass("quizForm");

		$fmt->h3("Provide details for the handouts, and then click Generate Quiz Data.");
		
	     // construct the form, and then the outer table that will hold the field definitions
		$fmt->write("<form method=\"POST\" action=\"makequiz.php\">");

		$fmt->startDivClass($divOptionOddEven[++$optionCounter % 2]);	
		$fmt->startDivClass($divMainLeft);
		
		$fmt->h4("Handout Title");
		
	    $fmt->write("<input type=text size=\"25\" maxlength=\"128\" name=\"title\" value=\"$title\"><br /><br />");
	
		$fmt->endDiv();
		$fmt->startDivClass($divMainRight);
	
	    	$helpstr = get_help("title");
	    if ($helpstr) $fmt->write("$helpstr<br />");
		$fmt->endDiv(2);
	
	
		$fmt->startDivClass($divOptionOddEven[++$optionCounter % 2]);	
		$fmt->startDivClass($divMainLeft);
			
		$fmt->h4("Number of variants");
	    $fmt->write("<input type=\"number\" min=\"1\" max=\"9\" name=\"variants\" value=\"$variants\"><br /><br />");
	
		$fmt->endDiv();
		$fmt->startDivClass($divMainRight);
	
	    	$helpstr = get_help("variants");
	    if ($helpstr) $fmt->write("$helpstr<br />");
	
		$fmt->endDiv(2);
	
		$fmt->startDivClass($divOptionOddEven[++$optionCounter % 2]);	
		$fmt->startDivClass($divMainLeft);
			
		$fmt->h4("Free Term");
		$maxterms = count($terms->getTerms());
	    $fmt->write("<input type=number min=\"0\" max=\"$maxterms\" name=\"freeterm\" value=\"$freeterm\"><br /><br />");

		$fmt->endDiv();
		$fmt->startDivClass($divMainRight);
	
	    	$helpstr = get_help("freeterm");
	    if ($helpstr) $fmt->write("$helpstr");
		if ($freeterm > 0){
			if($freeterm < $maxterms){
				$termlist = $terms->getTerms();
				$fmt->write("Based on the currently loaded term set, the free term will be \"".$termlist[$freeterm-1]->getTerm() . "\".");				
			} else{
				$fmt->write("The currently selected free term is not within the limits of the current term set.");
			}
		} else {
			$fmt->write("There will be no free term given, probably because you're a mean teacher or you have mean students...");		
		}
		
		$fmt->endDiv(2);
	
		$fmt->startDivClass($divOptionOddEven[++$optionCounter % 2]);	
		$fmt->startDivClass($divMainLeft);
			
		$fmt->h4("Align Free Term");
		if ($quiz->mapFTtoAlignedTD){
		    $fmt->write("<input type=checkbox name=\"alignft\" value=\"1\" checked=\"checked\"><br /><br />");		
		} else {
		    $fmt->write("<input type=checkbox name=\"alignft\" value=\"1\"><br /><br />");					
		}

		$fmt->endDiv();
		$fmt->startDivClass($divMainRight);
	
	    	$helpstr = get_help("alignft");
	    if ($helpstr) $fmt->write("$helpstr");
		
		$fmt->brk();
		$fmt->endDiv(2);
		
		$fmt->startP("clearAndCenter");
		$fmt->write("<input id=\"genQuizButton\" type=\"submit\" value=\"Generate Quiz Data\">");
		$fmt->endP();
		$fmt->write("</form>");

		$fmt->endDiv();			// quizForm end div

		$fmt->endSection();
	}

	$fmt->startSection("helpers");
	
	$fmt->linkbutton("$alias?type=".MAKE_TERMS."&size=3", "Generate 3x3 Sample Term Set",NULL,"helperButton");
	$fmt->brk();
	
	$fmt->linkbutton("$alias?type=".MAKE_TERMS."&size=5", "Generate 5x5 Sample Term Set",NULL,"helperButton");
	$fmt->brk();
	
	$fmt->linkbutton("$alias?type=".MAKE_TERMS."&size=7", "Generate 7x7 Sample Term Set",NULL,"helperButton");
	$fmt->brk();
	
	$fmt->linkbutton("$alias?type=".RESET, "Reset Everything and Start Over",NULL,"helperButton");
	$fmt->brk();

	$fmt->linkbutton("$alias", "Do a Refresh This of This Page",NULL,"helperButton");
	$fmt->brk();

	$fmt->endSection();
}
?>
