	</div>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="http://github.com/malsup/form/raw/master/jquery.form.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">

	/* Forgot Password Dialog */
	$('.flag').live("click", function() {
		var $link = $(this);
		if(typeof($flag_dialog) == "undefined") {
			$flag_dialog = $("<div />")
				.attr({'id': 'nsm_quarantine-flag-dialog', 'title': 'Flag as inappropriate'})
				.appendTo("body").
				dialog({ modal: true, draggable: false, width: 400, resizable: false, autoOpen: false });
		}
		$flag_dialog.load(
			$link.attr("href"),
			{},
			function (responseText, textStatus, XMLHttpRequest) {
				$(this).dialog("open");
				$("form", this).ajaxForm({
					'dataType' : "json",
					success: function(data){
						$flag_dialog.html("<p class='alerts success'>The "+data.quarantinable_type+" has been successfully " + data.response_action );
					}
				});
			}
		);
		return false;
	});

	</script>
</body>
</html>