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
		$text = "


== Header1 ==
=== Header2 ===
==== Header3 ====
===== Header4 =====
====== Header5 ======



'''This will be bold''' this wont


''how bout some italic'' how bout not

**strong**

__this is underline__ this is not underline

<b>bold1</b>
'''bold2'''

;asfdaf
:sdgdfhgdfh
''sdagsdfgdfgh''
'''dfgdsfg'''
asf

 indent
 dsfgdfgh
 sdgdfh

stgdfdhg
				[[link]]
				[http://google.com/ google.com]
	asdgfsdgsfdgdsfg
	sdgdsfgdfsghdfsgh
		
{| border=\"1\" cellspacing=\"0\" cellpadding=\"2\"
|-
!scope=\"col\"| Item
!scope=\"col\"| Quantity
!scope=\"col\"| Price
|-
!scope=\"row\"| Bread
| 0.3 kg
| $0.65
|-
!scope=\"row\"| Butter
| 0.125 kg
| $1.25
|-
!scope=\"row\" colspan=\"2\"| Total
| $1.90
|}



sdgsgdfgdsfgh

__something__
--else--


";
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