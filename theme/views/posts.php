<?php
/*
Template Name: Blog Loop
*/

$_post_format = get_post_format();
?>
<div class="container news-list">
	<div class="row">
<?php if ( have_posts() ) : ?>
	<?php while ( have_posts() ) : the_post(); ?>
<?php
	switch ($_post_format) {
		case "video":
?>
		<article class="video">
			<div class="post_info">
				<div class="inner">
					<div class="post_date">
						<span class="date"><?php the_time('d'); ?></span>
						<span class="month"><?php the_time('M'); ?></span>
					</div>
				</div>
			</div>
			<div class="post_content_holder">
				<div class="post_image">
					<div class="inner">
						<div class="post_image_video">
							<?php $_video_type = get_post_meta(get_the_ID(), "video_format_choose", true);?>
							<?php $video_height = 220; ?>
							<?php if($_video_type == "youtube") { ?>
								<iframe height="<?php echo $video_height; ?>" src="http://www.youtube.com/embed/<?php echo get_post_meta(get_the_ID(), "video_format_link", true);  ?>?wmode=transparent" wmode="Opaque" frameborder="0" allowfullscreen></iframe>
							<?php } elseif ($_video_type == "vimeo"){ ?>
								<iframe height="<?php echo $video_height; ?>" src="http://player.vimeo.com/video/<?php echo get_post_meta(get_the_ID(), "video_format_link", true);  ?>?title=0&amp;byline=0&amp;portrait=0" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
							<?php } ?>
						</div>
					</div>	
				</div>
				<div class="post_text">
					<div class="inner">
						<h2><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
						<span class="post_infos"><?php _e('In','qode'); ?> <?php the_category(', '); ?><?php if($blog_hide_comments != "yes"){ ?> <?php _e('|','qode'); ?> <a  class="post_comments" href="<?php comments_link(); ?>"><?php comments_number( __('No comment','qode'), '1 '.__('Comment','qode'), '% '.__('Comments','qode') ); ?></a><?php } ?></span>
						<?php the_excerpt(); ?>
					</div>
				</div>
			</div>
		</article>	

<?php
		break;
		case "audio":
?>
		<article class="audio">
			<div class="post_info">
				<div class="inner">
					<div class="post_date">
						<span class="date"><?php the_time('d'); ?></span>
						<span class="month"><?php the_time('M'); ?></span>
					</div>
					
				</div>
			</div>
			<div class="post_content_holder">
				<div class="post_image">
					<div class="inner">
						<audio src="<?php echo get_post_meta(get_the_ID(), "audio_link", true) ?>" controls="controls">
							<?php _e("Your browser don't support audio player","qode"); ?>
						</audio>
					</div>
				</div>
				<div class="post_text">
					<div class="inner">
						<h2><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
						<span class="post_infos"><?php _e('In','qode'); ?> <?php the_category(', '); ?><?php if($blog_hide_comments != "yes"){ ?> <?php _e('|','qode'); ?> <a  class="post_comments" href="<?php comments_link(); ?>"><?php comments_number( __('No comment','qode'), '1 '.__('Comment','qode'), '% '.__('Comments','qode') ); ?></a><?php } ?></span>
						<?php the_excerpt(); ?>
					</div>
				</div>
			</div>
		</article>
<?php
		break;
		case "link":
?>
			<article class="link">
				<div class="post_info">
					<div class="inner">
						<div class="post_date">
							<span class="date"><?php the_time('d'); ?></span>
							<span class="month"><?php the_time('M'); ?></span>
						</div>
					</div>
				</div>
				<div class="post_content_holder">
					<div class="link_holder">
						<div class="link_mark">
							<span></span>
						</div>
						<div class="link_text_holder">
							<div class="link_text">
								<h2><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>	
							</div>
						</div>
					</div>
				</div>
			</article>
<?php
		break;
		case "gallery":
?>
			<article class="gallery">
				<div class="post_info">
					<div class="inner">
						<div class="post_date">
							<span class="date"><?php the_time('d'); ?></span>
							<span class="month"><?php the_time('M'); ?></span>
						</div>
					</div>
				</div>
				<div class="post_content_holder">
					<div class="post_image">
						<div class="inner">
							<div class="flexslider">
								<ul class="slides">
									<?php
										$post_content = get_the_content();
										preg_match('/\[gallery.*ids=.(.*).\]/', $post_content, $ids);
										$array_id = explode(",", $ids[1]);
										
										foreach($array_id as $img_id){ ?>
											<li><a href="<?php the_permalink(); ?>"><?php echo wp_get_attachment_image( $img_id, 'full' ); ?></a></li>
										<?php } ?>
								</ul>
							</div>
						</div>
					</div>
					<div class="post_text">
						<div class="inner">
							<h2><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
							<span class="post_infos"><?php _e('In','qode'); ?> <?php the_category(', '); ?><?php if($blog_hide_comments != "yes"){ ?> <?php _e('|','qode'); ?> <a  class="post_comments" href="<?php comments_link(); ?>"><?php comments_number( __('No comment','qode'), '1 '.__('Comment','qode'), '% '.__('Comments','qode') ); ?></a><?php } ?></span>
							<?php the_excerpt(); ?>
						</div>
					</div>
				</div>
			</article>
<?php
		break;
		case "quote":
?>
			<article class="blockquote">
				<div class="post_info">
					<div class="inner">
						<div class="post_date">
							<span class="date"><?php the_time('d'); ?></span>
							<span class="month"><?php the_time('M'); ?></span>
						</div>
					</div>
				</div>
				<div class="post_content_holder">
					<div class="blockquote_holder">
						<div class="blockquote_mark">
							<span></span>
						</div>
						<div class="blockquote_text_holder">
							<div class="blockquote_text">
								<h2><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo get_post_meta(get_the_ID(), "quote_format", true); ?></a></h2>
								<span class="blockquote_author">&mdash; <?php the_title(); ?></span>	
							</div>
						</div>
					</div>
				</div>
			</article>
<?php
		break;
		default:
?>
		<article class="standard clearfix">
			<div class="post_info">
				<div class="inner">
					<div class="post_date">
						<span class="date"><?php the_time('d'); ?></span>
						<span class="month"><?php the_time('M'); ?></span>
					</div>
				</div>
			</div>
			<div class="post_content_holder">
				<?php
					$url = get_post_meta(get_the_ID(), "featured_image", true);
				?>
				<?php if ( $url ) { ?>
					<div class="post_image col-md-3">
						<div class="inner">
							<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
								<?php
									$url = get_post_meta(get_the_ID(), "featured_image", true); 

									if($url){
										echo sprintf("<img src=\"%s\" alt=\"post-image\" class=\"post-thumbnail\" />", $url);
									}
								?>

							</a>
						</div>
					</div>
				<?php } ?>
				<div class="post_text<?php if ($url) { echo " no_image col-md-8"; }else { echo " col-md-12"; } ?> ">
					<div class="inner">
						<h2><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
						<span class="post_infos"><?php _e('In','qode'); ?> <?php the_category(', '); ?><?php if($blog_hide_comments != "yes"){ ?> <?php _e('|','qode'); ?> <a  class="post_comments" href="<?php comments_link(); ?>"><?php comments_number( __('No comment','qode'), '1 '.__('Comment','qode'), '% '.__('Comments','qode') ); ?></a><?php } ?></span>  
						<?php the_excerpt(); ?>
					</div>
				</div>
			</div>
		</article>
<?php
}
?>
<?php endwhile; ?>
<?php endif; ?>
	</div>
</div>