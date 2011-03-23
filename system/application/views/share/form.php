<p>Feel free to share this article in:</p>
<div class="column">
	<p><?= anchor( 'http://del.icio.us/post?url=' . $url . '&title=' . htmlentities( $title ), img( base_url() . 'public/images/icons/delicious.png' ) . ' del.icio.us' ); ?></p>
	<p><?= anchor( 'http://www.google.com/bookmarks/mark?op=edit&output=popup&bkmk=' . $url . '&title=' . htmlentities( $title ), img( base_url() . 'public/images/icons/google.png' ) . ' Google' ); ?></p>
</div>
<div class="column">
	<p><?= anchor( 'http://www.facebook.com/share.php?u=' . $url, img( base_url() . 'public/images/icons/facebook.png' ) . ' Facebook' ); ?></p>
	<p><?= anchor( 'http://digg.com/submit?phase=2&url=' . $url . '&title=' . htmlentities( $title ), img( base_url() . 'public/images/icons/digg.png' ) . ' Digg' ); ?></p>
</div>
<div style="clear: both"></div>