$(document).ready(function() {
	console.log($('form#flagged-comments'));
	$('form#flagged-comments').submit(function() {
		select = $('select[name=action]').val();
		if(select == 'quarantine' || select == 'unquarantine')
		{
			document.flagged_comments.setAttribute('action', nsm_quarantine_flagged_comments_url.replace(/\&amp;/g,'&'));
		}
	});
});

// 
// $(document).ready(function() {
// 	updateDupes();
// 	updateNotificationTemplates();
// 	$('select[name=allow_duplicates]').change(updateDupes);
// 	$('select[name=notify_admins], select[name=notify_author]').change(updateNotificationTemplates);
// });
// 
// function updateDupes() {
// 	if($('select[name=allow_duplicates]').val() == 'n'){
// 		$('#check_ip, #check_cookie, #check_member_id').fadeIn();
// 	}
// 	else
// 	{
// 		$('#check_ip, #check_cookie, #check_member_id').hide();
// 	}
// }
// function updateNotificationTemplates() {
// 	if($('select[name=notify_admins]').val() == 'y'){
// 		$('.admin-notification').fadeIn();
// 	}
// 	else
// 	{
// 		$('.admin-notification').hide();
// 	}
// 	if($('select[name=notify_author]').val() == 'y'){
// 		$('.author-notification').fadeIn();
// 	}
// 	else
// 	{
// 		$('.author-notification').hide();
// 	}
// }