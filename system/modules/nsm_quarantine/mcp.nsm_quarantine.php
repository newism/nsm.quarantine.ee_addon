<?php

class Nsm_quarantine_CP extends Morphine_Addon_Module {

	var $developer_key = "NSM";
	var $addon_key = "Quarantine";

	var $actions = array( "Nsm_quarantine" => array("flag_entry", "ajax_form") );

	public $name 				= "NSM Quarantine";
	public $has_cp_backend 		= 'y';

	public $CP_base_url = FALSE;

	function __construct($switch = TRUE)
	{
		global $IN, $LANG;
		parent::__construct();
		$this->CP_base_url = BASE.AMP.'C=modules'.AMP.'M=Nsm_quarantine';
		if ($switch === TRUE)
		{
			$LANG->fetch_language_file('publish');
			// check the page
			switch($IN->GBL('P'))
			{
				case 'entries' : $this->entries();
				break;

				case 'comments' : $this->comments();
				break;

				case 'delete_flag_confirmation' : $this->delete_flag_confirmation();
				break;

				case 'flags';
					$method = $IN->GBL('quarantinable_type') . "_flags";
					$this->$method();
					break;

				default : $this->index();
				break;
			}
		}
	}
	
	private function index()
	{
		global $DSP, $IN, $LANG, $LOC;

		$flagged_entries_count = 0;
		if($flagged_entries = $this->find_flagged_entries())
		{
			$flagged_entries_count = count($flagged_entries);
			$flagged_entries = array_slice($flagged_entries, 0, 5);
		}

		$flagged_comments_count = 0;
		if($flagged_comments = $this->find_flagged_comments())
		{
			$flagged_comments_count = count($flagged_comments);
			$flagged_comments = array_slice($flagged_comments, 0, 5);
		}

		ob_start(); include(PATH_LIB.'nsm_quarantine/views/nsm_quarantine_cp/index.php'); $r = ob_get_clean();
		$this->render_layout("index", $r);
	}

	private function entries()
	{
		global $DSP, $IN, $LANG, $LOC;
		$this->update_quarantine_status('entry');
		$flagged_entries = $this->find_flagged_entries();
		ob_start(); include PATH_LIB . "nsm_quarantine/views/nsm_quarantine_cp/_flagged_entries_table.php"; $r = ob_get_clean();
		$this->render_layout("entries", $r);
	}

	private function comments()
	{
		global $DSP, $IN, $LANG, $LOC;
		$this->update_quarantine_status('comment');
		$flagged_comments = $this->find_flagged_comments();
		ob_start(); include PATH_LIB . "nsm_quarantine/views/nsm_quarantine_cp/_flagged_comments_table.php"; $r = ob_get_clean();
		$this->render_layout("comments", $r);
	}

	private function entry_flags()
	{
		global $DB, $DSP, $IN, $LANG, $LOC;

		$this->delete_flags($IN->GBL('toggle'));

		$entry_id = $IN->GBL('entry_id');
		$entries = $this->find_flagged_entries($entry_id);
		$entry = $entries[0];

		$title_replacements = array(
			"entry_title" => "<a href='".$entry['edit_url']."'>" . $entry['title'] . "</a>"
		);

		$flags = $this->find_flags($entry_id, "entry");
		ob_start(); include(PATH_LIB.'nsm_quarantine/views/nsm_quarantine_cp/entry_flags.php'); $r = ob_get_clean();
		$this->render_layout("entry_flags", $r, $title_replacements);
	}

	private function comment_flags()
	{
		global $DB, $DSP, $IN, $LANG, $LOC;

		$this->delete_flags($IN->GBL('toggle'));

		$comment_id = $IN->GBL('comment_id');
		$comments = $this->find_flagged_comments($comment_id);
		$comment = $comments[0];

		$title_replacements = array(
			"comment_id" => "<a href='".$comment['edit_url']."'>#" . $comment['id'] . "</a>",
			"entry_title" => "<a href='".$comment['entry_flags_url']."'>" . $comment['entry_title'] . "</a>"
		);

		$flags = $this->find_flags($comment_id, "comment");
		ob_start(); include(PATH_LIB.'nsm_quarantine/views/nsm_quarantine_cp/comment_flags.php'); $r = ob_get_clean();
		$this->render_layout("comment_flags", $r, $title_replacements);
	}

	private function delete_flag_confirmation()
	{
		global $DSP, $IN, $LANG, $LOC;

		$quarantinable_type 	= $IN->GBL('quarantinable_type');
		$comment_id 			= $IN->GBL('comment_id');
		$weblog_id 				= $IN->GBL('weblog_id');
		$entry_id 				= $IN->GBL('entry_id');

		if(($submission_ids = $IN->GBL('toggle', 'POST')) == FALSE)
		{
			$method = $quarantinable_type . "_flags";
			return $this->$method();
		}
		ob_start(); include(PATH_LIB.'nsm_quarantine/views/nsm_quarantine_cp/delete_flag_confirmation.php'); $r = ob_get_clean();
		$this->render_layout("delete_flag_confirmation", $r);
	}

	private function delete_flags($flags = FALSE)
	{
		global $DB, $LANG;
		// if this is a post and there is a toggle array
		if(empty($flags) == TRUE) return;
		// delete all the votes of the posted ids
		$DB->query("DELETE FROM exp_nsm_quarantine_submissions WHERE id IN (" . implode(",", $flags) . ")");

		// if there are rows affected
		if($DB->affected_rows > 0)
		{
			// add success message
			Morphine_Notification::set_notifications(array("success" => $LANG->line('delete_flags_success')), get_class($this));
		}
	}

	private function render_layout($page, $content = '', $title_replacements = array())
	{
		global $DSP, $LANG, $PREFS;

		$pages = array('index', 'entries', 'comments');

		$DSP->title = $LANG->line('nsm_quarantine_title_' . $page) ." | ". $this->name . " " . $this->version;
		$page_title = $LANG->line('nsm_quarantine_page_title_' . $page) . " <span> &ndash; " . $this->name . "</span>";

		foreach ($title_replacements as $target => $replacement)
		{
			$DSP->title = str_replace(LD.$target.RD, $replacement, $DSP->title);
			$page_title = str_replace(LD.$target.RD, $replacement, $page_title);
		}

		$DSP->crumbline = true;
		$DSP->crumb = "<a href='{$this->CP_base_url}'>{$this->name}</a>" . $DSP->crumb_item($LANG->line('nsm_quarantine_title_' . $page));

		$settings = $this->settings['addon'][$PREFS->ini('site_id')];

		$DSP->body .= "<div class='mor'>";
		$DSP->body .= "<ul class='menu hlist' id='nav-00'>";

		foreach ($pages as $menu_item)
		{
			$active = ($menu_item == $page) ? 'active' : '';
			$page_link = ($menu_item == 'index') ? '' : AMP . 'P=' . $menu_item;
			$DSP->body .= "<li class='" . $active . "'><a href='".$this->CP_base_url.$page_link."'>".$LANG->line('nsm_quarantine_title_' . $menu_item)."</a></li>";
		}
		$DSP->body .= "<li><a href='".$this->CP_base_url.AMP."C=admin".AMP."M=utilities".AMP."P=extension_settings".AMP."name=nsm_quarantine_ext'>" . $LANG->line('configuration') . "</a></li>";
		$DSP->body .= "</ul>";
		$DSP->body .= "<h1>" . $page_title . "</h1>";
		$DSP->body .= Morphine_Notification::render_notifications();

		$DSP->body .= $content;
		$DSP->body .= "</div>";

		Morphine_Display::insert_js('nsm_quarantine_flagged_comments_url = "'.$this->CP_base_url.AMP.'P=comments"');
		Morphine_Display::insert_js($PREFS->ini('theme_folder_url', 1) . "cp_themes/".$PREFS->ini('cp_theme')."/Morphine/js/jquery.tablesorter.2.0.3.min.js", TRUE);
		Morphine_Display::insert_js($PREFS->ini('theme_folder_url', 1) . "cp_themes/".$PREFS->ini('cp_theme')."/Morphine/js/MOR_MagicCheckboxes/jquery.MOR_MagicCheckboxes.js", TRUE);
		Morphine_Display::insert_js($PREFS->ini('theme_folder_url', 1) . "cp_themes/".$PREFS->ini('cp_theme')."/nsm_quarantine/js/admin.js", TRUE);
		Morphine_Display::insert_css($PREFS->ini('theme_folder_url', 1) . "cp_themes/".$PREFS->ini('cp_theme')."/nsm_quarantine/css/admin.css", TRUE);
	}

	private function update_quarantine_status($quarantinable_type)
	{
		global $IN, $DB, $LANG, $PREFS;
		// if this is a post and there is a toggle array
		if($quarantineable_ids = $IN->GBL('toggle', 'POST'))
		{
			$settings = $this->settings['addon'][$PREFS->ini('site_id')];
			$action = $IN->GBL('action');
			if($quarantinable_type == "entry")
			{
				$unquarantine_status = $human_unquarantine_status = $settings['unquarantine_status'];
				$quarantine_status = $human_quarantine_status = $settings['quarantine_status'];
				$table = 'exp_weblog_titles';
				$id_col = 'entry_id';
			}
			else
			{
				$unquarantine_status = "o";
				$human_unquarantine_status = "open";
				$quarantine_status = "c";
				$human_quarantine_status = "closed";
				$table = 'exp_comments';
				$id_col = 'comment_id';
				foreach ($quarantineable_ids as &$value)
				{
					$value = substr($value,1);
				}
			}
			$set = ($action == "unquarantine") ? "SET nsm_is_qua = 0, status = '".$unquarantine_status."'" : "SET nsm_is_qua = 1, nsm_qua_count = nsm_qua_count+1, status = '".$quarantine_status."'";
			$quarantine_query = $DB->query("UPDATE {$table} {$set} WHERE {$id_col} IN (" . implode(",", $quarantineable_ids) . ")");

			// add success message
			$status = ($action == "unquarantine") ? $human_unquarantine_status : $human_quarantine_status;
			$message = str_replace(
						array(LD."new_status".RD),
						array($status),
						$LANG->line($quarantinable_type.'_'.$action.'_success')
					);
			Morphine_Notification::set_notifications(array("success" => $message), get_class($this));
		}
	}

	public function find_flagged_entries($entry_id = FALSE)
	{
		global $DB, $PREFS;
		$w = ($entry_id) ? " t.entry_id={$entry_id} AND " : "";
		$flagged_entries_query = $DB->query("SELECT
										t.entry_id as id,
										t.weblog_id as weblog_id,
										t.author_id as author_id,
										t.title as title,
										t.url_title as url_title,
										t.status as status,
										t.entry_date as created_at,
										t.expiration_date as expires_at,
										t.nsm_qua_count as quarantine_count,
										t.nsm_is_qua as is_quarantined,
										COUNT(s.id) as flags,
										m.member_id as member_id,
										m.email as member_email,
										IF(STRCMP(m.screen_name,''),m.screen_name,m.username) as member_author_name
									FROM exp_weblog_titles as t
									LEFT JOIN `exp_nsm_quarantine_submissions` as s ON s.quarantinable_id = t.entry_id
									INNER JOIN exp_members as m ON t.author_id = m.member_id
									WHERE {$w} s.quarantinable_type = 'entry'
									AND s.site_id = " . $PREFS->ini('site_id') . "
									GROUP BY s.quarantinable_id
									ORDER BY t.entry_date DESC"
								);
		
		foreach($flagged_entries_query->result as &$entry)
		{
			if($entry['member_id'] != 0)
			{
				$entry['author_cp_url'] = BASE . AMP . "C=myaccount" . AMP . "id=" . $entry['author_id'];
				$entry['author_name'] = $entry['member_author_name'];
				$entry['author_email'] = $entry['member_email'];
			}

			$entry['edit_url'] = BASE . AMP . "C=edit"
											. AMP . "M=edit_entry"
											. AMP . "entry_id=" . $entry['id'] 
											. AMP . "weblog_id=" . $entry['weblog_id'];

			$entry['flags_url'] = $this->CP_base_url
											. AMP . "P=flags"
											. AMP . "quarantinable_type=entry"
											. AMP . "weblog_id=" . $entry['weblog_id']
											. AMP . "entry_id=" . $entry['id'];
		}
		return ($flagged_entries_query->num_rows > 0) ? $flagged_entries_query->result : FALSE;
	}

	public function find_flagged_comments($comment_id = FALSE)
	{
		global $DB, $LANG, $PREFS;
		$w = ($comment_id) ? " c.comment_id={$comment_id} AND " : "";
		$flagged_comments_query = $DB->query("SELECT
								c.comment_id as id,
								c.site_id as site_id,
								c.entry_id as entry_id,
								c.weblog_id as weblog_id,
								c.author_id as author_id,
								c.status as status,
								c.name as author_name,
								c.email as author_email,
								c.url as author_url,
								c.location as author_location,
								c.ip_address as author_ip,
								c.comment_date as created_at,
								c.edit_date as updated_at,
								c.comment as comment,
								c.notify as notify,
								c.nsm_qua_count as quarantine_count,
								c.nsm_is_qua as is_quarantined,
								count(s.id) as flags,
								t.title as entry_title,
								m.member_id as member_id,
								m.email as member_email,
								IF(STRCMP(m.screen_name,''),m.screen_name,m.username) as member_author_name
							FROM `exp_comments` as c
							LEFT JOIN `exp_nsm_quarantine_submissions` as s ON s.quarantinable_id = c.comment_id
							INNER JOIN `exp_weblog_titles` as t ON t.entry_id = c.entry_id
							LEFT JOIN `exp_members` as m ON m.member_id = c.author_id
							WHERE {$w} s.quarantinable_type = 'comment'
							AND s.site_id = " . $PREFS->ini('site_id') . "
							GROUP BY c.comment_id
							ORDER BY c.comment_date DESC"
						);

		if ( ! class_exists('Typography')) require PATH_CORE.'core.typography'.EXT;
		$TYPE = new Typography;

		$type_prefs = array(
			'text_format' 	=> 'xhtml',
			'html_format' 	=> 'safe',
			'auto_links' 	=> 'y',
			'allow_img_url' => 'y'
		);

		foreach($flagged_comments_query->result as &$comment)
		{
			$comment["status"] = ($comment["status"] == "o") ? $LANG->line("open") : $LANG->line("closed");

			if(strlen($comment['comment']) > 100)
			{
				$comment['comment'] = substr($comment['comment'],0,100) . " &hellip;";
			}
			$comment["comment"] = $TYPE->parse_type(stripslashes(htmlentities($comment['comment'], ENT_NOQUOTES, "UTF-8", FALSE)), $type_prefs);
			if($comment['member_id'] != 0)
			{
				$comment['author_cp_url'] = BASE . AMP . "C=myaccount" . AMP . "id=" . $comment['author_id'];
				$comment['author_name'] = $comment['member_author_name'];
				$comment['author_email'] = $comment['member_email'];
			}

			$comment['edit_url'] = BASE . AMP . "C=edit"
									. AMP . "M=edit_comment"
									. AMP . "weblog_id=" . $comment['weblog_id']
									. AMP . "entry_id=" . $comment['entry_id']
									. AMP . "comment_id=" . $comment['id'];

			$comment['flags_url'] = $this->CP_base_url
							. AMP . "P=flags"
							. AMP . "quarantinable_type=comment"
							. AMP . "weblog_id=" . $comment['weblog_id']
							. AMP . "entry_id=" . $comment['entry_id']
							. AMP . "comment_id=" . $comment['id'];

			$comment['entry_edit_url'] = BASE . AMP . "C=edit"
											. AMP . "M=edit_entry"
											. AMP . "entry_id=" . $comment['entry_id'] 
											. AMP . "weblog_id=" . $comment['weblog_id'];

			$comment['entry_flags_url'] = $this->CP_base_url
											. AMP . "P=flags"
											. AMP . "quarantinable_type=entry"
											. AMP . "weblog_id=" . $comment['weblog_id']
											. AMP . "entry_id=" . $comment['entry_id']
											. AMP . "comment_id=" . $comment['id'];
		}
		return ($flagged_comments_query->num_rows > 0) ? $flagged_comments_query->result : FALSE;
	}

	public function find_flags($quarantinable_id, $quarantinable_type)
	{
		global $DB, $LANG, $PREFS;
		$flag_query = $DB->query("SELECT
				# submissions
				s.id as id,
				s.ip as ip,
				s.created_at as created_at,
				s.flag_type as type,
				s.comment as comment,
				s.email as email,
				IF(STRCMP(s.name,''),s.name,'Guest') as name,
				m.member_id as member_id,
				m.email as member_email,
				m.url as member_url,
				IF(STRCMP(m.screen_name,''),m.screen_name,m.username) as member_name
			FROM exp_nsm_quarantine_submissions as s
			LEFT JOIN exp_members as m ON m.member_id = s.member_id
			WHERE s.quarantinable_id={$quarantinable_id} AND s.quarantinable_type='{$quarantinable_type}'
			AND s.site_id = ".$PREFS->ini('site_id')."
			ORDER BY s.created_at DESC"
		);

		if ( ! class_exists('Typography')) require PATH_CORE.'core.typography'.EXT;
		$TYPE = new Typography;

		$type_prefs = array(
			'text_format' 	=> 'xhtml',
			'html_format' 	=> 'safe',
			'auto_links' 	=> 'y',
			'allow_img_url' => 'y'
		);

		foreach ($flag_query->result as &$flag)
		{
			if(empty($flag['member_id']) === FALSE)
			{
				$flag['author_cp_url'] = BASE . AMP . "C=myaccount" . AMP . "id=" . $flag['member_id'];
				$flag['name'] = $flag['member_name'];
				$flag['email'] = $flag['member_email'];
			}
			$flag['comment'] = $TYPE->parse_type(stripslashes(htmlentities($flag['comment'])), $type_prefs);
		}
		return ($flag_query->num_rows > 0) ? $flag_query->result : FALSE;
	}

}