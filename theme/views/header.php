<!doctype html>
<html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml">

	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php wp_head(); ?>

		<title><?php echo get_bloginfo("site_name"); ?></title>

		<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />
	</head>
	
	<body <?php body_class(); ?>>
		<header class="meta">
			<div class="request-quote-form container" style="display: none;">
				<form action="<?php echo get_template_directory_uri(); ?>/api/forms/requestquote" method="post" role="form">
					<div class="col-md-6">
						<div class="form-group">
							<label for="name">Name</label>
							<input type="text" name="name" id="name" class="form-control" />
						</div>

						<div class="form-group">
							<label for="email">Email</label>
							<input type="email" name="email" id="email" class="form-control" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="message">Message</label>
							<textarea class="form-control" id="message" name="message"></textarea>
						</div>

						<div class="form-group">
							<p class="form-response"><span class="sending">Sending...</span></p>
						</div>

						<div class="form-group">
							<button class="btn btn-default send-btn">Send</button>
						</div>
					</div>
				</form>
			</div>
		</header>

	
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
				</div>
				</div>

			</header>

			
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