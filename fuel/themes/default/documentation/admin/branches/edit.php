<h2>Edit Source Branch #<?php echo $version->id; ?></h2>
<br>

<?php echo \Theme::instance()->view('documentation/admin/branches/_form', array('version' => $version, 'form' => 'edit', 'versions' => $versions)); ?>
