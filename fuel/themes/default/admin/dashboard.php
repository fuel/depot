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
			<td class="right"><?php echo $github_accounts; ?></td>
			<td>users logging in through github</td>
			<td class="right"><?php echo $twitter_accounts; ?></td>
			<td>users logging in through twitter</td>
		</tr>
	</tbody>
</table>

<h3>API Documentation Status</h3>
<?php if ($versions): ?>
<table class="table table-bordered table-condensed">
	<thead>
		<th>Version</th>
		<th>Default?</th>
		<th>Status</th>
	</thead>
	<tbody>
<?php foreach ($versions as $version): ?>		<tr>
			<td><?php echo $version->major.'.'.$version->minor.'/'.$version->branch; ?></td>
			<td><?php echo $version->default ? 'Yes' : 'No'; ?></td>
			<td><?php echo $count = \Admin\Model_Docblox::query()->where('version_id', $version->id)->count(); ?> file<?php echo $count == 1 ? '' : 's'; ?> documented</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>
<?php else: ?>
<p>There are no FuelPHP source code branches defined.</p>
<?php endif; ?><p>
