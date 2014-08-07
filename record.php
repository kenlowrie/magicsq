<?php

include_once ('session.php');

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
          $str = "Enter the title of the handout. This will be printed at the top of the student handout.";
          break;
     case 'mssize':
          $str = "Enter the magic square size. Currently, only odd sizes of 3x3, 5x5, 7x7 and 9x9 are supported.";
          break;
     case 'variants':
          $str = "Enter the number of variants that you want. For each variant, a new magic square is generated, and the list of terms is jumbled.";
          break;
     case 'filename':
          $str = "Select the input file from your computer and press submit. This must be a valid CSV file, with either .CSV or .TXT as the file extension. Each line requires two comma separated items, a term and its definition. e.g. \"My Term\", \"My Definition.\"";
          break;
     case 'freeterm':
          $str = "Enter the line number of the free term in the file that is/will be uploaded. This term will be solved automatically in the output to assist the student in understanding how to fill out the answer sheet. Enter zero (0) for no freebies!";
          break;
     default:
          $str = NULL;
     }
     return $str;
}

function writeTermFileLoader(){
	print("      <form method=\"POST\" action=\"uploadterms.php\" enctype=\"multipart/form-data\">");
	//print("        <label for=\"file\">Filename:</label>");
	print("        <input type=\"file\" name=\"file\" id=\"file\"><br />");
	print("        <input type=\"submit\" name=\"submit\" value=\"Submit\">");
	print("      </form>");
	$terms = getTerms();
	if (IsSet($terms)){
		print("<p>Currently loaded terms file: " . $terms->getFilename() . " with [" . count($terms->getTerms()) . "] terms loaded.</p>\n");
	}
}

/*
** Add a field to the form. Here is how everything is interpreted:
**
** $column is the name of the column (field). It will also be the _POST variable name.
** $value is the default or current value to display.
** $maxlen is the maximum number of characters that can be typed into a text field
** $popdown is NULL if this is a text field, otherwise it is a | separated list of values for a popdown menu.
** if $help is 1 then perform a lookup of the help string.
** if $help is NULL if no help should be displayed
** if $help is the help string for this field.
**
** this function prints one entire table row
*/

function add_field($row_name, $variable, $type, $value, $minlen, $maxlen, $popdown, $help=NULL)
{
     // get a friendly upper cased first letter version of the variable for display
    print("  <tr>\r\n");
	print("    <td width=\"100\" align=\"right\"><strong>$row_name</strong></td>");
	
	switch($type){
		case 'text':
		    print("    <td width=\"300\"><input type=text size=\"50\" maxlength=\"$maxlen\" name=$variable value=\"$value\"></td>\r\n");
			break;
		case 'select':
    		  	// this is a popdown menu, construct the select statement for it by
          	// breaking down the | separated list of values. take note of each
          	// value to see if it matches $value, and then have it selected by
          	// default.     
	     	print("    <td width=\"300\">");
			print("<select name=$variable>");
    	     	$option = strtok($popdown,'|');
			while($option){
				if ($option == $value)
					print("<option selected=\"selected\">$option</option>");
				else
					print("<option>$option</option>");
				$option = strtok("|");
			}
			print("</select></td>\r\n");
			break;
		case 'number':
		    print("    <td width=\"300\"><input type=number min=\"$minlen\" max=\"$maxlen\" name=$variable value=\"$value\"></td>\r\n");
			break;
		case 'file':
		    print("    <td width=\"300\">");
			writeTermFileLoader();
		    print("</td>\r\n");
			break;
		default:
			break;
	}
    // if $help is 1 then go do a lookup for the help string
    if($help == 1) {
    		$helpstr = get_help($variable);
        if ($helpstr) print("<td>$helpstr</td>\r\n");
    }
    elseif ($help != NULL)  { // if not NULL, then user is specifying the string
        print("<td>$help</td>\r\n");
     }
     print("  </tr>\r\n");
}

/*
** this is the generic function that displays the edit, add or delete form. the parameters are:
**
** $alias - the name of the script that will process the form
** $modtype - the string describing what we are doing: editing, deleting, adding ...
** $uid - the unique ID of the record being edited or deleted
** $record - a standard record array containing all the fields for the record
** $srow - the start_row value to pass to the display script
** $commit_id - the edit type that will be passed to the script processor so it knows what to do
** $showhelp - whether or not the caller wants the help strings displayed on the form
*/
function modify_record($alias, $quiz, $commit_id, $showhelp=1)
{
	$baseurl = BASE_URL;
	$title  = $quiz->quizTitle;
	$mstype = $quiz->magicSquareSize;
	$variants = $quiz->variants;
	$freeterm = $quiz->freeTerm;

     // if no help is displayed, use a smaller table
     if ($showhelp)
          $tablesize = 800;
     else
          $tablesize = 400;

     // construct the form, and then the outer table that will hold the field definitions
	print("<form method=\"POST\" action=\"$alias?type=$commit_id\">\r\n");

	print("<table width=\"$tablesize\" border=\"1\" cellpadding=\"1\" cellspacing=\"0\">");
	print("<tr><td colspan=2 align=\"center\"><strong>Magic Square Quiz Maker</strong></td>");
     if ($showhelp)
     {
          print("<td align=\"center\"><strong>Description</strong></td>\r\n");
     }
     print("</tr>\r\n");

     // now add one row per field value
     add_field('Title', 				'title',		"text", 		$title,		0,	128,		NULL,			$showhelp);
     add_field('Magic Square Type', 	'mssize', 	"select", 	$mstype,		0,	31,		get_mstypes(),	$showhelp);
     add_field('How many variants?', 'variants',	"number", 	$variants,	1,	9,		NULL,			$showhelp);
     add_field('Terms File Name', 	'filename',	"file", 		$inputfile,	0,	31,		NULL,			$showhelp);
     add_field('Free Term', 			'freeterm', "number", 	$freeterm,	0,	31,		NULL,			$showhelp);

     print("</table><br>\r\n");

     // now do another table to hold the buttons for the action or cancel function of the form
     print("<table>\r\n");
	 print("  <tr valign=\"top\">\r\n");
	 print("    <td>\r\n");
	 print("      <input type=submit value=\"Generate Quiz Data\">\r\n");
	 print("      </form>\r\n");
	 print("    </td>\r\n");

	$reset_id = MAKE_TERMS;
	 print("    <td>");
	 print("		  <form method=\"POST\" action=\"$alias?type=$reset_id\">\r\n");
	 print("        <input type=\"submit\" name=\"terms\" value=\"Generate Sample Terms\">");
	 print("      </form>");
	 print("    </td>");

	$reset_id = RESET;
	 print("    <td>");
	 print("		  <form method=\"POST\" action=\"$alias?type=$reset_id\">\r\n");
	 print("        <input type=\"submit\" name=\"reset\" value=\"Reset Session Data\">");
	 print("      </form>");
	 print("    </td>");
     print("  </tr>");
	 print("</table>\r\n");
	 
}
/*
<form action="upload_file.php" method="post"
enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="file" id="file"><br>
<input type="submit" name="submit" value="Submit">
</form>
*/
?>
