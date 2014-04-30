<?php
	namespace Free\System;

	defined("FR_FRAME") or die;

	class Utils extends Generic {
		private static $_instance;
		private $_scripts = array();
		private $_styles = array();
		
		/**
		 * Instantiate the class
		 * @return {Utils} object
		 */
		public static function getInstance(){
			if(false === isset(self::$_instance)){
				self::$_instance = new self;
			}

			return self::$_instance;
		}

		/**
		 * Get the actual page title.  Not the post title, not the tag title, 
		 * the actual, real page title.  Thank you for making this difficult, 
		 * Wordpress.
		 * @return string
		 */
		public function page_title(){
			$wptitle = single_post_title(null, false);

			//check to see if there is more than one word in the title
			$_parts = explode(" ", $wptitle);

			//glue them back together with a separator
			if(sizeof($_parts) > 0){
				$wptitle = implode("-", array_filter($_parts));
			}

			
			$output = trim(strtolower($wptitle));

			return $output;
		}

		public function get_header(){
			for($i = 0; $i < sizeof($this->_scripts["declarations"]); $i++){
				var_dump($this->_scripts["declarations"][$i]);
			}
		}

		public function addScriptDeclaration($js){
			if(strlen($js) > 5){
				$HTML = new HTML("script", array("text" => trim($js)));
			}

			$this->_scripts["declarations"][] = $HTML;

			return false;
		}

		public function addScript($url){
			return new HTML("script", array("src" => $url));
		}

		public function addStyleDeclaration($css){

		}

		public function addStyleSheet($url){

		}

		public function str_replace($search, $replace, $subject){
			$_parts = explode(" ", $subject);

			for($i = 0; $i < sizeof($_parts); $i++){
				if($_parts[$i] == $search){
					$_parts[$i] = $replace;
				}
			}

			return implode(" ", $_parts);
		}

		public function getSidebar($sidebar_name) {
			// global $wp_registered_sidebars, $wp_registered_widgets;
			// //var_dump($wp_registered_sidebars);
			
			// // Holds the final data to return
			// $output = array();
			
			// // Loop over all of the registered sidebars looking for the one with the same name as $sidebar_name
			// $sidebar_id = false;
			// foreach( $wp_registered_sidebars as $sidebar ) {
			// 	if( $sidebar['id'] == $sidebar_name ) {
			// 		// We now have the Sidebar ID, we can stop our loop and continue.
			// 		$sidebar_id = $sidebar['id'];
			// 		break;
			// 	}
			// }
			
			// if( !$sidebar_id ) {
			// 	// There is no sidebar registered with the name provided.
			// 	return $output;
			// } 
			
			// // A nested array in the format $sidebar_id => array( 'widget_id-1', 'widget_id-2' ... );
			// $sidebars_widgets = wp_get_sidebars_widgets();
			// $widget_ids = $sidebars_widgets[$sidebar_id];
			
			// if( !$widget_ids ) {
			// 	// Without proper widget_ids we can't continue. 
			// 	return array();
			// }
			
			// // Loop over each widget_id so we can fetch the data out of the wp_options table.
			// foreach( $widget_ids as $id ) {
			// 	// The name of the option in the database is the name of the widget class.  
			// 	$app = $wp_registered_widgets[$id]['callback'][0];
			// 	$option_name = $app->option_name;
			// 	// $shortcode = $app->getApplication();
				
			// 	// Widget data is stored as an associative array. To get the right data we need to get the right key which is stored in $wp_registered_widgets
			// 	$key = $wp_registered_widgets[$id]['params'][0]['number'];
			// 	var_dump($wp_registered_widgets[$id]);
			// 	$widget_data = get_option($option_name);
				
			// 	// Add the widget data on to the end of the output array.
			// 	$output[] = (object) $widget_data[$key];
			// }
			
			// return $output;
		}
	}

?>