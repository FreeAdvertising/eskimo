<?php
	/*
	Plugin Name: Custom Banners Widget
	Plugin URI: http://wearefree.ca
	Description: Display a custom banner widget in a custom banner position
	Author: Ryan Priebe
	Version: 1
	Author URI: http://wearefree.ca
	*/

	//begin the tragedy that is Wordpress' widget implementation
	 
	class custom_banner_widgets extends WP_Widget {
	 
		public function __construct() {
			parent::__construct(
				"custom_banners", 
				__("Custom Top Banners", "text_domain"), 
				array("description" => "Display custom text in the top banner area")
				);
		}
		 
		public function widget($args, $instance) {
			$page = (empty($instance["page_id"]) ? "" : $instance["page_id"]);
			$text = (empty($instance["text"]) ? "" : $instance["text"]);
			$title = (empty($instance["title"]) ? "" : $instance["title"]);
			$current_page_id = get_the_ID();

			if($page === $current_page_id){
				echo sprintf("<h1>%s</h1>", $title);
				echo $text;
			}
		}
		 
		public function update($new_instance, $old_instance) {
			$instance = $old_instance;

			$instance["page_id"] = (false === empty($new_instance["page_id"]) ? (int) $new_instance["page_id"] : 2);
			$instance["text"] = (false === empty($new_instance["text"]) ? $new_instance["text"] : "");
			$instance["title"] = (false === empty($new_instance["title"]) ? $new_instance["title"] : "");

			return $instance;
		}
		 
		public function form($instance) {
			$page = $instance["page_id"];
			$text = $instance["text"];
			$title = $instance["title"];

			//START HTML
			?>
				<p><label for="<?php echo $this->get_field_id('title'); ?>">Title</label></p>
				<p><input class="widefat" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo $title; ?>" /></p>
				<p><label for="<?php echo $this->get_field_id('page_id'); ?>">Page</label></p>
				<p><?php wp_dropdown_pages(array("name" => $this->get_field_name("page_id"), "id" => $this->get_field_id("page_id"), "class" => "widefat", "selected" => $page)); ?></p>
				<p><label for="<?php echo $this->get_field_id('text'); ?>">Text</label></p>
				<p><textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea></p>
			<?php
			//END HTML
		}

	}
?>