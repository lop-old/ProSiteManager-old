<?php namespace psm\Utils;
if(!defined('psm\\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die('<font size="+2">Access Denied!!</font>');}
global $ClassCount; $ClassCount++;
final class DirsFiles {
	private function __construct() {}


	/**
	 * Merge path strings easily.
	 *
	 * @param string $paths... Paths to merge. This can be an array or
	 *                         multiple arguments.
	 * @return string Merged path string.
	 */
	public static function MergePaths($args='') {
		if(!is_array($args))
			$args = \func_get_args();
		// trim separator
		$args = self::TrimPath($args);
		// build path
		return \implode(DIR_SEP, $args);
	}


	/**
	 * Trim / or \ from start and end of a path string.
	 *
	 * @param string $path Path to trim.
	 * @return string Cleaned path string.
	 */
	public static function TrimPath($path, $sepBefore=FALSE, $sepAfter=FALSE) {
		if(is_array($path))
			return \array_map(__METHOD__, $path);
		$path = \str_replace(
			(DIR_SEP=='/' ? '\\' : '/' ),
			DIR_SEP,
			$path
		);
		while(substr($path, 0, 1) == DIR_SEP)
			$path = \substr($path, 1);
		while(substr($path, -1, 1) == DIR_SEP)
			$path = \substr($path, 0, -1);
		return
			($sepBefore ? DIR_SEP : '').
			$path.
			($sepAfter ? DIR_SEP : '');
	}


	/**
	 * Sanitize file names.
	 *
	 * @param string $filename File name to be sanitized.
	 * @return string Returns the sanitized file name.
	 */
	public static function SanFilename($filename) {
		if(is_array($filename))
			return array_map(__METHOD__, $filename);
		$filename = \trim($filename);
		if(empty($filename))
			return '';
		// shouldn't contain /
		if(\strpos($filename, '/') !== FALSE) {
\psm\Portal::Error('Stop at SanFilename() '.$filename);
		}
		// remove dots from front and end
		while(\psm\Utils\Strings::StartsWith($filename, '.'))
			$filename = \substr($filename, 1);
		while(\psm\Utils\Strings::EndsWith($filename, '.'))
			$filename = \substr($filename, 0, -1);
		// clean string
		$filename = \str_replace(\str_split(\preg_replace('/([[:alnum:]_\\.-]*)/', '_', $filename)), '_', $filename);
		return \trim($filename);
	}


//	/**
//	 *
//	 *
//	 */
	// format file size
	public static function fromBytes($size) {
		if($size < 0) $size = 0;
		if($size < 1024)          return round($size                , 0).'&nbsp;Bytes';
		if($size < 1048576)       return round($size / 1024         , 2).'&nbsp;KB';
		if($size < 1073741824)    return round($size / 1048576      , 2).'&nbsp;MB';
		if($size < 1099511627776) return round($size / 1073741824   , 2).'&nbsp;GB';
		                          return round($size / 1099511627776, 2).'&nbsp;TB';
	}


	// find file from list
	public static function FindFile($filename, $paths) {
		if(!is_array($paths)) $paths = array($paths);
		// loop paths
		foreach($paths as $path) {
			if(empty($path)) continue;
			$path = self::TrimPath($path, TRUE, TRUE).$filename;
			if(\file_exists($path))
				return $path;
		}
		return FALSE;
	}
	// find file and get contents
	public static function FindFileContents($filename, $paths) {
		$file = self::FindFile($filename, $paths);
		if($file === FALSE)
			return FALSE;
		return \file_get_contents($file);
	}


	public static function chmodR($path, $fileMode, $dirMode) {
		$path = \trim($path);
		if(empty($path)) return;
		if(\strlen($path) < 10) return;
		// dir
		if(\is_dir($path)) {
			if(!\chmod($path, $dirMode)) {
				$modeStr = \decoct($dirMode);
				echo '<p>Failed to chmod '.$modeStr.' '.$path.'</p>';
				return;
			}
			$handler = \opendir($path);
			while( ($file = \readdir($handler)) !== FALSE ) {
				if($file == '.' || $file == '..') continue;
				self::chmodR($path.DIR_SEP.$file, $fileMode, $dirMode);
			}
		// file
		} else {
			if(\is_link($path)) return;
			if(!\chmod($path, $fileMode)) {
				$modeStr = \decoct($fileMode);
				echo '<p>Failed to chmod '.$modeStr.' '.$path.'</p>';
				return;
			}
		}
	}


}
?>