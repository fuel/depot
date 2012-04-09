<?php if ($versions): ?>
<table class="table zebra-striped table-bordered table-condensed">
	<thead>
		<tr>
			<th></th>
			<th>Version</th>
			<th>Default?</th>
			<th class="center">Options</th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($versions as $version): ?>		<tr>

			<td class="right shrink"><?php echo '#'.$version->id; ?></td>
			<td><?php echo $version->major.'.'.$version->minor.'/'.$version->branch; ?></td>
			<td><?php echo $version->default ? 'Yes' : 'No'; ?></td>
			<td class="center shrink">
				<?php echo Html::anchor('admin/branches/view/'.$version->id, 'View', array('class' => 'btn inline')); ?>
				<?php echo Html::anchor('admin/branches/edit/'.$version->id, 'Edit', array('class' => 'btn inline')); ?>
				<?php echo Html::anchor('admin/branches/delete/'.$version->id, 'Delete', array('onclick' => "return confirm('Are you sure?')", 'class' => 'btn inline')); ?>
			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php echo \Pagination::create_links(); ?>

<?php else: ?>
<p>There are no source branches defined.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('admin/branches/create', 'Add new branch', array('class' => 'btn success pull-right')); ?>
</p>
