<?php
	\Theme::instance()->asset->css(array('../markitup/sets/markdown/style.css','../markitup/skins/markitup/style.css'), array(), 'header');
	\Theme::instance()->asset->js(array('../markitup//jquery.markitup.js', '../markitup/sets/markdown/set.js', '../markitup//jquery.markitup.load.js'), array(), 'footer');
?>
<h3>Add a new page</h3>
<?php echo \Form::open(array('class' => 'form left')); ?>
<ul>
	<li><?php echo Form::label('Title of this page', 'title'), Form::input('title', $title, array('class' => 'large')); ?></li>
	<li><?php echo Form::label('Slug', 'slug'), Form::input('slug', $slug, array()); ?></li>
	<li><?php echo Form::label('Insert new page after this one', 'node'), Form::select('node', $node, $pagetree); ?></li>
	<?php if (! empty($preview)):?>
	<li>
		<label>Preview</label>
		<hr />
		<?php echo $preview; ?>
		<hr />
		</li>
	<?php endif; ?>
	<li class="markdown"><?php echo Form::label('Page content', 'page'), Form::textarea('page', $page, array('class' => 'markItUp')); ?></li>
</ul>
<?php
	echo \Form::submit('preview', 'Preview', array('class' => 'btn purple '));
	echo \Form::submit('submit', 'Create page', array('class' => 'btn '));
	echo \Form::submit('cancel', 'Cancel', array('class' => 'btn '));
	echo \Form::close();
?>
