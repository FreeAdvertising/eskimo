<?php	
	//Seriously, WP?  This is the recommended way to handle a custom posts page
	//template?  What a joke.
	if(have_posts()){
		return \Free\System\Factory::getTheme()->render("posts");
	}

	\Free\System\Factory::getTheme()->render();

?>