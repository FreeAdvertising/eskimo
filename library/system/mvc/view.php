<?php
	namespace Free\System;

	use Free\System\Exception\FileNotFoundException;
	
	defined("FR_FRAME") or die;

	class View extends Generic {
		private $_loaderData;
		private $_view;
		private $_output;

		public $data;

		public function __construct($view = null, $options = array()){
			$this->_loaderData = new Loader();

			if(false === is_null($view)){
				$this->_view = sprintf("%s%s.php", $this->_loaderData->directories->path["views"], $view);
			}

			if(sizeof($options) > 0){
				$this->data = new Generic();
				$this->data->setProperties($options);
			}

			return $this->load();
		}

		protected function load(){
			try {
				if(is_null($this->_view)){
					throw new FileNotFoundException($this->_view);
				}

				ob_start();
					include($this->_view);
				$this->_output = trim(preg_replace('/\s+/', ' ', ob_get_clean()));

			}catch(FileNotFoundException $e){
				die($e->getMessage());
			}
		}

		public function __toString(){
			return html_entity_decode($this->_output);
		}
	}
?>