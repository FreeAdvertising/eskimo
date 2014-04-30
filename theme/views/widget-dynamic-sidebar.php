<?php if(is_dynamic_sidebar()): ?>
	<aside class="sidebar-<?php echo $option["position"]; ?> col-md-4">
		<?php if($option["container"] != ""): ?>
			<<?php echo ($option["container"] ? $option["container"] : "div"); ?> class="<?php echo ($option["container_class"] ? $option["container_class"] : ""); ?>">
		<?php else : ?>
			<ul>
		<?php endif; ?>
			
			<?php dynamic_sidebar(sprintf("%s-sidebar", $option["position"])); ?>

		<?php if($option["container"] != ""): ?>
			</<?php echo ($option["container"] ? $option["container"] : "div"); ?>>
		<?php else : ?>
			</ul>
		<?php endif; ?>
	</aside>
<?php endif; ?>