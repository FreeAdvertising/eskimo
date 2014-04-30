<?php
	namespace Free\System;

	defined("FR_FRAME") or die;

	class Controller extends Generic {
		public $data;
		public $controller;
		public $output;

		protected $model;

		public function __construct(){
			$this->data = new Generic();
			$this->output = new Output();
		}

		public function set($key = null, $value){
			if(false === is_null($key)){
				return $this->data->{$key} = $value;
			}

			return false;
		}

		public function setModel($model){
			if($model instanceof Model){
				return ($this->model = $model);
			}

			return false;
		}

		public function setChildController($controller){
			if($controller instanceof Controller){
				return ($this->controller = $controller);
			}

			return false;
		}
	}
?>