<?php
	namespace Free\Theme;

	use \Free\System\Controller;
	use \Free\System\Output;
	use \Free\System\RequestParams;

	defined("FR_FRAME") or die;

	class ApiController extends Controller {
		public function __construct(){
			parent::__construct();
			
			return $this->output->setResponse("OK", 200)->setType("application/json");
		}

		public function requestquote(RequestParams $params){
			//ensure this request returns correct type and status
			$this->output->setResponse("OK", 200)->setType("application/json");

			$output = array();
			$output["message"] = "There was a problem submitting your request.";
			$output["status"] = 0;
			$params->model_method = __FUNCTION__;

			if($result = $this->model->doRequestQuote($url, $params)){
				$output["message"] = "Your request has been received.  Please allow for 24-48 hours for a response.";
				$output["status"] = 1;

				//request was successful but there is invalid data
				if(false === $result->success){
					if($result->errors["email"]){
						$output["message"] = "Please enter a valid email";
					}

					if($result->errors["name"]){
						$output["message"] = "Please enter your name.";
					}

					if($result->errors["message"]){
						$output["message"] = "Please enter a message.";
					}
				}
			}

			echo json_encode($output);
		}
	}
?>