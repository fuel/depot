<?php
	\Theme::instance()->asset->css(array('../markitup/sets/markdown/style.css','../markitup/skins/markitup/style.css'), array(), 'header');
	\Theme::instance()->asset->js(array('../markitup//jquery.markitup.js', '../markitup/sets/markdown/set.js', '../markitup//jquery.markitup.load.js'), array(), 'footer');
?>
<h3>Delete a page</h3>
<?php echo \Form::open(array('class' => 'form left'));
	echo \Form::submit('delete', 'Confirm delete', array('class' => 'btn '));
	echo \Form::submit('cancel', 'Cancel', array('class' => 'btn purple '));
?>
<br /><hr />
<ul>
	<li>
		<?php echo $doc; ?>
	</li>
</ul>
<?php
	echo \Form::close();
?>
