<?php

define('psm\DEBUG',          TRUE);
//define('psm\DEFAULT_MODULE', 'testportal');
//define('psm\DEFAULT_PAGE',   'home');

// load the portal
include(__DIR__.'/portal/Portal.php');
$portal = \psm\Portal::factory();

?>