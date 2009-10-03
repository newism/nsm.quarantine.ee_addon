<?php

class Nsm_quarantine_ext extends Morphine_Addon_Extension {

	protected $developer_key 	= "NSM";
	protected $addon_key 		= "Quarantine";

	public $name 				= "NSM Quarantine";
	public $description 		= "Master extension for NSM Quarantine";

	public $settings_exist		= 'y';

	protected $default_settings = array(
		"enabled" 								=> TRUE,
		"check_for_updates" 					=> TRUE,
		'flag_member_groups'					=> array(1),
		'quarantine_member_groups'				=> array(1),
		'quarantine_status'						=> 'closed',
		'unquarantine_status'					=> 'open',
		'allow_duplicates'						=> FALSE,
		'check_ip'								=> TRUE,
		'check_cookie'							=> TRUE,
		'check_member_id'						=> TRUE,
		'comment_flag_count'					=> 3,
		'entry_flag_count'						=> 5,
		'quarantine_flag_types' 				=> "Personal abuse\nOff topic\nLegal issue\nTrolling\nHate speech\nOffensive / threatening language\nCopyright\nSpam\nOther",
		'notification_email_type'				=> 'plain',
		'notify_admins'							=> '',
		'notify_admin_emails'					=> '',
		'admin_comment_notification_subject'	=> '',
		'admin_comment_notification_template'	=> '',
		'admin_entry_notification_subject'		=> '',
		'admin_entry_notification_template'		=> '',
		'notify_author'							=> 'n',
		'author_comment_notification_subject'	=> '',
		'author_comment_notification_template'	=> '',
		'author_entry_notification_subject'		=> '',
		'author_entry_notification_template'	=> '',
		'ajax_response_flag_added'				=> '',
		'ajax_response_quarantine_success' 		=> '',
	);

	function build_default_settings()
	{
		global $PREFS, $LANG;
		$LANG->fetch_language_file('nsm_quarantine_ext');
		$this->default_settings = array_merge($this->default_settings, array(
			'notify_admin_emails'					=> $PREFS->ini('webmaster_email') . (($PREFS->ini('webmaster_name') != '') ? "|" . $PREFS->ini('webmaster_name') : ''),
			'admin_comment_notification_subject'	=> $LANG->line('admin_comment_notification_subject'),
			'admin_comment_notification_template'	=> $LANG->line('admin_comment_notification_template'),
			'admin_entry_notification_subject'		=> $LANG->line('admin_entry_notification_subject'),
			'admin_entry_notification_template'		=> $LANG->line('admin_entry_notification_template'),
			'author_comment_notification_subject'	=> $LANG->line('author_comment_notification_subject'),
			'author_comment_notification_template'	=> $LANG->line('author_comment_notification_template'),
			'author_entry_notification_subject'		=> $LANG->line('author_entry_notification_subject'),
			'author_entry_notification_template'	=> $LANG->line('author_entry_notification_template'),
			'ajax_response_flag_added'				=> $LANG->line('ajax_response_flag_added'),
			'ajax_response_quarantine_success' 		=> $LANG->line('ajax_response_quarantine_success')
		));
	}

	protected $hooks = array(
		'nsm_addon_update_register_source',
		'nsm_addon_update_register_addon'
	);

	public function enable()
	{
		global $DB;

		$DB->query("CREATE TABLE IF NOT EXISTS `exp_nsm_quarantine_submissions` (
					`id` INT( 10 ) NOT NULL AUTO_INCREMENT,
					`site_id` INT( 10 ) NOT NULL,
					`quarantinable_id` INT( 10 ) NOT NULL,
					`quarantinable_type` VARCHAR( 255 ) NOT NULL,
					`flag_type` VARCHAR( 255 ) NOT NULL,
					`member_id` INT( 10 ) NOT NULL,
					`email` VARCHAR( 255 ) NOT NULL,
					`comment` TEXT NOT NULL,
					`ip` VARCHAR( 16 ) NOT NULL,
					`created_at` INT( 10 ) NOT NULL,
					`year` INT( 4 ) NOT NULL,
					`month` INT( 2 ) NOT NULL,
					`day` INT( 2 ) NOT NULL,
					`country` VARCHAR( 10 ) NOT NULL,
					PRIMARY KEY ( `id` , `entry_id` )
				)");

		$table_query = $DB->query("SHOW COLUMNS FROM exp_comments WHERE Field = 'nsm_qua_count'");
		if($table_query->num_rows == 0)
		{
			$DB->query("ALTER TABLE `exp_comments` ADD `nsm_qua_count` INT( 2 ) NOT NULL");
			$DB->query("ALTER TABLE `exp_comments` ADD `nsm_is_qua` CHAR( 1 ) NOT NULL DEFAULT '0'");

			$DB->query("ALTER TABLE `exp_weblog_titles` ADD `nsm_qua_count` INT( 2 ) NOT NULL");
			$DB->query("ALTER TABLE `exp_weblog_titles` ADD `nsm_is_qua` CHAR( 1 ) NOT NULL  DEFAULT '0'");
		}
		$this->create_hooks();

	}

	function __construct()
	{
		parent::__construct();
	}

	public function settings_form_content()
	{
		global $DB, $DSP, $FNS, $LANG, $PREFS, $REGX, $SESS;
		$DSP->right_crumb($LANG->line('view_quarantined_items'), BASE.AMP . 'C=modules'.AMP.'M=Nsm_quarantine', '', FALSE);
		$settings = $this->settings['addon'][$PREFS->ini('site_id')];
		$member_group_query = $DB->query("SELECT group_id, group_title FROM exp_member_groups WHERE site_id = " . $PREFS->ini('site_id') . " ORDER BY group_id");
		Morphine_Display::insert_js($PREFS->ini('theme_folder_url', 1) . "cp_themes/".$PREFS->ini('cp_theme')."/nsm_quarantine/js/settings_form.js", TRUE);
		ob_start(); include PATH_LIB.'nsm_quarantine/views/nsm_quarantine_ext/form_settings.php'; $ret = ob_get_clean();
		return $ret;
	}

	public function save_settings_start($settings)
	{
		if(isset($settings['flag_member_groups']) === FALSE)
			$settings['flag_member_groups'] = array();

		if(isset($settings['quarantine_member_groups']) === FALSE)
			$settings['quarantine_member_groups'] = array();

		return $settings;
	}

}

?>