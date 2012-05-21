<h2>Edit User #<?php echo $user->id; ?></h2>
<br>

<?php echo \Theme::instance()->view('users/admin/_form', array('groups' => $groups, 'user' => $user), false); ?>
<p>
	<?php echo Html::anchor('admin/users/users/view/'.$user->id, 'View', array('class' => 'btn')); ?>
	<?php echo Html::anchor('admin/users/users', 'Back', array('class' => 'btn')); ?>
</p>
