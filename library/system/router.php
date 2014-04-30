<?php
	namespace Free\System;

	defined("FR_FRAME") or die;

	/**
	 * Route special pages to the right places
	 */
	class Router extends Generic {
		private $_path;
		private $_loader;
		private $_output;

		/**
		 * Initialize class properties
		 */
		public function __construct(){
			$this->_path = $_SERVER["REQUEST_URI"];
			$this->_loader = new Loader();
			$this->_output = new Output();
		}

		/**
		 * Takes a raw path, removes extraneous parts and returns a usable
		 * path which will be used to determine the m/v/c for the page
		 * @return array
		 */
		private function _processPath(){
			$_parts = array_filter(explode("/", $this->_path));
			
			$key = array_search("free-framework", $_parts);

			for($i = $key; $i > 0; $i--){
				unset($_parts[$i]);
			}

			//reset array keys (0,1,2,...)
			return array_values($_parts);
		}

		/**
		 * Determine what m/v/c to call, then call it
		 * @return mixed
		 */
		private function _setup(){
			$_path = $this->_processPath();

			//supports 2 path configurations
			//1: Controller::method        - logic is handled within the callable
			//	 method
			//2: Controller::model->method - logic is handled within the model
			//	 and passed back to the controller
			switch(sizeof($_path)){
				case 2:
					$this->set("controller", $_path[0]);
					$this->set("method", $_path[1]);
				break;

				case 3:
					$this->set("controller", $_path[0]);
					$this->set("model", $_path[1]);
					$this->set("method", $_path[2]);
				break;

				default:
					$this->_output->setResponse("OK", 200)->setType("application/json");

					throw new \InvalidArgumentException(json_encode(array("message" => "Route failure, path does not contain the right number of parts", "status" => 0)));
			}

			$oController = $this->_loader->getController($this->controller);

			//there is a model, tell the controller to use it
			if($this->model){
				$oModel = $this->_loader->getModel($this->model);
				$oController->setModel($oModel);
			}

			//if the method we want exists, call it
			if(method_exists($oController, $this->method)){
				//because call_user_func_array expects an array as the second param
				//and the method we're calling expects a RequestParam object
				return $oController->{$this->method}(\Free\System\Security::sanitizeRequestData($_POST));
			}else {
				throw new \InvalidArgumentException(sprintf("Method not found: %s::%s", $oController->__toString(), $this->method));
			}

			return false;
		}

		/**
		 * Get the first section of the processed path
		 * @return string
		 */
		public function getPath(){
			$_path = $this->_processPath();
			
			return $_path[0];
		}

		/**
		 * Main entry point for the class, a wrapper for the _setup method
		 * @return [type] [description]
		 */
		public function route(){
			try {
				$this->_setup();
			}catch(\InvalidArgumentException $e){
				die($e->getMessage());
			}
		}
	}

?>