<?php
	namespace Free\System;

	defined("FR_FRAME") or die;

	class RequestParams extends Generic {
		// acceptable request params, all others are ignored
		public $id = 0;
		public $data = array();
		public $model_method;
	}

?>