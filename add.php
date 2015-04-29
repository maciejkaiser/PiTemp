<?php
	function __autoload($class) {
	  require "lib/{$class}.php";
	}
	// Use your login/password  and raspberry_pi_ip_address
	$db = new DB("mysql","pi_temp","localhost","root","password");
	$measure = new Temperature($db);
	$k = $measure->doMeasure("raspberry_pi_ip_address", "pi", "raspberry");
	$measure->insertMeasure($k["date"], $k["temperature"], $k["humidity"]);
?>