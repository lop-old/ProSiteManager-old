<?php namespace psm;

define('DEFINE_INDEX_FILE',TRUE);
include('portal/index.php');

$psm = new portal();
$psm->addPortal('testportal');

?>