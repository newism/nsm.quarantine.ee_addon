<?php 
	print $DSP->form_open(
		array(
			'action' => 'C=modules'
						.AMP.'M=Nsm_quarantine'
						.AMP.'P=flags',
			'method' => 'post',
		),
		array(
			"quarantinable_type"	=> $quarantinable_type,
			"comment_id" 			=> $comment_id,
			"entry_id" 				=> $entry_id,
			"weblog_id" 			=> $weblog_id
		)
	);
?>

<?php foreach ($submission_ids as $submission_id) : ?>
	<input type='hidden' name='toggle[]' value='<?php print $submission_id; ?>' />
<?php endforeach; ?>

<div class='alertHeading'><?php print $LANG->line('delete_confirm') ?></div>
<div class='box'>
	<div class='defaultBold'><?php print ($submission_ids == 1) ? $LANG->line('delete_flag_confirm') : $LANG->line('delete_flags_confirm') ; ?></div><br/>
	<div class='alert'><?php print $LANG->line('action_can_not_be_undone') ?></div><br/>
	<input type='submit' value='<?php print $LANG->line('delete') ?>' />
</div>

</form>