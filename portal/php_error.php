<?php

// php_error library
include(
	\psm\Paths::getLocal('portal').DIR_SEP.
	'debuggers'.DIR_SEP.
	'php_error.php'
);
if(\function_exists('php_error\reportErrors')) {
	$reportErrors = '\php_error\reportErrors';
	$reportErrors(array(
		'catch_ajax_errors'      => TRUE,
		'catch_supressed_errors' => FALSE,
		'catch_class_not_found'  => FALSE,
		'snippet_num_lines'      => 11,
		'application_root'       => __DIR__,
		'background_text'        => 'PSM',
	));
	//unset($reportErrors);
}

?>