<?php
	namespace Free\System;

	defined("FR_FRAME") or die;

	class Logger extends Generic {
		private $_path;
		private static $_instance;

		//path must include file name (?)
		public function __construct($log_path = null){
			$this->_path = FR_BASE ."/library/system/logs/default.log";

			if(false === is_null($log_path)){
				$this->_path = $log_path;
			}
		}

		public static function getInstance($log_path = null){
			if(false === self::$_instance instanceof self){
				$class = __CLASS__;
				
				self::$_instance = new $class($log_path);
			}
			
			return self::$_instance;
		}

		public function record($to_log, $class_method = null){
			try {
				$to_log = $this->_logify($to_log, $class_method);

				if(false === file_put_contents($this->_path, $to_log, FILE_APPEND)){
					throw new \InvalidArgumentException(sprintf("File could not be read %s", $this->_path));
				}
			}catch(\InvalidArgumentException $e){
				echo $e->getMessage();
			}
		}


		public function setLogFile($filename = null){
			try {
				if(false === is_null($filename)){
					$this->_path = sprintf("%s/library/system/logs/%s.log", FR_BASE, $filename);

					return $this->_path;
				}

				throw new \InvalidArgumentException(sprintf("File could not be read %s", $this->_path));
			}catch(\InvalidArgumentException $e){
				echo $e->getMessage();
			}
		}

		private function _logify($to_log, $class_method = null){
			$to_log = $to_log ."\r\n";
			$datetime = date("m/d/Y g:i:s");
			$output = sprintf("[%s] [%s] %s", $datetime, $class_method, $to_log);

			if(is_null($class_method)){
				$output = sprintf("[%s] %s", $datetime, $to_log);
			}
			
			return $output;
		}
	}

?>