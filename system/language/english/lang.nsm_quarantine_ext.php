<?php
/**
* Language file for NSM Quarantine.
* 
* This file must be placed in the
* /system/language/english/ folder in your ExpressionEngine installation.
*
* @package NSM Quarantine
* @version 1.0.0
* @author Leevi Graham <http://leevigraham.com>
* @see http://leevigraham.com/cms-customisation/expressionengine/addon/nsm-quarantine/
* @copyright Copyright (c) 2007-2008 Leevi Graham
* @license http://leevigraham.com/cms-customisation/commercial-license-agreement
*/

$L = array(
"nsm_quarantine_title" => "NSM Quarantine",

'member_flagging_prefs_title'					=> 'Member flagging preferences',
'member_flagging_prefs_info'					=> 'Flagging of entries / comments can be restricted to certain member groups. You can also choose if a member can flag an article more than once and how the number of their flags will be calculated.',
'which_groups_are_allowed_to_flag_label'		=> 'Which groups are allowed to flag?',
'which_groups_are_allowed_to_quarantine_label'	=> 'Which groups are allowed to automatically quarantine?',
'allow_duplicates_label'						=> 'Can site visitors flag an entry / comment more than once?',

'quarantine_defaults_title' 	=> 'Quarantine defaults',
'quarantine_defaults_info'		=> '<p>An entry or comment is quarantined once a certain number of \'flags\' have been thrown. Comments will be set to closed and entries will be set to the "Quarantined entry status" below.</p>
									<p>When entries are unquarantined their status will be changed to  the "Unquarantined entry status" below',
'comment_flag_count_label'		=> 'Number of flags before a comment is quarantined',
'entry_flag_count_label'		=> 'Number of flags before an entry is quarantined',
'quarantine_status_label'		=> 'Quarantined entry status',
'unquarantine_status_label'		=> 'Unquarantined entry status',
'quarantine_flag_types_info'	=> 'When an entry is flagged a flag type can be set. Flag types appear as a select list in the submission form. Enter each flag type on a new line.',
'quarantine_flag_types_label'	=> 'Flag Types',

'check_cookie_label'			=> 'Check cookies?',
'check_ip_label'				=> 'Check IP Address',
'check_member_id_label'			=> 'Check Member ID',

'notification_template_title'			=> 'Notification Templates',
'notification_template_info'			=> '<p>Once an entry or comment has been quarantined it is possible to send an email to the site administrator or author of the entry/comment.<p>
											<p>The following variables will be replaced inside the both entry and comment templates:</p>
											<ul>
												<li><code>{entry_type}</code>: Either \'entry\' or \'comment\'</li>
												<li><code>{entry_id}</code>: The quarantined entry_id or comment_id</li>
												<li><code>{weblog_id}</code>: The weblog id</li>
												<li><code>{entry_title}</code>: The entry title</li>
												<li><code>{author_name}</code>: The author name</li>
												<li><code>{author_email}</code>: The author email</li>
												<li><code>{flags}</code>: The number of times this entry/comment has been flagged</li>
												<li><code>{quarantine_count}</code>: The number of time the entry/comment has been quarantined</li>
												<li><code>{edit_entry_url}</code>: The url to edit the entry in your admin panel</li>
												<li><code>{edit_comments_url}</code>: The url to an entries comments in your admin panel</li>
												<li><code>{status}</code>: The original entry status</li>
												<li><code>{quarantine_status}</code>: The new quarantined status</li>
											</ul>
											<p>The following variables are available in comment notifications:</p>
											<ul>
												<li><code>{edit_comment_url}</code>: The url to edit the comment in your admin panel</li>
											</ul>
											<p>Emails are either sent as Plain Text or HTML based on your email configuration preferences.</p>',
'notification_email_type_label'				=> 'Send emails as:',

'admin_notification_template_title'			=> 'Admin notification templates',
'notify_admin_emails_label'		 			=> 'Comma separated list of quarantine notification emails.',
'notify_admin_label'			 			=> 'Send the site administrator a notification template when an entry/comment is quarantined?',
'admin_notification_subject_entry_label'	=> 'Admin entry notification subject',
'admin_notification_template_entry_label' 	=> 'Admin entry notification template',
'admin_notification_subject_comment_label'	=> 'Admin comment notification subject',
'admin_notification_template_comment_label'	=> 'Admin comment notification template',

'author_notification_template_title'		=> 'Author notification templates',
'notify_author_label'						=> 'Send the author a notification template when an entry/comment is quarantined?',
'author_notification_subject_entry_label'	=> 'Author entry notification subject',
'author_notification_template_entry_label' 	=> 'Author entry notification template',
'author_notification_subject_comment_label'	=> 'Author comment notification subject',
'author_notification_template_comment_label'=> 'Author comment notification template',

'ajax_notification_title'					=> 'AJAX Server Responses',
'ajax_notification_info'					=> "If the quarantine form is submitted via ajax the server will respond with either of the two messages below. Any of the notification template variables above will be replaced in the server response.",
'ajax_form_template_info'					=> "Submission forms can be generated dynamically and returned to the browser using ajax. Which template would you like processed and to return to the user?",
'ajax_form_template_label'					=> 'Submission form template',
'ajax_response_quarantine_success_label'	=> 'Entry / comment quarantined',
'ajax_response_flag_added_label'			=> 'Flag added',

// default notification settings
'admin_comment_notification_subject'	=> 'A comment has been quarantined on {site_name}',
'admin_comment_notification_template'	=> "Hi,\n\nThe following comment made by '{author_name}' has been quarantined and its status changed from '{status}' to '{quarantine_status}' on '{site_name}'.\n\nYour comment:\n\n{comment}\n\nEdit this comment: {edit_comment_url}",
'admin_entry_notification_subject'		=> 'An entry has been quarantined on {site_name}',
'admin_entry_notification_template'		=> "Hi,\n\nAn entry titled '{entry_title}' has been quarantined and its status changed from '{status}' to '{quarantine_status}' on '{site_name}'.\n\nEdit this entry: {edit_entry_url}",

'author_comment_notification_subject'	=> 'Your comment has been quarantined on {site_name}',
'author_comment_notification_template'	=> "Hi {author_name},\n\nAn comment you made on the entry '{entry_title}' has been quarantined and its status changed from '{status}' to '{quarantine_status}' on '{site_name}'.\n\nYour comment:\n\n{comment}",
'author_entry_notification_subject'		=> 'Your entry has been quarantined on {site_name}',
'author_entry_notification_template'	=> "Hi {author_name},\n\nAn entry you published titled '{entry_title}' has been quarantined and its status changed from '{status}' to '{quarantine_status}' on '{site_name}'.",

'ajax_response_flag_added'				=> 'Thanks for flagging this {entry_type}.',
'ajax_response_quarantine_success' 		=> 'The {entry_type} you have flagged has been quarantined. Thankyou',

'view_quarantined_items' => 'View quarantined items',

// END
''=>''
);
?>