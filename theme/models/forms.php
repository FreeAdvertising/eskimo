<?php
	namespace Free\Theme;
	
	use \Free\System\Model;
	use \Free\System\Database;
	use \Free\System\Generic;
	use \Free\System\Security;
	use \Free\System\RequestParams;

	defined("FR_FRAME") or die;

	class FormsModel extends Model {
		public function doRequestQuote($url, RequestParams $params){
			$output = new Generic();
			$output->set("errors", array());
			$output->set("success", false);

			if(false === Security::is_email($params->data["email"])){
				$output->errors["email"] = true;
			}

			if(Security::is_empty($params->data["name"])){
				$output->errors["name"] = true;
			}

			if(Security::is_empty($params->data["message"])){
				$output->errors["message"] = true;
			}

			//there are no errors, lets do what we need to do here
			if(empty($output->errors)){
				//send email to the administrator
				$mail = array(
					"to" => "JaredDubinsky@pioneertrucklines.com",
					"subject" => "New Request for Quote",
					"message" => urldecode($params->data["message"]),
					"headers" => array(sprintf("From: %s <%s>", urldecode($params->data["name"]), urldecode($params->data["email"]))),
					);

				$output->success = wp_mail($mail["to"], $mail["subject"], $mail["message"], $mail["headers"]);

				//use the named log file instead of default.log
				//$this->logger->setLogFile($params->model_method);
				//write data to a log file (backup in case website stops sending
				//emails)
				//$this->logger->record(sprintf("Email: %s | Name: %s | Message: %s | Time: %s", urldecode($params->data["email"]), urldecode($params->data["name"]), urldecode($params->data["message"]), date("F jS, Y g:i:a")));
			}

			return $output;
		}
	}
?>