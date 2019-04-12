<?php load_header() ?>

<?php phook('blog_before_title', '') ?>

    <h1 class="title" style="margin-bottom: 5px;"><?php echo $page_title; ?></h1>

<?php phook('blog_after_title', '') ?>

<?php load_message() ?>

<?php if (model()->_get_link_list()): ?>

    <div class="detail_content">
        <h2 class="title">Thông báo</h2>

        <?php foreach (model()->_get_link_list() as $link): ?>

            <div class="item">

                <?php echo $link['tag_start'] ?>
                <a href="<?php echo $link['link'] ?>" rel="<?php echo $link['rel'] ?>" title="<?php echo $link['title'] ?>" style="<?php echo $link['style'] ?>" target="_blank"><?php echo $link['name'] ?></a>
                <?php echo $link['tag_end'] ?>

            </div>

        <?php endforeach ?>

    </div>

<?php endif ?>

<!-- start top new -->

<?php phook('blog_before_top_new', '') ?>

<?php if (count($list['new_posts'])): ?>

    <div class="detail_content">
        <h2 class="title border_red">Mới nhất</h2>

        <?php foreach ($list['new_posts'] as $new): ?>

            <div class="item">

                <?php echo icon('item'); ?>
                <a href="<?php echo $new['full_url']; ?>" title="<?php echo $new['title']; ?>"><?php echo $new['name']; ?></a>
                <span class="text_smaller gray"><i>(<?php echo get_time($new['time'], false) ?>)</i></span>

            </div>

        <?php endforeach; ?>

    </div>

<?php endif ?>

<?php phook('blog_after_top_new', '') ?>

<!-- end top new -->


<!-- start top hot -->
<?php phook('blog_before_top_hot', '') ?>

<?php if (count($list['hot_posts'])): ?>

    <div class="detail_content">
        <h2 class="title border_red">Xem nhiều nhất</h2>

        <?php foreach ($list['hot_posts'] as $hot): ?>
            <a href="<?php echo $hot['full_url']; ?>" title="<?php echo $hot['title']; ?>" class="link_items">
                <div class="item">
                    <span class="info">
                        <img src="<?php echo $hot['full_icon'] ?>" width="40px" height="40px"
                             class="item_thumb_icon float_left"/>
                        <b><?php echo $hot['name']; ?></b>
                        <span class="text_smaller gray">
                            <i>
                                <?php echo icon('view', 'vertical-align: text-bottom;') ?> <?php echo $hot['view'] ?>
                            </i>
                        </span>
                    </span>
                </div>
            </a>
            <div class="clean_both"></div>
        <?php endforeach; ?>

    </div>

<?php endif ?>

<?php phook('blog_after_top_hot', '') ?>

<!-- end top hot -->


<!-- start display cat -->

<?php phook('blog_before_display_cat', '') ?>

<?php if (empty($cats)): ?>

    <div class="notice">
        Hiện tại chưa có bài viết nào.

        <?php if (is(ROLE_MANAGER)): ?>
            <br/>Click vào <b><a href="<?php echo _HOME ?>/blog/manager/cpanel">đây</a></b> và bắt đầu thêm nội dung
        <?php endif ?>
    </div>

<?php else: ?>

    <?php foreach ($cats as $id => $cat): ?>

        <?php if (!empty($cat['sub_cat'])): ?>

            <?php phook('blog_before_cat', '') ?>

            <div class="detail_content">

                <h3 class="title border_blue">
                    <a href="<?php echo $cat['full_url']; ?>" title="<?php echo $cat['title']; ?>"><?php echo $cat['name']; ?></a>
                </h3>

                <?php phook('blog_before_list_sub_cat', '') ?>

                <?php foreach ($cat['sub_cat'] as $sub_cat): ?>

                    <div class="item">
                        <?php echo icon('item'); ?>
                        <a href="<?php echo $sub_cat['full_url']; ?>" title="<?php echo $sub_cat['title']; ?>"><?php echo $sub_cat['name']; ?></a>
                    </div>

                <?php endforeach; ?>

                <?php phook('blog_after_list_sub_cat', '') ?>
            </div>

            <?php phook('blog_after_cat', '') ?>

        <?php endif; ?>

    <?php endforeach; ?>

<?php endif ?>

<?php phook('blog_after_display_cat', '') ?>

<!-- end display cat -->

<?php load_footer() ?>