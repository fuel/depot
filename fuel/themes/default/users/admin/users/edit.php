<h2>Edit User #<?php echo $user->id; ?></h2>
<br>

<?php echo \Theme::instance()->view('users/admin/users/_form', array('groups' => $groups, 'user' => $user), false); ?>
