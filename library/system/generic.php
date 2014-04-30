<?php
	
	namespace Free\System;

	/**
	 * Generic 
	 *
	 * A slight improvement over stdClass (new feature: methods!!) for generic objects
	 */
	class Generic {
		/**
		 * Errors can be tracked per-instance, this stores them
		 * @var array
		 */
		private $_errors = array();

		/**
		 * Shortcut instead of doing sizeof($this->_errors) > 0
		 * @var boolean
		 */
		public $hasError = false;

		/**
		 * Create the object instance
		 * @return  Generic object
		 */
		public function __construct(){
			return $this;
		}

		/**
		 * Get the name of the class
		 * @return string
		 */
		public function toString(){
			return get_class($this);
		}

		/**
		 * Get a specific property by KEY
		 * @param  string $key     The property you want to retrieve
		 * @param  string $default A default value to display if the property does not exist
		 * @return mixed
		 */
		public function get($key = null, $default = null){
			$ret = $default;

			if(false === is_null($key)){
				if(isset($this->$key)){
					$ret = $this->$key;
				}
			}

			return $ret;
		}

		/**
		 * Set a property
		 * @param string $key   Name of the property you want to set
		 * @param mixed $value  Value you want the property to have (array, object, string, etc)
		 */
		public function set($key, $value){
			$ret = (isset($this->$key) ? $this->$key : null);
			
			$this->$key = $value;
			
			return $ret;
		}

		/**
		 * Set instance properties from an existing array
		 * @param array $properties
		 */
		public function setProperties($properties = array()){
			if(sizeof($properties) > 0 && (is_array($properties) || is_object($properties))){
				foreach($properties as $key => $value){
					$this->$key = $value;
				}

				return true;
			}

			return false;
		}

		/**
		 * Get all properties (all public by default, private by $private flag)
		 * @param  boolean $private Return value includes private properties
		 * @return array
		 */
		public function getProperties($private = false){
			$ret = array();

			$properties = get_object_vars($this);

			foreach($properties as $key => $value){
				if(strpos($key, "_") === false && false === $private){
					$ret[] = array($key => $value);
				}else {
					$ret[] = array($key => $value);
				}
			}

			return $ret;
		}

		/**
		 * Set the instance error message
		 * @param string $error_msg
		 */
		public function setError($error_msg){
			$ret = array("class" => $this->toString(), "error" => $error_msg);

			$this->_errors[] = $ret;

			$this->hasError = true;

			return $ret;
		}

		/**
		 * Get ALL the errors
		 * @return mixed
		 */
		public function getError(){
			if($this->hasError){
				return $this->_errors;
			}

			return false;
		}
	}

?>