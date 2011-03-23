<h2>Latest <em>feeds</em></h2>
<div id="post">
	<?php $alt = 1; foreach ( $items as $item ) : ?>
	
	<div class="hentry <?= ( ( $alt % 2) == 0 ? 'alt' : '' ); ?>" id="item-<?= $item['id']; ?>">
		<abbr><?= date( 'd-m-Y', $item['datetime'] ); ?></abbr>
		<div class="entry-content">
			<p>
				<span class="entry-title"><?= anchor( 'item/visit/' . $item['id'] . '/' . $item['slug'], $item['title'] ) ; ?></span>  &raquo; <?= $item['excerpt']; ?> 
				<span class="metadata">&mdash; <em>By</em> <?= anchor( 'mobile/author/' . $item['author']['id'], $item['author']['name'] ); ?> <em>from</em> <?= anchor( 'mobile/site/' . $item['site']['id'], $item['site']['name'] ); ?></span>.
			</p>
		</div>
		<div class="separator"></div>
	</div>
	
	<?php $alt++; endforeach; ?>
</div>
<div class="page"><?= $pagination; ?><div class="clear"></div></div>
