<?php
	/**
	 * Bootstrap Free Framework
	 */
	define("FR_BASE", dirname(__FILE__));

	include_once "library/includes/defines.php";
	include_once "library/bootstrap.php";
	include_once FR_BASE ."/widgets.php";

	$theme_url = get_template_directory_uri() ."/theme/";
	
	/**
	 * Setup theme options
	 * @return [type] [description]
	 */
	function free_framework_setup(){
		if(!is_admin()){
			add_theme_support('automatic-feed-links');
			//enable support for custom post formats - NOPE not yet
			//add_theme_support('post-formats', array( 'aside', 'gallery' ));

			// This theme supports a variety of post formats.
			add_theme_support('post-formats', array('aside', 'image', 'link', 'quote', 'status'));
			add_theme_support('post-thumbnails');
			//register menus
			register_nav_menu('primary', __('Primary', 'free-framework'));
			register_nav_menu('secondary', __('Secondary', 'free-framework'));
		}
	}
	add_action( 'after_setup_theme', 'free_framework_setup' );

	/**
	 * Create widget positions
	 */	 
	function free_framework_widget_init(){
		//register dynamic sidebars
		register_sidebar( array(
			"id"          => 'top-banner',
			"name"        => __("Top banner", "free-framework"),
			"description" => __("Top banner, if disabled page displays full width.", "free-framework"),
		) );
		register_sidebar( array(
			"id"          => 'right-sidebar',
			"name"        => __("Right sidebar", "free-framework"),
			"description" => __("Right hand sidebar, if disabled page displays full width.", "free-framework"),
		) );

		register_sidebar( array(
			"id"          => 'left-sidebar',
			"name"        => __("Left Sidebar", "free-framework"),
			"description" => __("Left hand sidebar, if disabled page displays full width.", "free-framework"),
		) );

		register_sidebar( array(
			"id"          => 'footer-c1',
			"name"        => __("Footer Column 1", "free-framework"),
			"description" => __("First footer widget column", "free-framework"),
		) );

		register_sidebar( array(
			"id"          => 'footer-c2',
			"name"        => __("Footer Column 2", "free-framework"),
			"description" => __("Second footer widget column", "free-framework"),
		) );

		register_sidebar( array(
			"id"          => 'footer-c3',
			"name"        => __("Footer Column 3", "free-framework"),
			"description" => __("Third footer widget column", "free-framework"),
		) );

		register_sidebar( array(
			"id"          => 'footer-c4',
			"name"        => __("Footer Column 4", "free-framework"),
			"description" => __("Fourth footer widget column", "free-framework"),
		) );

		register_sidebar(array(
		  'name' => __( 'Right Hand Sidebar' ),
		  'id' => 'right-sidebar',
		  'description' => __( 'Widgets in this area will be shown on the right-hand side.' ),
		  'before_title' => '<h1>',
		  'after_title' => '</h1>'
		));

		register_widget('custom_banner_widgets');
	}
	add_action( 'widgets_init', 'free_framework_widget_init' );
	
	/**
	 * Add 'Read More' link to the post excerpt
	 */	 
	function new_excerpt_more( $more ) {
		return ' <a class="read-more" href="'. get_permalink( get_the_ID() ) . '">Read More</a>';
	}
	add_filter( 'excerpt_more', 'new_excerpt_more' );
	
	/**
	 * enqueue CSS for wp_head()
	 */
	function free_framework_enqueue_styles(){
		global $theme_url;

		if (is_singular() && comments_open() && get_option( 'thread_comments' )){
			wp_enqueue_script( 'comment-reply' );
		}

		//build in TWBS and custom stylesheets
		wp_enqueue_style('free-framework-bootstrap', $theme_url ."vendor/bootstrap/css/bootstrap.min.css");
		wp_enqueue_style('free-framework-bootstrap-theme', $theme_url ."vendor/bootstrap/css/bootstrap-theme.min.css");
		wp_enqueue_style('free-framework-custom-css', $theme_url ."css/custom.css");
		wp_enqueue_style('free-framework-font-titillium', "http://fonts.googleapis.com/css?family=Titillium+Web");
	}
	add_action("wp_enqueue_scripts", "free_framework_enqueue_styles");

	/**
	 * Add body classes to WPJOBBOARD pages
	 */
	function free_framework_add_jobboard_bodyclass($classes){
		$uri = Free\System\URI::getInstance();

		//add generic plugin class to wpjobboard pages
		if($uri->segment(0) == "jobs"){
			$classes[] = "plugin-wpjobboard";
		}

		//add job class if it's a job page, duh
		if($uri->segment(0) == "jobs" && $uri->segment(1) == "view"){
			$classes[] = "job";
		}

		$classes[] = Free\System\Factory::getUtils()->page_title();
		
		return $classes;
	}
	add_filter("body_class", "free_framework_add_jobboard_bodyclass");

	/**
	 * enqueue JS for wp_head()
	 */
	function free_framework_enqueue_scripts(){
		global $theme_url;

		//build in TWBS and custom stylesheets
		wp_enqueue_script('free-framework-bootstrap-jquery', $theme_url ."vendor/bootstrap/js/jquery.min.js");
		wp_enqueue_script('free-framework-bootstrap-core', $theme_url ."vendor/bootstrap/js/bootstrap.min.js");
		wp_enqueue_script('free-framework-detect-js', $theme_url ."vendor/detect.js");
		wp_enqueue_script('free-framework-custom-js', $theme_url ."js/custom.js");
		// wp_enqueue_script('free-framework-calmconsole', "http://labs.ryanpriebe.com/gonzo/CalmConsole.js");
	}
	add_action("wp_footer", "free_framework_enqueue_scripts");

	/**
	 * Run the following shortcodes on widgets
	 */
	add_filter('widget_text', 'do_shortcode');

	/**
	 * Process shortcodes
	 */
	add_shortcode("item",        array("\Free\System\SectionFactory", "generate"));
	add_shortcode("datatable",   array("\Free\System\SectionFactory", "generate"));
	add_shortcode("slider",      array("\Free\System\SectionFactory", "generate"));
	add_shortcode("testimonial", array("\Free\System\SectionFactory", "generate"));
	add_shortcode("section",     array("\Free\System\SectionFactory", "generate"));
	add_shortcode("linebreak",   array("\Free\System\SectionFactory", "generate"));
	add_shortcode("paragraph",   array("\Free\System\SectionFactory", "generate"));
	add_shortcode("image",       array("\Free\System\SectionFactory", "generate"));
	add_shortcode("title",       array("\Free\System\SectionFactory", "generate"));
	add_shortcode("container",   array("\Free\System\SectionFactory", "generate"));
	add_shortcode("column",      array("\Free\System\SectionFactory", "generate"));
	add_shortcode("video",       array("\Free\System\SectionFactory", "generate"));
	add_shortcode("sidebar",     array("\Free\System\SectionFactory", "generate"));
	add_shortcode("button",      array("\Free\System\SectionFactory", "generate"));
	add_shortcode("linkbutton",  array("\Free\System\SectionFactory", "generate"));

?>