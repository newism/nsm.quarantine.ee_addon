<?php if ($flags == FALSE) : ?>
	<div class="mor-alert success"><?php print $LANG->line('comment_flags_no_results'); ?></div>
<?php else:
 	print $DSP->form_open( 
			array(
				'action'	=> "C=modules".AMP."M=Nsm_quarantine".AMP."P=delete_flag_confirmation",
				'method'	=> 'post',
				'id' 		=> 'comment-flags',
				'name'		=> 'comment_flags'
			),
			array(
				"quarantinable_type"=>"comment",
				"comment_id" => $comment['id'],
				"entry_id" => $comment['entry_id'],
				"weblog_id" => $comment['weblog_id']
			)
		);
?>

	<ul class="summary-bar info">
		<li>
			<span class="term">Comment ID:</span>
			<span class="desc"><a href='<?php print $comment['edit_url'] ?>'>#<?php print $comment['id']; ?></a></span>
		</li>

		<li>
			<span class="term">Quarantined?</span>
			<span class="desc"><?php print ($comment["is_quarantined"]) ? $LANG->line("yes") : $LANG->line("no"); ?></span>
		</li>
		<li>
			<span class="term">Flag count:</span>
			<span class="desc"><?php print $comment["flags"]; ?></span>
		</li>
		<li>
			<span class="term">Status:</span>
			<span class="desc"><?php print ucfirst($comment["status"]); ?></span>
		</li>
		<li>
			<span class="term">Author:</span>
			<span class="desc">
				<?php if ($comment['member_id']) : ?>
					<a href="<?php print $comment['author_cp_url'] ?>"><?php print $comment['author_name'] ?></a>
				<?php else: ?>
					<?php print$comment['author_name'] ?>
				<?php endif; ?>
				<a class='btn icon mail-small' href='mailto:<?php print($comment['author_email']); ?>' title='<?php print($comment['author_email']); ?>'>Email</a>
				<?php /* if (empty($comment['author_url']) === FALSE) : ?>
				<a class='btn icon globe-small' href='<?php print($comment['author_url']); ?>' title='<?php print($comment['author_url']); ?>'>URL</a>
				<?php endif; */ ?>
			</span>
		</li>
		<li>
			<span class="term">Created at:</span>
			<span class="desc"><?php print $LOC->set_human_time($comment['created_at']) ?></span>
		</li>
		<li class="text">
			<!-- span class="term">Comment:</span -->
			<div class="desc"><?php print $comment['comment']; ?></div>
		</li>
	</ul>

	
	<div class="tg">
		<h2>Flags</h2>
		<?php include PATH_LIB . "nsm_quarantine/views/nsm_quarantine_cp/_comment_flags_table.php"; ?>
	</div>
	<div class='table-actions'>
		<input type='submit' value='<?php print $LANG->line('submit') ?>' />
		<select name='action'>
			<option value='delete'><?php print $LANG->line('delete_selected'); ?></option>
		</select>
	</div>
</form>
<?php endif; ?>