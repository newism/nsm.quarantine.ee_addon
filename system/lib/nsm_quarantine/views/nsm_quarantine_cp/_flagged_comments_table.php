<?php print $DSP->form_open( array( 'action' => 'C=edit' .AMP.'M=modify_comments', 'method' => 'post', 'id' => 'flagged-comments', 'name' => 'flagged_comments')); ?>
	<div class='tg'>
		<?php if(isset($flagged_comments_title) === TRUE){ print "<h2>{$flagged_comments_title}</h2>"; } ?>
		<table class='data col-sortable'>
			<thead>
				<tr>
					<th scope="col" class='id'><?php print $LANG->line('id') ?></th>
					<th scope="col" class='comment'><?php print $LANG->line('comment') ?></th>
					<th scope="col" class='author'>Comment author</th>
					<th scope="col" class='date'>Comment date</th>
					<th scope="col" class='status'><?php print $LANG->line('status') ?></th>
					<th scope="col" class='flags'><?php print $LANG->line('flags')  ?></th>
					<th scope="col" class='qua_count'><?php print $LANG->line('qua_count') ?></th>
					<th scope="col" class='is_qua'><?php print $LANG->line('is_qua') ?></th>
					<th scope="col">Entry</th>
					<th scope="col" class='checkbox {sorter:false}'><input type="checkbox" name="toggleTrigger" /></th>
				</tr>
			</thead>
			<tbody>
				<?php if($flagged_comments == FALSE): ?>
				<tr class="mor-alert success">
					<td colspan="9"><?php print $LANG->line('flagged_comments_no_results'); ?></td>
				</tr>
				<?php else: ?>
				<?php foreach ($flagged_comments as $count => $comment) : ?>
				<?php $class = ($count % 2) ? "odd" : "even"; ?>
				<tr class="<?php print($class); ?> <?php if($comment['is_quarantined'] == TRUE) print "status-closed" ?>">
					<td class="number"><a href="<?php print $comment['edit_url']; ?>" class="btn pencil-small"><?php print $comment['id'] ?></a></td>
					<th><?php print $comment["comment"]; ?></th>
					<td>
						<?php if ($comment['member_id']) : ?>
							<a href="<?php print $comment['author_cp_url'] ?>"><?php print $comment['author_name'] ?></a>
						<?php else: ?>
							<?php print$comment['author_name'] ?>
						<?php endif; ?>
						<a class='btn icon mail-small' href='mailto:<?php print($comment['author_email']); ?>' title='<?php print($comment['author_email']); ?>'>Email</a>
					</td>
					<td class="date"><?php print $LOC->set_human_time($comment['created_at']) ?></td>
					<td class='status-<?php print strtolower($comment['status']); ?>'><?php print $comment['status'] ?></td>
					<td class="number"><a href="<?php print $comment['flags_url']; ?>" class="btn flag-small"><?php print $comment['flags'] ?></a></td>
					<td class="number"><?php print $comment['quarantine_count'] ?></td>
					<td><?php print $comment['is_quarantined'] == TRUE ? $LANG->line('yes') : $LANG->line('no'); ?></td>
					<td><a class="btn pencil-small" href="<?php print $comment['entry_edit_url']?>"><?php print $comment['entry_title'] ?></a></td>
					<td><?php print $DSP->input_checkbox('toggle[]', "c".$comment['id']) ?></td>
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
			<option value='delete'><?php print $LANG->line('delete_selected'); ?></option>
			<option value='move'><?php print $LANG->line('move_selected'); ?></option>
		</select>
	</div>
</form>