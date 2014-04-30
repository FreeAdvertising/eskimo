<?php
	namespace Free\Theme;
	
	use \Free\System\Controller;

	defined("FR_FRAME") or die;

	class HomeController extends Controller {
		public function display(){

			$this->set("test", "value");
			$this->set("test2", "value");

			
		}
	}
?>