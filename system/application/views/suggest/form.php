<form id="suggest-site">
	<fieldset>
		<div class="panel">
			<label>Site URL <strong>*</strong></label>
			<input type="text" name="site_url" value="http://" class="long required" />
			<em>e.g: http://example.com</em>
		</div>
		<div class="panel">
			<label>Why do you think it should be in FeedMalaya? <strong>*</strong></label>
			<input type="text" name="site_desc" value="" class="long required" />
		</div>
	</fieldset>
</form>
<div style="clear: both"></div>
<script type="text/javascript">
<!--
Js.data.suggest.dialog.fixDimension();
Js.data.suggest.dialog.addButton({
	text: 'Submit',
	type: 'submit',
	callback: function() {
		var form = new Js.ext.validate('#suggest-site');
		
		if ( !!form.result ) {
			$.post(
				'<?php echo site_url("suggest/save"); ?>',
				form.result,
				function( reply ) {
					if ( reply.code === 1 ) {
						Js.data.suggest.dialog.content.html('<p>Site successfully added to suggestion list</p>');
						Js.data.suggest.dialog.closePanel();
					}
					else if ( reply.code == 2 ) {
						Js.data.suggest.dialog.content.append('<p>Session expired, please login again</p>');
					}
					else {
						Js.data.suggest.dialog.content.append('<p>Site failed to be added</p>');
					}
				},
				'json'
			);
			
		} else {
			alert('Please make sure all field is validated');
		}
		
		return false;
	}
});
-->
</script>