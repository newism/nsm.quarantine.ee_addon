NSM Quarantine - Community powered, peer review comment and weblog entry monitoring system
==========================================================================================

**This ExpressionEngine addon requires Morphine (the painless ExpressionEngine framework). Grab the latest version of Morphine from [http://github.com/newism/nsm.morphine.ee_addon](http://github.com/newism/nsm.morphine.ee_addon) and follow the readme instructions to install.**

**This addon is for testing purposes only and is considered a public beta**

NSM Quarantine is a MSM compatible community powered, peer review, comment and weblog entry monitoring system.

Weblog comments and entires can be flagged by users as innapropriate. Once a pre-determined number of flags have been thrown the comment or entry is "quarantined" and its status is updated to closed.

Quarantined comments and entries are monitored through a powerful administration panel. Flagged and quarantined comments and entries can be re-openned, edited or deleted by the site administrator.

Screenshots
-----------

[![](http://s3.amazonaws.com/ember/PPUN07mh0G0X1llY9UM1SjgXMA3Ey7db_s.jpg "Dashboard")](http://emberapp.com/leevigraham/images/dashboard-nsm-quarantine-1-0-0-ee-168-sandbox/)
[![](http://s3.amazonaws.com/ember/oDENV7R69FcDFupo2u8vJEFyTiCZLcJm_s.jpg "Flagged entries")](http://emberapp.com/leevigraham/images/flagged-entries-nsm-quarantine-1-0-0-ee-168-s/)
[![](http://s3.amazonaws.com/ember/WTptI1hxzfahr3ppD07vFpDMT7BJFWhx_s.jpg "Entry flags")](http://emberapp.com/leevigraham/images/entry-flags-nsm-quarantine-1-0-0-ee-168-sandb/)
[![](http://s3.amazonaws.com/ember/JqywtpSoTvi0VAmIA13rI4L8gfoybbHC_s.jpg "Flagged comments")](http://emberapp.com/leevigraham/images/flagged-comments-nsm-quarantine-1-0-0-ee-168-/)
[![](http://s3.amazonaws.com/ember/RTHG3oc8HR4eMHBIfpOlEXZuoWim6iCi_s.jpg "Comment flags")](http://emberapp.com/leevigraham/images/comment-flags-nsm-quarantine-1-0-0-ee-168-san/)
[![](http://s3.amazonaws.com/ember/GeSv9DFrPmdNUzQLdXExAnWT9UKeHIwp_s.jpg "Extension settings")](http://emberapp.com/leevigraham/images/nsm-quarantine-1-0-0-extension-settings-ee-16-1/)
[![](http://s3.amazonaws.com/ember/p0meC8TQEGc8md7Rd92d5qb0TVd1uBwT_s.jpg "Flagging content")](http://emberapp.com/leevigraham/images/entry-details-nsm-quarantineentry-nsm-quarant/)

Requirements
------------

* **ExpressionEngie**: NSM Quarantine requires ExpressionEngine 1.6.8+. New version update notifications will only be displayed if LG Addon Updater is installed.
* **jQuery**: NSM Quarantine also requires jQuery 1.3.2+ for comment and entry administration.
* **Browser**: NSM Quarantine requires a _standards compliant_ web browser like Safari or Firefox. There has been no testing in IE8 and below.
* **Server**: Your server must be running PHP5.2 or greater on a Linux flavoured OS.

Installation
------------

* Install and activate Morphine (the painless ExpressionEngine framework) available from: [http://github.com/newism/nsm.morphine.ee_addon](http://github.com/newism/nsm.morphine.ee_addon)
* Copy all the downloaded folders into your EE install. Note: you may need to change the <code>system</code> folder to match your EE installation
* Activate the NSM Quarantine extension.

Template Tags
-------------

Example templates are provided in <code>/themes/site\_themes/default/nsm\_quarantine</code>.

* Create a new template group called <code>nsm_quarantine</code>
* Create the following templates and save them as files:
	* .head
	* .foot
	* \_form_quarantine
	* entry
	* index
* Copy the included templates into the <code>nsm_quarantine</code> directory.
* Set the <code>{assign_variable:this_index_weblog="....."}</code> variable to use an existing weblog.
* Visit <code>http://yoursite.com/index.php?nsm_quarantine</code>

More template docs coming soon.

