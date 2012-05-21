<h2>Viewing #<?php echo $user->id; ?></h2>

<?php echo Form::open(array('class' => 'form-stacked')); ?>
<fieldset>
	<div class="clearfix">
		<?php echo Form::label('Username', 'username'); ?>

		<div class="input">
			<p class="span6"><?php echo $user->username; ?></p>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Fullname', 'full_name'); ?>

		<div class="input">
			<p class="span6"><?php echo $user->profile_fields['full_name']; ?></p>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Email', 'email'); ?>

		<div class="input">
			<p class="span6"><?php echo $user->email; ?></p>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Group', 'group'); ?>

		<div class="input">
			<p class="span6"><?php echo $groups[$user->group]; ?></p>
		</div>
	</div>

</fieldset>
<?php echo Form::close(); ?>

<p>
	<?php echo Html::anchor('admin/users/users/edit/'.$user->id, 'Edit', array('class' => 'btn')); ?>
	<?php echo Html::anchor('admin/users/users', 'Back', array('class' => 'btn')); ?>
</p>
