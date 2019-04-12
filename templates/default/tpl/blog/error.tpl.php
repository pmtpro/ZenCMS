<?php load_header() ?>

    <?php phook('blog_error_before_title', '') ?>

    <h1 class="title border_blue"><?php echo $page_title ?></h1>

    <?php phook('blog_error_after_title', '') ?>

<?php load_message() ?>

<?php phook('blog_error_before_rand_post', '') ?>

<?php if (isset ($rand_posts) && count($rand_posts)): ?>
    <div class="detail_content">
        <div class="title"><?php echo icon('title'); ?> Xem thêm</div>

        <?php foreach ($rand_posts as $rand_post): ?>
            <div class="item">
                <?php echo icon('item'); ?>
                <a href="<?php echo $rand_post['full_url'] ?>" title="<?php echo $rand_post['title'] ?>">
                    <?php echo $rand_post['name'] ?>
                </a>
            </div>
        <?php endforeach ?>
    </div>
<?php endif ?>

<?php phook('blog_error_after_rand_post', '') ?>

<?php load_footer() ?>