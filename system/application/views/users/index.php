<h2>List <em>of</em> Users</h2>
<div id="post" class="hentry">
	<ul>
	<?php if ( !( is_array( $users ) AND count( $users ) > 0 ) ) : ?>
		<li>
			<p><?php fm_show_404(TRUE); ?></p>
		</li>
	<?php else : ?>
		<?php foreach ( $users as $user ) : ?>
		
		<li id="member-<?= $user['id']; ?>">
			<?= img( $user['image'] ); ?>
			<?= heading( anchor( 'users/profile/' . $user['id'], $user['name'] ), 3) ; ?>
			<p><?= anchor( $user['url'], "Facebook's Profile" ); ?> &#183; <?= $user['like']; ?> likes &#183; <?= $user['hide']; ?> hidden</p>
			<div class="clear"></div>
		</li>
		
		<?php endforeach; ?>
	<?php endif; ?>
</div>
<div class="page"><?= $pagination; ?></div>