<script type="text/javascript">
<!--
Js.data.page = {
	hide : {
		init: function() {
			Js.$('.item_hide', '#content').bind('click', function() {
				var href = Js.$(this).attr('href');
				
				Js.$.post(href, {}, function( reply ) {
					Js.parse.xhr.init( reply );
				}, 'text');
				
				return false;
			});
		}
	},
	like: {
		init: function() {
			Js.$('.item_like', '#content').bind('click', function() {
				var href = Js.$(this).attr('href');
				
				Js.$.post(href, {}, function( reply ) {
					Js.parse.xhr.init( reply );
				}, 'text');
				
				return false;
			});
		},
		reply: function( reply ) {
			var node = null;
			if ( Js.$('#item_like_' + reply[0] ).size() > 0 ) {
				Js.$( '#item_like_' + reply[0] ).text( reply[1] );
			}
			else {
				Js.$('<p/>')
					.attr( 'id', 'item_like_' + reply[0] )
					.text( reply[1] )
					.insertAfter( '#item_option_' + reply[0] );
			}
		}	
	},
	more: function() {
		Js.$('.tag_more', '#content').bind('click', function() {
			var node = Js.$(this);
			var identity = node.attr('id');
			var id = identity.split('_');
			
			Js.$( '#tag_snippet_' + id[2] ).html( Js.$( '#tag_full_' + id[2] ).html() );
			node.remove();
			
			return false;
		}).attr('href', '#');
	},
	share: { 
		dialog: null,
		init: function() {
			Js.$('.item_share', '#content').bind('click', function() {
				var node = Js.$(this);
				var hrefs = node.attr('href');
				hrefs.match(/(\#item\-)(\d*)/g);
				var id = RegExp.$2;
				
				Js.data.page.share.dialog = new Js.widget.dialog({
					element: 'share-site',
					title: 'Share this',
					width: 430,
					content: '<p>Loading...</p>'
				});
				
				Js.data.page.share.dialog.content.html( Js.$( '#item_share_' + id ).html() );
				Js.data.page.share.dialog.fixDimension();
				
				return false;
			});
		},
		
	}
};
Js.$(function($) {
	Js.data.page.more();
	Js.data.page.share.init();
	Js.data.page.like.init();
	Js.data.page.hide.init();
});
-->
</script>