<h2>New Source Branch</h2>
<br>

<?php echo \Theme::instance()->view('documentation/admin/branches/_form', array('form' => 'create', 'versions' => $versions)); ?>

<p>
	<?php echo Html::anchor('documentation/admin/branches', 'Index', array('class' => 'btn')); ?>
</p>
