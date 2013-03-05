<?php namespace psm\html;
if(!defined('psm\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class BlockArray {

	private $title;
	private $blocks = array();
	private $prepend  = NULL;
	private $postpend = NULL;

	// temp output buffer (when not echoing)
	private $output = NULL;


	public function __construct($title) {
		$this->title = $title;
	}


	public function &Display($useEcho=TRUE) {
		$this->useEcho = $useEcho;
		if(!$useEcho)
			$this->output = '';
		// no blocks to display
		if(count($this->blocks) == 0) {
			$this->_display(
				NEWLINE.
				$this->_display_TitleLine().NEWLINE.
				$this->_display_Title().NEWLINE.
				$this->_display_TitleLine().NEWLINE.
				NEWLINE
			);
		// display blocks
		} else {
			$this->_display(
				NEWLINE.
				$this->_display_TitleLine().NEWLINE.
				$this->_display_Title().NEWLINE
			);
			// prepend
			if($this->prepend != NULL)
				$this->_display(
					$this->prepend.NEWLINE
				);
			// loop blocks
			$i = 0;
			foreach($this->blocks as &$data) {
				if(empty($data)) continue;
				$i++;
				$this->_display(
					($i==1 ? '' :
						NEWLINE.
						'<!-- ~~~~~~~~~~ -->'.
						NEWLINE).
					NEWLINE.
					$data.
					NEWLINE
				);
			}
			unset($block, $this->blocks);
			// postpend
			if($this->postpend != NULL)
				$this->_display(
					NEWLINE.$this->postpend
				);
			$this->_display(
				NEWLINE.
				$this->_display_Title().NEWLINE.
				$this->_display_TitleLine().NEWLINE.
				NEWLINE
			);
		}
		// return output
		if($useEcho)
			$this->output = NULL;
		return $this->output;
	}
	private function _display_Title() {
		return '<!-- '.$this->title.' -->';
	}
	private function _display_TitleLine() {
		return '<!-- '.str_repeat( '=', strlen($this->title) ).' -->';
	}
	private function _display($data) {
		if($this->useEcho == NULL)
			return;
		\psm\html\engine::renderGlobalTags($data);
		if($this->useEcho)
			echo $data;
		else
			$this->output .= $data;
	}


	// add block
	public function add($data, $top=FALSE) {
		if(!empty($data))
			\psm\Utils\Utils::appendArray($this->blocks, $data);
	}


	// set prepend/postpend
	public function setPrepend($data=NULL) {
		$this->prepend = $data;
	}
	public function setPostpend($data=NULL) {
		$this->postpend = $data;
	}


}
?>