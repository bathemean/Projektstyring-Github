<?php

	function unixTimezoneConvert($time, $zone) {

		$dt = date_create("@$time"); 
	    date_timezone_set($dt, timezone_open($zone));
	    $adjusted_timestamp = date_format($dt, 'U') + date_offset_get($dt);

	    return $adjusted_timestamp;

	}

	/**
	* Auther: elusive (http://stackoverflow.com/questions/4128323/in-array-and-multidimensional-array)
	**/
	function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}

?>