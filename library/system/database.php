<?php
	namespace Free\System;
	
	defined("FR_FRAME") or die;

	class Database extends Generic {
		private $_db;
		private $_default_options = array(
			"limit" => -1, //don't limit results
			"id" => 0,
			"table" => null,
			"columns" => array(),
			"filter" => null,
			);

		public function __construct(){
			global $wpdb; //gross, wordpress.. you're gross

			$this->_db = $wpdb;
		}

		public function get(Generic $params){
			//merge default and user-selected parameters
			$param = (object) array_merge($this->_default_options, (array) $params);

			//column items should be strings
			//TODO: move "Process $data->columns[$i] to determine if there are any AS"
			//formatting here from SectionFactory::table
			$columns = implode($param->columns, ", ");

			if(false === is_null($param->filter)){
				$query = sprintf("SELECT %s FROM %s%s WHERE %s = %d", $columns, $this->_db->prefix, $param->table, $param->filter, (int) $param->id);
			}else {
				$query = sprintf("SELECT %s FROM %s%s", $columns, $this->_db->prefix, $param->table);
			}

			//limit results if necessary
			if($param->limit > 0){
				$query .= sprintf(" LIMIT %d", (int) $param->limit);
			}

			$results = $this->_db->get_results($query);

			return new DatabaseResult($results, $this, $param->columns);
		}

		public function loadArbitraryObjectList($queryString){

		}

		public function loadObjectList(Generic $param){

		}

		public function loadObject(Generic $param){

		}
	}

?>