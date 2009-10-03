<?php print $DSP->form_open( array( 'action' => 'C=edit'.AMP.'M=multi_edit', 'method' => 'post', 'id' => 'flagged-entries', 'name' => 'flagged_entries')); ?>
	<div class='tg'>
		<?php if(isset($flagged_entries_title) === TRUE){ print "<h2>{$flagged_entries_title}</h2>" ; } ?>
		<table class='data col-sortable'>
			<thead>
				<tr>
					<th scope="col" class='id'><?php print $LANG->line('id') ?></th>
					<th scope="col" class='title'><?php print $LANG->line('title') ?></th>
					<th scope="col" class='author'><?php print $LANG->line('author_name') ?></th>
					<th scope="col" class='date'>Entry date</th>
					<th scope="col" class='status'><?php print $LANG->line('status') ?></th>
					<th scope="col" class='flags'><?php print $LANG->line('flags')  ?></th>
					<th scope="col" class='qua_count'><?php print $LANG->line('qua_count') ?></th>
					<th scope="col" class='is_qua'><?php print $LANG->line('is_qua') ?></th>
					<th scope="col" class='checkbox {sorter:false}'><input type="checkbox" name="toggleTrigger" /></th>
				</tr>
			</thead>
			<tbody>
				<?php if($flagged_entries == FALSE): ?>
				<tr class="mor-alert success">
					<td colspan="9"><?php print $LANG->line('flagged_entries_no_results'); ?></td>
				</tr>
				<?php else: ?>
				<?php foreach ($flagged_entries as $count => $entry) : ?>
				<?php $class = ($count % 2) ? "odd" : "even"; ?>
				<tr class="<?php print($class); ?> <?php if($entry['is_quarantined'] == TRUE) print "status-closed" ?>">
					<td class="number"><a href="<?php print $entry['edit_url']; ?>" class="btn pencil-small"><?php print $entry['id'] ?></a></td>
					<th scope="row"><?php print $entry['title'] ?></th>
					<td>
						<a href="<?php print $entry['author_cp_url'] ?>"><?php print $entry['author_name'] ?></a>
						<a class='btn icon mail-small' href='mailto:<?php print($entry['author_email']); ?>' title='<?php print($entry['author_email']); ?>'>Email</a>
					</td>
					<td class="date"><?php print $LOC->set_human_time($entry['created_at']) ?></td>
					<td class='status-<?php print strtolower($entry['status']); ?>'><?php print ucfirst($entry['status']); ?></td>
					<td class="number"><a href="<?php print $entry['flags_url'] ?>" class="btn flag-small"><?php print $entry['flags'] ?></a></td>
					<td class="number"><?php print $entry['quarantine_count'] ?></td>
					<td><?php print $entry['is_quarantined'] == TRUE ? $LANG->line('yes') : $LANG->line('no'); ?></td>
					<td><?php print $DSP->input_checkbox('toggle[]', $entry['id']) ?></td>
				</tr>
				<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
	<div class='table-actions'>
		<input type='submit' value='<?php print $LANG->line('submit') ?>' />
		<select name='action'>
			<option value='quarantine'><?php print $LANG->line('quarantine'); ?></option>
			<option value='unquarantine'><?php print $LANG->line('unquarantine'); ?></option>
			<option value=''>----------------</option>
			<option value='edit'><?php print $LANG->line('edit_selected'); ?></option>
			<option value='delete'><?php print $LANG->line('delete_selected'); ?></option>
			<option value=''>----------------</option>
			<option value='add_categories'><?php print $LANG->line('add_categories'); ?></option>
			<option value='remove_categories'><?php print $LANG->line('remove_categories'); ?></option>
		</select>
	</div>
</form>