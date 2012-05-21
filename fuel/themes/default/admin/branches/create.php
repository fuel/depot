<h2>New Source Branch</h2>
<br>

<?php echo \Theme::instance()->view('admin/branches/_form', array('form' => 'create', 'versions' => $versions)); ?>

<p>
	<?php echo Html::anchor('admin/admin/branches', 'Index', array('class' => 'btn')); ?>
</p>
