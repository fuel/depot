<div class="center">
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<h1>Not Found</h1>
	<p>&nbsp;</p>
	<p>No documenation is available for the selected topic.</p>
	<?php if (\Auth::has_access('access.admin') or \Session::get('ninjauth.authentication.provider', false) == 'github'): ?>
		<p>Click on the "New page" button to add the documenation.</p>
	<?php endif; ?>
</div>
