<?php
	namespace Free\System;
	
	defined("FR_FRAME") or die;

	/**
	 * A class that makes building
	 * html elements easy, and is used
	 * similar to jQuery
	 *
	 * @package default
	 * @author Baylor Rae
	 * @url https://github.com/BaylorRae/HTML-Class-For-PHP/blob/master/html.class.php
	 */
	class HTML extends Generic {
	  
		private $tag;
		private $self_closing = false;
		private $attrs = array();
		private $self_closing_list = array('input', 'img', 'hr', 'br', 'meta', 'link');

		/**
		* Create an html element
		* 
		* If you leave $self_closing blank it will
		* determine whether or not to auto close
		*
		* @param string $tag - The tag's name div, input, form
		* @param array $attrs - Attributes class, id
		* @param boolean $self_closing
		* @author Baylor Rae'
		*/
		public function __construct($tag, $attrs = array(), $self_closing = null) {
			$this->tag = $tag;

			// force this tag to self close?
			if( is_null($self_closing)  )
				$this->self_closing = in_array($tag, $this->self_closing_list);
			else
				$this->self_closing = $self_closing;
			  
			// Make sure text is set
			$attrs['text'] = (empty($attrs['text'])) ? '' : $attrs['text'];

			$this->attrs = $attrs;
		}

		/**
		* Build the html element
		* 
		* @see $this->build()
		* @return void
		* @author Baylor Rae'
		*/
		public function __toString() {
			return $this->build();
		}

		/**
		* Add an attribute to the element
		* or multiple attributes if the first param is an array
		*
		* @param mixed $attr 
		* @param string $value 
		* @return void
		* @author Baylor Rae'
		*/
		public function attr($attr, $value = null) {
			if( is_array($attr) )
			  $this->attrs = array_merge($this->attrs, $attr);
			else
			  $this->attrs = array_merge($this->attrs, array($attr => $value));
		}

		/**
		 * Add items to the class attribute, if they aren't already there
		 * @param void
		 */
		public function addClass($value){
			if(isset($this->attrs["class"]) && false === $this->hasClass($value)){
				$this->attrs["class"] .= " $value";
			}

			return $this;
		}

		/**
		 * Functionally similar to document.querySelector('...').classList.contains('class')
		 * 
		 * @param  string $value Class to compare against the list of classes
		 * @return bool
		 */
		public function hasClass($value){
			return (strpos($this->attrs["class"], $value) !== false);
		}

		/**
		 * Removes a class from the attribute list
		 * 
		 * @param  string $value Class to remove
		 * @return bool
		 */
		public function removeClass($value){
			$classes = array_filter(explode(" ", $this->attrs["class"])); //filtered because each one classlist has a space at the end

			for($i = 0; $i < sizeof($classes); $i++){
				if(is_numeric($int = substr($classes[$i], -1))){
					if($result = sprintf($value, $int) !== false){
						//remove the class here
						unset($classes[$i]);

						$this->attrs["class"] = implode($classes, " ");

						return true;
					}
				}
			}

			return false;
		}

		public function text($value = null){
			if(false === is_null($value))
				$this->attrs["text"] = $value;

			return $this->attrs["text"];
		}

		/**
		* Creates the html element's opening and closing tags
		* as well as the attributes.
		*
		* @return void
		* @author Baylor Rae'
		*/
		public function build() {
			// Start the tag
			$output = '<' . $this->tag;

			// Add the attributes
			foreach( $this->attrs as $attr => $value ) {
				if( $attr == 'text' )
					continue;
			  
				if( is_integer($attr) )
					$attr = $value;

			  $output .= ' ' . $attr . '="' . $value . '"';
			}

			// Close the tag
			if( $this->self_closing ){
				$output .= ' />';
			} else {
				//$output .= '>' . Factory::getUtils()->str_replace("[break]", "<br />", $WHATEVER) . '</' . $this->tag . '>';
				$output .= '>' . $this->attrs["text"] . '</' . $this->tag . '>';
			}

			return $output;
		}

		/**
		 * Takes a string and makes any HTML tags HTML class objects
		 * TODO: re-implement using recursion because there are entirely too many
		 * 		 foreach's here
		 * 
		 * @return array
		 * @author Ryan Priebe
		 */
		public static function fromString($str, $test = false){
			$output = array();

			if($test){
				var_dump($str);
			}

			//if there is an HTML tag, do things
			if(strpos($str, "<") !== false){
				$htmlStringArray = array_filter(explode("\n", $str));
				$looper = array_merge($htmlStringArray, array());
				
				//there is still HTML in this string, remove it from it's current
				//location and append it to htmlStringArray
				if(preg_match("|<[^>]+>(.*)</[^>]+>|U", $htmlStringArray[0], $matches) > 0){
					$looper[0] = str_replace($matches[0], "", $looper[0]);
					$looper[] = $matches[0];
				}

				foreach($looper as $htmlstr){
					//empty elements aren't always array_filtered out, skip them
					if(strlen($htmlstr) < 2 || strpos($htmlstr, "<") === false){
						continue;
					}

					$xml = simplexml_load_string($htmlstr);
					$_obj = new HTML($xml->getName());
					$_obj->attr("text", $xml->__toString());

					foreach($xml->attributes() as $attribute => $value){
						$_obj->attr($attribute, $value);
					}

					foreach($xml as $key => $value){
						$c_xml = simplexml_load_string($value->asXML());
						$_child = new HTML($key);
						$_child->attr("text", $value->__toString());

						foreach($value->attributes() as $attribute => $value){
							$_child->attr($attribute, $value);
						}

						foreach($c_xml as $k => $v){
							$_grandchild = new HTML($k);
							$_grandchild->attr("text", $v->__toString());

							foreach($v->attributes() as $attribute => $va){
								$_grandchild->attr($attribute, $va);
							}

							$_child->append($_grandchild);
						}

						$_obj->append($_child);
					}

					$output[] = $_obj;
				}
			}

			//process [break] "tags" into <br/>'s
			if(sizeof($output) > 0){
				foreach($output as $el){
					//$el->text(Factory::getUtils()->str_replace("[break]", "<br />", $el->text()));
					$el->text(str_replace("[break]", "<br />", $el->text()));
				}
			}

			return $output;
		}

		/**
		* Clone the current element
		* to prevent effecting the original
		*
		* @return new html object
		* @author Baylor Rae'
		*/
		public function _clone() {
			return new html($this->tag, $this->attrs, $this->self_closing);
		}

		/**
		* Check if the object being used
		* in the methods below, is part of the html class
		*
		* @package default
		* @author Baylor Rae'
		*/
		private function check_class($obj) {
			return (@get_class($obj) == __class__);
		}

		/**
		* Append an element to the current
		* or for multiple use each element
		* as a parameter
		*
		* @return $this (for chaining)
		* @author Baylor Rae'
		*/
		public function append() {
			$elems = func_get_args();

			foreach( $elems as $obj ) {
				if( !$this->check_class($obj) )
					continue;
				
				$this->attrs['text'] .= $obj->build();
			}
				
			return $this;
		}

		/**
		 * Generate closing elements (</p>, </div>, etc) to add to an element
		 * @param  string  $element HTML tag name
		 * @param  integer $number  How many you want to append
		 * @return {HTML} object
		 */
		public function closed($element, $number = 1){
			$output = array();

			for($i = 0; $i < $number; $i++){
				$output[] = new HTML(sprintf("/%s", $element));
			}

			return $output;
		}

		/**
		* Prepend an element to the current
		* or for multiple use each element
		* as a parameter
		*
		* @return $this (for chaining)
		* @author Baylor Rae'
		*/
		public function prepend() {
			$elems = func_get_args();
			$elems = array_reverse($elems);

			foreach( $elems as $obj ) {
				if( !$this->check_class($obj) )
					continue;
				
				$this->attrs['text'] = $obj->build() . $this->attrs['text'];
			}

			return $this;
		}

		/**
		* Append this element onto another
		*
		* @param object $obj 
		* @return void
		* @author Baylor Rae'
		*/
		public function appendTo($obj) {
			if(!$this->check_class($obj))
				return false;
			  
			$obj->attrs['text'] .= $this->build();
		}

		/**
		* Prepend this element onto another
		*
		* @param object $obj 
		* @return void
		* @author Baylor Rae'
		*/
		public function prependTo($obj) {
			if( !$this->check_class($obj) )
				return false;
			  
				$obj->attrs['text'] = $this->build() . $obj->attrs['text'];
		}
	}

?>