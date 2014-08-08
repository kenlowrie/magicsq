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
     default:
          $str = NULL;
     }
     return $str;
}


function display_quiz_form($alias, $quiz, $commit_id)
{
	$title  = $quiz->quizTitle;
	$mstype = $quiz->magicSquareSize;
	$variants = $quiz->variants;
	$freeterm = $quiz->freeTerm;

	$fmt = new cHTMLFormatter;
	
	$fmt->startDiv("step1");

	$fmt->h3("Step 1 - Load input file with terms and definitions");
	
	$terms = getTerms();
	if (IsSet($terms)){
		$fmt->p("\"" . $terms->getFilename() . "\" with [" . count($terms->getTerms()) . "] terms is currently loaded.");
		$fmt->p("To replace these terms, just load another file using the buttons below, otherwise continue with Step 2...");
	}

	$loadfile = LOAD_FILE;
	print("      <form method=\"POST\" action=\"$alias?type=$loadfile\" enctype=\"multipart/form-data\">\n");
	//print("        <label for=\"file\">Filename:</label>");
	print("        <input type=\"file\" name=\"file\" id=\"file\">\n");
	print("        <input type=\"submit\" name=\"submit\" value=\"Submit\">\n");
	print("      </form>\r\n");

	$fmt->endDiv();
		
	$fmt->h3("Step 2 - Provide details for the handouts, or if everything is okay as-is, continue with Step 3...");
	
     // construct the form, and then the outer table that will hold the field definitions
	print("<form method=\"POST\" action=\"$alias?type=$commit_id\">\n");

	$fmt->startDiv("clearboth");		//TODO: Need to make this happen automatically. Can I add to Left??
	$fmt->startDiv("left");
	
	$fmt->h3("Handout Title");
	
    print("<input type=text size=\"25\" maxlength=\"128\" name=\"title\" value=\"$title\"><br /><br />\n");

	$fmt->endDiv();
	$fmt->startDiv("right");

    	$helpstr = get_help("title");
    if ($helpstr) print("$helpstr<br />\r\n");
	$fmt->endDiv();
	$fmt->endDiv();


	$fmt->startDiv("clearboth");		//TODO: Need to make this happen automatically. Can I add to Left??
	$fmt->startDiv("left");
		
	$fmt->h3("Size of Magic Square");

	print("<select name=\"mssize\"");
	$popdown = get_mstypes();
 	$option = strtok($popdown,'|');
	while($option){
		if ($option == $value)
			print("<option selected=\"selected\">$option</option>");
		else
			print("<option>$option</option>");
		$option = strtok("|");
	}
	print("</select><br /><br />\n");

	$fmt->endDiv();
	$fmt->startDiv("right");

    	$helpstr = get_help("mssize");
    if ($helpstr) print("$helpstr<br />\n");

	$fmt->endDiv();
	$fmt->endDiv();


	$fmt->startDiv("clearboth");		//TODO: Need to make this happen automatically. Can I add to Left??
	$fmt->startDiv("left");
		
	$fmt->h3("Number of variants");
    print("<input type=\"number\" min=\"1\" max=\"9\" name=\"variants\" value=\"$variants\"><br /><br />\n");

	$fmt->endDiv();
	$fmt->startDiv("right");

    	$helpstr = get_help("variants");
    if ($helpstr) print("$helpstr<br />\n");

	$fmt->endDiv();
	$fmt->endDiv();


	$fmt->startDiv("clearboth");		//TODO: Need to make this happen automatically. Can I add to Left??
	$fmt->startDiv("left");
		
	$fmt->h3("Free Term");
    print("<input type=number min=\"0\" max=\"25\" name=\"freeterm\" value=\"$freeterm\"><br /><br />\n");

	$fmt->endDiv();
	$fmt->startDiv("right");

    	$helpstr = get_help("freeterm");
    if ($helpstr) print("$helpstr<br />\n");
	
	$fmt->brk();
	$fmt->endDiv();
	$fmt->endDiv();

	print("<input type=submit value=\"Generate Quiz Data\">\n");
	print("</form><br /><br />\r\n");

	$fmt->h3("Step 3 - Use the buttons below as needed.");
	
	$reset_id = MAKE_TERMS;
	print("		  <form method=\"POST\" action=\"$alias?type=$reset_id\">\n");
	print("        <input type=\"submit\" name=\"terms\" value=\"Generate Sample Terms\">\n");
	print("      </form><br /><br />\n");

	$reset_id = MAKE_TERMS;
	print("		  <form method=\"POST\" action=\"displayterms.php\">\n");
	print("        <input type=\"submit\" name=\"terms\" value=\"Display Current Terms\">\n");
	print("      </form><br /><br />");

	$reset_id = RESET;
	print("		  <form method=\"POST\" action=\"$alias?type=$reset_id\">\n");
	print("        <input type=\"submit\" name=\"reset\" value=\"Reset Session Data\">\n");
	print("      </form><br />\n");
	 
}
?>
