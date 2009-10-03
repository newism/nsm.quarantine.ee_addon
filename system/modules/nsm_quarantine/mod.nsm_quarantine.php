<?php
if ( ! defined('EXT')) exit('Invalid file request');

class Nsm_quarantine extends Morphine_Addon_Module{

	protected $developer_key 	= "NSM";
	protected $addon_key 		= "Quarantine";

	protected $quarantinable_id			= FALSE;
	protected $quarantinable_type		= FALSE;
	protected $conditionals = array(
		'can_flag'			=> FALSE,	// assume the user cannot flag
		'can_quarantine'	=> FALSE,	// assume the user cannot quarantine
		'has_flagged'		=> TRUE,	// assume the user has flagged
		'restricted'		=> TRUE,	// assume the user is restricted
	);

	function __construct()
	{
		parent::__construct();
	}

	public function form()
	{
		global $FNS, $IN, $LANG, $PREFS, $OUT, $TMPL;
		
		if (($insert_action = $FNS->fetch_action_id('Nsm_quarantine', 'flag_entry')) == FALSE)
		{
			return $OUT->show_user_error('general', $LANG->line('weblog_no_action_found'));
		}

		$tagdata = $TMPL->tagdata;
		$settings = $this->settings['addon'][$PREFS->ini('site_id')];

		$this->quarantinable_id = $TMPL->fetch_param('quarantinable_id');
		$this->quarantinable_type = $TMPL->fetch_param('quarantinable_type');
		
		$this->clean_tag_params();
		$this->prep_conditionals($TMPL->fetch_param('quarantinable_id'), $TMPL->fetch_param('quarantinable_type'));

		// if there is a quarantine form
		if(strpos($TMPL->tagdata, "quarantine_form") !== FALSE)
		{
			// check for the {quarantine_form} tag pair
			if(preg_match_all("/".LD."quarantine_form".RD."(.*?)".LD.SLASH."quarantine_form".RD."/s", $TMPL->tagdata, $matches))
			{
				$form_params = array(
					'quarantinable_id'		=> $this->quarantinable_id,
					'quarantinable_type'	=> $this->quarantinable_type,
					'notify_admins'			=> $TMPL->fetch_param('notify_admins') == "yes" ? TRUE : FALSE,
					'notify_admin_emails'	=> $TMPL->fetch_param('notify_admin_emails') == "yes" ? TRUE : FALSE,
					'notify_author'			=> $TMPL->fetch_param('notify_author') == "yes" ? TRUE : FALSE,
					'count'					=> $TMPL->fetch_param('quarantine_count') == "yes" ? TRUE : FALSE,
					'quarantine_status'		=> $TMPL->fetch_param('quarantine_status') == "yes" ? TRUE : FALSE,
				);

				$params_id = $this->insert_form_params($form_params);

				$RET = ($IN->GBL('RET')) ? $IN->GBL('RET') : $FNS->fetch_current_uri();
				$XID = $IN->GBL('XID');
				$URI = ($IN->URI == '') ? 'index' : $IN->URI;
				$return = isset($_POST['return_url']) ? $_POST['return_url'] : str_replace(SLASH, '/', $TMPL->fetch_param('return'));
				$return_if_quarantined = isset($_POST['return_if_quarantined_url']) ? $_POST['return_if_quarantined_url'] : str_replace(SLASH, '/', $TMPL->fetch_param('return_if_quarantined_url'));
				$ajax_return = isset($_POST['ajax_return_url']) ? $_POST['ajax_return_url'] : str_replace(SLASH, '/', $TMPL->fetch_param('ajax_return'));

				// create the form with the new params variable which we will use later
				$form_data = array(
					'action' 		=> $FNS->fetch_site_index(),
					'enctype'		=> '',
					'class'			=> 'nsm_quarantine-flag_form nsm_quarantine-flag_form-'.$this->quarantinable_id,
					'name'			=> trim($TMPL->fetch_param('form_name')),
					'id'			=> trim($TMPL->fetch_param('form_id')),
					'hidden_fields'	=> array(
											'form_params_id'			=> $params_id,
											'ACT'						=> $insert_action,
											'URI'						=> $URI,
											'RET'						=> $RET,
											'XID'						=> $XID,
											'return_url'				=> $return,
											'return_if_quarantined_url'	=> $return_if_quarantined,
											'ajax_return_url'			=> $ajax_return
										)
				);

				$form_open = $FNS->form_declaration($form_data);
				$form_close = "</form>";

				// Loop through each of the {quarantine_form} pairs
				for ($i=0; $i < count($matches[0]); $i++)
				{ 
					// the whole tag data including the {quarantine_form} tag pair
					$form_chunk = $matches[0][$i];
					$form_chunk_content = $matches[1][$i];

					$flag_types = '';
					foreach (explode("\n", $settings['quarantine_flag_types']) as $flag_type) {
						$flag_types .= "<option value='{$flag_type}'>{$flag_type}</option>\n";
					}

					$form_content = $TMPL->swap_var_single('flag_types', $flag_types, $form_chunk_content);
					$tagdata = str_replace($form_chunk, $form_open . $form_content . $form_close, $tagdata);
				}
			}
		}
		$tagdata .= "\n<!-- \nEntry and comment flagging powered by:\nNSM Quarantine {$this->version}\n{$this->docs_url} \n-->";
		return $FNS->prep_conditionals($tagdata, $this->conditionals);
	}

	public function flag_entry()
	{
		global $DB, $FNS, $IN, $LOC, $SESS, $PREFS, $REGX;

		$errors = array();
		$settings = $this->settings['addon'][$PREFS->ini('site_id')];

		// if the forms are secure
		if ($PREFS->ini('secure_forms') == 'y')
		{
			// query the database for the security hash
			$query = $DB->query("
				SELECT COUNT(*) AS count
				FROM exp_security_hashes
				WHERE hash='".$DB->escape_str($_POST['XID'])."'
				AND ip_address = '".$IN->IP."'
				AND date > UNIX_TIMESTAMP()-7200");
			// keep hash valid only for 2 hours
			if ($query->row['count'] == 0)
			{
				 // no data insertion if a hash isn't found or is too old 
				$errors[] = 'Your security signature could not be found or is too old. Please refresh the page and resubmit the form.';
			}
			else
			{
				// remove secure hash
				$DB->query("
					DELETE FROM exp_security_hashes
					WHERE (
						hash='".$DB->escape_str($_POST['XID'])."'
						AND
						ip_address = '".$IN->IP."'
					)
					OR date < UNIX_TIMESTAMP()-7200
				"); // helps garbage collection for old hashes
			}
		}

		if(empty($errors) === FALSE)
			return $OUT->show_user_error('submission_error', $errors);

		$this->quarantinable_type = ($this->fetch_form_param('quarantinable_type') == '') ? 'entry' : $this->fetch_form_param('quarantinable_type');
		$this->quarantinable_id = $this->fetch_form_param('quarantinable_id');
		$quarantine_count = ($this->fetch_form_param('quarantine_count') == '') ? $settings[$this->quarantinable_type . '_flag_count'] : $this->fetch_form_param('quarantine_count');

		// insert our new flag
		$insert_data = array(
			'quarantinable_id' 		=> $this->quarantinable_id,
			'quarantinable_type'	=> $this->quarantinable_type,
			'member_id'				=> $SESS->userdata['member_id'],
			'email'					=> $IN->GBL('email'),
			'flag_type'				=> $IN->GBL('flag_type'),
			'comment'				=> $IN->GBL('comment'),
			'created_at'			=> $LOC->now,
			'year'					=> gmdate('Y', $LOC->now),
			'month'					=> gmdate('m', $LOC->now),
			'day'					=> gmdate('d', $LOC->now),
			'ip'					=> $IN->IP,
			'country'				=> $this->get_country(),
			'site_id' 				=> $PREFS->ini('site_id')
		);
		$insert_string = $DB->insert_string('exp_nsm_quarantine_submissions', $insert_data);
		$insert_query = $DB->query($insert_string);

		// merge any template params into the settings
		foreach ($settings as $key => $value)
		{
			if($this->fetch_form_param($key))
				$settings[$key] = $this->fetch_form_param($key);
		}

		// now are we working with an entry or a comment
		$quarantinables = ($this->quarantinable_type == "entry") ? Nsm_quarantine_ext::find_flagged_entries() : Nsm_quarantine_ext::find_flagged_comments();
		$this->entry_data = $quarantinables[0];
		$this->entry_data['quarantinable_type'] = $this->quarantinable_type; 

		// ok now we see if this entry / comment is quarantined... again?
		if($this->entry_data['flags'] % $quarantine_count && $IN->GBL('auto_quarantine') === FALSE)
		{
			$this->entry_data['response_action'] = 'flagged';
		}
		else
		{
			// add one to the quarantine count
			++$this->entry_data['quarantine_count'];

			// this entry has been quarantined again
			$this->entry_data['response_action'] = 'quarantined';

			if($this->quarantinable_type == 'comment')
			{
				$table = 'exp_comments';
				$this->entry_data['status'] = 'Open';
				$this->entry_data['quarantine_status'] = 'Closed';
			}
			else
			{
				$table = 'exp_weblog_titles';
				$this->entry_data['quarantine_status'] = $settings['quarantine_status'];
			}

			// add one to the entry / comment nsm_qua_total and set the entry nsm_is_qua to 'y'
			$update_data = array(
				'status'		=> $this->entry_data['quarantine_status'],
				'nsm_qua_count'	=> $this->entry_data['quarantine_count'],
				'nsm_is_qua' 	=> TRUE
			);
			$update_string = $DB->update_string($table, $update_data, array($this->quarantinable_type . '_id' => $this->quarantinable_id));
			$update_query = $DB->query($update_string);

			if($DB->affected_rows > 0)
			{
				// are we notifying admins?
				// send them an email
				if($settings['notify_admins'] == TRUE)
					$this->send_notifications('admin', $settings['notify_admin_emails'], $this->entry_data);

				// are we notifying the author?
				// build the author email from our entry data
				if($settings['notify_author'] == TRUE)
					$this->send_notifications('author', $this->entry_data['author_email'], $this->entry_data);
			}
		}

		// set the cookie on the users machine
		$subs = (isset($_COOKIE['nsm_quarantine_submissions'])) ? unserialize(stripslashes($_COOKIE['nsm_quarantine_submissions'])) : array('entry' => array(), 'comment' => array());
		if(in_array($this->quarantinable_id, $subs[$this->quarantinable_type]) === FALSE)
		{
			$subs[$this->quarantinable_type][] = $this->quarantinable_id;
		}
		setcookie("nsm_quarantine_submissions", serialize($subs), time() + 30000000, '/');

		// GET OUT OF HERE

		// print our ajax response straight to the browser
		if(AJAX_REQUEST === TRUE)
		{
			header("HTTP/1.0 200 OK");
			die(json_encode($this->entry_data));
			exit;
		}

		// get the url
		if($this->entry_data['response_action'] == 'quarantined' && $IN->GBL('return_if_quarantined_url') != FALSE)
		{
			$return	= $IN->GBL('return_if_quarantined_url', 'POST');
		}
		elseif($IN->GBL('return_url', 'POST'))
		{
			$return = $IN->GBL('return_url', 'POST');
		}
		else
		{
			$return	= $IN->GBL('RET', 'POST');
		}

		// parse the url
		if ( preg_match( "/".LD."\s*path=(.*?)".RD."/", $return, $match ) > 0 )
		{
			$return	= $FNS->create_url( $match['1'] );
		}
		elseif ( stristr( $return, "http://" ) === FALSE )
		{
			$return	= $FNS->create_url( $return );
		}

		// Return the user
		if ( $return != '' )
		{
			$FNS->redirect( $return );
		}
		else
		{
			$FNS->redirect( $FNS->fetch_site_index() );
		}

		exit;
	}

	/**
	* Sends an email to either the site administrator or the author of the entry / comment.
	*
	* @param	string 		$to_who 	author or admin
	* @param	string 		$emails 	comma separated list of emails
	* @since version 1.0.0
	*/
	private function send_notifications($to_who, $emails, $data)
	{
	
		global $DSP, $PREFS;

		// grab the template
		$settings = $this->settings['addon'][$PREFS->ini('site_id')];
		$template = $this->parse_template($settings["{$to_who}_{$this->quarantinable_type}_notification_template"], $data);
		$subject = $this->parse_template($settings["{$to_who}_{$this->quarantinable_type}_notification_subject"], $data);

		/** ----------------------------
		/**  Send email
		/** ----------------------------*/
		// get the email class
		if ( ! class_exists('EEmail'))
		{
			require PATH_CORE.'core.email'.EXT;
		}

		// create a new email object
		$E = new EEmail;        
		$E->wordwrap = $PREFS->ini('word_wrap');
		$E->mailtype = $PREFS->ini('mail_format');
		$E->priority = 3;

		// set the prefs
		// im sending it to myself
		$E->from($PREFS->ini('webmaster_email'), $PREFS->ini('webmaster_name'));
		$E->to($emails);

		// create a subject line
		$E->subject($subject);

		// add the message to the email object
		$E->message($template);

		if ($E->Send() === FALSE)
		{
			return $DSP->error_message($LANG->line('error_sending_email'), 0);
		}
	}

	/**
	* Parse EE variables out of string
	* 
	* @param $str string a block of text with embeded EE varaibles
	* @return string original block of text with parsed EE vars
	* @since version 1.0.0
	*/
	private function parse_template($str, $data)
	{
		global $PREFS;

		// build the replacements
		foreach ($data as $key => $value)
		{
			if (strpos($str, LD.$key.RD) !== FALSE)
			{
				$str = str_replace(LD.$key.RD, $value, $str);
			}
		}

		// build the replacements
		foreach ($PREFS->core_ini as $key => $value)
		{
			if(is_array($value) === FALSE)
			{
				if (strpos($str, LD.$key.RD) !== FALSE)
				{
					$str = str_replace(LD.$key.RD, $value, $str);
				}
			}
		}

		$str = str_replace(LD."edit_entry_url".RD, $PREFS->ini('cp_url') . "?C=edit&M=edit_entry&weblog_id={$data['weblog_id']}&entry_id={$data['entry_id']}", $str);
		$str = str_replace(LD."edit_comment_url".RD, ($this->entry_data['quarantinable_type'] != 'comment') ? '' : $PREFS->ini('cp_url') . "?C=edit&M=edit_comment&weblog_id={$data['weblog_id']}&entry_id={$data['entry_id']}&comment_id={$data['comment_id']}&current_page=0", $str);
		$str = str_replace(LD."edit_comments_url".RD, $PREFS->ini('cp_url') . "?&C=edit&M=view_comments&weblog_id={$data['weblog_id']}&entry_id={$data['entry_id']}", $str);
		print($str);
		return $str;
	}

	/**
	* Checks numerous voting and poll conditions
	*
	* The conditions checked include:
	* - {@link can_vote() Can the user vote?}
	* - {@link has_voted() Has the user voted?}
	*
	* @return string An error if no entry_id is specified or no poll is found for the specified entry_id
	* @since version 1.0.0
	*/
	private function prep_conditionals()
	{
		global $LANG, $OUT, $SESS, $PREFS, $TMPL;

		$settings = $this->settings['addon'][$PREFS->ini('site_id')];

		// check that the use isn't being stupid
		if ($this->quarantinable_type != 'comment' && $this->quarantinable_type != 'entry')
			return $OUT->show_user_error('general', $LANG->line('wrong_entry_type'));

		// can flag?
		$this->conditionals['can_flag'] = $this->can_flag();
		// can quarantine?
		if(
			$this->conditionals['can_flag'] &&
			(in_array($SESS->userdata['group_id'], $settings['quarantine_member_groups']) === TRUE))
		{
			$this->conditionals['can_quarantine'] = TRUE;
		}
	}

	/**
	* Checks to see if the user can quarantine
	*
	* - Is the user banned?
	* - Is the users nation banned?
	* - Has the user been blacklisted or are they on the whitelist?
	* - Are duplicates flags allowed?
	*
	* @return bool If the user can vote or not
	* @since version 1.0.0
	*/
	private function can_flag()
	{
		global $PREFS, $FNS, $IN, $LOC, $SESS, $TMPL;

		$settings = $this->settings['addon'][$PREFS->ini('site_id')];
		$this->conditionals['restricted'] = (
			// is the user banned?
			($SESS->userdata['is_banned'] == TRUE) ||
			// is the nation of the user banned?
			$SESS->nation_ban_check(FALSE) ||
			// blacklist whitelist check
			($IN->blacklisted == 'y' && $IN->whitelisted == 'n') ||
			// is the current user in the voting groups array?
			(in_array($SESS->userdata['group_id'], $settings['flag_member_groups']) === FALSE)
		) ? TRUE : FALSE;

		$this->conditionals['has_flagged'] = $this->has_flagged();

		// return if this visitor can vote
		return (
			$this->conditionals['restricted'] === TRUE ||
			($this->conditionals['has_flagged'] === TRUE && $settings['allow_duplicates'] == 'n')
		) ? FALSE : TRUE;
	}

	/**
	* Checks to see if the user has previously flagged a specific entry.
	*
	* @return bool If the user has voted or not
	* @since version 1.0.0
	*/
	private function has_flagged(){
		
		global $DB, $IN, $PREFS, $SESS;

		$submissions = FALSE;
		$settings = $this->settings['addon'][$PREFS->ini('site_id')];

		if($settings['check_cookie'] && isset($_COOKIE['nsm_polls_quarantine']))
		{
			$subs = unserialize($_COOKIE['nsm_polls_quarantine']);
			if(isset($subs[$this->quarantinable_type]) && in_array($this->quarantinable_id, $subs[$this->quarantinable_type]))
				 $submissions = TRUE;
		}

		// if we are checking for a duplicate IP
		if($settings['check_ip'])
		{
			$results = $DB->query("SELECT count(id) as count FROM exp_nsm_quarantine_submissions WHERE ip = '" . $IN->IP . "' AND quarantinable_id = " . $this->quarantinable_id . " AND quarantinable_type = '" . $this->quarantinable_type . "' LIMIT 1");
			if($results->row['count'] > 0) $submissions = TRUE;
		}

		// if we are checking for a duplicate member id
		if($settings['check_member_id'] && $SESS->userdata['member_id'] != FALSE)
		{
			$results = $DB->query("SELECT count(id) as count FROM exp_nsm_quarantine_submissions WHERE member_id = " . $SESS->userdata['member_id'] . " AND quarantinable_id = " . $this->quarantinable_id . " AND quarantinable_type = '" . $this->quarantinable_type . "' LIMIT 1");
			if($results->row['count'] > 0) $submissions = TRUE;
		}
		return $submissions;
	}

	/**
	* Gets the country code of the current user based on IP if Ip2Nation is installed
	*
	* @return bool|string FALSE if Ip2Nation is not installed or the users country cannot be found.
	* @since version 1.0.0
	*/
	private function get_country()
	{
		global $DB, $IN, $PREFS, $REGX, $SESS;

		if ($DB->table_exists('exp_ip2nation') === FALSE || $PREFS->ini('ip2nation') == 'n')
			return FALSE;

		$result = $DB->query("SELECT country FROM exp_ip2nation WHERE ip < INET_ATON('".$DB->escape_str($IN->IP)."') ORDER BY ip DESC LIMIT 0,1");
		return ($result->num_rows == 1) ? $result->row['country'] : FALSE;
	}



}