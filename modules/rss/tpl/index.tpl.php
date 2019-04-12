<?php $links = ZenView::$D['links'] ?>
<?php header('content-type: application/xml'); ?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss xmlns:slash="http://purl.org/rss/1.0/modules/slash/" version="2.0">
	<channel>
				<title>Rss Feed <?php ZenView::get_title() ?> </title>
				<copyright><?php  ZenView::get_title()  ?></copyright>
				<generator><?php echo HOME ?></generator>
				<link><?php echo HOME ?></link>
				<description<?php ZenView::get_desc() ?>></description>
				<language>vn</language>
                
  

			<?php foreach ($links as $link) : ?>
					<item>
						<title><?php echo $link['title'] ?></title>
						<link><?php echo $link['full_url'] ?></link>
						<guild><?php echo $link['full_url'] ?></guild>
						<image><?php echo $link['full_icon'] ?></image>
							<description><![CDATA[<a href="<?php echo $link['full_url'] ?>"><img width=130 height=100 src="<?php echo $link['full_icon'] ?>" ></a></br>.]]><?php echo $link['sub_content'] ?></description>
						<lastBuildDate><?php echo date("Y-m-d\TH:i:s+07:00", $link['time']) ?></lastBuildDate>
				    </item>
			<?php endforeach ?>
		</channel>
	</rss>