<?php
	namespace Free\System;

	use Free\System;
	use Free\System\Exception\InvalidModelException;
	use Free\System\Exception\InvalidServiceProviderException;
	
	defined("FR_FRAME") or die;

	abstract class SectionFactory {
		private static $_loader;
		private static $_model;
		private static $_container = array();
		private static $_allowed_attrs = array(); //TODO: add stuff to this list
		private static $_id = 1;

		private static $_video_sources = array(
			"youtube" => "http://youtu.be/",
			"vimeo"   => "http://vimeo.com/",
			);

		/**
		 * Creates the wrapper elements required for a sliding element feature
		 *
		 * @shortcode [sidebar id="%ID%"/]
		 * @param  array  $atts    Attributes passed from do_shortcode()
		 * @param  string $content Content passed from do_shortcode()
		 * @return {View} object
		 */
		public static function sidebar($atts, $content){
			$options = array(
				"container" => (isset($atts["container"]) ? $atts["container"] : ""),
				"container_class" => (isset($atts["container_class"]) ? $atts["container_class"] : "row"),
				"position" => (isset($atts["position"]) ? $atts["position"] : "right"),
			);

			$view = new View("widget-sidebar", $options);

			return $view;
		}

		/**
		 * Loads the sidebar when it is required
		 * 
		 * @param  boolean $test Flag which switches return type to boolean
		 * @return mixed
		 */
		public static function dynamicSidebar($test = false){
			$show =  (get_post_meta(get_the_id(), "show_sidebar", true) == "true" ? true : false);

			if($show){
				self::$_loader = new Loader();

				$option = array(
					"container" => "",
					"container_class" => "row",
					"position" => "right",
					);

				if(false === $test){
					return self::$_loader->getView("widget-dynamic-sidebar", $option);
				}else {
					return true;
				}
			}

			return false;
		}

		/**
		 * Load the site footer
		 * 
		 * @param  boolean $test Flag which switches return type to boolean
		 * @return mixed
		 */
		public static function footer($test = false){
			self::$_loader = new Loader();

			if(false === $test){
				return self::$_loader->getView("widget-footer");
			}else {
				return true;
			}
		}

		/**
		 * Load the banner widgets
		 * 
		 * @param  boolean $test Flag which switches return type to boolean
		 * @return mixed
		 */
		public static function bannerWidgets($test = false){
			$show =  (get_post_meta(get_the_id(), "show_banner_widgets", true) == "true" ? true : false);

			if($show){
				self::$_loader = new Loader();

				if(false === $test){
					return self::$_loader->getView("widget-banner");
				}else {
					return true;
				}
			}

			return false;
		}

		/**
		 * Display a break tag
		 * 
		 * @return {HTML} object
		 */
		public static function linebreak(){
			return new HTML("br");
		}

		/**
		 * Creates the wrapper elements required for a sliding element feature
		 *
		 * @shortcode [slider dir="horizontal"][/slider]
		 * @param  array  $atts    Attributes passed from do_shortcode()
		 * @param  string $content Content passed from do_shortcode()
		 * @return {HTML} object
		 */
		public static function slider($atts, $content){
			extract(shortcode_atts(array(
				"class" => "title",
				), $atts));

			$_setup = new Generic();
			$_setup->attributes = array(
				"class" => sprintf("slider %s", $atts["class"]),
				"dir" => $atts["dir"],
				);

			$HTML = new HTML("div", $_setup->attributes);
			$children = HTML::fromString(do_shortcode(strip_tags($content)));

			for($i = 0; $i < sizeof($children); $i++){
				$HTML->append($children[$i]);
			}

			return $HTML;
		}

		/**
		 * Creates an HTML button
		 *
		 * @shortcode [button href="#" text=""/]
		 * @param  array  $atts    Attributes passed from do_shortcode()
		 * @param  string $content Content passed from do_shortcode()
		 * @return {HTML} object
		 */
		public static function button($atts, $content){
			extract(shortcode_atts(array(
				"class" => "title",
				), $atts));

			$_setup = new Generic();
			$_setup->attributes = array(
				"class"  => sprintf("btn btn-default %s", $atts["class"]),
				"text"   => $atts["value"],
				);

			$HTML = new HTML("button", $_setup->attributes);

			return $HTML;
		}

		/**
		 * Creates an HTML anchor element which looks like a button
		 *
		 * @shortcode [linkbutton href="#"]Link Text[/linkbutton]
		 * @param  array  $atts    Attributes passed from do_shortcode()
		 * @param  string $content Content passed from do_shortcode()
		 * @return {HTML} object
		 */
		public static function linkbutton($atts, $content){
			extract(shortcode_atts(array(
				"class" => "title",
				), $atts));

			$_setup = new Generic();
			$_setup->attributes = array(
				"class"  => sprintf("btn btn-default %s", $atts["class"]),
				"text"   => $content,
				"href"   => (isset($atts["href"]) ? $atts["href"] : "#"), 
				"target" => (isset($atts["target"]) ? $atts["target"] : "self"),
				);

			$HTML = new HTML("a", $_setup->attributes);

			return $HTML;
		}

		/**
		 * Display a single testimonial 
		 *
		 * @shortcode [testimonial by="Name"]Testimonial text[/testimonial]
		 * @param  array  $atts    Attributes passed from do_shortcode()
		 * @param  string $content Content passed from do_shortcode()
		 * @return {HTML} object
		 */
		public static function testimonial($atts, $content){
			extract(shortcode_atts(array(
				"class" => "title",
				), $atts));

			$_setup = new Generic();
			$_setup->by = (isset($atts["by"]) ? $atts["by"] : "");
			$_setup->testimonial = (strlen($content) > 0 ? $content : "");
			$_setup->attributes = array(
				"class" => sprintf("testimonial %s", $atts["class"]),
				);

			//create the parent element
			$HTML = new HTML("div", $_setup->attributes);
			//append the testimonial text
			$container = new HTML("article", array("class" => "testimonial-body"));
			$children = HTML::fromString(do_shortcode(strip_tags($_setup->testimonial)));

			if(sizeof($children) > 0){
				$container->addClass("no-quotes");
				
				for($i = 0; $i < sizeof($children); $i++){
					$container->append($children[$i]);
				}
			}else {
				$container->text($_setup->testimonial);
			}
			
			$HTML->append($container);

			//append the "by: User" text
			$HTML->append(new HTML("article", array("class" => "testimonial-author", "text" => $_setup->by)));

			return $HTML;
		}

		/**
		 * Display an H1, H2, H3, H4, H5 or H6 element
		 * 
		 * @param  array  $atts    Attributes passed from do_shortcode()
		 * @param  string $content Content passed from do_shortcode()
		 * @return {HTML} object
		 */
		public static function title($atts, $content){
			extract(shortcode_atts(array(
				"class" => "title",
				), $atts));

			$atts["class"] .= " section-title";

			$_setup = new Generic();
			$_setup->size = (isset($atts["size"]) && $atts["size"] > 0 && $atts["size"] < 7 ? (int) $atts["size"] : 2);
			$_setup->attributes = array("class" => $atts["class"], "text" => strip_tags($content));
			
			$HTML = new HTML(sprintf("h%d", $_setup->size), $_setup->attributes);

			if($_setup->size === 1){
				$HTML->addClass("page-title");
			}

			return $HTML;
		}

		/**
		 * Display a paragraph element
		 * 
		 * @param  array  $atts    Attributes passed from do_shortcode()
		 * @param  string $content Content passed from do_shortcode()
		 * @return {HTML} object
		 */
		public static function paragraph($atts, $content){
			extract(shortcode_atts(array(
				"class" => "title",
				), $atts));

			//why do I need this if the above chunk of code is supposed to do
			//the same damn thing?  thanks, wordpress
			if(false === isset($atts["class"])){
				$atts["class"] = "paragraph";
			}

			$_setup = new Generic();
			$_setup->attributes = array("class" => $atts["class"], "text" => strip_tags($content));
			
			$HTML = new HTML("p", $_setup->attributes);

			return $HTML;
		}

		/**
		 * Displays an image
		 * 
		 * @param  array  $atts    Attributes passed from do_shortcode()
		 * @param  string $content Content passed from do_shortcode()
		 * @return {HTML} object
		 */
		public static function image($atts, $content){
			extract(shortcode_atts(array(
				"class" => "image",
				), $atts));

			$_setup = new Generic();
			$_setup->tag = "img";
			$_setup->attributes = array(
				"class" => sprintf("grid-image %s", $atts["class"]),
				"src"   => (isset($atts["src"]) ? get_template_directory_uri() ."/theme/images/". $atts["src"] : ""),
				);

			$HTML = new HTML($_setup->tag, $_setup->attributes);

			return $HTML;
		}

		/**
		 * Display a simple element, no more than text wrapped in a variable
		 * sized div element
		 *
		 * @shortcode [item size="#" class="className"]Lorem ipsum dolor.[/item]
		 * @param  array  $atts    Attributes passed from do_shortcode()
		 * @param  string $content Content passed from do_shortcode()
		 * @return {HTML} object
		 */
		public static function item($atts, $content){
			extract(shortcode_atts(array(
				"icon" => "services/picker.png",
				), $atts));

			$classes = (empty($atts["class"]) ? "" : $atts["class"]);
			$size = (isset($atts["size"]) ? (int) $atts["size"] : 1);
			$_setup = new Generic();
			$_setup->tag = "a";
			$_setup->attributes = array("class" => sprintf("item col-md-%d %s", $size, $classes), "id" => sprintf("item-%d", self::$_id));

			if((int) $size === -1){
				$_setup->attributes["class"] = sprintf("item %s", $classes);
			}

			if(false === isset($atts["href"])){
				$_setup->tag = "div";
			}else {
				$_setup->attributes["href"] = sprintf("%s%s", get_bloginfo("url"), $atts["href"]);
			}

			$HTML = new HTML($_setup->tag, $_setup->attributes);
			$HTML->append(new HTML("p", array("class" => "item-desc", "text" => strip_tags($content))));

			//implement item-childrens
			//$children = HTML::fromString(do_shortcode(strip_tags($content)));
			
			//for($i = 0; $i < sizeof($children); $i++){
				//$HTML->append($children[$i]);
			//}
			self::$_id++;

			
			return $HTML;
		}

		/**
		 * Creates an HTML section element
		 * 
		 * @param  array $atts      Attributes passed from do_shortcode()
		 * @param  string $content  Inner content passed from do_shortcode()
		 * @return {HTML} object
		 */
		public static function section($atts, $content, $data = null){
			try {

				$container = new HTML("div", array("class" => "container"));
				//$container = self::container(array("class" => "container"), $content, $data);

				$classes = (isset($atts["class"]) ? sprintf("grid %s", $atts["class"]) : "");
				$HTML = new HTML("section", array("class" => $classes, "id" => sprintf("section-%d", self::$_id)));

				if(false === is_null($data) && isset($atts["_sender"])){
					//creates an element and populates it with data based on an
					//SQL query, determines output style by how the _sender prop
					//is set
					$HTML->addClass(sprintf("generator-%s inline", $atts["_sender"]));
					//stupid PHP version issues
					//PHP 5.3 is fucking stupid
					//5.4: $HTML->append(self::{$atts["_sender"]}($data));
					//5.3:
					switch($atts["_sender"]){
						case "table": 
							$HTML->append(self::table($data));
						break;
					}
					//grumblegrumblegrumble
				}

				//give the section a title if required
				if(isset($atts["title"])){
					$HTML->append(new HTML("h3", array("text" => $atts["title"], "class" => "section-title")));
				}

				//add support for TWBS columns
				if(isset($atts["size"])){
					$HTML->addClass(sprintf("col-md-%d", $atts["size"]));
				}

				//process child elements
				$children = HTML::fromString(do_shortcode(strip_tags($content)));

				//classlist contains the inline class, no <div container> element required
				if(false === $HTML->hasClass("inline")){
					//if the classlist doesn't contain "full", wrap $HTML
					//if(strpos($classes, "full") === false){
					if(false === $HTML->hasClass("full")){
						for($i = 0; $i < sizeof($children); $i++){
							$HTML->append($children[$i]);
						}

						$container->append($HTML);

						return $container;
					}else {
						//otherwise, $HTML wraps the container div (child elements will)
						//be centered within the full width elements
						for($i = 0; $i < sizeof($children); $i++){
							$container->append($children[$i]);
						}

						$HTML->append($container);
					}
				}else {
					for($i = 0; $i < sizeof($children); $i++){
						//parent element contains the column size, not necessary
						//to have it on the child element as well
						if(isset($atts["size"])){
							$children[$i]->removeClass("col-md-%d");
						}

						$HTML->append($children[$i]);
					}
				}

				return $HTML;
			}catch(InvalidModelException $e){
				die($e->getMessage());
			}
		}

		/**
		 * Creates an empty HTML column element
		 * 
		 * @param  array $atts      Attributes passed from do_shortcode()
		 * @param  string $content  Inner content passed from do_shortcode()
		 * @return {HTML} object
		 */
		public static function column($atts, $content, $data = null){
			$atts["class"] .= "column";

			$_setup = new Generic();
			$_setup->size = (isset($atts["size"]) && $atts["size"] > 0 ? sprintf("col-md-%d", (int) $atts["size"]) : "col-md-2");
			$_setup->attributes = array("class" => $atts["class"]);
			
			$HTML = new HTML("div", $_setup->attributes);
			$HTML->addClass($_setup->size);

			$children = HTML::fromString(do_shortcode(strip_tags($content)));

			for($i = 0; $i < sizeof($children); $i++){
				$HTML->append($children[$i]);
			}

			return $HTML;
		}

		/**
		 * Creates an HTML container element
		 * 
		 * @param  array $atts      Attributes passed from do_shortcode()
		 * @param  string $content  Inner content passed from do_shortcode()
		 * @return {HTML} object
		 */
		public static function container($atts, $content, $data = null){
			$content = str_replace("<br />", "", trim($content));
			$HTML = new HTML("div", array("class" => sprintf("section-container container %s", isset($atts["class"]) ? $atts["class"] : "" )));

			//this doesn't
			$children = HTML::fromString(do_shortcode(strip_tags($content)));

			for($i = 0; $i < sizeof($children); $i++){
				$HTML->append($children[$i]);
			}

			return $HTML;
		}

		/**
		 * Builds an HTML table from a database query
		 * 
		 * @param  DatabaseResult $data
		 * @return {HTML} object
		 */
		public static function table($data){
			if(false === $data instanceof DatabaseResult){
				throw new InvalidModelException("Data set must be of type <strong>DatabaseResult</strong>.");
			}

			$HTML = new HTML("table", array(
				"class" => "table table-bordered table-hover table-striped data-table", 
				"data-source" => (method_exists($data->callback, "__toString") ? strtolower($data->callback->__toString()) : "null"),
				"width" => "100%",
				)
			);
			$row = new HTML("thead");

			//build table headers
			for($i = 0; $i < sizeof($data->columns); $i++){
				//Process $data->columns[$i] to determine if there are any AS
				//statements, if so use that instead of the whole "Foo AS Bar"
				//TODO: move to Database class
				if(preg_match("/as/i", $data->columns[$i], $matches) > 0){
					$parts = explode($matches[0], $data->columns[$i]);

					$data->columns[$i] = trim($parts[1]);
				}

				$row->append(new HTML("th", array("text" => $data->columns[$i])));
			}

			$HTML->append($row);

			//build table content
			if($data->num_rows === 1){ //single item
				$result_row = new HTML("tr");

				foreach($data->result as $value){
					$result_row->append(new HTML("td", array("text" => $value)));
				}

				$HTML->append($result_row);
			}else { //multiple items
				foreach($data->results as $result){
					//create a new TR for each result
					$result_row = new HTML("tr");
					//create a new TD for each property within each result
					foreach($result as $value){
						$td = new HTML("td", array("text" => $value));

						//append each drop to the row
						$result_row->append($td);
					}

					//append each row to the HTML object
					$HTML->append($result_row);
				}

			}

			return $HTML;
		}

		/**
		 * Generates a section and automatically populates it with data from a 
		 * model, returns an HTML table
		 * 
		 * @param  array $atts      Attributes passed from do_shortcode()
		 * @return {HTML} object
		 */
		public static function datatable($atts, $content){
			try {
				$params = new Generic();
				$params->set("limit", (int) $atts["limit"]);

				$loader = new Loader();
				$modelData = $loader->getModel($atts["source"], $params);

				$atts["_sender"] = "table";

				$args = array(
					"atts" => $atts,
					"content" => $content,
					"data" => $modelData,
				);

				//source and limit are used by the model, don't pass them to the
				//section method
				unset($args["atts"]["source"], $args["atts"]["limit"]);

				return self::section($args["atts"], $args["content"], $args["data"]);
			}catch(InvalidModelException $e){
				die($e->getMessage());
			}
		}

		/**
		 * Determine whether the contents of an element are a video link
		 * 
		 * @param  string  $str Content to match
		 * @return array
		 */
		private static function isVideoGetInfo($str){
			//ignore extra long strings
			if(strlen($str) < 200){
				foreach(self::$_video_sources as $key => $src){
					return array(strpos($str, $src) !== false, $key);
				}
			}

			return false;
		}		

		/**
		 * Creates an HTML video element
		 * 
		 * @param  array $atts      Attributes passed from do_shortcode()
		 * @param  string $content  Inner content passed from do_shortcode()
		 * @return {HTML} object
		 */
		public static function video($atts, $content, $data = null){
			try {
				$HTML = new HTML("div", array("id" => sprintf("video-player-%s", self::$_id)));
				$util = Factory::getUtils();
				$js = "";
				$info = self::isVideoGetInfo($atts["src"]);
				$videoId = str_replace(self::$_video_sources[$info[1]], "", $atts["src"]);

				switch($info[1]){
					case "youtube":
						$js = str_replace(array("\n", "\t"), "", trim("
							  var tag = document.createElement('script');
							  tag.src = 'https://www.youtube.com/player_api';
							  var firstScriptTag = document.getElementsByTagName('script')[0];
							  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

							  var player;
							  function onYouTubePlayerAPIReady() {
							    player = new YT.Player('". sprintf("video-player-%s", self::$_id) ."', {
							      height: '390',
							      width: '100%',
							      videoId: '$videoId'
							    });
							  }"));
						break;

					case "vimeo": //not implemented yet
					default:
						throw new InvalidServiceProviderException(sprintf("%s is not an approved video service provider", $info[1]));
				}
				$script = new HTML("script", array("text" => trim($js)));

				echo $script->__toString(); //MEH
				
				return $HTML;
			}catch(InvalidServiceProviderException $e){
				die($e->getMessage());
			}
		}

		/**
		 * Wrapper function for all section plugins
		 *
		 * @shortcode [any_shortcode]
		 * @param  string $plugin   The plugin you want to create
		 * @param  mixed $data      Data to populate the plugin result with
		 * @return {SectionFactory} object
		 */
		public static function generate(){
			try {
				$class = __CLASS__;
				$callbackArgs = array();
				$args = func_get_args();

				if(method_exists($class, $args[2])){
					$callbackArgs = array(
						"atts" => $args[0],
						"content" => $args[1],
						);

					if(isset($args[1]["data"]) && is_array($args[1]["data"])){
						$callbackArgs["data"] = $args[2]["data"];
					}

					return call_user_func_array(array($class, $args[2]), $callbackArgs);
				}else {
					throw new \InvalidArgumentException(sprintf("Method not found SectionFactory::%s", $args[2]));
				}
			}catch(\InvalidArgumentException $e){
				die($e->getMessage());
			}
		}

		/**
		 * =====================================================================
		 * Boolean functions
		 * =====================================================================
		 */
		
		

	}

?>