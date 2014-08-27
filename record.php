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
          $str = "Enter the title of the handout in the text box. This title will be printed at the top of the student handout.";
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
          $str = "Line number of item in the uploaded file that will be solved automatically. This will assist the student in understanding how to fill out the answer sheet. Zero (0) means <i>\"No Free Term\"</i>.";
          break;
     case 'alignft':
          $str = "Check to automatically align free term (see above description) to the term/definition that is aligned in the output. In each magic square, there is at least one term/definition that will be aligned. By checking here, the aligned term becomes the free term, as long as free term (above) is not 0.";
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
	$terms = getTerms();

	$fmt->startSection("loadTerms");

	if (IsSet($terms)){
		$fmt->h3($terms->getHeader(1)."s and ".$terms->getHeader(2)."s");		
	} else {
		$fmt->h3("Terms and Definitions");		
	}

	$fmt->startDivClass("termInfo");	
	if (IsSet($terms)){
		$numterms = count($terms->getTerms());
		$fmt->h5("Current file information");
		$fmt->startP();
		switch($terms->getTermsType()){
			case TERMS_AUTO:
				$fmt->write("The current terms were generated by the system under the name: \"".$terms->getFilename()."\".");
				break;
			case TERMS_FILE:
				$fmt->write("\"" . $terms->getFilename() . "\" is the currently loaded file.");
				break;
			case TERMS_MANUAL:
				$fmt->write("The current set of terms was entered manually. You can edit them using the button below.");
				break;
			default:
				$fmt->write("The current terms type is unavailable or not set.");
				break;
		}
		$squaresize = sqrt($numterms);
		if( isValidSquareSize($squaresize)){
			$quiz->magicSquareSize = intval($squaresize);
			$fmt->write("There are " . $numterms . " items, which will yield a " . $quiz->magicSquareSize . "x" . $quiz->magicSquareSize . " magic square.");
		} else {
			$quiz->magicSquareSize = 0;
			$fmt->write("Based on this set, I cannot generate a magic square for you. Only odd sized squares of 3, 5, 7 or 9 are currently supported, therefore I need an input file with 9, 25, 49 or 81 items in it.");
		}
		if (!isAppleDevice()){
			$fmt->write("To replace this set of items, load a custom file using the button below. You can also enter a set of terms manually using the button below, or just generate a new set of sample terms.");
		}
		$fmt->endP();
	}
	if (!isAppleDevice()){
		$fmt->h5("Load a new input file");
		$loadfile = LOAD_FILE;
		$fmt->write("<form method=\"POST\" action=\"$alias\" enctype=\"multipart/form-data\">");
		$fmt->write("<input type=\"file\" name=\"file\" class=\"fancyButton loadTermsButton\">");
		$fmt->brk();
		$fmt->write("<input type=\"submit\" name=\"submit\" value=\"Choose Input File and Click Here to Upload\" class=\"fancyButton loadTermsButton\">");
		$fmt->write("<input type=\"hidden\" name=\"type\" value=\"$loadfile\">");
		$fmt->write("</form>");		
		$fmt->brk(2);
	} else {
		$fmt->write("Uploading files with iOS devices is not supported at this time. Manually enter using the following button, or use the Generate buttons below to test.");		
	}

	$fmt->h5("Enter input data manually");
	$fmt->p("You can enter the data manually (or edit previously entered data) by using the following button to display a text area.");
	$fmt->linkbutton("uploadmanual.php", "Enter Using Text Box",NULL,"fancyButton loadTermsButton");


	$fmt->endDiv();
	$fmt->endSection();
	
	if ($quiz->magicSquareSize > 0 && IsSet($terms)){

		$fmt->startSection("selectOptions");

		$optionCounter = 1;		
		$divOptionOddEven = array("quizOptionOdd","quizOptionEven");
		$divMainLeft = "quizOptionLeft";
		$divMainRight = "quizOptionRight";
		
		$fmt->startDivClass("quizForm");

		$fmt->h3("Provide details for the handouts, and then click Generate Quiz Data.");
		
	     // construct the form, and then the outer table that will hold the field definitions
		$fmt->write("<form method=\"POST\" action=\"makequiz.php\">");
		//$fmt->write("")

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
	    $fmt->write("<input type=\"number\" min=\"1\" max=\"99\" name=\"variants\" value=\"$variants\"><br /><br />");
	
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
				$fmt->write("Based on the currently loaded set, the free term will be \"".$termlist[$freeterm-1]->getTerm() . "\".");				
			} else{
				$fmt->write("The currently selected free term is not within the limits of the current set.");
			}
		} else {
			$fmt->write("There will be no free term in the output PDFs.");		
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
		$fmt->write("<input class=\"fancyButton genQuizButton\" type=\"submit\" value=\"Generate Quiz Data\">");
		$fmt->endP();
		$fmt->write("</form>");

		$fmt->endDiv();			// quizForm end div

		$fmt->endSection();
	}

	$fmt->startSection("helpers");
	
	$hiddenVars = array( 
		array("type" => MAKE_TERMS, "size" => 3),
		array("type" => MAKE_TERMS, "size" => 5),
		array("type" => MAKE_TERMS, "size" => 7),
		array("type" => RESET),
	);
	$fmt->linkbutton($alias, "Generate 3x3 Term Set",NULL,"fancyButton helperButton",$hiddenVars[0]);
	$fmt->linkbutton($alias, "Generate 5x5 Term Set",NULL,"fancyButton helperButton",$hiddenVars[1]);
	$fmt->linkbutton($alias, "Generate 7x7 Term Set",NULL,"fancyButton helperButton",$hiddenVars[2]);
	$fmt->linkbutton($alias, "Reset and Start Over",NULL,"fancyButton helperButton",$hiddenVars[3]);
	$fmt->linkbutton($alias, "Refresh This Page",NULL,"fancyButton helperButton");
	$fmt->linkbutton("mshelp.php", "Help",NULL,"fancyButton helperButton");

	$fmt->endSection();
}
?>
