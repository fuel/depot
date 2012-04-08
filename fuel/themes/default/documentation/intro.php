<div class="center">
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<h1>Not Found</h1>
	<p>&nbsp;</p>
	<p>No documenation is available for this version of FuelPHP.</p>
	<?php if (\Auth::has_access('access.admin') or \Session::get('ninjauth.authentication.provider', false) == 'github'): ?>
		<p>Click on the "Edit menu" button to start adding topics.</p>
	<?php endif; ?>
</div>
