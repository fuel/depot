<?php echo \Theme::instance()->view('templates/header'); ?>
<!-- Begin body -->
<body id="top" class="home">

<div id="subheaderWrapper">
	<div id="subheader">

		<div id="logo">
			<h1><a href="/" title="Home"><?php echo \Html::img(\Theme::instance()->asset('img/logo.png'));?></a></h1>
		</div>

		<ul id="nav">
			<li><a href="/about">About</a></li>
			<li><a href="/documenation">Documentation</a></li>
			<li><a href="/api">Class API</a></li>
			<li><a href="/tutorials">Tutorials</a></li>
			<li><a href="/screencasts">Screencasts</a></li>
			<li><a href="/snippets">Snippets</a></li>
			<li><a href="/cells">Cells</a></li>
			<li><a href="/forums">Forums</a></li>
			<li><a href="/login" target="" class="logout">Login</a></li>
		</ul>

	</div>

</div>

<div id="contentWrapper">

	<div id="content"><?php if (isset($content)) echo $content; ?></div>

</div>

<?php echo \Theme::instance()->view('templates/footer'); ?>
</body>
</html>
