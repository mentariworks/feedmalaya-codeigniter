<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	>
<channel>
	<title><?= $site_name; ?></title>
	<atom:link href="<?= $current_link; ?>" rel="self" type="application/rss+xml" />
	<link><?= $link; ?></link>
	<description><?= $description; ?></description>

	<pubDate><?= $pubDate; ?></pubDate>
	<generator><?= $generator; ?></generator>
	<language><?= $language; ?></language>
	<sy:updatePeriod>hourly</sy:updatePeriod>
	<sy:updateFrequency>1</sy:updateFrequency>
	
	<?php foreach ( $items as $item ) : ?>
	
	<item>
		<title><?= $item['title']; ?></title>
		<link><?= site_url( 'item/visit/' . $item['id'] . '/' . $item['slug'] ); ?></link>
		<pubDate><?= date( 'r', $item['datetime'] ); ?></pubDate>
		<dc:creator><?= $item['author']['name']; ?></dc:creator>
		<description><![CDATA[<?= $item['excerpt']; ?>]]></description>
		<content:encoded><![CDATA[<?= $item['excerpt']; ?>]]></content:encoded>
	</item>
	
	<?php endforeach; ?>
</channel>
