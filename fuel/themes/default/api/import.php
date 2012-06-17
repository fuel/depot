<?php \Theme::instance()->asset->css(array('docs.css'), array(), 'header'); ?>
<div>
	<h1>Docblox Import</h1>
</div>
<div class="clearfix"></div>
<div>
	<h4>Version: <?php echo $version;?></h4>
	<h4>Docblox XML: <?php echo $xmlfile; ?></h4>
</div>
<div>
	<pre><?php echo $content; ?></pre>
</div>
