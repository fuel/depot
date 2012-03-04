<h2>Editing User</h2>
<br>

<?php echo render('users/_form'); ?>
<p>
	<?php echo Html::anchor('admin/users/view/'.$user->id, 'View'); ?> |
	<?php echo Html::anchor('admin/users', 'Back'); ?></p>
