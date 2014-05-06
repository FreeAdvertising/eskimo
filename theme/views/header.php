<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml">

	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php wp_head(); ?>

		<title><?php echo get_bloginfo("site_name"); ?></title>

		<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />
	</head>
	
	<body <?php body_class(); ?>>	
		<div class="custom-container">
			<div id="headerBg">
				<div id="squares">
					<header class="top">
						<div class="container">
							<div class="row">
								<div class="top-logo col-md-2">
									<h1 class="logo"><a href="<?php bloginfo('url'); ?>" style="background-image: url('<?php echo get_template_directory_uri(); ?>/theme/images/logo.png');"><?php echo get_bloginfo("site_name"); ?></a></h1>
								</div>

								<div class="top-menu col-md-10">
									<?php 
										//desktop menu
										wp_nav_menu(array(
											"theme_location" => "primary",
											"container" => "nav",
											"container_class" => "desktop-menu clearfix hidden-xs",
											)); 
									?>
								</div>
							</div>
								<?php echo \Free\System\SectionFactory::bannerWidgets(); ?>
						</div>
					</header>
				</div>
			</div>
			
			<header class="navbar navbar-default navbar-fixed-top hidden-lg hidden-md hidden-sm">
				<div class="navbar-header container">
					<a class="navbar-brand" href="/">
						<span class="glyphicon glyphicon-heart"></span> <?php echo get_bloginfo("site_name"); ?>
					</a>

					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#primary-container-mobile">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>

				<?php 
					//mobile menu, invisible to desktop/tablet users
					wp_nav_menu(array(
						"theme_location" => "primary",
						"menu_class" => "nav navbar",
						"container" => "nav",
						"container_class" => "collapse navbar-collapse",
						"container_id" => "primary-container-mobile"
						)); 
				?>
				
			</header>

			<div class="content-container">