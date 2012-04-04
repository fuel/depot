<?php \Theme::instance()->asset->css(array('docs.css'), array(), 'header'); ?>

<div style="border-bottom:1px solid #ddd;padding-bottom:10px;">
	<div style="float:left;">
		<h1>Class API Documentation</h1>
	</div>
	<div style="float:right">
		<form style="text-align:right;" name="version_select" method="POST">
			<h5 style="margin-bottom:0px;">FuelPHP version: </h5>
			<?php echo \Form::select('branch', $selection['version'], $versions, array('style' => 'min-width:125px;', 'onchange' => 'this.form.action = this.form.action + \'/api/version/\' + this.value; this.form.submit();')); ?>
		</form>
	</div>
	<div class="clearfix"></div>
</div>

<div id="docs">
	<div style="float:left;width:250px;padding-top:10px;margin-right:0px;overflow:hidden;">
		<ul class="menutree">
			<li><button class="small expand_all">Expand All</button> <button class="small collapse_all">Collapse All</button></li>
		</ul>

		<h5>Constants</h5>
		<?php echo \Html::ul($constantlist, array('id' => 'constantlist', 'class' => 'menutree')); ?>

		<h5>Functions</h5>
		<?php echo \Html::ul($functionlist, array('id' => 'functionlist', 'class' => 'menutree')); ?>

		<h5>Classes</h5>
		<?php echo \Html::ul($classlist, array('id' => 'classlist', 'class' => 'menutree')); ?>

	</div>
	<div style="float:right;width:739px;padding-top:10px;margin-left:0px;padding-left:10px;">
		<?php echo $details; ?>
	</div>
	<div class="clearfix"></div>
</div>
