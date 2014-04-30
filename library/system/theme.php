<?php
	namespace Free\System;

	use Free\System\Exception\FileNotFoundException;

	defined("FR_FRAME") or die;

	class Theme extends Generic {
		private $_directories;
		private $_defaults;
		private $_path = array();
		private $_scripts = array();
		private $_stylesheets = array();
		private $_view;
		private $_model;
		private $_controller;
		private $_options = array(
			"hide_header" => false,
			"hide_footer" => false,
			"hide_view" => false,
			);

		public function __construct(){
			$this->loader = new Loader();

			//setup theme defaults
			$this->_defaults = new Generic();
			$this->_defaults->set("view", "home.php");
			$this->_defaults->set("controller", "home.php");
			$this->_defaults->set("model", "home.php");

			return $this;
		}

		public function setup(){
			try {
				$this->loader->autoLoadRequired();

				$_c = sprintf("\Free\Theme\%sController", $this->_getName($this->_defaults->controller));
				$_m = sprintf("\Free\Theme\%sModel", $this->_getName($this->_defaults->model));

				//views are (currently) just HTML files, they don't need to be a class
				$this->_view = $this->_path["view"];
				//instantiate the Model class
				$this->_model = new $_m;
				//instantiate the Controller class
				$this->_controller = new $_c;//call_user_func(array(new $_c, "display"));

				return true;
			}catch(FileNotFoundException $e){
				die($e->getMessage());
			}
		}

		public function setOption($key, $value){
			if(isset($this->_options[$key])){
				return ($this->_options[$key] = $value);
			}

			return false;
		}

		private function _getName($file){
			return ucwords(str_replace(".php", "", $file));
		}

		private function _getFileNameFromPath($path){
			$_parts = explode("/", $path);
			$ret = $this->loader->directories->path["views"] . $_parts[sizeof($_parts)-1];

			if(strpos($ret, ".php") === false){
				$ret .= ".php";
			}

			return $ret;
		}

		public function render($path = null, $options = array()){
			ob_start();

				$this->_controller->set("view_uri", $this->loader->directories->uri["views"]);
				$this->_controller->set("theme_root", $this->loader->directories->uri["root"]);
				$this->_controller->set("vendor_root", $this->loader->directories->uri["vendor"]);
				$this->_controller->set("view_path", $this->loader->directories->path["views"]);
				$this->_controller->display();

				if(sizeof($options) === 0){
					$options = array();
					$options["hide_header"] = false;
					$options["hide_footer"] = false;
					$options["num_cols"] = (is_dynamic_sidebar() && $this->loader->isSectionLoaded("dynamicSidebar") ? 8 : 12);
					$options["show_title"] = $this->_getMeta("show_title", false);
					$options["show_sidebar"] = $this->_getMeta("show_sidebar", false);
					$options["show_banner_widgets"] = $this->_getMeta("show_banner_widgets", false);
				}

				$options = array_merge($options, $this->_options);

				//the path contains a filename, we will use that to load our template file
				if(false === is_null($path)){
					$this->loader->file["view"] = $this->_getFileNameFromPath($path);
				}

				//load the header
				if(false === $options["hide_header"]){
					$this->loader->getView($this->loader->directories->path["views"] . "header.php", $options);
				}

				//load the actual view file we want
				if(false === $options["hide_view"]){
					$this->loader->getView($this->loader->file["view"], $options);
				}

				//load the footer
				if(false === $options["hide_footer"]){
					$this->loader->getView($this->loader->directories->path["views"] . "footer.php", $options);
				}

			echo ob_get_clean();
		}

		private function _getMeta($key, $default){
			$result = get_post_meta(get_the_id(), $key, true);

			if(strlen($result) === 0){
				return $default;
			}

			return ($result == "false" ? false : true);
		}
	}
?>