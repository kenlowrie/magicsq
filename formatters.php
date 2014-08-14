<?php

function printMagicSquare($ms){
	
}

class cHTMLFormatter{
	public $justPrint=true;
	
	public function _print($outinfo){
		if($this->justPrint) print($outinfo);
		return $outinfo;
	}
	public function hr(){
		return $this->_print("<hr />");
	}
	public function brk(){
		return $this->_print("<br />");
	}
	public function h1($text){
		return $this->_print("<h1>". $text . "</h1>\n");
	}
	public function h2($text){
		return $this->_print("<h2>". $text . "</h2>\n");
	}
	public function h3($text){
		return $this->_print("<h3>". $text . "</h3>\n");
	}
	public function h4($text){
		return $this->_print("<h4>". $text . "</h4>\n");
	}
	public function p($text, $class=NULL){
		$element = IsSet($class) ? "<p class=\"$class\">" : "<p>";
		return $this->_print($element . $text . "</p>\r\n");
	}
	public function write($text,$newline=true,$autobreak=false){
		$curtext = $this->_print($text);
		if ($newline) return $curtext .= $this->_print($autobreak ? "<br />" : "" . "\n");
		//if ($newline) print("\n");
		return $curtext;
	}
	public function writeAndBreak($text){
		return $this->_print($text . "<br />\n");
	}
	public function addLink($page,$text,$blank=false){
		if(!$blank){
			return $this->_print("<a href=\"$page\">$text</a>");			
		} else{
			return $this->_print("<a href=\"$page\" target=\"_blank\">$text</a>");			
		}
	}

	public function linkbutton($action, $text, $help=NULL, $method="POST", $type="submit", $name="name", $newwin=false)
	{
		if ($newwin){
			$curtext = $this->_print("<form method=\"$method\" action=\"$action\" target=\"_blank\">\n");		
		} else {
			$curtext = $this->_print("<form method=\"$method\" action=\"$action\">\n");					
		}
		$curtext .= $this->_print("  <input type=\"$type\" name=\"$name\" value=\"$text\">\n");
		$curtext .= $this->_print("</form>\n");
		$curtext .= $this->_print($help."\n");	
		return $curtext;	
	}
	
	public function startDiv($id){
		return $this->_print("<div id=\"$id\">\n");
	}
	public function startDivs($array_of_divs){
		$curtext = "";
		foreach ($array_of_divs as $div) {
			$curtext .= $this->startDiv($div);
		}
		return $curtext;
	}
	public function endDiv($count=1){
		$curtext = "";
		while ($count--){
			$curtext .= $this->_print("</div>\n");		
		}
		return $curtext;
	}

	public function startTable(){
		return $this->_print("  <table>\n");
	}
	public function endTable(){
		return $this->_print("  </table>\n");
	}

	public function startRow(){
		return $this->_print("    <tr>\n");
	}
	public function endRow(){
		return $this->_print("    </tr>\n");
	}

	public function startCell($class){
		if ($class != ""){
			return $this->_print("      <td class=\"$class\">");
		} else {
			return $this->_print("      <td>");		
		}
	}
	public function endCell(){
		return $this->_print("</td>\n");
	}
	public function writeRawData($format_string, $newline=true, $autobreak=false){
		$curtext = "";
		if( func_num_args() == 1){
			$curtext .= $this->_print($format_string);
		} else {
			$args = func_get_args();
			array_shift($args);
			$tmp = vsprintf($format_string, $args);
			$curtext .= $this->_print($tmp);	
		}
		if ($newline) $curtext .= $this->_print($autobreak ? "<br />" : "" . "\n");
		return $curtext;
	}
	public function writeCellData($format_string)
	{
		$curtext = $this->startCell("");
		if( func_num_args() == 1){
			$curtext .= $this->_print($format_string);
		} else {
			$args = func_get_args();
			array_shift($args);
			$tmp = vsprintf($format_string, $args);
			$curtext .= $this->_print($tmp);
		}
		$curtext .= $this->endCell();
		return $curtext;
	}
	public function writeClassData($class, $format_string)
	{
		$curtext = $this->startCell($class);
		if( func_num_args() == 2){
			$curtext .= $this->_print($format_string);
		} else {
			//TODO: This is an error if called with 1 arg...
			$args = func_get_args();
			array_shift($args);			// EAT $class
			array_shift($args);			// EAT $format_string
			$tmp = vsprintf($format_string, $args);
			$curtext .= $this->_print($tmp);
		}
		$curtext .= $this->endCell();
		return $curtext;
	}
}

?>
