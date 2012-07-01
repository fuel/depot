<?php echo Form::open(array('class' => 'form-stacked')); ?>

<fieldset>
	<div class="clearfix">
		<?php echo Form::label('Major version', 'major'); ?>

		<div class="input">
			<?php echo Form::input('major', Input::post('major', isset($version) ? $version->major : ''), array('class' => 'span1')); ?>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Minor version', 'minor'); ?>

		<div class="input">
			<?php echo Form::input('minor', Input::post('minor', isset($version) ? $version->minor : ''), array('class' => 'span1')); ?>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Branch name', 'branch'); ?>

		<div class="input">
			<?php echo Form::input('branch', Input::post('branch', isset($version) ? $version->branch : ''), array('class' => 'span3')); ?>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Default?', 'default'); ?>

		<div class="input">
			<?php echo Form::select('default', Input::post('default', isset($version) ? $version->default : 0), array(0 => 'No', 1 => 'Yes'), array('class' => 'span2')); ?>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Editable?', 'editable'); ?>

		<div class="input">
			<?php echo Form::select('editable', Input::post('editable', isset($version) ? $version->editable : 0), array(0 => 'No', 1 => 'Yes'), array('class' => 'span2')); ?>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Local path to the code repository', 'codepath'); ?>

		<div class="input">
			<?php echo Form::input('codepath', Input::post('codepath', isset($version) ? $version->codepath : ''), array('class' => 'span8')); ?>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Local path to the docs repository', 'docspath'); ?>

		<div class="input">
			<?php echo Form::input('docspath', Input::post('docspath', isset($version) ? $version->docspath : ''), array('class' => 'span8')); ?>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Local path to the docblox output', 'docbloxpath'); ?>

		<div class="input">
			<?php echo Form::input('docbloxpath', Input::post('docbloxpath', isset($version) ? $version->docbloxpath : ''), array('class' => 'span8')); ?>
		</div>
	</div>
	<div class="clearfix">
		<?php echo Form::label('Copy documentation from', 'docsversion'); ?>

		<div class="input">
			<?php echo Form::select('docsversion', Input::post('docsversion', isset($docsversion) ? $docsversion : 0), $versions, array('class' => 'span2')); ?>
		</div>
	</div>

	<div class="actions">
		<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>
		<?php echo Html::anchor('documentation/admin/branches', 'Cancel', array('class' => 'btn btn-warning')); ?>
		<?php if ($form == 'edit') echo Html::anchor('documentation/admin/branches/view/'.$version->id, 'View', array('class' => 'btn')); ?>
	</div>

</fieldset>
<?php echo Form::close(); ?>
