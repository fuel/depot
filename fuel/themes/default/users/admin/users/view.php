<h2>View User #<?php echo $user->id; ?></h2>

<?php echo Form::open(array('class' => 'form-stacked')); ?>
<fieldset>
	<div class="clearfix">
		<?php echo Form::label('Username', 'username'); ?>

		<div class="uneditable-input input-small">
			<?php echo $user->username; ?>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Fullname', 'full_name'); ?>

		<div class="uneditable-input input-large">
			<?php echo $user->profile_fields['full_name']; ?>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Email', 'email'); ?>

		<div class="uneditable-input input-xlarge">
			<?php echo $user->email; ?>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Group', 'group'); ?>

		<div class="uneditable-input input-medium">
			<?php echo $groups[$user->group]; ?>
		</div>
	</div>

</fieldset>
<?php echo Form::close(); ?>

<p>
	<?php echo Html::anchor('admin/users/users', 'Index', array('class' => 'btn btn-primary')); ?>
	<?php echo Html::anchor('admin/users/users/edit/'.$user->id, 'Edit', array('class' => 'btn')); ?>
</p>
