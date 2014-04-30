<?php if(is_dynamic_sidebar()): ?>
	<footer class="section-footer">
		<section class="container">
			<div class="row columns">
				<ul class="col-md-3 col-1">
					<?php dynamic_sidebar("footer-c1"); ?>
				</ul>
				<ul class="col-md-3 col-2">
					<?php dynamic_sidebar("footer-c2"); ?>
				</ul>
				<ul class="col-md-3 col-3">
					<?php dynamic_sidebar("footer-c3"); ?>
				</ul>
				<ul class="col-md-3 col-4">
					<?php dynamic_sidebar("footer-c4"); ?>
				</ul>
			</div>

			<div class="row copyright">
				<div class="col-md-10">
					Copyright <?php echo date("Y"); ?> All Rights Reserved
				</div>
				<div class="col-md-2">
					<p>Built by <a href="http://wearefree.ca" target="_blank">Free</a></p>
				</div>
			</div>
		</section>
	</footer>
<?php endif; ?>