<?php if ($users): ?>
<table class="table zebra-striped table-bordered table-condensed">
	<thead>
		<tr>
			<th></th>
			<th>Username</th>
			<th>Email</th>
			<th>Group</th>
			<th>Active</th>
			<th>Joined</th>
			<th>Last visit</th>
			<th class="center">Options</th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($users as $user): ?>		<tr>

			<td class="right shrink"><?php echo '#'.$user->id; ?></td>
			<td><?php echo $user->username; ?></td>
			<td><?php echo $user->email; ?></td>
			<td><?php echo isset($groupnames[$user->group]) ? $groupnames[$user->group]['name'] : ('Group '.$user->group) ; ?></td>
			<td class="center"><?php echo $user->group == -1 ? 'No' : 'Yes'; ?></td>
			<td><?php echo Date::forge($user->created_at)->format('eu_full'); ?></td>
			<td><?php echo Date::forge($user->last_login)->format('eu_full'); ?></td>
			<td class="center shrink">
				<?php echo Html::anchor('admin/users/view/'.$user->id, 'View', array('class' => 'btn inline')); ?>
				<?php echo Html::anchor('admin/users/edit/'.$user->id, 'Edit', array('class' => 'btn inline')); ?>
				<?php echo Html::anchor('admin/users/delete/'.$user->id, 'Delete', array('onclick' => "return confirm('Are you sure?')", 'class' => 'btn inline')); ?>
			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php echo \Pagination::create_links(); ?>

<?php else: ?>
<p>There are no users defined.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('admin/users/create', 'Add new User', array('class' => 'btn success pull-right')); ?>
</p>
