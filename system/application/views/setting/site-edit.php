<form id="edit-site">
	<input type="hidden" name="site_id" value="<?= $id; ?>" />
	<fieldset>
		<div class="panel">
			<label>Site Name <strong>*</strong></label>
			<input type="text" name="site_name" value="<?= $name; ?>" class="long required" />
		</div>
		<div class="panel">
			<label>Site Feed <strong>*</strong></label>
			<input type="text" name="site_feed" value="<?= $feed; ?>" class="long required" />
			<em>e.g: http://example.com/feed</em>
		</div>
	</fieldset>
</form>
<div style="clear: both"></div>
<script type="text/javascript">
<!--
Js.data.setting.site.bindSubmitEdit();
-->
</script>