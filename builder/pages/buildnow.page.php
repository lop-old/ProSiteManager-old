<?php namespace BuildServ\Pages;
global $ClassCount; $ClassCount++;
class page_buildnow extends \psm\Portal\Page {


	public function Render() {
		return '


<script type="text/javascript" language="javascript"><!-- //
function frameGo(loc) {
	document.getElementById(\'buildframe\').src = loc;
	return false;
}
// --></script>


<!-- <a href="./?action=buildnow" class="btn btn-primary btn-large">Build Now..</a> -->
<a href="#" onClick="return frameGo(\'./?page=buildnow&action=buildnow\');" class="btn btn-primary btn-large">Build Now..</a>


<iframe id="buildframe" src="http://duckduckgo.com/" style="
	margin-left: auto;
	margin-right: auto;
	margin-top: 10px;
	width: 100%;
	height: 60%;
	border-width: 1px;
	border-style: solid;
	border-color: black;
	background-color: #dddddd;
" scrolling="yes">Not supported, sorry..</iframe></div>


<!-- <div id="builddiv" style="width: 500px; height: 400px; background-color: blue;"></div> -->


';
	}


	public function Action($action) {
echo $action;
exit();
//		if($action == 'buildnow') {
//echo 'building now...';
//exit();
//		}
	}


}
?>