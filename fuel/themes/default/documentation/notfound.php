<div class="center">
	<h2>No documentation found</h2>
	<div class="clearfix" style="margin-left:150px;"><?php echo \Theme::instance()->asset->img('newpage.png', array('style' => 'border:none;background:transparent;')); ?></div>
	<h6>
		<?php echo $page ? 'No page has been created yet for the selected topic.' : ''; ?>
	</h6>
	<p>&nbsp;</p>
	<?php if (\Auth::has_access('access.staff') or \Session::get('ninjauth.authentication.provider', false) == 'github'): ?>
		<?php if ($page): ?>
			<?php if ($page->editable): ?>
				<h5>Click on the "Edit page" button to add content to this topic.</h5>
			<?php endif; ?>
		<?php else: ?>
			<h5>Click on the "New page" button to add a new page to the documenation.</h5>
		<?php endif; ?>
	<?php endif; ?>
</div>
