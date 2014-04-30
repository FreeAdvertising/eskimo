<?php if(is_dynamic_sidebar()): ?>
	<section class="widget-banner">
		<div class="row columns">
			<ul class="col-md-6 col-1">
				<?php dynamic_sidebar("top-banner"); ?>
			</ul>
		</div>
	</section>
<?php endif; ?>