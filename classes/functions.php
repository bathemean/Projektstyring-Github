<?php

	function unixTimezoneConvert($time, $zone) {

		$dt = date_create("@$time"); 
	    date_timezone_set($dt, timezone_open($zone));
	    $adjusted_timestamp = date_format($dt, 'U') + date_offset_get($dt);

	    return $adjusted_timestamp;

	}

?>