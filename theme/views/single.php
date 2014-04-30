<?php
	$url = get_post_meta(get_the_ID(), "featured_image", true);
?>
<div class="container news-list view-detail">
	<div class="row">
		<div id="primary" class="site-content">
			<div id="content" role="main">
				<?php while ( have_posts() ) : the_post(); ?>
					<div class="post">
						<?php if($url): ?>
							<div class="col-md-4 post-thumbnail">
								<?php echo sprintf("<img src=\"%s\" alt=\"post-image\" class=\"post-thumbnail\" />", $url); ?>
							</div>
						<?php endif; ?>

						<div class="col-md-8">
							<h1 class="entry-title section-title skinny"><?php the_title(); ?></h1>

							<?php the_content(); ?>

							<nav class="nav-single">
								<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentytwelve' ); ?></h3>
								<span class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'twentytwelve' ) . '</span> %title' ); ?></span>
								<span class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'twentytwelve' ) . '</span>' ); ?></span>
							</nav><!-- .nav-single -->
						</div>
					</div>
				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->
	</div><!-- .row -->
</div><!-- .news-list -->