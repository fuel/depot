<h2>Viewing #<?php echo $version->id; ?></h2>

<?php echo Form::open(array('class' => 'form-stacked')); ?>
<fieldset>
	<div class="clearfix">
		<?php echo Form::label('Major version', 'major'); ?>

		<div class="input">
			<?php echo $version->major; ?>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Minor version', 'minor'); ?>

		<div class="input">
			<?php echo $version->minor; ?>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Branch name', 'branch'); ?>

		<div class="input">
			<?php echo $version->branch; ?>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Default?', 'default'); ?>

		<div class="input">
			<?php echo $version->default ? 'Yes' : 'No'; ?>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Local path to the code repository', 'codepath'); ?>

		<div class="input">
			<?php echo $version->codepath; ?>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Local path to the docs repository', 'docspath'); ?>

		<div class="input">
			<?php echo $version->docspath; ?>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Local path to the docblox output', 'docbloxpath'); ?>

		<div class="input">
			<?php echo $version->docbloxpath; ?>
		</div>
	</div>

</fieldset>
<?php echo Form::close(); ?>

<p>
	<?php echo Html::anchor('admin/docblox/edit/'.$version->id, 'Edit', array('class' => 'btn')); ?>
	<?php echo Html::anchor('admin/docblox', 'Back', array('class' => 'btn')); ?>
</p>
