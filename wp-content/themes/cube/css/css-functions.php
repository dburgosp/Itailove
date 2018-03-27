<?php

if(!function_exists('rem_calc')){
	function rem_calc($value) {
	  	if (is_numeric($value)){
	    	return $value / 16 . "rem";
	  	} else return $value;
	}
}


?>
