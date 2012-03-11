<h2>Edit Source Branch #<?php echo $version->id; ?></h2>
<br>

<?php echo render('docblox/_form', array('version' => $version), false); ?>
<p>
	<?php echo Html::anchor('admin/docblox/view/'.$version->id, 'View', array('class' => 'btn')); ?>
	<?php echo Html::anchor('admin/docblox', 'Back', array('class' => 'btn')); ?>
</p>
