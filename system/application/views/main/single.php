 <h2>Latest Items <em>from</em> the blogosphere</h2>
<div class="hentry">
	<ul>
		<?php $alt = 1; foreach ( $items as $item ) : ?>
		
		<li id="item-<?= $item['id']; ?>">
			<?= heading( fm_post_permalink($item), 3) ; ?>
			<p class="metadata small">
					Retrieved at <abbr><?= fm_post_datetime($item); ?></abbr> / 
					<em>By</em> <abbr><?= fm_post_author($item); ?></abbr> 
					from <?= fm_post_site($item); ?>
				</p>
			<p class="excerpt"><?= $item['excerpt']; ?></p>
			<?= fm_post_tags($item , '<p class="small tags">Tags: ', '</p>'); ?>
			<?= fm_post_meta($item); ?>
			<?= fm_post_fav($item); ?>
			<div id="fb-comment" numposts="4" title="<?= $item['title']; ?>" xid="item-<?= $item['id']; ?>" width="520px" reverse="true" simple="true"></div> 
		</li>
		
		<?php $alt++; endforeach; ?>
	</ul>
</div>
<div class="page"><?= $pagination; ?></div>
<script type="text/javascript">
<!--
Js.$(function($){
	FB.Bootstrap.requireFeatures(["XFBML","Connect"], function() { 
		FB.Facebook.init("{{MODULE-FACEBOOK-APIKEY}}", "/xd_receiver.htm", {"reloadIfSessionStateChanged":true});
		FB.XFBML.Host.autoParseDomTree = false; 
		FB.XFBML.Host.addElement(
			new FB.XFBML.Comments(document.getElementById("fb-comment"))
		); 
	});
});
-->
</script>
