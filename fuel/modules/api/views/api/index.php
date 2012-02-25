<?php \Theme::instance()->asset->css(array('api.css'), array(), 'header'); ?>
<div style="border-bottom:1px solid #ddd;padding-bottom:10px;">
	<div style="float:left;">
		<h1>Class API Documentation</h1>
	</div>
	<div style="float:right">
		<form style="text-align:right;" name="version_select" method="POST">
			<h5 style="margin-bottom:0px;">FuelPHP version: </h5>
			<?php echo \Form::select('branch', $version, $versions, array('style' => 'min-width:125px;', 'onchange' => 'this.form.action = this.form.action + \'/api/\' + this.value; this.form.submit();')); ?>
		</form>
	</div>
	<div class="clearfix"></div>
</div>
<div id="api">
	<div style="float:left;width:250px;padding-top:10px;margin-right:0px; overflow:hidden;">
			<h5>Constants</h5>
			<?php echo \Html::ul($constantlist, array('id' => 'constantlist', 'class' => 'menutree')); ?>

			<h5>Functions</h5>
			<?php echo \Html::ul($functionlist, array('id' => 'functionlist', 'class' => 'menutree')); ?>

			<h5>Classes</h5>
			<?php echo \Html::ul($classlist, array('id' => 'classlist', 'class' => 'menutree')); ?>
	</div>
	<div style="float:right;width:739px;padding-top:10px;border-left:1px solid #ddd;margin-left:0px;padding-left:10px;">
		<?php echo $details; ?>
	</div>
	<div class="clearfix"></div>
</div>
