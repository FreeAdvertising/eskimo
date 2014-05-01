<?php
	namespace Free\System;

	use \Free\System\GenericList;
	
	defined("FR_FRAME") or die;
	
	class DatabaseResult {
		/**
		 * Holds data
		 * @var GenericList object
		 */
		private $_output;

		/**
		 * Create the databaseresult object
		 * @param array    $results  Array of results from a database query
		 * @param function $callback A method to call after building the DatabaseResult list (optional)
		 * @param array    $columns  Array of table columns to build a SectionFactory:: datatable with (optional)
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

		/**
		 * Get all results from the database call
		 * @return GenericList object
		 */
		public function getResults(){
			return $this->_output;
		}
	}
?>