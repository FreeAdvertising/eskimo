<?php
	namespace Free\System;

	defined("FR_FRAME") or die;
	
	abstract class Security {
		/**
		 * Clean _POST or _GET request data
		 * @param  array  $values
		 * @return {RequestParams} object
		 */
		public static function sanitizeRequestData($values = array()){
			$output = new RequestParams();

			if(sizeof($values) > 0){
				$output->id = (int) $values["id"];

				foreach($values as $key => $value){
					if(is_string($value)){
						$output->data[$key] = filter_var($value, FILTER_SANITIZE_ENCODED, FILTER_SANITIZE_STRING);
					}

					if(is_numeric($value)){
						$output->data[$key] = (int) filter_var($value, FILTER_SANITIZE_NUMBER_INT);
					}
				}
			}

			return $output;
		}

		/**
		 * Determine if a string is a valid email
		 * @param  string  $email
		 * @return boolean
		 */
		public static function is_email($email){
			$email = urldecode($email); //FILTER_SANITIZE_ENCODED urlencode's the string

			return (false === filter_var($email, FILTER_VALIDATE_EMAIL) ? false : true);
		}

		/**
		 * Determine if the string has a value
		 * TODO: move to \Utils
		 * @param  string $value
		 * @return boolean
		 */
		public static function is_empty($value){
			return (empty($value) && strlen($value) === 0);
		}
	}
?>