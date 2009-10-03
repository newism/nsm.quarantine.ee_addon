{assign_variable:this_index_weblog="nsm_polls"}
{exp:weblog:entries
	weblog="{this_index_weblog}"
	limit="1"
	require_entry="yes"
}
{if no_results}{redirect="404"}{/if}

{embed="nsm_quarantine/.head" title="{title} | nsm_quarantine/entry"}

<h1>{title} <small> &mdash; Entry details</small></h1>

<div class="entry-meta">
	<div class="entry-published">
		<strong>Published:</strong>
		<abbr title="{entry_date format="{DATE_ISO8601}"}" class="published">
			<a href="{path=nsm_quarantine/monthly-archive}{entry_date format="%Y/%m"}/" title="View monthly archive for: {entry_date format="%M %Y"}">{entry_date format="%D, %F %d, %Y - %g:%i:%s"}</a>
			({relative_date})
		</abbr>
	</div>

	<div class="comments">
		<strong>Comments:</strong>
		<a href="{if page_uri}{page_uri}{if:else}{entry_id_path='nsm_quarantine/entry'}{/if}#comments" title="">{comment_total} comment{if "{comment_total}" != "1" }s{/if}</a>
	</div>

	{if edit_date}
	<div class="entry-updated">
		<strong>Last Modified:</strong>
		<abbr title="{edit_date format="{DATE_ISO8601}"}" class="updated pretty-date">{edit_date format="%D, %F %d, %Y - %g:%i:%s"} ({relative_date})</abbr>
	</div>
	{/if}

	{if expiration_date}
	<div class="entry-expires">
		<strong>Expires:</strong>
		<abbr title="{expiration_date format="{DATE_ISO8601}"}" class="expires pretty-date">{expiration_date format="%D, %F %d, %Y - %g:%i:%s"} ({relative_date})</abbr>
	</div>
	{/if}

	{if "{categories}{category_id}{/categories}"}
	<div class="categories">
		<strong>Categories:</strong>
		<ul>
			{categories}
			<li><a href="{path=nsm_quarantine/category-archive}" title="Browse {category_name}" rel="tag">{category_name}</a></li>
			{/categories}
		</ul>
	</div>
	{/if}
	
	{exp:nsm_quarantine:form quarantinable_type='entry' quarantinable_id='{entry_id}'}
	<h2>Inappropriate content?</h2>
	{if can_flag}
		{if has_flagged}
			<p class="alerts">You have already flagged this entry, but you can flag it again.</p>
		{/if}
		{quarantine_form}
			<fieldset>
				<legend>Flag <em>{title}</em> as inappropriate</legend>
				<div>
					<label for="flag_type-{entry_id}">Reason:</label>
					<select name='flag_type' id="flag_type-{entry_id}">
						<option value=''>--  Please Choose  --</option>
						{flag_types}
					</select>
				</div>
				<div>
					<label for="comment-{entry_id}">Comment:</label>
					<textarea name='comment' id="comment-{entry_id}" rows='3' cols='20'></textarea>
				</div>
				<div>
					<label for="email-{entry_id}">Email:</label>
					<input type='text' id="email-{entry_id}" name='email' value='{logged_in_email}' />
				</div>
				{if can_quarantine}
					<div>
						<label for="auto_quarantine-{entry_id}">Auto Quarantine?</label>
						<input type='checkbox' id="auto_quarantine-{entry_id}" name='auto_quarantine' value='y' />
					</div>
				{/if}
			</fieldset>
			<p><input type='submit' value='Flag as inappropriate' /></p>
		{/quarantine_form}
	{if:else}
		{if has_flagged}
			<p>Sorry, you have already flagged this entry.</p>
		{if:else}
			<p>You don't have permission to flag this entry.</p>
		{/if}
	{/if}
	{/exp:nsm_quarantine:form}

{/exp:weblog:entries}

	<h2>Comments</h2>
	<ol>
		{exp:comment:entries sort="asc" limit="20"}
		{if no_results}<li>No comments for this entry</li>{/if}
		{comment} <a href="{path=nsm_quarantine/_form_quarantine/{comment_id}/comment}" class="flag">Flag as inappropriate</a>
		{/exp:comment:entries}
	</ol>
	<h2>Add a comment</h2>
	{exp:comment:form weblog="{this_index_weblog}"}
	{if logged_out}
		<p>Name: <input type="text" name="name" value="{name}" size="50" /></p>
		<p>Email: <input type="text" name="email" value="{email}" size="50" /></p>
		<p>Location: <input type="text" name="location" value="{location}" size="50" /></p>
		<p>URL: <input type="text" name="url" value="{url}" size="50" /></p>
	{/if}

	<p><textarea name="comment" cols="70" rows="10">{comment}</textarea></p>
	<p><input type="checkbox" name="save_info" value="yes" {save_info} /> Remember my personal information</p>
	<p><input type="checkbox" name="notify_me" value="yes" {notify_me} /> Notify me of follow-up comments?</p>
	<input type="submit" name="submit" value="Submit" />

	{/exp:comment:form}
</div>
{embed="nsm_quarantine/.foot"}
