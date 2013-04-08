<?php namespace psm\Utils;
if(!defined('psm\\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die('<font size="+2">Access Denied!!</font>');}
global $ClassCount; $ClassCount++;
final class FuncArgs {
	private function __construct() {}


	// \psm\FuncArgs::funcValidate(\func_get_args(), 'pattern pattern', 'funcname');
	// string{minlength,maxlength};
	// integer{minval,maxval};
	public static function funcValidate($args=array(), $pattern='', $funcName='') {
		if(empty($funcName)) $funcName = ''; else $funcName.': ';
		$i = -1;
		// parse args
		foreach(\explode(';', ((string) $pattern)) as $pat) {
			$pat = \trim($pat);
			if(empty($pat)) continue;
			$i++;
			$arg = isset($args[$i]) ? $args[$i] : NULL;

			// string
			if(\psm\Utils\Strings::StartsWith($pat, 'str')) {
				$t = (\psm\Utils\Strings::StartsWith($pat, 'string') ? 6 : 3);
				$pat = \trim(\substr($pat, $t));
				// optional
				if(\psm\Utils\Strings::StartsWith($pat, '[') && empty($arg))
					continue;
				// check type
				if(!\is_string($arg))
					\psm\Portal::Error('Argument '.$i.' is required to be a String!');
				$pat = \trim(\substr($pat, 1, -1));
				if(empty($pat))
					continue;
				// min/max length
				if(\psm\Utils\Strings::Contains($pat, ',')) {
					list($min, $max) = \explode(',', $pat, 2);
					$min = (int) $min;
					$max = (int) $max;
					$len = \strlen($arg);
					if($len < $min)
						\psm\Portal::Error('Argument '.$i.' doesn\'t meet required length of '.$min.' chars!');
					if($len > $max)
						\psm\Portal::Error('Argument '.$i.' exceeds required length of '.$max.' chars!');
				// fixed length string
				} else {
					$req = (int) $pat;
					if(\strlen($arg) != $req)
						\psm\Portal::Error('Argument '.$i.' doesn\'t meet required fixed length of '.$req.' chars!');
				}
				continue;
			}

			// integer
			if(\psm\Utils\Strings::StartsWith($pat, 'int')) {
				$t = (\psm\Utils\Strings::StartsWith($pat, 'integer') ? 7 : 3);
				$pat = \substr($pat, $t);
				// optional
				if(\psm\Utils\Strings::StartsWith($pat, '[') && $arg === NULL)
					continue;
				// check type
				if(!\is_int($arg))
					\psm\Portal::Error('Argument '.$i.' is required to be an Integer!');
				$pat = \trim(\substr($pat, 1, -1));
				if(empty($pat))
					continue;
				// min/max value
				if(\psm\Utils\Strings::Contains($pat, ',')) {
					list($min, $max) = \explode(',', $pat, 2);
					$min = (int) $min;
					$max = (int) $max;
					if($arg < $min)
						\psm\Portal::Error('Argument '.$i.' doesn\'t meet required value of '.$min.'!');
					if($arg > $max)
						\psm\Portal::Error('Argument '.$i.' exceeds required value of '.$max.'!');
				// max value
				} else {
					$max = (int) $pat;
					if($arg > $max)
						\psm\Portal::Error('Argument '.$i.' exceeds required value of '.$max.'!');
				}
				continue;
			}

			// double

			// float

			// boolean

		}
	}


	/**
	 * Validates the type of class for an object.
	 *
	 * @return boolean Returns true if object is the type $className.
	 */
	public static function classEquals($className, $clss) {
		//echo '<p>$className - '.$className.'</p>';
		//echo '<p>get_class($clss) - '.get_class($clss).'</p>';
		//echo '<p>get_parent_class($clss) - '.get_parent_class($clss).'</p>';
		return
			get_class($clss) == $className ||
			is_subclass_of($clss, $className
		);
	}
	/**
	 * Validates the type of class for an object, throwing an exception
	 * if invalid.
	 */
	public static function classValidate($className, $clss) {
		if(!self::classEquals($className, $clss))
			\psm\Portal::Error("Class object isn't of type ".$className);
	}


}
?>