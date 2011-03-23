<script type="text/javascript">
<!--
if ( Js.helper.isnull( Js.data.setting ) ) 
	Js.data.setting = {};

Js.data.setting.suggestion = {
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
	add: function( com, grid ) {
		if ( Js.$( '.trSelected', grid ).size() > 0) {
			var item_list = [];
			var items = Js.$( '.trSelected', grid );
			for ( i = 0; i < items.length; i++ ) 
			{
				item_list.push( items[i].id.substr(3) );
			}
			
			Js.$.post(
				'<?php echo site_url("setting/suggestion/add"); ?>',
				{ items: item_list.join(',') },
				function( reply ) {
					Js.$( '#<?= $identity; ?>' ).flexReload();
				}
			);
		}
	},
	remove: function( com, grid ) {
		if ( Js.$( '.trSelected', grid ).size() > 0) {
			var item_list = [];
			var items = Js.$( '.trSelected', grid );
			for ( i = 0; i < items.length; i++ ) 
			{
				item_list.push( items[i].id.substr(3) );
			}
			
			Js.$.post(
				'<?php echo site_url("setting/suggestion/remove"); ?>',
				{ items: item_list.join(',') },
				function( reply ) {
					Js.$( '#<?= $identity; ?>' ).flexReload();
				}
			);
		}
	}
};
-->
</script>
