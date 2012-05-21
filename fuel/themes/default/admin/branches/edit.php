<h2>Edit Source Branch #<?php echo $version->id; ?></h2>
<br>

<?php echo \Theme::instance()->view('admin/branches/_form', array('version' => $version, 'form' => 'edit')); ?>

<p>
	<?php echo Html::anchor('admin/admin/branches/view/'.$version->id, 'View', array('class' => 'btn')); ?>
	<?php echo Html::anchor('admin/admin/branches', 'Index', array('class' => 'btn')); ?>
</p>
