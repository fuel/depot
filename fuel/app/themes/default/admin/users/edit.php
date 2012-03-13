<h2>Edit User #<?php echo $user->id; ?></h2>
<br>

<?php echo \Theme::instance()->view('admin/users/_form', array('groups' => $groups, 'user' => $user), false); ?>
<p>
	<?php echo Html::anchor('admin/users/view/'.$user->id, 'View', array('class' => 'btn')); ?>
	<?php echo Html::anchor('admin/users', 'Back', array('class' => 'btn')); ?>
</p>
