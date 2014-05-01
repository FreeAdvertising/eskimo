<?php
	namespace Free\Theme;
	
	use \Free\System\Model;
	use \Free\System\Generic;
	use \Free\System\Database;

	defined("FR_FRAME") or die;

	class HomeModel extends Model {
		public function test($properties){
			$params = new Generic();
			$params->set("table", "usermeta");
			$params->set("columns", array("umeta_id as ID", "user_id As UID", "meta_key as M_KEY"));
			$params->set("limit", $properties->limit);
			//$params->set("filter", "umeta_id");
			//$params->set("id", $id);

			return $this->db->get($params);
		}
	}
?>