<?php \Theme::instance()->asset->css(array('docs.css', 'highlight.css'), array(), 'header'); ?>

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

	<div class="content">

		<div class="sidebar">

			<ul class="menutree">
				<li>
					<?php if (\Auth::has_access('access.admin') or \Session::get('ninjauth.authentication.provider', false) == 'github')
					{
						echo \Form::open(array('style' => 'display:inline;'));
						echo \Form::hidden('form', 'new');
						echo \Form::submit('new', 'New page', array('class' => 'btn small purple '));
						echo \Form::close();
					}
					?>
					<button class="btn small expand_all">Expand All</button>
					<button class="btn small collapse_all">Collapse All</button>
				</li>
			</ul>

			<?php echo $menutree; ?>

		</div>

		<div class="page">
			<?php if (\Auth::has_access('access.admin') or \Session::get('ninjauth.authentication.provider', false) == 'github'): ?>
				<div class="editpage">
					<?php
						echo \Form::open();
						if (\Input::post('form', 'back') != 'back')
						{
							echo \Form::hidden('form', 'back');
							echo \Form::submit('back', 'Back', array('class' => 'btn small purple '));
						}
						else
						{
							if ($doccount)
							{
								echo \Form::hidden('form', 'edit');
								echo \Form::submit('edit', 'Edit this page', array('class' => 'btn small purple'));
								if ($doccount > 1)
								{
									echo \Form::submit('diff', 'View changes', array('class' => 'btn small'));
								}
							}
							else
							{
								echo \Form::hidden('form', 'create');
								echo \Form::submit('create', 'Create this page', array('class' => 'btn small purple '));
							}
						}
						echo \Form::close();
					?>
				</div>
			<?php endif; ?>
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

<?php \Theme::instance()->asset->js(array('highlight.pack.js'), array(), 'footer'); ?>
