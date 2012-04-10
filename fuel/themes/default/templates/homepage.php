<?php echo \Theme::instance()->view('templates/header'); ?>

<!-- Begin body -->
<body id="top" class="home">

<div id="headerWrapper">
	<div id="header">

		<div id="logo">
			<h1><a href="/" title="Home"><?php echo \Theme::instance()->asset->img('logo.png');?></a></h1>
		</div>

		<?php echo $partials['navbar']; ?>

		<div id="intro">

			<div id="about">
				<h1>
					Welcome to the Fuel Depot!
				</h1>
				<p>&nbsp;</p>
				<h1>
					This is the central repository of FuelPHP screencasts, tutorials, documentation, code snippets and Fuel Cells.
				</h1>
			</div>

			<ul id="calltos">
				<li id="discover">
					<a href="/discover" title="Discover"><span class="link">Discover</span></a>
					<span class="desc">Learn more about Fuel Depot.</span>
				</li>

				<li id="contribute">
					<a href="/contribute" title="Contribute"><span class="link">Contribute</span></a>
					<span class="desc">Be part of it. Read it. Use it. Improve it.!</span>
				</li>

				<li id="cells">
					<a href="/cells" title="Cells"><span class="link">Fuel Cells</span></a>
					<span class="desc">Boost the power of your application!</span>
				</li>
			</ul>

		</div>

	</div>

	<div id="midbar">

		<div id="twitter">
			<?php echo \Theme::instance()->asset->img('twitter_bird.png');?>
			<ul id="twitter_update_list">
				<li>&nbsp;</li>
			</ul>
		</div>
	</div>

</div>

<div id="contentWrapper">

	<div id="content">

		<div class="page">

			<div class="page-chunk default">

				<div class="one_third">
					<h4>What exactly is Fuel Depot?</h4>
					<p>
						Fuel Depot supplies the web application developer with everything needed to create awesome applications using FuelPHP!
					</p>
					<p>
						In the depot you will find the online documentation of both the current release of FuelPHP and of the latest development version.
						You will have access to the complete API documentation of all classes, video and text tutorials, code snippets and other user contributions.
					</p>
				</div>

				<div class="one_third">
					<h4>Use the Cells Luke...</h4>
					<p>
						FuelPHP introduces the concept of Fuel Cells.
					</p>
					<p>
						Cells are installable packages, which you can install manually or using the <strong>oil</strong> commandline tool.
					</p>
					<p>
						With Cells you can quickly add functionality to your FuelPHP installation, which will enable you to create applications
						rich in functionality very quickly.
					</p>
					<p>
						To see what Cells are available, browse the <a href="/cells">online Cells repository</a>.
					</p>
				</div>

				<div class="one_third_last">
					<h4>Join the community!</h4>
					<p>
						Whether you're an experienced PHP developer or a newcomer just getting started, there are many ways for you to participate
						in the FuelPHP project.
					</p>
					<p>
						Help improving the documentation, post your code snippets, tutorials or screencasts.
						Publish your Cells in your central Cells repository. Join the forum to get help or help others with FuelPHP.
					</p>
					<p>
						Whatever you do, becoming an active member of the FuelPHP community is your step to creating something amazing.
					</p>
				</div>

			</div>

		</div>

	</div>

</div>

<?php echo \Theme::instance()->view('templates/footer'); ?>

<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
<script type="text/javascript" src="http://api.twitter.com/1/statuses/user_timeline/fuelphp.json?callback=twitterCallback2&amp;count=1&amp;include_rts=true"></script>

</body>
</html>
