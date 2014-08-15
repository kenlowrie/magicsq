<?php

include_once ('session.php');
include ('uploadterms.php');

/*
** The common functions for handling the record forms are in this file.
*/

/*
** Returns the list of magic square types
*/
function get_mstypes()
{
	return '3x3|5x5|7x7|9x9';
}

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
          $str = "Enter the line number of the free term in the file that is/will be uploaded. This term will be solved automatically in the output to assist the student in understanding how to fill out the answer sheet. Enter zero (0) for no freebies!";
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

	$fmt->startDiv("loadterms");

	$fmt->h3("Load input file with terms and definitions");
	
	$terms = getTerms();
	if (IsSet($terms)){
		$numterms = count($terms->getTerms());
		$fmt->p("\"" . $terms->getFilename() . "\" with [" . $numterms . "] terms is currently loaded.");
		$squaresize = sqrt($numterms);
		if( isValidSquareSize($squaresize)){
			$quiz->magicSquareSize = intval($squaresize);
			$fmt->p("Based on this set of terms, you will have a " . $quiz->magicSquareSize . "x" . $quiz->magicSquareSize . " magic square.");
		} else {
			$quiz->magicSquareSize = 0;
			$fmt->p("Based on this term set, I cannot generate a magic square for you. Only odd sized squares of 3, 5, 7 or 9 are currently supported, therefore I need a terms file with 9, 25, 48 or 81 terms in it.");
		}
		$fmt->p("To replace these terms, just load another file using the button below.");
	}

	$loadfile = LOAD_FILE;
	$fmt->write("      <form method=\"POST\" action=\"$alias?type=$loadfile\" enctype=\"multipart/form-data\">");
	//$fmt->write("        <label for=\"file\">Filename:</label>");
	$fmt->write("        <input type=\"file\" name=\"file\" id=\"file\">");
	$fmt->write("        <input type=\"submit\" name=\"submit\" value=\"Load Terms\">");
	$fmt->write("      </form>");

	$fmt->brk();

	$fmt->endDiv();
		
	if (IsSet($terms)){

		$divs = array("clearboth","mainleft");
		
		$fmt->h3("Provide details for the handouts, and then click Generate Quiz Data.");
		
	     // construct the form, and then the outer table that will hold the field definitions
		$fmt->write("<form method=\"POST\" action=\"makequiz.php\">");
	
		$fmt->startDivs($divs);
		
		$fmt->h3("Handout Title");
		
	    $fmt->write("<input type=text size=\"25\" maxlength=\"128\" name=\"title\" value=\"$title\"><br /><br />");
	
		$fmt->endDiv();
		$fmt->startDiv("mainright");
	
	    	$helpstr = get_help("title");
	    if ($helpstr) $fmt->write("$helpstr<br />");
		$fmt->endDiv(2);
	
	
		$fmt->startDivs($divs);
			
		$fmt->h3("Number of variants");
	    $fmt->write("<input type=\"number\" min=\"1\" max=\"9\" name=\"variants\" value=\"$variants\"><br /><br />");
	
		$fmt->endDiv();
		$fmt->startDiv("mainright");
	
	    	$helpstr = get_help("variants");
	    if ($helpstr) $fmt->write("$helpstr<br />");
	
		$fmt->endDiv(2);
	
	
		$fmt->startDivs($divs);
			
		$fmt->h3("Free Term");
		//TODO: I need to set the maximum based on how many terms are in the term file...
		$maxterms = count($terms->getTerms());
	    $fmt->write("<input type=number min=\"0\" max=\"$maxterms\" name=\"freeterm\" value=\"$freeterm\"><br /><br />");

		$fmt->endDiv();
		$fmt->startDiv("mainright");
	
	    	$helpstr = get_help("freeterm");
	    if ($helpstr) $fmt->write("$helpstr");
		if ($freeterm > 0){
			if($freeterm < $maxterms){
				$termlist = $terms->getTerms();
				$fmt->write("The free term in the generated magic squares will be \"".$termlist[$freeterm-1]->getTerm() . "\".");				
			} else{
				$fmt->write("The currently selected free term is not within the limits of the current term set.");
			}
		} else {
			$fmt->write("There will be no free term given, probably because you're a mean teacher or you have mean students...");		
		}
		
		$fmt->brk();
		$fmt->endDiv(2);
	
		$fmt->startDivs(array("clearboth","mainall"));
		$fmt->write("<input type=submit value=\"Generate Quiz Data\">");
		$fmt->write("</form>");
		$fmt->write("Click this button to preview your squares and term sets.");
		$fmt->endDiv(2);
	}

	$fmt->startDivs(array("clearboth","mainall"));
		
	$fmt->h3("Other helpful options...");
	
	$fmt->linkbutton("$alias?type=".MAKE_TERMS."&size=3", "Generate 3x3 Sample Terms",get_help("genterms3"));
	$fmt->brk();
	
	$fmt->linkbutton("$alias?type=".MAKE_TERMS."&size=5", "Generate 5x5 Sample Terms",get_help("genterms5"));
	$fmt->brk();
	
	$fmt->linkbutton("$alias?type=".MAKE_TERMS."&size=7", "Generate 7x7 Sample Terms",get_help("genterms7"));
	$fmt->brk();
	
	$fmt->linkbutton("$alias?type=".RESET, "Reset Session Data",get_help("reset"));
	$fmt->brk();

	$fmt->endDiv(2);	 
}
?>
