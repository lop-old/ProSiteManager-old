<?php

// debug mode
define('psm\\DEBUG', TRUE);

//define('psm\\DEFAULT_MODULE', 'testportal');
//define('psm\\DEFAULT_PAGE',   'home');

// load the portal
include(__DIR__.'/psm/Loader.php');
\psm\Portal::SimpleLoad();
//$portal = \psm\Portal::factory();

?>