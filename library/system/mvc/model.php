<?php
	namespace Free\System;

	defined("FR_FRAME") or die;

	use Free\System\Exception\InvalidModelException;

	class Model extends Generic {
		protected $db;
		protected $logger;

		public function __construct(){
			$this->db = new Database();
			$this->logger = new Logger();
		}

		/**
		 * Instantiates the required model and calls the method
		 * @deprecated 1.1
		 * @param  string $model [description]
		 * @return mixed
		 */
		public function call($model, $data = array()){
			if(strpos($model, "/") !== false){
				$parts = explode("/", $model);

				if(false === is_array($data)){
					$data = array($data);
				}
				
				$_modelName = ucwords($parts[0]);

				//there is no model, throw an exception
				//if(false === $this->_exists($_modelName)){
				if(false === method_exists($_modelName, $parts[1])){
					throw new InvalidModelException(sprintf("<strong>/theme/models/%s.php</strong> or <strong>%s::%s</strong> not found", $parts[0], $_modelName, $parts[1]));
				}

				$_m = new $_modelName;

				return call_user_func_array(array($_m, $parts[1]), $data);
			}

			return false;
		}
	}
?>