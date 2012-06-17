<h2>Viewing #<?php echo $version->id; ?></h2>
<br>

<?php echo Form::open(array('class' => 'form-stacked')); ?>

<fieldset>
	<div class="clearfix">
		<?php echo Form::label('Major version', 'major'); ?>

		<div class="input">
			<p class="span1"><?php echo $version->major; ?></p>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Minor version', 'minor'); ?>

		<div class="input">
			<p class="span1"><?php echo $version->minor; ?></p>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Branch name', 'branch'); ?>

		<div class="input">
			<p class="span6"><?php echo $version->branch; ?></p>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Default?', 'default'); ?>

		<div class="input">
			<p class="span3"><?php echo $version->default ? 'Yes' : 'No'; ?></p>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Editable?', 'editable'); ?>

		<div class="input">
			<p class="span3"><?php echo $version->editable ? 'Yes' : 'No'; ?></p>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Local path to the code repository', 'codepath'); ?>

		<div class="input">
			<p class="span12"><?php echo $version->codepath; ?>&nbsp;</p>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Local path to the docs repository', 'docspath'); ?>

		<div class="input">
			<p class="span12"><?php echo $version->docspath; ?>&nbsp;</p>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Local path to the docblox output', 'docbloxpath'); ?>

		<div class="input">
			<p class="span12"><?php echo $version->docbloxpath; ?>&nbsp;</p>
		</div>
	</div>

</fieldset>
<?php echo Form::close(); ?>

<p>
	<?php echo Html::anchor('documentation/admin/branches/edit/'.$version->id, 'Edit', array('class' => 'btn')); ?>
	<?php echo Html::anchor('documentation/admin/branches', 'Index', array('class' => 'btn')); ?>
</p>
