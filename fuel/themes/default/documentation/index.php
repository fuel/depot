<?php \Theme::instance()->asset->css(array('docs.css'), array(), 'header'); ?>

<div id="docs">
	<div class="header">
		<div class="alignleft">
			<h1>Online Documentation</h1>
		</div>
		<div class="alignright">
			<form name="version_select" method="POST">
				<h5 style="margin-bottom:0px;">FuelPHP version: </h5>
				<?php echo \Form::select('branch', $selection['version'], $versions, array('style' => 'min-width:125px;', 'onchange' => 'this.form.action = this.form.action + \'/documentation/version/\' + this.value; this.form.submit();')); ?>
			</form>
		</div>
	</div>

	<div class="content">

		<div class="sidebar">

			<?php echo $edittree; ?>

			<ul class="menutree">
				<li><button class="btn danger small expand_all">Expand All</button> <button class="small collapse_all">Collapse All</button></li>
			</ul>

			<?php echo $menutree; ?>

		</div>

		<div class="page">
			<?php echo $editpage; ?>
			<?php if ($pagedata):?>
				<p class="right">
					Page last modified by <strong><?php echo $pagedata['user']; ?></strong> on <strong><?php echo \Date::forge($pagedata['updated'])->format('eu_full');?></strong>
				</p>
			<?php endif; ?>
			<div class="details">
				<?php echo $details; ?>
			</div>
		</div>
		<div class="clearfix"></div>

	</div>
</div>
