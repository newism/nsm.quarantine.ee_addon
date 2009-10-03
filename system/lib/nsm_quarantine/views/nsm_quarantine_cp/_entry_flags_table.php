<table class="data col-sortable">
	<thead>
		<th scope="col" class="id"><?php print $LANG->line('id') ?></th>
		<th scope="col"><?php print $LANG->line('flagged_by') ?></th>
		<th scope="col"><?php print $LANG->line('flag_reason') ?></th>
		<th scope="col"><?php print $LANG->line('comment') ?></th>
		<th scope="col" class="date"><?php print $LANG->line('flagged_on') ?></th>
		<th scope="col" class="ip"><?php print $LANG->line('ip_address') ?></th>
		<th class='checkbox {sorter:false}'><input type="checkbox" name="toggleTrigger" /></th>
	</thead>
	<tbody>
		<?php foreach ($flags as $count => $flag) : 
			$class = ($count % 2) ? "odd" : "even";
		?>
		<tr class="<?php print($class); ?>">
			<td><?php print $flag['id']; ?></td>
			<td>
				<?php if(empty($flag['author_cp_url']) === FALSE) : ?>
					<a href="<?php print $flag['author_cp_url']; ?>"><?php print $flag['name']; ?></a>
				<?php else: ?>
					<?php print $flag['name']; ?>
				<?php endif; ?>
				<?php if(empty($flag['email']) === FALSE) : ?>
					<a href="mailto:<?php print $flag['email']; ?>" class="icon btn mail-small"><?php print $flag['email']; ?></a>
				<?php endif; ?>
			</td>
			<td><?php print $flag['type']; ?></td>
			<td><?php print $flag['comment']; ?></td>
			<td><?php print $LOC->set_human_time($flag['created_at']); ?></td>
			<td><?php print $flag['ip']; ?></td>
			<td><?php print $DSP->input_checkbox('toggle[]', $flag['id']) ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>