<?php namespace wa\Pages;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class page_wiki extends \psm\Portal\Page {

	const dbName = 'main';
	private $action = NULL;


	public function Render() {
		if($this->action == 'edit') {
			return $this->_edit();
		}
		$wiki = \psm\Widgets\Widget_Wiki::factory();


		$db = \psm\pxdb\dbPool::getDB(self::dbName);
		$sql = 'SELECT `topic_id`, `topic`, UNIX_TIMESTAMP(`timestamp`) AS `timestamp`, `text`, `last_editby` FROM `pxn_WikiPages` WHERE `topic` = ? LIMIT 1';
		$db->Prepare($sql);
//		$db->setString(1, 'ProSiteManager');
		$db->setString(1, 'test');
		$db->Exec();
		if($db->getRowCount() == 0 || !$db->hasNext())
			return 'Page not found!';
		$text = $db->getString('text');
		return $wiki->Render($text);
	}


	protected function Action($action) {
		if(empty($action)) return;
		if($action == 'edit')
			$this->action = 'edit';
	}


	// edit wiki page
	private function _edit() {
		return '';
	}


}
?>