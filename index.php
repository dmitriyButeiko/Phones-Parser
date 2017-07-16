<?php
    // Turn on errors and increase time execution time
    set_time_limit(100000);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Execute phones parsing
    require_once "includes/PhonesParser.php";			
    $phonesParser = new PhonesParser();
    $phonesParser->parseEmailData();
?>
