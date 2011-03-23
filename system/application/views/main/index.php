<h2>Latest Items <em>from</em> the blogosphere</h2>
<div id="post" class="hentry">
	<ul>
		<?php if ( !( is_array( $items ) AND count( $items ) > 0 ) ) : ?>
			<li>
				<p><?= fm_show_404(); ?></p>
			</li>
		<?php else : ?>
			<?php $alt = 1; foreach ( $items as $item ) : ?>
			
			<li id="item-<?= $item['id']; ?>">
				<?= heading( fm_post_permalink($item), 3) ; ?>
				<p class="metadata small">
					Retrieved at <abbr><?= fm_post_datetime($item); ?></abbr> / 
					By <abbr><?= fm_post_author($item); ?></abbr> 
					from <?= fm_post_site($item); ?>
				</p>
				<p class="excerpt"><?= $item['excerpt']; ?></p>
				<?= fm_post_tags($item , '<p class="small tags">Tags: ', '</p>'); ?>
				<?= fm_post_meta($item); ?>
				<?= fm_post_fav($item); ?>
			</li>
			
			<?php $alt++; endforeach; ?>
		<?php endif; ?>
	</ul>
</div>
<div class="page"><?= $pagination; ?></div>
