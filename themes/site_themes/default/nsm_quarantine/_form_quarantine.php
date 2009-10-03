{exp:nsm_quarantine:form quarantinable_type="{segment_4}" quarantinable_id="{segment_3}" form_id="nsm_quarantine"}
	{if can_flag}
		{if has_flagged}
			<p class="alerts">You have already flagged this entry, but you can flag it again.</p>
		{/if}
		{quarantine_form}
			<fieldset>
				<div>
					<label for="flag_type-{entry_id}">Why are you flagging this {segment_4}?</label>
					<select name='flag_type' id="flag_type-{entry_id}">
						<option value=''>--  Please Choose  --</option>
						{flag_types}
					</select>
				</div>
				<div>
					<label for="comment-{entry_id}">Comment:</label>
					<textarea name='comment' id="comment-{entry_id}" rows='3' cols='20'></textarea>
				</div>
				{if not_logged_in}
				<div>
					<label for="email-{entry_id}">Email:</label>
					<input type='text' id="email-{entry_id}" name='email' value='{logged_in_email}' />
				</div>
				{/if}
				{if can_quarantine}
					<div>
						<label for="auto_quarantine-{entry_id}">Auto quarantine this {segment_4}?</label>
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