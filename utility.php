<?php

function MyLog($format_string)
{
	if( func_num_args() == 1){
		printf("%s\n<br />",$format_string);
		return;
	}
	
	$args = func_get_args();
	array_shift($args);
	$tmp = vsprintf($format_string, $args);
	
	printf("%s\n<br />",$tmp);
}

function MyPrint($format_string)
{
	if( func_num_args() == 1){
		printf("%s",$format_string);
		return;
	}
	
	$args = func_get_args();
	array_shift($args);
	$tmp = vsprintf($format_string, $args);
	
	printf("%s",$tmp);
}

?>
