<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">

<!-- Begin head -->
<head profile="http://gmpg.org/xfn/11">

<title>Fuel Depot&nbsp;&rsaquo;&nbsp;The documentation repository for the FuelPHP framework.</title>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="author" content="fuelphp.com" />
<meta name="copyright" content="fuelphp.com" />
<meta name="robots" content="index, follow" />
<meta name="distribution" content="global" />
<meta name="resource-type" content="document" />
<meta name="language" content="en" />

<link rel="shortcut icon" href="/favicon.png" type="image/x-icon" />

<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Droid+Serif:regular,italic,bold,bolditalic" type="text/css" />
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Droid+Sans:regular,bold" type="text/css" />

<?php echo \Theme::instance()->asset->css(array('reset.css', 'typo.css', 'global.css')); ?>
<?php echo \Theme::instance()->asset->render('header'); ?>

</head>
<!-- End head -->

<!-- Begin body -->
<body id="top" class="home">

<!-- Begin messages -->
<?php
	foreach (array('error', 'warning', 'success', 'info') as $type)
	{
		foreach(\Messages::instance()->get($type) as $message)
		{
			echo '<div class="',$message['type'],'-box">',$message['body'],'</div>',"\n";
		}

	}
	\Messages::reset();
?>
<!-- End of messages -->
