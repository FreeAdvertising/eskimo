<!doctype html>
<html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml">

	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php wp_head(); ?>

		<title><?php echo get_bloginfo("site_name"); ?> | North America's leader 
in pipeline and oilfield transportation</title>

<meta name="description" content="Privately owned and operated, Pioneer Truck Lines Ltd. is Edmonton’s total transportation solutions company, specializing in oilfield and pipeline delivery services.">
<meta name="keywords" content="edmonton, camrose, alberta, trucking, transportation, hauling, pipeline, stockpiling, heavy hauling, oilfield, picker, crane, logistics, storage, canada, Pipe stringing, Stockpiling pipe, Pipeline stringing, Pipelining, Pipeline construction, Pipe hauling, Hauling pipe, Hauling equipment, Northern Alberta Trucking, Alberta Trucking, Fort McMurray Trucking, Specailized transportation, Edmonton dispatchers, North Dakota pipe, North Dakota trucking, Trans Border pipe, Trans Border shipping, Sideboom Rental, Pipelayers, Pipelayer rentals, Pipe laying equipment, Sideboom haul, Stringing stick, Heavy Haulers, Edmonton transportation companies, Edmonton trucking companies, Edmonton trucking, Pipeline trucking, Pipeline trucking company, Pipeline logistics company, Construction transport, Calgary trucking, Pipe storage, Oil Sands construction, Oil sands hauling, Facilities construction logistics, Pipeline hauling, Pipe logistics, Transload, Reload, Pickers, Boom trucks, Heavy equipment haul, Alberta Logistics, Western Canadian Logistics, Pipeline consultants, Energy logistics, Energy sector logistics, Facilities transporter, Heavy construction transporter, Energy facility logistics, Hauling pipe, Stringing pipe, Pipeline spread transportation, Line Pipe, Coated pipe, Pole trailers, Mainline construction, Spool hauling, Pipe spool hauling, Valve transportation, Pipeline installation, Oilfield hauling, Oil and Gas logistics, Bridge Beam hauling, Specialty loads, Rig mat hauling, 80 foot pipe, Double jointed pipe, Ice road trucking, Camp moves, Pipe yard, Pipe transportation, Low bed, Lowbedding, Flat deck hauling, Crane services">

		<!-- <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>  -->

<meta property="og:title" content="Pioneer Trucklines"/>
<meta property="og:type" content="website"/>
<meta property="og:url" content="http://www.pioneertrucklines.com/"/>
<meta property="og:image" content="http://pioneer.wearefree.ca/wp-content/uploads/2014/03/Pioneer_logo.jpg"/>
<meta property="og:site_name" content="Pioneer Trucklines"/>
<meta property="og:description" content="Privately owned and operated, Pioneer Truck Lines Ltd. is Edmonton’s total transportation solutions company, specializing in oilfield and pipeline delivery services."/>

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

			<div class="container">
				<div class="row">
					<nav class="col-md-3">
						<ul class="social-icons">
							<li><a href="https://www.facebook.com/pages/Pioneer-Truck-Lines-Ltd/678286332234829?ref=hl" target="_blank" class="facebook" title="Facebook">Facebook</a></li>
							<li><a href="https://www.youtube.com/channel/UCtMFnAU6-jP0Oov5K3QHPRA" target="_blank" class="youtube" title="Youtube">Youtube</a></li>
							<li><a href="https://twitter.com/PioneerTrucking" target="_blank" class="twitter" title="Twitter">Twitter</a></li>
							<li><a href="http://www.linkedin.com/profile/view?id=333039844&trk=nav_responsive_tab_profile" target="_blank" class="linkedin" title="Linkedin">Linkedin</a></li>
							<li><a href="mailto:general@pioneertrucklines.com?Subject=Website%20Quote%20Request" target="_top"" class="email" title="Email">Email</a></li>
						</ul>
					</nav>

					<nav class="col-md-9">
						<ul>
							<li class="request-a-quote"><a href="#" class="request-quote">Request A Quote</a></li>
							<li class="toll-free">Call us Toll Free 1-800-315-3148</li>
							<li class="search"><?php get_search_form(); ?></li>
						</ul>
					</nav>
				</div>
			</div>
		</header>

	
		<div class="custom-container">
		<div id="headerBg">
		<div id="squares">			<header class="top">
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