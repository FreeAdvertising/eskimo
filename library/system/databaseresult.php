<?php
	namespace Free\System;
	
	defined("FR_FRAME") or die;

	use \Free\System\GenericList;
	
	class DatabaseResult {
		/**
		 * Holds data
		 * @var GenericList object
		 */
		private $_output;

		/**
		 * Create the databaseresult object
		 * @param array  $results  [description]
		 * @param [type] $callback [description]
		 * @param array  $columns  [description]
		 */
		public function __construct($results = array(), $callback = null, $columns = array()){
			$this->_output = new GenericList();

			if(sizeof($results) === 1){
				$this->_output->push($results[0], "result");
			}else {
				$this->_output->push($results, "results");
			}

			$this->_output->push($columns, "columns");
			$this->_output->push($callback, "callback");
			$this->_output->push(sizeof($results), "num_rows");

			return $this;
		}

		public function getResults(){
			return $this->_output;
		}
	}
?>