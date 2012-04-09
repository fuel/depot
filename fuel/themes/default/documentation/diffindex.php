<h3>View page diff's</h3>
<p>Select the versions you want to compare:</p>
<?php echo \Form::open(array('class' => 'form left')); ?>
		<table style="width:100%;">
			<tr>
				<th style="width:1px;min-width:1px;">&nbsp;</th>
				<?php if (\Auth::has_access('access.staff')):?>
				<th style="width:1px;min-width:1px;" class="center">Delete?</th>
				<?php endif; ?>
				<th>Date</th>
				<th>Modified by</th>
				<th style="width:1px;min-width:1px;" class="center">Source</th>
				<th style="width:1px;min-width:1px;" class="center">Target</th>
			</tr>
			<?php $count = 0; foreach($docs as $doc):?>
			<tr>
				<td style="width:1px;min-width:1px;">#<?php echo ++$count; ?></td>
				<?php if (\Auth::has_access('access.staff')):?>
				<td class="center" style="min-width:1px;"><input type="checkbox" name="selected[]" value="<?php echo $doc->id; ?>" /></td>
				<?php endif; ?>
				<td><?php echo \Date::forge($doc->created_at)->format('eu_full'); ?></td>
				<td><?php echo $doc->user->profile_fields['full_name'];?></td>
				<td class="center"><input type="radio" name="before" value="<?php echo $doc->id; ?>"<?php if ($count == 1) echo ' checked="checked"'; ?> /></td>
				<td class="center"><input type="radio" name="after" value="<?php echo $doc->id; ?>"<?php if ($count == 2) echo ' checked="checked"'; ?> /></td>
			</tr>
			<?php endforeach; ?>
		</table>
<?php
	echo \Form::submit('view', 'View diff', array('class' => 'btn purple'));
	if (\Auth::has_access('access.staff'))
	{
		echo \Form::submit('delete', 'Delete selected', array('class' => 'btn '));
	}
	echo \Form::submit('cancel', 'Cancel', array('class' => 'btn '));
	echo \Form::close();
?>
