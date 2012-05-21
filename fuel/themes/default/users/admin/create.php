<h2>New User</h2>
<br>

<?php echo \Theme::instance()->view('users/admin/_form', array('groups' => $groups), false); ?>


<p>
	<?php echo Html::anchor('admin/users/users', 'Back', array('class' => 'btn')); ?>
</p>
