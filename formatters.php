<?php
// This file is part of the Magic Square Quiz Maker (magicsq) application.
// Copyright (C) 2014-2016 Ken Lowrie
//
// magicsq is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// magicsq is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// See LICENSE.TXT for more information.   

function printMagicSquare($ms){
	
}

class cHTMLFormatter{
	public $justPrint=true;
	protected $prefix="    ";
	
	protected function prefixDec(){
		$this->prefix = substr($this->prefix,0,-2);
	}
	protected function prefixInc(){
		$this->prefix .= "  ";
	}
	
	public function _print($outinfo,$incAfter=false,$prefix=true){
		$retStr = ($prefix ? $this->prefix : "") . $outinfo;
		if($this->justPrint) print($retStr);
		if($incAfter) $this->prefixInc();
		
		return $retStr;
	}
	public function hr(){
		return $this->_print("<hr />\n");
	}
	public function brk($howMany=1){
		$retStr = "";
		for(;$howMany--;){
			$retStr .= $this->_print("<br />\n");
		}
		return $retStr;
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
	public function h5($text){
		return $this->_print("<h5>". $text . "</h5>\n");
	}
	public function h6($text){
		return $this->_print("<h6>". $text . "</h6>\n");
	}
	public function startP($class=NULL){
		$element = IsSet($class) ? "<p class=\"$class\">" : "<p>";
		return $this->_print($element);
	}
	public function endP(){
		return $this->_print("</p>");
	}
	public function p($text, $class=NULL){
		$element = IsSet($class) ? "<p class=\"$class\">" : "<p>";
		return $this->_print($element . $text . "</p>\r\n");
	}
	public function write($text,$newline=true,$autobreak=false){
		$curtext = $this->_print($text);
		if ($newline) return $curtext .= $this->_print(($autobreak ? "<br />" : "") . "\n",false,false);
		//if ($newline) print("\n");
		return $curtext;
	}
	public function writeAndBreak($text){
		return $this->_print($text . "<br />\n");
	}
	public function anchor($id){
			return $this->_print("<a id=\"$id\"></a>");			
	}
	public function addLink($page,$text,$blank=false){
		if(!$blank){
			return $this->_print("<a href=\"$page\">$text</a>");			
		} else{
			return $this->_print("<a href=\"$page\" target=\"_blank\">$text</a>");			
		}
	}
	public function startLink($page){
		return $this->_print("<a href=\"$page\">");			
	}
	public function endLink(){
		return $this->_print("</a>");			
	}

	public function linkbutton($action, $text, $help=NULL, $buttonClass=NULL, $hiddenVars=NULL, $method="POST", $type="submit", $name="name", $newwin=false)
	{
		if ($newwin){
			$curtext = $this->_print("<form method=\"$method\" action=\"$action\" target=\"_blank\">\n");		
		} else {
			$curtext = $this->_print("<form method=\"$method\" action=\"$action\">\n");					
		}
		if (!IsSet($buttonClass)){
			$btnClass="";
		} else {
			$btnClass=" class=\"".$buttonClass."\"";
		}
		$curtext .= $this->_print("  <input type=\"$type\" name=\"$name\" value=\"$text\"$btnClass>\n");
		if (IsSet($hiddenVars)){
			foreach ($hiddenVars as $var => $value) {
				$curtext .= $this->_print("  <input type=\"hidden\" name=\"$var\" value=\"$value\">\n");
			}
		}
		$curtext .= $this->_print("</form>\n");
		$curtext .= $this->_print($help."\n");	
		return $curtext;	
	}
	public function svg($svgImage,$svgTitle="SVG Image",$svgWidth=16,$svgHeight=16){
		return $this->_print("<svg><image width=\"$svgWidth\" height=\"$svgHeight\" xlink:href=\"$svgImage\"></image><title>$svgTitle</title></svg>\n");
	}
	public function startNoScript(){
		return $this->_print("<noscript>\n",true);
	}
	public function endNoScript(){
		$this->prefixDec();
		return $this->_print("</noscript>\n");
	}
	
	public function startHeader($id="mainHeader"){
		return $this->_print("<header id=\"".$id."\">\n",true);
	}
	public function endHeader(){
		$this->prefixDec();
		return $this->_print("</header>\n");
	}
	
	public function startSection($id){
		return $this->_print("<section id=\"".$id."\">\n",true);
	}
	public function endSection(){
		$this->prefixDec();
		return $this->_print("</section>\n");
	}
	
	public function startArticle($id){
		return $this->_print("<article id=\"".$id."\">\n",true);
	}
	public function endArticle(){
		$this->prefixDec();
		return $this->_print("</article>\n");
	}
	
	public function startAside($id){
		return $this->_print("<aside id=\"".$id."\">\n",true);
	}
	public function endAside(){
		$this->prefixDec();
		return $this->_print("</aside>\n");
	}
	
	public function getTitleAttr($title){
		if(IsSet($title)){
			return " title=\"$title\"";
		}
		return "";
	}
	
	public function startDiv($id,$title=null){
		if(IsSet($title)){
			return $this->_print("<div id=\"$id\" title=\"$title\">\n",true);
		}
		return $this->_print("<div id=\"$id\">\n",true);
	}
	public function startDivClass($class){
		return $this->_print("<div class=\"$class\">\n",true);
	}
	public function startDivOfClass($id, $class){
		return $this->_print("<div id=\"$id\" class=\"$class\">\n",true);
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
			$this->prefixDec();
			$curtext .= $this->_print("</div>\n");		
		}
		return $curtext;
	}

	public function startTable(){
		return $this->_print("<table>\n",true);
	}
	public function endTable(){
		$this->prefixDec();
		return $this->_print("</table>\n");
	}
	public function startTHead(){
		return $this->_print("<thead>\n",true);		
	}
	public function endTHead(){
		$this->prefixDec();
		return $this->_print("</thead>\n");		
	}

	public function startTBody(){
		return $this->_print("<tbody>\n",true);		
	}
	public function endTBody(){
		$this->prefixDec();
		return $this->_print("</tbody>\n");		
	}

	public function startRow(){
		return $this->_print("<tr>\n",true);
	}
	public function endRow(){
			$this->prefixDec();
		return $this->_print("</tr>\n");
	}

	public function startScript(){
		return $this->_print("<script>\n",true);
	}
	public function endScript(){
			$this->prefixDec();
		return $this->_print("</script>\n");
	}

	public function startCell($class){
		if ($class != ""){
			return $this->_print("<td class=\"$class\">",true);
		} else {
			return $this->_print("<td>",true);		
		}
	}
	public function endCell(){
		$this->prefixDec();
		return $this->_print("</td>\n",false,false);
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
			$curtext .= $this->_print($format_string,false,false);
		} else {
			$args = func_get_args();
			array_shift($args);
			$tmp = vsprintf($format_string, $args);
			$curtext .= $this->_print($tmp,false,false);
		}
		$curtext .= $this->endCell();
		return $curtext;
	}
	public function writeClassData($class, $format_string)
	{
		$curtext = $this->startCell($class);
		if( func_num_args() == 2){
			$curtext .= $this->_print($format_string,false,false);
		} else {
			//TODO: This is an error if called with 1 arg...
			$args = func_get_args();
			array_shift($args);			// EAT $class
			array_shift($args);			// EAT $format_string
			$tmp = vsprintf($format_string, $args);
			$curtext .= $this->_print($tmp,false,false);
		}
		$curtext .= $this->endCell();
		return $curtext;
	}
	public function startList($which="ul"){
		return $this->_print("<" . $which . ">\n",true);
	}
	public function endList($which="ul"){
		$this->prefixDec();
		return $this->_print("</" . $which . ">\n");
	}
	public function writeListItem($item){
		return $this->_print("<li>" . $item . "</li>\n");
	}

	public function textArea($data,$name,$form,$rows,$cols){
		return $this->_print("<textarea name=\"" . $name . "\" form=\"". $form . "\" rows=\"" . $rows . "\" cols=\"" . $cols . "\">" . $data . "</textarea>\n");
	}

}

?>
