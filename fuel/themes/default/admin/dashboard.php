<h3>User Account Information</h3>
<table class="table table-bordered table-condensed">
	<tbody>
		<tr>
			<td class="right"><?php echo $active_users; ?></td>
			<td>active user accounts</td>
			<td class="right"><?php echo $banned_users; ?></td>
			<td>banned user accounts</td>
		</tr>
		<tr>
			<td colspan="4"></td>
		</tr>
		<tr>
			<td class="right"><?php echo $github_accounts; ?></td>
			<td>users logging in through github</td>
			<td class="right"><?php echo $twitter_accounts; ?></td>
			<td>users logging in through twitter</td>
		</tr>
		<tr>
			<td class="right"><?php echo $facebook_accounts; ?></td>
			<td>users logging in through facebook</td>
			<td class="right"><?php echo $google_accounts; ?></td>
			<td>users logging in through google</td>
		</tr>
	</tbody>
</table>

<h3>API Documentation Status</h3>
<?php if ($versions): ?>
<table class="table table-bordered table-condensed">
	<thead>
		<th>Version</th>
		<th>Default?</th>
		<th>Documentation</th>
		<th>API docs</th>
	</thead>
	<tbody>
<?php foreach ($versions as $version): ?>		<tr>
			<td><?php echo $version->major.'.'.$version->minor.'/'.$version->branch; ?></td>
			<td><?php echo $version->default ? 'Yes' : 'No'; ?></td>
			<td><?php echo $count = \Admin\Model_Page::query()->where('version_id', $version->id)->count(); ?> page<?php echo $count == 1 ? '' : 's'; ?> created</td>
			<td><?php echo $count = \Admin\Model_Docblox::query()->where('version_id', $version->id)->count(); ?> page<?php echo $count == 1 ? '' : 's'; ?> created</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>
<?php else: ?>
<p>There are no FuelPHP source code branches defined.</p>
<?php endif; ?><p>
