<?php
	namespace Free\System;

	defined("FR_FRAME") or die;

	class Output extends Generic {
		private $_accepted_mimetypes = array(
			"application/json",
			"text/html"
			);
		private $_type = "text/html";

		public function setType($mimetype = null){
			if(false === is_null($mimetype) && in_array($mimetype, $this->_accepted_mimetypes)){
				$this->_type = $mimetype;

				header(sprintf("Content-Type: %s", $this->_type));
			}else {
				throw new \InvalidArgumentException(sprintf("Invalid mimetype requested, must be one of: %s", implode($this->_accepted_mimetypes, ", ")));
			}

			return $this;
		}

		public function getType(){
			return $this->_type;
		}

		public function setResponse($responsetype = null, $code = 0){
			if($code > 99 && $code < 506){
				//TODO: more rigorous checking on the combination of code/responsetypes
				//so you can't do 503 Not Found or 200 Unsupported Media Type
				header(sprintf("HTTP/1.1 %d %s", $code, $responsetype));
			}else {
				throw new \InvalidArgumentException("HTTP response code is outside the acceptable range (100-505)");
			}

			return $this;
		}

		public function addType($mimetype = null){
			if(false === is_null($mimetype)){
				if(strpos($mimetype, "/")){ //lazy way to determine if it is a mimetype
					$this->_accepted_mimetypes[] = $mimetype;
				}
			}

			return $this;
		}

		public function output(){

		}
	}

?>