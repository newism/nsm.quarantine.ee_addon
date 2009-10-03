<?php $hide_checkboxes = TRUE; ?>
<ul class="summary-bar info">
	<li>
		<span class="term">Flagged entries</span>
		<a href="<?php print $this->CP_base_url . AMP."P=entries" ?>" class="desc"><?php print $flagged_entries_count ?></a>
	</li>
	<li>
		<span class="term">Flagged comments</span>
		<a href="<?php print $this->CP_base_url . AMP."P=comments" ?>" class="desc"><?php print $flagged_comments_count ?></a>
	</li>
</ul>
<?php 
	$flagged_entries_title = "Latest flagged entries";
	include PATH_LIB . "nsm_quarantine/views/nsm_quarantine_cp/_flagged_entries_table.php";

	$flagged_comments_title = "Latest flagged comments";
	include PATH_LIB . "nsm_quarantine/views/nsm_quarantine_cp/_flagged_comments_table.php";
?>
