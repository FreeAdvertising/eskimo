<?php

	namespace Free\System;

	/**
	 * GenericList
	 *
	 * Pretty self explanatory.  jQuery-esque syntax for ease of use.
	 */
	class GenericList {
		/**
		 * Stores list data
		 * @var array
		 */
		private $_bucket = array();
		
		/**
		 * Type of the array (associative|numeric)
		 * @var string
		 */
		private $_type = null;

		/**
		 * Used in GenericList::sort to get around usort scope issue
		 * PUBLIC because of PHP 5.3, don't ask
		 * @var string
		 */
		public $_key = null;

		/**
		 * Number of elements in the _bucket
		 * @var integer
		 */
		public $length = 0;

		/**
		 * Create the list and add default elements to it if required
		 * @param array $source Default item array
		 */
		public function __construct($source = array(), $type = "numeric"){
			$this->setType($type);

			if(is_array($source) && sizeof($source) > 0){
				$this->_bucket = $source;

				$this->length = sizeof($source);
			}

			return $this;
		}

		/**
		 * Set the type of the list
		 * @param string $type Desired array generic type
		 */
		public function setType($type){
			try {
				if(in_array($type, array("associative", "numeric"))){
					$this->_type = $type;
				}
			}catch(Exception $e){
				echo $e->getMessage();
			}

			return $this;
		}

		/**
		 * Get the type of list
		 * @return string
		 */
		public function getType(){
			return $this->_type;
		}

		/**
		 * Add an item to the list
		 * @param  mixed  $item Item you want to add to the list
		 * @param  string $key  An optional key value
		 * @return mixed
		 */
		public function push($item, $key = null){
			if(false === is_null($key) || $this->_type === "associative"){
				$this->_bucket[$key] = $item;
			}else {
				$this->_bucket[] = $item;
			}

			return $item;
		}

		/**
		 * Remove an item from the list by key
		 * Supports chaining
		 * @param  string $key Key value for the item you want to delete
		 * @return GenericList object
		 */
		public function pop($key){
			if(isset($this->_bucket[$key])){
				unset($this->_bucket[$key]);
			}

			return $this;
		}

		/**
		 * Prepend items to the beginning of _bucket
		 * Supports chaining
		 * Note: Does NOT preserve keys
		 * @param  mixed $args  String|Array, items you want to prepend to the list
		 * @return GenericList object
		 */
		public function unshift($args){
			$diff = array_unshift($this->_bucket, $args);

			//size of array changes, modify length property accordingly
			$this->length = ($this->length - $diff);

			return $this;
		}

		/**
		 * Append items to the end of _bucket
		 * Supports chaining
		 * Note: Does NOT preserve keys
		 * @return GenericList object
		 */
		public function shift(){
			array_shift($this->_bucket);

			//size of array changes, modify length property accordingly
			$this->length++;

			return $this;
		}

		/**
		 * Returns a specific item by key
		 * @param  string $key     The key whose value you want to return
		 * @param  string $default A default value to return if the key value does not exist
		 * @return string
		 */
		public function indexOf($key, $default = null){
			if(isset($this->_bucket[$key])){
				return $this->_bucket[$key];
			}

			return $default;
		}

		/**
		 * Modify an existing _bucket list item by key
		 * @param  mixed $key   Key value of the item you would like to manipulate
		 * @param  mixed $value The value you would like to assign
		 * @return bool
		 */
		public function modify($key, $value){
			if(array_key_exists($key, $this->_bucket)){
				return ($this->_bucket[$key] = $value);
			}

			return false;
		}

		/**
		 * Limit the _bucket list to a specific length (similar to SQL LIMIT #)
		 * Supports chaining
		 * @param  integer $size Final length of the list
		 * @return GenericList object
		 */
		public function limit($size = 10){
			$_tmp = array();

			//using an index-agnostic loop to get around some index issues
			//when using $size = 1
			$counter = 1;
			foreach($this->_bucket as $key => $item){
				if($counter <= (int) $size){
					$_tmp[$key] = $item;
				}

				$counter++;
			}

			if(sizeof($_tmp) > 0){
				$this->_bucket = $_tmp;
				$this->length = sizeof($_tmp);
			}

			return $this;
		}

		/**
		 * Returns a value in the array, if an element in the array satisfies 
		 * the provided testing function. Otherwise a default is returned.
		 * @param  string $value   The value you want to locate within _bucket
		 * @param  mixed  $default A default value to return
		 * @return mixed
		 */
		public function find($value, $default = null){
			$match = null;

			for($i = 0; $i < $this->length; $i++){
				if(is_object($this->_bucket[$i]) || is_array($this->_bucket[$i])){
					foreach($this->_bucket[$i] as $key => $_item){
						if($key == $value || $_item == $value){
							$match = $this->_bucket[$i];
						}
					}
				}else {
					if($value == $this->_bucket[$i]){
						$match = $this->_bucket[$i];
					}
				}
			}

			if(false === is_null($match)){
				return $match;
			}

			return $default;
		}

		/**
		 * Get the key values of the _bucket list
		 * @param  string $filter  Only get keys whose values equal this
		 * @return array
		 */
		public function keys($filter = null){
			if(false === is_null($filter))
				return array_keys($this->_bucket, $filter);

			return array_keys($this->_bucket);
		}

		/**
		 * Sort the _bucket list by KEY
		 * Returns an entirely new GenericList object by design, the problem was
		 * that running multiple sort()'s in a row caused subsequent results to
		 * be sorted according to each previous call's criteria
		 * @param string $key  Key value to sort the array by
		 * @param string $dir  Sorting direction
		 * @return GenericList object
		 */
		public function sort($key, $dir = "DESC"){
			//hack to get around scoping issue
			$this->_key = $key;
			$dir = strtoupper($dir);
			$clone = $this->_bucket;
			$ref = $this;

			if($dir == "DESC"){
				usort($clone, function($a, $b) use ($ref) {
					if(is_object($a) && is_object($b)){
						if(property_exists($a, $ref->_key) && property_exists($b, $ref->_key))
							return ($a->{$ref->_key} - $b->{$ref->_key} > 0);

						return false;
					}

					return $a[$this->_key] - $b[$this->_key];
				});
			}elseif($dir == "ASC"){
				usort($clone, function($a, $b) use($ref) {
					if(is_object($a) && is_object($b)){
						if(property_exists($a, $ref->_key) && property_exists($b, $ref->_key))
							return ($a->{$ref->_key} - $b->{$ref->_key} < 0);

						return false;
					}

					return $a[$this->_key] - $b[$this->_key] * -1;
				});
			}

			return new GenericList($clone);
		}

		/**
		 * View the raw _bucket list
		 * @return array
		 */
		public function dump(){
			if($this->length === 1){
				return $this->_bucket[0];
			}

			return $this->_bucket;
		}

		/**
		 * String representation of the array
		 * @return string
		 */
		public function toString(){
			return serialize($this->_bucket);
		}

		/**
		 * Short hand for method to loop through GenericList items
		 * TODO: implement a counter to pass to callback
		 * @param  function $callback       A function to call which handles data within the loop
		 * @param  array    $out_of_scopes  A list of objects which should be added to the local scope
		 * @param  bool     $return         Return the value of $callback
		 * @return mixed
		 */
		public function each($callback, $out_of_scopes = array(), $return = false){
			try {
				if(is_callable($callback)){
					$oos = new GenericList($out_of_scopes);
					$index = 1;

					switch($this->_type){
						case "associative":
							foreach($this->_bucket as $item){
								//numeric types have an index by default, associative
								//do not
								$oos->push($index++);

								if(false === is_null($item)){
									if($return){
										return $callback($item, $oos);
									}else {
										$callback($item, $oos);
									}
								}
							}
						break;
						
						default:
						case "numeric":
							for($i = 0; $i < sizeof($this->_bucket); $i++){
								if(false === is_null($this->_bucket[$i])){
									if($return){
										return $callback($i, $this->_bucket[$i], $oos);
									}else {
										$callback($i, $this->_bucket[$i], $oos);
									}
								}
							}
						break;
					}

					return $this;
				}else {
					throw new Exception("GenericList::loop requires a function for the callback argument.");
				}
			}catch(Exception $e){
				echo $e->getMessage();
			}

			return false;
		}
	}

?>