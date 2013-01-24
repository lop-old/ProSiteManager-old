<?php namespace psm;
if(!defined('PORTAL_INDEX_FILE') || \PORTAL_INDEX_FILE!==TRUE){if(headers_sent()){echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}else{header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die("<font size=+2>Access Denied!!</font>");}
class Utils_File {


	/**
	 * Merge path strings easily.
	 *
	 * @param string $paths... Paths to merge. This can be an array or
	 *                         multiple arguments.
	 * @return string Merged path string.
	 */
	public static function mergePaths($args) {
		if(!is_array($args))
			$args = func_get_args();
		// trim separator
		$args = self::trimPath($args);
		// build path
		return implode(DIR_SEP, $args);
	}


	/**
	 * Trim / or \ from start and end of a path string.
	 *
	 * @param string $path Path to trim.
	 * @return string Cleaned path string.
	 */
	public static function trimPath($path) {
		if(is_array($path))
			return array_map(__METHOD__, $path);
		$path = str_replace(
			(DIR_SEP=='/' ? '\\' : '/' ),
			DIR_SEP,
			$path
		);
		while(substr($path, 0, 1) == DIR_SEP)
			$path = substr($path, 1);
		while(substr($path, -1, 1) == DIR_SEP)
			$path = substr($path, 0, -1);
		return $path;
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
		$filename = trim($filename);
		if(empty($filename))
			return '';
		// shouldn't contain /
		if(strpos($filename, '/') !== FALSE) {
echo '<p>stop SanFilename() '.$filename.'</p>'; exit();
		}
		// remove dots from front and end
		while(substr($filename, 0, 1) == '.')
			$filename = substr($filename, 1);
		while(substr($filename,-1, 1) == '.')
			$filename = substr($filename, 0, -1);
		// clean string
		$filename = str_replace(str_split(preg_replace('/([[:alnum:]_\\.-]*)/', '_', $filename)), '_', $filename);
		return trim($filename);
	}


//	/**
//	 *
//	 *
//	 */
	// format file size
	public static function fromBytes($size) {
		if($size < 0) $size = 0;
		if($size < 1024)          return(round($size                , 0).'&nbsp;Bytes');
		if($size < 1048576)       return(round($size / 1024         , 2).'&nbsp;KB');
		if($size < 1073741824)    return(round($size / 1048576      , 2).'&nbsp;MB');
		if($size < 1099511627776) return(round($size / 1073741824   , 2).'&nbsp;GB');
		                          return(round($size / 1099511627776, 2).'&nbsp;TB');
	}


}
?>