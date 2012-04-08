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
					<?php if (\Auth::has_access('access.admin'))
					{
						echo \Form::open(array('action' => 'documentation/menu/'.$selection['version'], 'style' => 'display:inline;'));
						echo \Form::submit('menu', 'Edit menu', array('class' => 'btn small purple '));
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
						if ( ! in_array(Uri::segment(2), array('version', 'page', 'edit')))
						{
							echo \Form::open(array('action' => 'documentation/page/'.$selection['page'], 'style' => 'display:inline;'));
							echo \Form::submit('back', 'Back', array('class' => 'btn small purple '));
							echo \Form::close();
						}
						else
						{
							if ($doccount !== false)
							{
								if ($doccount)
								{
									echo \Form::open(array('action' => 'documentation/edit/'.$selection['page'], 'style' => 'display:inline;'));
									echo \Form::submit('edit', 'Edit page', array('class' => 'btn small purple'));
									echo \Form::close();
									if ($doccount > 1)
									{
										echo \Form::open(array('action' => 'documentation/diff/'.$selection['page'], 'style' => 'display:inline;'));
										echo \Form::submit('diff', 'View changes', array('class' => 'btn small'));
										echo \Form::close();
									}
								}
								else
								{
									echo \Form::open(array('action' => 'documentation/edit/'.$selection['page'], 'style' => 'display:inline;'));
									echo \Form::submit('new', 'New page', array('class' => 'btn small purple '));
									echo \Form::close();
								}
							}
						}
					?>
				</div>
			<?php endif; ?>
			<?php if ($pagedata):?>
				<p class="right">
					Page last modified by <strong><?php echo $pagedata['user']; ?></strong> on <strong><?php echo \Date::forge($pagedata['updated'])->format('eu_full');?></strong> GMT
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
