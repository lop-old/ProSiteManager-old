<?php namespace wa\html;
global $ClassCount; $ClassCount++;
use \psm\Portal as Portal;
use \psm\Widgets as Widgets;
class html_main extends \psm\html\tplFile_Main {

	private $mainMenu;
	private $subMenu;


	/**
	 * html header
	 *
	 * @internal {site title}
	 * @internal {css}
	 * @internal {add to header}
	 * @return string
	 */
	protected function _head() {

		// main menu
		$this->mainMenu = Widgets\Widget_NavBar::factory()
		->setSelected(Portal::getModName())
		->setBrand(Portal::getModObj()->getModTitleHtml())
		->addBreak()
		->addButton('',			'Home',				Portal\URL::factory()->setRawURL('/'),			'icon-home')
		->addButton('wa',		'WebAuction',		Portal\URL::factory()->setMod('wa'),			'icon-shopping-cart')
		->addButton('wb',		'WeBook',			Portal\URL::factory()->setMod('wb'),			'icon-book')
		->addButton('wiki',		'Wiki',				Portal\URL::factory()->setPage('wiki'),			'icon-align-justify',	TRUE)
		->addDropdown('profile','lorenzop',			Portal\URL::factory(),							'icon-user',			TRUE)
		;
		// sub menu
		$this->subMenu  = Widgets\Widget_NavBar::factory()
		->setSelected(Portal::getPage())
		->addButton('current',	'Current Sales',	Portal\URL::factory()->setPage('current'),		'icon-home')
		->addButton('myshop',	'My Shop',			Portal\URL::factory()->setPage('myshop'),		'icon-shopping-cart')
		->addButton('mymailbox','My Mailbox',		Portal\URL::factory()->setPage('mymailbox'),	'icon-envelope')
		;

//Portal::getPortal()->getEngine()->addCss('
//');
		// css
		self::addFileCSS(
			'{path=static}bootstrap/Cerulean/bootstrap.min.css',
			//'{path=static}bootstrap/css/bootstrap.min.css',
			'{path=static}bootstrap/css/bootstrap-responsive.min.css',
			'{path=theme}main.css'
		);
		// javascript
		self::addFileJS_top(
//			'{path=static}inputfunc.js'
			'{path=static}jquery/jquery-1.8.3.min.js',
			'{path=static}bootstrap/js/bootstrap.min.js'
		);
		// custom css/js
//		$this->addFileCSS_ifExists('{path=theme}custom.css');
//		$this->addFileJS_ifExists ('{path=theme}custom.js');
		return
'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>{title}</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="icon" type="image/x-icon" href="{path=static}favicon.ico" />

{header content}

</head>
<body>
';
	}


	protected function _body() {
		return '

'.$this->mainMenu->Render().'
<div id="page-wrap">
'.$this->subMenu->Render(TRUE).'
<div class="container">


<!--
<div class="alert alert-block alert-info" style="margin-bottom: 0px;">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
	<strong>Warning!</strong> Some error!
</div>
<div class="alert alert-block alert-success" style="margin-bottom: 0px;">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
	<strong>Warning!</strong> Some error!
</div>
<div class="alert alert-block alert-warning" style="margin-bottom: 0px;">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
	<strong>Warning!</strong> Some error!
</div>
<div class="alert alert-block alert-error" style="margin-bottom: 0px;">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
	<strong>Warning!</strong> Some error!
</div>
-->

<!--
<div class="alert alert-block alert-error" style="margin-bottom: 0px;">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
	<strong>Warning!</strong> Some error!
</div>
-->








{page content}

</div>
';
	}


	protected function _foot() {
$num_queries=3;
		return '
	<div id="footer-push"></div>
</div>
<footer id="footer">
	<div class="container">
		<table id="footer-table">
		<tr>
			<td class="footer-td-1">'.
				'Rendered page in '.Portal::GetRenderTime().' Seconds<br />'.
				'with '.((int)@$num_queries).' Queries and {class count} Classes</b>'.
			'</td>
			<td class="footer-td-2">


<!-- Paste advert code here -->
<!--                        -->
<!--                        -->
<!-- ====================== -->


{footer content}

{footer copyright}

			</td>
			<td class="footer-td-3">'.
				'<a href="http://twitter.github.com/bootstrap/" target="_blank">'.
				'<img src="{path=static}bootstrap-logo-128.png" alt="Powered by Twitter Bootstrap" style="width: 32px; height: 32px;" /></a>'.
//				'&nbsp;&nbsp;'.
//				'<a href="http://validator.w3.org/#validate_by_input" target="_blank">'.
//				'<img src="{path=static}valid-xhtml10.png" alt="Valid XHTML 1.0 Transitional" style="width: 88px; height: 31px;" /></a>'.
			'</td>
		</tr>
		</table>
	</div>
</footer>
';
	}


}
?>