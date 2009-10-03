<?php if ($flags == FALSE) : ?>
	<div class="mor-alert success"><?php print $LANG->line('entry_flags_no_results'); ?></div>
<?php else:
 	print $DSP->form_open( 
			array(
				'action'	=> "C=modules"
								.AMP."M=Nsm_quarantine"
								.AMP."P=delete_flag_confirmation",
				'method'	=> 'post',
				'id' 		=> 'entry-flags',
				'name'		=> 'entry_flags'
			),
			array(
				"quarantinable_type"=>"entry",
				"entry_id" => $entry["id"],
				"weblog_id" => $entry["weblog_id"]
			)
		);
?>
	<ul class="summary-bar info">
		<li>
			<span class="term">Entry ID:</span>
			<span class="desc"><a href='<?php print $entry['edit_url'] ?>'>#<?php print $entry['id']; ?></a></span>
		</li>
		<li>
			<span class="term">Quarantined?</span>
			<span class="desc"><?php print ($entry["is_quarantined"]) ? $LANG->line("yes") : $LANG->line("no"); ?></span>
		</li>
		<li>
			<span class="term">Flag count:</span>
			<span class="desc"><?php print $entry["flags"]; ?></span>
		</li>
		<li>
			<span class="term">Status:</span>
			<span class="desc"><?php print ucfirst($entry["status"]); ?></span>
		</li>
		<li>
			<span class="term">Author:</span>
			<span class="desc">
				<a href="<?php print $entry['author_cp_url'] ?>"><?php print $entry['author_name'] ?></a>
				<a class='btn icon mail-small' href='mailto:<?php print($entry['author_email']); ?>' title='<?php print($entry['author_email']); ?>'>Email</a>
			</span>
		</li>
		<li>
			<span class="term">Created at:</span>
			<span class="desc"><?php print $LOC->set_human_time($entry['created_at']) ?></span>
		</li>
	</ul>
	<div class="tg">
		<h2>Flags</h2>
		<?php include PATH_LIB . "nsm_quarantine/views/nsm_quarantine_cp/_entry_flags_table.php"; ?>
	</div>
	<div class='table-actions'>
		<input type='submit' value='<?php print $LANG->line('submit') ?>' />
		<select name='action'>
			<option value='delete'><?php print $LANG->line('delete_selected'); ?></option>
		</select>
	</div>
</form>
<?php endif; ?>