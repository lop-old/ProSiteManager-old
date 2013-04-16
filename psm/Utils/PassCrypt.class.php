<?php namespace psm\Utils;
global $ClassCount; $ClassCount++;
//methods:
// md5				- standard md5
// rev				- reverse the hash/string
// splitrev:x		- reverse last x chars
// revsplit:x		- reverse first x chars
// salt:#x			- salt using the first x chars
// salt:#-x			- salt using the last x chars
// salt:string		- salt using custom string
// loop:x:action	- loop action x times
//TODO: loopasc:x:action	- loop by the value
//examples:
// 'md5'
//    - standard md5 only
// 'md5 salt:{salt} md5'
//    - md5, salt using a custom string, then md5 once more
// 'loop:1000:md5 revsplit:10 salt:#6 salt:#-6 loop:1000:md5 salt:{salt} loop:1000:md5 splitrev:22 loop:500:md5';
//    - md5 x 1000, reverse first 10 chars, salt using first 6 chars, salt using last 6 chars,
//      md5 x 1000, salt using a custom string, md5 x 1000, reverse last 10 chars, md5 x 500


class PassCrypt {

	const defaultHashSequence = 'loop:1000:md5 revsplit:10 salt:#6 salt:#-6 loop:1000:md5 salt:{salt} loop:1000:md5 splitrev:22 loop:500:md5';
	private $salt = NULL;
	private $hashSequence = '';


	public function __construct($hashSequence='', $salt='') {
		if(!empty($hashSequence))
			$this->hashSequence = $hashSequence;
		if(!empty($salt))
			$this->salt = $salt;
	}


	// set salt (only once)
	public function setSalt($salt) {
		if($this->salt != NULL) return;
		$this->salt = $salt;
	}


	// single use hash
	public static function hashNow($data, $hashSequence='', $salt='') {
		$crypt = new self($hashSequence, $salt);
		$output = $crypt->hash($data);
		unset($crypt);
		return $output;
	}


	// hash a string
	public function hash($data) {
		// default hash sequence
		if(empty($this->hashSequence))
			$this->hashSequence = self::defaultHashSequence;
		// split to array
		$modes = \explode(' ', $this->hashSequence);
		foreach($modes as $hashMode) {
			$hashMode = \trim($hashMode);
			if(empty($hashMode)) continue;
			self::_hashThis($data, $hashMode, $this->salt);
		}
		return $data;
	}
	private static function _hashThis(&$data, $mode, $salt) {
		@list($mode, $arg) = \explode(':', $mode, 2);
		if(!empty($salt))
			$mode = \str_replace('{salt}', $this->salt, $mode);
		$mode = \trim($mode);
		$arg  = \trim($arg);
		// md5
		if($mode == 'md5') {
			$data = \md5($data);
			return;
		}
		// reverse string
		if($mode == 'rev') {
			$data = \strrev($data);
			return;
		}
		// split / reverse
		if($mode == 'splitrev') {
			$data =
				\substr($data, 0, \strlen($data)/2).
				\strrev(\substr($data, \strlen($data)/2));
			return;
		}
		// reverse / split
		if($mode == 'revsplit') {
			$data =
				\strrev(\substr($data, 0, \strlen($data)/2)).
				\substr($data, \strlen($data)/2);
			return;
		}
		// salt
		if($mode == 'salt') {
			// salt self length
			if(\psm\Utils\Strings::startsWith($arg, '#')) {
				$arg = (int) \substr($arg, 1);
				// from front of hash
				if($arg > 0)
					$data = \substr($data, 0, $arg).':'.$data;
				// from end of hash
				else
					$data = $data.':'.\substr($data, $arg);
			// salt string
			} else {
				$data = \trim($arg).':'.$data;
			}
			return;
		}
		// loop
		if($mode == 'loop') {
			$parts = \explode(':', $arg, 2);
			$count = (int) $parts[0];
			for($i=0; $i < $count; $i++)
				self::_hashThis($data, $parts[1]);
			return;
		}
		// ignored
echo '<p>Ignored hash mode: '.$mode.'</p>';
	}


}
?>