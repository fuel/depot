<h2>New User</h2>
<br>

<?php echo \Theme::instance()->view('admin/users/_form', array('groups' => $groups), false); ?>


<p>
	<?php echo Html::anchor('admin/users', 'Back', array('class' => 'btn')); ?>
</p>
