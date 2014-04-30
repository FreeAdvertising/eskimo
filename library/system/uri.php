<?php

	namespace Free\System;

	defined("FR_FRAME") or die;

	class URI {
		private $_segments = array();
		private static $_instance;

		public function __construct(){
			$this->_segments = array_values(array_filter(explode("/", $_SERVER["REQUEST_URI"])));

			return $this;
		}

		public function segment($index){
			if($this->exists($index)){
				return $this->_segments[$index];
			}

			return null;
		}

		public function getSegments(){
			return $this->_segments;
		}

		public function exists($index){
			return isset($this->_segments[$index]);
		}

		public static function getInstance($log_path = null){
			if(false === self::$_instance instanceof self){
				$class = __CLASS__;
				
				self::$_instance = new $class($log_path);
			}
			
			return self::$_instance;
		}
	}

?>