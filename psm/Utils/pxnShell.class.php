<?php namespace psm\Utils;
if(!defined('psm\\INDEX_FILE') || \psm\INDEX_FILE!==TRUE) {if(headers_sent()) {echo '<header><meta http-equiv="refresh" content="0;url=../"></header>';}
	else {header('HTTP/1.0 301 Moved Permanently'); header('Location: ../');} die('<font size="+2">Access Denied!!</font>');}
global $ClassCount; $ClassCount++;
class pxnShell {

	private $process = NULL;
	private $stdIn  = NULL;
	private $stdOut = NULL;

	private $SaveMaxRunTime;
	private $divName;


	public static function Exec($cmd, $divName='') {
		if(empty($divName)) {
			\srand(\microtime() * 1000000.0);
			$divName = 'divScroller'.\rand(0, 9000);
		}
		\psm\Utils\Utils::ScrollToBottom();
		$data = '';
		$shell = new self($cmd);
		$i = 0;
		while(TRUE) {
			$i++;
			$line = $shell->getLine();
			if($line === FALSE)
				break;
			$line = trim($line);
			$data .= $line.NEWLINE;
			echo '<p>'.$line.'</p>'.NEWLINE;
//TODO: add a timer to this too
			if($i >= 5) {
				$i = 0;
				\psm\Utils\Utils::ScrollToBottom($divName);
				@\ob_flush();
			}
		}
		$shell->Unload();
		unset($shell);
		\psm\Utils\Utils::ScrollToBottom($divName);
		\psm\Utils\Utils::ScrollToBottom();
		@\ob_flush();
		return $data;
	}
	public function __construct($cmd, $logFile='') {
		if(empty($cmd)) return;
		// save max run time
		$this->SaveMaxRunTime = \ini_get('max_execution_time');
		set_time_limit(600);
		if(empty($logFile))
			$logFile = '/dev/null';
		$desc = array(
			0 => array('pipe', 'r'),
			1 => array('pipe', 'w'),
			2 => array('file', $logFile, 'a'),
		);
		$pipes = array();
		$dir = '/tmp';
		$env = array();
		$this->process = \proc_open($cmd, $desc, $pipes, $dir, $env);
		if(!\is_resource($this->process))
			\psm\Portal::Error('Failed to run shell command: '.$cmd);
		$this->stdIn  = &$pipes[1];
		$this->stdOut = &$pipes[0];
	}
	public function __destruct() {
		$this->Unload();
	}
	public function Unload() {
		@\fclose($this->stdOut);
		@\fclose($this->stdIn);
		$this->stdOut = NULL;
		$this->stdIn  = NULL;
		@\proc_close($this->process);
		$this->process = NULL;
		// restore max run time
		set_time_limit($this->SaveMaxRunTime);
	}


	public function sendLine($cmd) {
		\fwrite($this->stdOut, $cmd);
	}
	public function getLine() {
		if($this->stdIn == NULL || $this->process == NULL)
			return FALSE;
		$status = \proc_get_status($this->process);
		if(!$status['running'])
			return FALSE;
		return \fgets($this->stdIn, 1024);
//		return \stream_get_line($this->stdIn, 1024, NEWLINE);
	}


}
?>