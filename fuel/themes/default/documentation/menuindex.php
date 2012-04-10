<?php \Theme::instance()->asset->css(array('docs.css'), array(), 'header'); ?>

<div id="docs">
	<div class="header">
		<div class="alignleft">
			<h1>Online Documentation</h1>
		</div>
		<div class="alignright">
			<?php echo \Form::open(array('name' => 'version_select', 'action' => '/', 'method' => 'post'));?>
				<h5 style="margin-bottom:0px;">FuelPHP version: </h5>
				<?php echo \Form::select('branch', $selection['version'], $versions, array('style' => 'min-width:125px;', 'onchange' => 'this.form.action = \'/documentation/version/\' + this.value; this.form.submit();')); ?>
			<?php echo \Form::close(); ?>
		</div>
	</div>

	<div id="menueditor" class="content">

		<div class="sidebar">
			OPTIONS HERE

			<?php
				echo \Form::open(array('class' => 'form left'));
				echo \Form::submit('back', 'Back', array('class' => 'btn'));
				echo \Form::close();
			?>
		</div>

		<div id="menu_page">
			<h3>Books and chapters</h3>

			<div id="menu_list">
				<?php echo $menutree; ?>
			</div>

		</div>
			<div class="clearfix"></div>
	</div>
</div>

<?php \Theme::instance()->asset->js(array('jquery.cooki.js', 'jquery.ui.nestedSortable.js'), array(), 'footer'); ?>
