<?php
	namespace Free\System;

	use Free\System\Exception\ThemeNotFoundException;
	
	defined("FR_FRAME") or die;

	abstract class Factory {
		private static $_theme;

		private static $_routes = array(
			"api",
			);

		public static function getTheme(){
			$_theme = new \Free\System\Theme();
			$_router = new \Free\System\Router();

			try {
				if($_theme->setup()){
					if(in_array($_router->getPath(), self::$_routes)){
						$_theme->setOption("hide_header", true);
						$_theme->setOption("hide_footer", true);
						$_theme->setOption("hide_view", true);
						$_theme->setOption("show_banner_widgets", false);

						$_router->route();

						//TODO: reset values of Theme::controller, ::model here
					}else {
						//need to do some sort of error here if the path is not
						//valid, but not for regular wordpress pages
						//throw new ThemeNotFoundException("NOPE");
					}

					return (self::$_theme = $_theme);
				}else {
					throw new ThemeNotFoundException(sprintf("Theme not found or does not contain the required files & folders."));
				}
			}catch(ThemeNotFoundException $e){
				die($e->getMessage());
			}
		}

		/**
		 * Display custom formatted data about the website
		 * @return {Util} object
		 */
		public static function getUtils(){
			return \Free\System\Utils::getInstance();
		}

		public static function getHeader(){
			return self::$_theme->getHeader();
		}
	}

?>