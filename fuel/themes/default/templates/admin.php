<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo $title; ?></title>
	<?php echo \Theme::instance()->asset->css('bootstrap.css'); ?>
	<style>
		body { margin: 50px; }
	</style>
	<?php echo \Theme::instance()->asset->js(array(
		'http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js',
		'bootstrap.js'
	)); ?>
	<script>
		$(function(){ $('.topbar').dropdown(); });
	</script>
</head>
<body>

	<?php if ($current_user): ?>
	<div class="topbar">
	    <div class="fill">
	        <div class="container">
	            <h3><a href="/">Fuel Depot</a></h3>
	            <ul>
	                <li class="<?php echo Uri::segment(2) == '' ? 'active' : '' ?>">
						<?php echo Html::anchor('admin', 'Dashboard') ?>
					</li>

					<?php foreach (glob(APPPATH.'../modules/*/classes/controller/admin/*.php') as $controller): ?>

						<?php
						$section_segment = basename($controller, '.php');
						if ($section_segment == 'dashboard')
						{
							continue;
						}
						$section_title = Inflector::humanize($section_segment);
						$controller = explode('/', $controller);
						$module = $controller[count($controller)-5];
						?>

	                <li class="<?php echo Uri::segment(2) == $section_segment ? 'active' : '' ?>">
						<?php echo Html::anchor('admin/'.$module.'/'.$section_segment, $section_title) ?>
					</li>
					<?php endforeach; ?>
	          </ul>

	          <ul class="nav secondary-nav">
	            <li class="menu">
	                <a href="#" class="menu"><?php echo $current_user->username ?></a>
	                <ul class="menu-dropdown">
	                    <li><?php echo Html::anchor('users/profile', 'Profile') ?></li>
	                    <li><?php echo Html::anchor('users/logout', 'Logout') ?></li>
	                </ul>
	            </li>
	          </ul>
	        </div>
	    </div>
	</div>
	<?php endif; ?>

	<div class="container">
		<div class="row">
			<div class="span16">
				<h1><?php echo $title; ?></h1>
				<hr>
				<!-- Begin messages -->
				<?php
					foreach (array('error', 'warning', 'success', 'info') as $type)
					{
						foreach(\Messages::instance()->get($type) as $message)
						{
							echo '<div class="alert-message '.($type=='warning'?'danger':$type).'"><p>'.$message['body'].'</p></div>'."\n";
						}

					}
					\Messages::reset();
				?>
				<!-- End of messages -->
			</div>
			<div class="span16">
<?php echo $partials['content']; ?>
			</div>
		</div>
		<footer>
			<p class="pull-right">Page rendered in {exec_time}s using {mem_usage}mb of memory.</p>
			<p>
				<a href="http://fuelphp.com">FuelPHP</a> is released under the MIT license.<br>
				<small>Running on the <?php echo e(Fuel::VERSION); ?> code branch</small>
			</p>
		</footer>
	</div>
</body>
</html>
