<?php if ( have_posts() ) : ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<?php if(true === $option["show_title"]): ?>
			<h1 class="entry-title">
				<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
			</h1>
		<?php endif; ?>
		<?php the_content(); ?>
	<?php endwhile; ?>

<?php else : ?>
	<article id="post-0" class="post no-results not-found">

	<?php if ( current_user_can( 'edit_posts' ) ) :
		// Show a different message to a logged-in user who can add posts.
	?>
		<header class="entry-header">
			<h1 class="entry-title"><?php _e( 'No posts to display', 'twentytwelve' ); ?></h1>
		</header>

		<div class="entry-content">
			<p><?php printf( __( 'Ready to publish your first post? <a href="%s">Get started here</a>.', 'twentytwelve' ), admin_url( 'post-new.php' ) ); ?></p>
		</div><!-- .entry-content -->

	<?php else :
		// Show the default message to everyone else.
	?>
		<header class="entry-header">
			<h1 class="entry-title"><?php _e( 'Nothing Found', 'twentytwelve' ); ?></h1>
		</header>

		<div class="entry-content">
			<p><?php _e( 'Apologies, but no results were found. Perhaps searching will help find a related post.', 'twentytwelve' ); ?></p>
			<?php get_search_form(); ?>
			<?php get_sidebar( $Right_Hand_Sidebar ); ?>
		</div><!-- .entry-content -->
	<?php endif; // end current_user_can() check ?>

	</article><!-- #post-0 -->

<?php endif; // end have_posts() check ?>