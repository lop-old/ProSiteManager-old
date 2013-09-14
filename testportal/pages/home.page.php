<?php namespace testportal\Pages;
global $ClassCount; $ClassCount++;
class page_home extends \psm\Portal\Page {


	public function Render() {
		return 'home page';
	}


	public function Action($action) {
	}


}
?>