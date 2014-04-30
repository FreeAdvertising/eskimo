<?php
	namespace Free\System;
	
	defined("FR_FRAME") or die;
	
	class DatabaseResult {
		public function __construct($results = array(), $callback = null, $columns = array()){
			if(sizeof($results) === 1){
				$this->result = $results[0];
			}else {
				$this->results = $results;
			}

			$this->columns = $columns;
			$this->callback = $callback;
			$this->num_rows = sizeof($results);

			return $this;
		}
	}
?>