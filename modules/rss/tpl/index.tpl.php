<?php header('content-type: application/xml'); ?>
<?xml version="1.0" encoding="UTF-8"?>
	<rss version="2.0"
		xmlns:content="http://purl.org/rss/1.0/modules/content/"
		xmlns:wfw="http://wellformedweb.org/CommentAPI/"
		xmlns:dc="http://purl.org/dc/elements/1.1/"
		xmlns:atom="http://www.w3.org/2005/Atom"
		xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
		xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	>
		<channel>
				<title>Rss Feed <?php echo HOME ?> </title>
				<copyright><?php $page_des; ?></copyright>
				<generator><?php $page_des; ?></generator>
				<atom:link href="<?php HOME ?>/rss" rel="self" type="application/rss+xml" />
				<link><?php echo HOME ?></link>
				<description><?php $page_des; ?></description>
				<language>vn</language>


			<?php foreach ($links as $link) : ?>
					<item>
						<title><?php $link['name'] ?></title>
						<link><?php echo $link['full_url'] ?></link>
						<guild><?php echo $link['full_url'] ?></guild>
						<image><?php $link['full_icon'] ?></image>
						<description><?php echo $link['sub_content'] ?></description>
						<lastBuildDate><?php echo date("Y-m-d\TH:i:s+07:00", $link['time']) ?></lastBuildDate>
				    </item>
			<?php endforeach ?>
		</channel>
	</rss>