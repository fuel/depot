<?php \Theme::instance()->asset->css(array('docs.css', 'highlight.css'), array(), 'header'); ?>

<div id="docs">
	<div id="spinner"></div>
	<div class="header">
		<div class="alignleft">
			<h1>Online API Documentation</h1>
		</div>
		<div class="alignright">
			<?php echo \Form::open(array('name' => 'version_select', 'action' => '/', 'method' => 'post'));?>
				<h5 style="margin-bottom:0px;">FuelPHP version: </h5>
				<?php echo \Form::select('branch', $version, $versions, array('style' => 'min-width:125px;', 'onchange' => 'this.form.action = \'/api/version/\' + this.value; this.form.submit();')); ?>
			<?php echo \Form::close(); ?>
		</div>
	</div>

	<div class="content">

		<div class="sidebar">

			<ul>
				<li>
					<?php if (true or $menutree): ?>
					<div style="float:left;">
						<?php echo \Form::open(array('name' => 'apitype', 'action' => '/api/version/'.$version, 'method' => 'post'));?>
							<?php echo \Form::select('apitype', \Session::get('apitype', 'packages'), array('packages' => 'Packages', 'namespaces' => 'Namespaces', 'files' => 'Files'), array('class' => 'btn purple small', 'style' => 'width:95px;margin-right:3px;', 'onchange' => 'this.form.submit();')); ?>
						<?php echo \Form::close(); ?>
						<?php endif; ?>
					</div>
					<button class="btn small expand_all">Expand all</button>
					<button class="btn small collapse_all">Collapse all</button>
				</li>
			</ul>

			<p></p>
			<div id="menu_list">
				<?php echo $menutree; ?>
			</div>

		</div>

		<div class="page">
			<div class="details">
			<?php echo $details; ?>
			</div>
		</div>
		<div class="clearfix"></div>

	</div>
</div>

<?php \Theme::instance()->asset->js(array('jquery.cooki.js', 'jquery.ui.nestedSortable.js', 'jquery.clickable.js', 'highlight.pack.js'), array(), 'footer'); ?>
