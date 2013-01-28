<?php namespace psm;
if(!defined('PORTAL_INDEX_FILE') || \PORTAL_INDEX_FILE!==TRUE){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class PassCrypt {
	//methods:
	// md5
	// rev
	// splitrev
	// revsplit
	// salt:#
	// loop:#:mode
	//examples:
	// 'md5'
	//   - standard md5() only
	// 'md5 salt:6 md5'
	//   - md5(), salt using first 6 chars, md5() once more
	// 'loop:1000:md5 revsplit:10 salt:6 loop:1000:md5 splitrev:22 loop:500:md5'
	//   - md5() x 1000, reverse first 10 chars, salt using first 6 chars,
	//     md5() x 1000, reverse last 10 chars, md5() x 500


	private $hashSequence = 'loop:1000:md5 revsplit:10 salt:6 loop:1000:md5 splitrev:22 loop:500:md5';


	public function __construct($hashSequence='') {
		if(!empty($hashSequence))
			$this->hashSequence = $hashSequence;
	}


	public function hash($data) {
		$modes = explode(' ', $this->hashSequence);
		foreach($modes as $hashMode) {
			if(empty($hashMode)) continue;
			self::_hashThis($data, $hashMode);
		}
		return $data;
	}
	private static function _hashThis(&$data, $mode) {
		@list($mode, $arg) = explode(':', $mode, 2);
		// md5
		if($mode == 'md5') {
			$data = md5($data);
			return;
		}
		// reverse string
		if($mode == 'rev') {
			$data = strrev($data);
			return;
		}
//		// split / reverse
//		if($mode == 'splitrev') {
//			return;
//		}
//		// reverse / split
//		if($mode == 'splitrev') {
//			return;
//		}
		// salt
		if($mode == 'salt') {
			$data = substr($data, 0, ((int) $arg) ).':'.$data;
			return;
		}
		// loop
		if($mode == 'loop') {
			$parts = explode(':', $arg, 2);
			for($i=0; $i< ((int) $parts[0]); $i++)
				self::hashThis($data, $parts[1]);
			return;
		}
		// ignored
echo '<p>Ignored hash mode: '.$mode.'</p>';
	}


}
?>