<?php header("content-type:text/xml;charset=utf-8"); ?>
<?php echo '<?xml-stylesheet type="text/xsl" href="'.$path_sitemap_xsl.'" ?>' ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc><?php echo HOME ?></loc>
        <lastmod><?php echo date("Y-m-d\TH:i:s+07:00", $last_update['time']) ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>1</priority>
    </url>
    <?php foreach ($folders as $folder) : ?>
        <url>
            <loc><?php echo $folder['full_url'] ?></loc>
            <lastmod><?php echo date("Y-m-d\TH:i:s+07:00", $folder['time']) ?></lastmod>
            <changefreq>daily</changefreq>
            <priority>0.7</priority>
        </url>
    <?php endforeach ?>
    <?php foreach ($posts as $post) : ?>
        <url>
            <loc><?php echo $post['full_url'] ?></loc>
            <lastmod><?php echo date("Y-m-d\TH:i:s+07:00", $post['time']) ?></lastmod>
            <changefreq>daily</changefreq>
            <priority>0.5</priority>
        </url>
    <?php endforeach ?>
</urlset>