<?php widget_group('footer') ?>

<?php if (model()->_get_link_list('friend_link')): ?>
    <div class="detail_content">
        <div class="title border_orange">Link liên kết</div>

        <div class="item">

            <?php foreach (model()->_get_link_list('friend_link') as $link): ?>

                <?php echo $link['tag_start'] ?>
                    <a href="<?php echo $link['link'] ?>" rel="<?php echo $link['rel'] ?>" title="<?php echo $link['title'] ?>" style="<?php echo $link['style'] ?>" target="_blank">
                        <?php echo $link['name'] ?>
                    </a>
                <?php echo $link['tag_end'] ?>,

            <?php endforeach ?>

        </div>
    </div>
<?php endif ?>

    <div class="footer">
        <?php if (is(ROLE_MANAGER)): ?>
            <span><a href="<?php echo _HOME ?>/admin">Admin CP</a></span><br/>
        <?php endif ?>
        <a href="<?php echo _HOME ?>/sitemap.xml" title="sitemap">Sitemap</a><br/>

        Power by <a href="http://zencms.vn" target="_blank" title="ZenCMS - Web developers - code web - code wap">ZenCMS</a><br/>
        <?php phook('public_copyright', '&copy; 2013 <a href="http://zenthang.com" target="_blank" title="ZenThang">ZenThang</a>') ?>
    </div>

    </div>
    </body>
    </html>
<?php phook('public_count_cache', "<!-- Load Cache: " . $GLOBALS['count']['cache'] . " -->") ?>