<div class="col-md-4">
	<?php if(is_dynamic_sidebar()): ?>
		<aside class="static-sidebar-<?php echo $this->data->position; ?>">
			<ul>
				<?php dynamic_sidebar(sprintf("%s-sidebar", $this->data->position)); ?>
			</ul>
		</aside>
	<?php endif; ?>
</div>