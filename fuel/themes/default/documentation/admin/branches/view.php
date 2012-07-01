<h2>View Source Branch #<?php echo $version->id; ?></h2>
<br>

<?php echo Form::open(array('class' => 'form-stacked')); ?>

<fieldset>
	<div class="clearfix">
		<?php echo Form::label('Major version', 'major'); ?>

		<div class="uneditable-input input-mini">
			<?php echo $version->major; ?>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Minor version', 'minor'); ?>

		<div class="uneditable-input input-mini">
			<?php echo $version->minor; ?>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Branch name', 'branch'); ?>

		<div class="uneditable-input input-small">
			<?php echo $version->branch; ?>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Default?', 'default'); ?>

		<div class="uneditable-input input-small">
			<?php echo $version->default ? 'Yes' : 'No'; ?>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Editable?', 'editable'); ?>

		<div class="uneditable-input input-small">
			<?php echo $version->editable ? 'Yes' : 'No'; ?>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Local path to the code repository', 'codepath'); ?>

		<div class="uneditable-input input-xxlarge">
			<?php echo $version->codepath; ?>&nbsp;
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Local path to the docs repository', 'docspath'); ?>

		<div class="uneditable-input input-xxlarge">
			<?php echo $version->docspath; ?>&nbsp;
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Local path to the docblox output', 'docbloxpath'); ?>

		<div class="uneditable-input input-xxlarge">
			<?php echo $version->docbloxpath; ?>&nbsp;
		</div>
	</div>

</fieldset>
<?php echo Form::close(); ?>


	<?php echo Html::anchor('documentation/admin/branches', 'Index', array('class' => 'btn btn-primary')); ?>
	<?php echo Html::anchor('documentation/admin/branches/edit/'.$version->id, 'Edit', array('class' => 'btn')); ?>

