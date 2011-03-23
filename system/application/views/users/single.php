<h2>User Profile</h2>
<div id="post">
	<?php if ( !( is_array( $users ) AND count( $users ) > 0 ) ) : ?>
		<div class="hentry">
			<div class="entry-content">
				<p><?= fm_show_404(); ?></p>
			</div>
		</div>
	<?php else : ?>
		<?php $alt = 1; foreach ( $users as $user ) : ?>
		
		<div class="hentry <?= ( ( $alt % 2) == 0 ? 'alt' : '' ); ?>" id="member-<?= $user['id']; ?>">
			<?= img( $user['image'] ); ?>
			<?= heading( anchor( 'users/profile/' . $user['id'], $user['name'] ), 3) ; ?>
			<p><?= anchor( $user['url'], "Facebook's Profile" ); ?> &#183; <?= $user['like']; ?> likes &#183; <?= $user['hide']; ?> hidden</p>
			<div class="clear"></div>
			<p><fb:user-status uid="<?= $user['id']; ?>" linked="true"></fb:user-status></p>
		</div>
		
		<?php $alt++; endforeach; ?>
		
		<div class="hentry <?= ( ( $alt % 2) == 0 ? 'alt' : '' ); ?>" id="member-like-<?= $user['id']; ?>">
			<?= heading( $user['name'] . ' like these article', 4) ; ?>
			<ul>
			<?php foreach ( $likes as $like ) : ?>
				<li><?= $like['anchor']; ?></li>
			<?php endforeach; ?>
			</ul>
			<div class="clear"></div>
		</div>
		
		<?php $alt++; ?>
		
		<div class="hentry <?= ( ( $alt % 2) == 0 ? 'alt' : '' ); ?>" id="member-hide-<?= $user['id']; ?>">
			<?= heading( $user['name'] . ' hide these site', 4) ; ?>
			<ul>
			<?php foreach ( $hides as $hide ) : ?>
				<li><?= $hide['anchor']; ?></li>
			<?php endforeach; ?>
			</ul>
			<div class="clear"></div>
		</div>
	<?php endif; ?>
</div>