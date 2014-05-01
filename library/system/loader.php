<?php
	namespace Free\System;

	use Free\System\Exception\InvalidModelException;
	use Free\System\Exception\FileNotFoundException;
	
	defined("FR_FRAME") or die;

	class Loader {
		public $search_path;
		public $directories;
		public $file = array();

		private $_defaults;

		public function __construct(){
			//setup class properties
			$this->search_path = FR_BASE ."/theme";

			$searchDir = $this->search_path ."/";
			$searchURI = "http://". $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]. "wp-content/themes/eskimo/theme/";

			$this->directories = new Generic();
			$this->directories->set("path", array(
				"views" => $searchDir ."views/",
				"models" => $searchDir ."models/",
				"controllers" => $searchDir ."controllers/",
				"root" => $searchDir,
				));
			$this->directories->set("uri", array(
				"views" => $searchURI ."views/",
				"models" => $searchURI ."models/",
				"controllers" => $searchURI ."controllers/",
				"vendor" => $searchURI ."vendor/",
				"root" => $searchURI,
				));

			//setup theme defaults
			$this->_defaults = new Generic();
			$this->_defaults->set("view", "home.php");
			$this->_defaults->set("controller", "home.php");
			$this->_defaults->set("model", "home.php");

			$this->file["view"] = $this->directories->path["views"].$this->_defaults->view;
			$this->file["controller"] = $this->directories->path["controllers"].$this->_defaults->controller;
			$this->file["model"] = $this->directories->path["models"].$this->_defaults->model;
		}

		private function _isPath($potentialPath){
			return strpos($potentialPath, "/") !== false;
		}

		private function _exists($input){
			$output = new Generic();
			$output->result = false;
			//$output->file_path = $this->directories->path["views"] . $input .".php";
			$output->file_path = ($this->_isPath($input) ? $input : $this->directories->path["views"] . $input .".php");

			if(file_exists($this->file["view"]) && file_exists($this->file["controller"]) && file_exists($this->file["model"]) && is_dir($this->search_path)){
				$output->result = file_exists($output->file_path);
			}
			
			return $output;
		}

		/**
		 * Determine if a section is currently loaded or needs to be
		 * @param  string  $plugin Method name of in class SectionFactory
		 * @return boolean
		 */
		public function isSectionLoaded($plugin){
			return (false === call_user_func_array(array("\Free\System\SectionFactory", $plugin), array(true)) ? false : true);
		}

		/**
		 * Loads and instantiates a controller
		 * @param  string $file     Filename of the controller you want to load
		 * @return Controller object
		 */
		public function getController($file = null){
			$controllerPath = $this->directories->path["controllers"].$file.".php";

			try {
				$_exists = $this->_exists($controllerPath);

				if($_exists->result){
					include($_exists->file_path);

					//add theme namespace
					$className = sprintf("\Free\Theme\%sController", ucwords($file));

					return new $className();
				}else {
					throw new FileNotFoundException(sprintf("<strong>%s</strong> could not be located.", $_exists->file_path));
				}
			}catch(FileNotFoundException $e){
				die($e->getMessage());
			}
		}

		/**
		 * Loads and instantiates a model
		 * @param  string $slug     Model and method you want to call
		 * @return Model object
		 */
		public function getModel($slug = null, $data){
			$parts = array("Home", "sampleMethod");

			if(strpos($model, "/") !== false){
				$parts = explode("/", $model);
			}

			$modelPath = $this->directories->path["models"].$parts[0].".php";

			try {
				$_exists = $this->_exists($modelPath);

				if(false === is_array($data)){
					$data = array($data);
				}

				if($_exists->result){
					include($_exists->file_path);

					//add theme namespace
					$className = sprintf("\Free\Theme\%sModel", ucwords($parts[0]));
					$instance = new $className();

					//call the requested method
					try {
						if(method_exists($instance, $parts[1])){
							//return $instance->{$parts[1]};
							return call_user_func_array(array($instance, $parts[1]), $data);
						}
						throw new InvalidModelException(sprintf("<strong>/theme/models/%s.php</strong> or <strong>%s::%s</strong> not found", $parts[0], $parts[0], $parts[1]));
					}catch(InvalidModelException $e){
						die($e->getMessage());
					}
				}else {
					throw new FileNotFoundException(sprintf("<strong>%s</strong> could not be located.", $_exists->file_path));
				}
			}catch(FileNotFoundException $e){
				die($e->getMessage());
			}
		}

		/**
		 * [getView description]
		 * @param  [type] $filename [description]
		 * @param  array  $option   [description]
		 * @return [type]           [description]
		 */
		public function getView($filename = null, $option = array()){
			try {
				$_exists = $this->_exists($filename);

				if(false === is_null($filename) && $_exists->result){
					include($_exists->file_path);
				}else {
					throw new FileNotFoundException(sprintf("<strong>%s</strong> could not be located.", $_exists->file_path));
				}
			}catch(FileNotFoundException $e){
				die($e->getMessage());
			}
		}		

		public function autoLoadRequired(){
			foreach($this->file as $key => $value){
				if($key == "model" || $key == "controller"){
					include($value);
				}
			}

			return $this;
		}
	}

?>