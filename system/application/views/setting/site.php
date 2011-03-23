<script type="text/javascript">
<!--
if ( Js.helper.isnull( Js.data.setting ) ) 
	Js.data.setting = {};

Js.data.setting.site = {
	tick: function( com, grid ) {
		if ( com == 'Select All' )
		{
			Js.$( '.bDiv tbody tr', grid ).addClass( 'trSelected' );
		}
		else if ( com == 'DeSelect All' )
		{
			Js.$( '.bDiv tbody tr', grid ).removeClass( 'trSelected' );
		}
	},
	edit: function( com, grid ) {
		if ( Js.$( '.trSelected', grid ).size() == 1 ) {
			var items = Js.$( '.trSelected', grid );
			var item_list = items[0].id.substr(3);
			
			Js.data.setting.site.dialog = new Js.widget.dialog({
				element: 'edit-site',
				title: 'Edit Site',
				width: 400,
				content: '<p>Loading...</p>'
			});
			
			Js.data.setting.site.dialog.content.load( '<?php echo site_url("setting/site/edit"); ?>/' + item_list );
		}
	},
	bindSubmitEdit: function() {
		Js.data.setting.site.dialog.fixDimension();
		Js.data.setting.site.dialog.addButton({
			callback: function() {
				var form = new Js.ext.validate('#edit-site');
				
				if ( !!form.result ) {
					$.post(
						'<?php echo site_url("setting/site/update"); ?>',
						form.result,
						function( reply ) {
							if ( reply.code === 1 ) {
								Js.data.setting.site.dialog.closePanel();
								Js.$( '#<?= $identity; ?>' ).flexReload();
							}
							else if ( reply.code == 2 ) {
								alert('Session expired, please login again');
							}
							else {
								alert('Fail');
							}
						},
						'json'
					);
					
				} else {
					alert('Please make sure all field is validated');
				}
				
				return false;
			},
			text: 'Submit',
			type: 'submit'
		});
	},
	addQueeue: function( com, grid ) {
		if ( Js.$( '.trSelected', grid ).size() == 1 ) {
			var items = Js.$( '.trSelected', grid );
			var item_list = items[0].id.substr(3);
			
			Js.$.post(
				'<?php echo site_url("setting/site/add"); ?>',
				{ items: item_list },
				function( reply ) {
					Js.$( '#<?= $identity; ?>' ).flexReload();
				}
			);
		}
	},
	removeQueeue: function( com, grid ) {
		if ( Js.$( '.trSelected', grid ).size() == 1 ) {
			var items = Js.$( '.trSelected', grid );
			var item_list = items[0].id.substr(3);
			
			Js.$.post(
				'<?php echo site_url("setting/site/remove"); ?>',
				{ items: item_list },
				function( reply ) {
					Js.$( '#<?= $identity; ?>' ).flexReload();
				}
			);
		}
	},
	activate: function( com, grid ) {
		if ( Js.$( '.trSelected', grid ).size() == 1 ) {
			var items = Js.$( '.trSelected', grid );
			var item_list = items[0].id.substr(3);
			
			Js.$.post(
				'<?php echo site_url("setting/site/activate"); ?>',
				{ items: item_list },
				function( reply ) {
					Js.$( '#<?= $identity; ?>' ).flexReload();
				}
			);
		}
	},
	deActivate: function( com, grid ) {
		if ( Js.$( '.trSelected', grid ).size() == 1 ) {
			var items = Js.$( '.trSelected', grid );
			var item_list = items[0].id.substr(3);
			
			Js.$.post(
				'<?php echo site_url("setting/site/deactivate"); ?>',
				{ items: item_list },
				function( reply ) {
					Js.$( '#<?= $identity; ?>' ).flexReload();
				}
			);
		}
	}
};
-->
</script>
