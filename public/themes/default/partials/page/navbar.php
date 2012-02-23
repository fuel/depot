		<ul id="nav">
			<li><a href="/about">About</a></li>
			<li><a href="/documentation">Documentation</a></li>
			<li><a href="/api">Class API</a></li>
			<li><a href="/tutorials">Tutorials</a></li>
			<li><a href="/screencasts">Screencasts</a></li>
			<li><a href="/snippets">Snippets</a></li>
			<li><a href="/cells">Cells</a></li>
			<li><a href="http://fuelphp.com/forums">Forums</a></li>
			<li>
				<?php if (\Auth::check()) { ?>
				<a href="/logout">Logout</a>
				<?php } else { ?>
				<a href="/login">Login</a>
				<?php } ?>
			</li>
		</ul>
