<?php

function printMagicSquare($ms){
	
}

class cHTMLFormatter{
	public function hr(){
		print("<hr />");
	}
	public function brk(){
		print("<br />");
	}
	public function h1($text){
		print("<h1>". $text . "</h1>\n");
	}
	public function h2($text){
		print("<h2>". $text . "</h2>\n");
	}
	public function h3($text){
		print("<h3>". $text . "</h3>\n");
	}
	public function h4($text){
		print("<h4>". $text . "</h4>\n");
	}
	public function p($text, $class=NULL){
		$element = IsSet($class) ? "<p class=\"$class\">" : "<p>";
		print($element . $text . "</p>\r\n");
	}
	public function write($text){
		print($text);
	}
	public function addLink($page,$text){
		print("<a href=\"$page\">$text</a>");
	}
	public function startDiv($id){
		print("<div id=\"$id\">\n");
	}
	public function endDiv(){
		print("</div>\n");
	}

	public function startTable(){
		print("  <table>\n");
	}
	public function endTable(){
		print("  </table>\n");
	}

	public function startRow(){
		print("    <tr>\n");
	}
	public function endRow(){
		print("    </tr>\n");
	}

	public function startCell($class){
		if ($class != ""){
			print("      <td class=\"$class\">");
		} else {
			print("      <td>");		
		}
	}
	public function endCell(){
		print("</td>\n");
	}
	public function writeRawData($format_string){
		if( func_num_args() == 1){
			print($format_string);
		} else {
			$args = func_get_args();
			array_shift($args);
			$tmp = vsprintf($format_string, $args);
			print($tmp);	
		}
	}
	public function writeCellData($format_string)
	{
		$this->startCell("");
		if( func_num_args() == 1){
			print($format_string);
		} else {
			$args = func_get_args();
			array_shift($args);
			$tmp = vsprintf($format_string, $args);
			print($tmp);
		}
		$this->endCell();	
	}
	public function writeClassData($class, $format_string)
	{
		$this->startCell($class);
		if( func_num_args() == 2){
			print($format_string);
		} else {
			//TODO: This is an error if called with 1 arg...
			$args = func_get_args();
			array_shift($args);			// EAT $class
			array_shift($args);			// EAT $format_string
			$tmp = vsprintf($format_string, $args);
			print($tmp);
		}
		$this->endCell();
	}
}

?>
