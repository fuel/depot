<h3>API Documentation Status</h3>
<?php if ($versions): ?>
<table class="table table-bordered table-condensed">
	<thead>
		<th>Branch</th>
		<th>Documentation</th>
		<th>API docs</th>
		<th>Default?</th>
	</thead>
	<tbody>
<?php foreach ($versions as $version): ?>		<tr>
			<td><?php echo $version->major.'.'.$version->minor.'/'.$version->branch; ?></td>
			<td><?php echo $pagecounts[$version->id]; ?> page<?php echo $pagecounts[$version->id] == 1 ? '' : 's'; ?> created</td>
			<td><?php echo $apicounts[$version->id]; ?> file<?php echo $apicounts[$version->id] == 1 ? '' : 's'; ?> documented</td>
			<td><?php echo $version->default ? 'Yes' : 'No'; ?></td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>
<?php else: ?>
<p>There are no FuelPHP source code branches defined.</p>
<?php endif; ?><p>
