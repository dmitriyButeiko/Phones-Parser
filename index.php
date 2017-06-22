<?php 
	// Turn on errors and increase time execution time
	set_time_limit(99999999);
	ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

	require_once "includes/PhonesParser.php";		
	
	$phonesParser = new PhonesParser();
	$phonesParser->parseEmailData();
?>