<h2>Viewing #<?php echo $user->id; ?></h2>

<?php echo Form::open(array('class' => 'form-stacked')); ?>
<fieldset>
	<div class="clearfix">
		<?php echo Form::label('Username', 'username'); ?>

		<div class="input">
			<?php echo $user->username; ?>

		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Fullname', 'full_name'); ?>

		<div class="input">
			<?php echo $user->profile_fields['full_name']; ?>

		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Email', 'email'); ?>

		<div class="input">
			<?php echo $user->email; ?>

		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Group', 'group'); ?>

		<div class="input">
			<?php echo $groups[$user->group]; ?>

		</div>
	</div>

</fieldset>
<?php echo Form::close(); ?>

<p>
	<?php echo Html::anchor('admin/users/edit/'.$user->id, 'Edit', array('class' => 'btn')); ?>
	<?php echo Html::anchor('admin/users', 'Back', array('class' => 'btn')); ?>
</p>
