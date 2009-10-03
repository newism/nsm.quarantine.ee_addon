<div class="tg">
	<h2><?php print $LANG->line('member_flagging_prefs_title') ?></h2>
	<div class="info"><?php print $LANG->line('member_flagging_prefs_info') ?></div>
	<table>
		<tbody>
			<tr class="even">
				<th><?php print $LANG->line('which_groups_are_allowed_to_flag_label') ;?></th>
				<td>
					<? foreach($member_group_query->result as $row ): ?>
						<label class='checkbox'>
							<?php print $row['group_title']; ?>
							<input
								<? if(in_array($row['group_id'], $settings['flag_member_groups'])) print("checked='checked'"); ?>
								name="Nsm_quarantine_ext[flag_member_groups][]"
								type="checkbox"
								value="<?php print $row['group_id']; ?>"
							>
						</label>
					<? endforeach; ?>
				</td>
			</tr>
			<tr class="odd">
				<th><?php print $LANG->line('which_groups_are_allowed_to_quarantine_label') ;?></th>
				<td>
					<? foreach($member_group_query->result as $row ): ?>
						<label class='checkbox'>
							<?php print $row['group_title']; ?>
							<input
								<? if(in_array($row['group_id'], $settings['quarantine_member_groups'])) print("checked='checked'"); ?>
								name="Nsm_quarantine_ext[quarantine_member_groups][]"
								type="checkbox"
								value="<?php print $row['group_id']; ?>"
							>
						</label>
					<? endforeach; ?>
				</td>
			</tr>
			<tr class="even">
				<th><?php print $LANG->line('allow_duplicates_label') ;?></th>
				<td>
					<?php print Morphine_Display::select_box(
						$settings["allow_duplicates"],
						array("1" => "yes", "0" => "no"),
						"Nsm_quarantine_ext[allow_duplicates]"
					); ?>
				</td>
			</tr>
			<tr class="odd">
				<th class="sub-heading">
					<?php print $LANG->line("check_cookie_label"); ?>
					<div class="note">Low security - Most consistent</div>
				</th>
				<td>
					<?php print Morphine_Display::select_box(
						$settings["check_cookie"],
						array("1" => "yes", "0" => "no"),
						"Nsm_quarantine_ext[check_cookie]");
					?>
				</td>
			</tr>
			<tr class="even">
				<th class="sub-heading">
					<?php print $LANG->line("check_ip_label"); ?>
					<div class="note">High security - May cause problems with multiple votes on the same network</div>
				</th>
				<td>
					<?php print Morphine_Display::select_box(
						$settings["check_ip"],
						array("1" => "yes", "0" => "no"),
						"Nsm_quarantine_ext[check_ip]"
					); ?>
				</td>
			</tr>
			<tr class="odd">
				<th class="sub-heading">
					<?php print $LANG->line("check_member_id_label"); ?>
					<div class="note">High security - Only applicable to member only polls</div>
				</th>
				<td>
					<?php print Morphine_Display::select_box(
						$settings["check_member_id"],
						array("1" => "yes", "0" => "no"),
						"Nsm_quarantine_ext[check_member_id]"
					); ?>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<div class="tg">
	<h2><?php print $LANG->line('quarantine_defaults_title') ?></h2>
	<div class="info"><?php print $LANG->line('quarantine_defaults_info') ?></div>
	<table>
		<tbody>
			<tr class="even">
				<th><?php print $LANG->line('comment_flag_count_label'); ?></th>
				<td><input type='text' name='Nsm_quarantine_ext[comment_flag_count]' value='<?php print $REGX->form_prep($settings['comment_flag_count']); ?>' /></td>
			</tr>
			<tr class="odd">
				<th><?php print $LANG->line('entry_flag_count_label') ;?></th>
				<td><input type='text' name='Nsm_quarantine_ext[entry_flag_count]' value='<?php print $REGX->form_prep($settings['entry_flag_count']); ?>' /></td>
			</tr>
			<tr class="even">
				<th><?php print $LANG->line('quarantine_status_label') ;?></th>
				<td><input type='text' name='Nsm_quarantine_ext[quarantine_status]' value='<?php print $REGX->form_prep($settings['quarantine_status']); ?>' /></td>
			</tr>
			<tr class="odd">
				<th><?php print $LANG->line('unquarantine_status_label') ;?></th>
				<td><input type='text' name='Nsm_quarantine_ext[unquarantine_status]' value='<?php print $REGX->form_prep($settings['unquarantine_status']); ?>' /></td>
			</tr>
			<tr class="even">
				<th><?php print $LANG->line('quarantine_flag_types_label') ;?>
					<div class="note">
						<?php print $LANG->line('quarantine_flag_types_info') ;?>
					</div>
				</th>
				<td><textarea name='Nsm_quarantine_ext[quarantine_flag_types]' rows="12"><?php print $REGX->form_prep($settings['quarantine_flag_types']); ?></textarea></td>
			</tr>
		</tbody>
	</table>
</div>

<div class="tg">
	<h2><?php print $LANG->line('notification_template_title') ?></h2>
	<div class="info"><?php print $LANG->line('notification_template_info') ?></div>
	<h3><?php print $LANG->line('admin_notification_template_title'); ?></h3>
	<table>
		<tbody>
			<tr class="even">
				<th><?php print $LANG->line('notify_admin_label'); ?></th>
				<td>
					<?php print Morphine_Display::select_box(
						$settings["notify_admins"],
						array("1" => "yes", "0" => "no"),
						"Nsm_quarantine_ext[notify_admins]"
					); ?>
				</td>
			</tr>
			<tr class="odd">
				<th><?php print $LANG->line('notify_admin_emails_label'); ?></th>
				<td><input type='text' name='Nsm_quarantine_ext[notify_admin_emails]' value='<?php print $REGX->form_prep($settings['notify_admin_emails']); ?>' /></td>
			</tr>
			<tr class="even">
				<th><?php print $LANG->line('admin_notification_subject_entry_label'); ?></th>
				<td><input type='text' name='Nsm_quarantine_ext[admin_entry_notification_subject]' value='<?php print $REGX->form_prep($settings['admin_entry_notification_subject']); ?>' /></td>
			</tr>
			<tr class="odd">
				<th><?php print $LANG->line('admin_notification_template_entry_label'); ?></th>
				<td><textarea name='Nsm_quarantine_ext[admin_entry_notification_template]' rows="12"><?php print $REGX->form_prep($settings['admin_entry_notification_template']); ?></textarea></td>
			</tr>
			<tr class="even">
				<th><?php print $LANG->line('admin_notification_subject_comment_label'); ?></th>
				<td><input type='text' name='Nsm_quarantine_ext[admin_comment_notification_subject]' value='<?php print $REGX->form_prep($settings['admin_comment_notification_subject']); ?>' /></td>
			</tr>
			<tr class="odd">
				<th><?php print $LANG->line('admin_notification_template_comment_label'); ?></th>
				<td><textarea name='Nsm_quarantine_ext[admin_comment_notification_template]' rows="12"><?php print $REGX->form_prep($settings['admin_comment_notification_template']); ?></textarea></td>
			</tr>
		</tbody>
	</table>
	<h3><?php print $LANG->line('author_notification_template_title'); ?></h3>
	<table>
		<tbody>
			<tr class="even">
				<th><?php print $LANG->line('notify_author_label'); ?></th>
				<td>
					<?php print Morphine_Display::select_box(
						$settings["notify_author"],
						array("1" => "yes", "0" => "no"),
						"Nsm_quarantine_ext[notify_author]"
					); ?>
				</td>
			</tr>
			<tr class="odd">
				<th><?php print $LANG->line('author_notification_subject_entry_label'); ?></th>
				<td><input type='text' name='Nsm_quarantine_ext[author_entry_notification_subject]' value='<?php print $REGX->form_prep($settings['author_entry_notification_subject']); ?>' /></td>
			</tr>
			<tr class="even">
				<th><?php print $LANG->line('author_notification_template_entry_label'); ?></th>
				<td><textarea name='Nsm_quarantine_ext[author_entry_notification_template]' rows="12"><?php print $REGX->form_prep($settings['author_entry_notification_template']); ?></textarea></td>
			</tr>
			<tr class="odd">
				<th><?php print $LANG->line('author_notification_subject_comment_label'); ?></th>
				<td><input type='text' name='Nsm_quarantine_ext[author_comment_notification_subject]' value='<?php print $REGX->form_prep($settings['author_comment_notification_subject']); ?>' /></td>
			</tr>
			<tr class="even">
				<th><?php print $LANG->line('author_notification_template_comment_label'); ?></th>
				<td><textarea name='Nsm_quarantine_ext[author_comment_notification_template]' rows="12"><?php print $REGX->form_prep($settings['author_comment_notification_template']); ?></textarea></td>
			</tr>
		</tbody>
	</table>
</div>