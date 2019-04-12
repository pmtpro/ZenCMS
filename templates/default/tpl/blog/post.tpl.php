<?php load_header() ?>

<?php phook('blog_post_before_title', '') ?>

<h1 class="title"><?php echo $blog['name'] ?></h1>

<?php phook('blog_post_after_title', '') ?>

<div class="breadcrumb"><?php echo $display_tree; ?></div>

<?php load_message() ?>

<div class="detail_content padding6">

    <!-- START display post info -->

    <?php phook('blog_post_before_post_info', '') ?>

    <div class="post_info" itemtype="http://data-vocabulary.org/Recipe" itemscope="">

        <img itemprop="photo" src="<?php echo $blog['full_icon'] ?>" class="post_icon" style="float: left;" alt="<?php echo $blog['title'] ?>"/>

        <div class="post_name" itemprop="name">
            <a href="<?php echo $blog['full_url'] ?>" title="<?php echo $blog['title'] ?>">
                <?php echo $blog['name'] ?>
            </a>
        </div>

        <?php load_layout('blog_info') ?>

        <span style="display: none" itemprop="review" itemscope="" itemtype="http://data-vocabulary.org/Review-aggregate">
            <span itemprop="rating">4.5</span> sao trên <span itemprop="count">1024</span>người dùng
		</span>

        <?php load_layout('blog_share') ?>

        <?php load_layout('blog_manager_bar', true) ?>

    </div>

    <div class="clean_both"></div>

    <?php phook('blog_post_after_post_info', '') ?>

    <!-- END display post info -->



    <!-- START download button -->

    <?php phook('blog_post_before_download_button', '') ?>

    <?php if (!empty($blog['downloads'])): ?>
        <div class="download">
            <div class="btn_download"><a href="<?php echo $blog['full_url'] ?>#download">Tải về</a></div>
        </div>
    <?php endif ?>

    <?php phook('blog_post_after_download_button', '') ?>

    <!-- END download button -->



    <!-- START display content -->

    <?php phook('blog_post_before_content', '') ?>

    <div class="content">
        <?php echo $blog['content']; ?>
    </div>

    <?php phook('blog_post_after_content', '') ?>

    <!-- END display content -->



    <!-- START display download -->

    <?php phook('blog_post_before_download_list', '') ?>

    <?php if (!empty($blog['downloads'])): ?>
        <?php load_layout('blog_download') ?>
    <?php endif ?>

    <?php phook('blog_post_after_download_list', '') ?>

    <!-- END display download -->



    <!-- START display tags -->

    <?php phook('blog_post_before_tags', '') ?>

    <?php if (!empty($blog['tags'])): ?>
        <?php load_layout('blog_tag') ?>
    <?php endif ?>

    <?php phook('blog_post_after_tags', '') ?>

    <!-- END display tags -->

</div>




<!-- START display comments -->

<?php phook('blog_post_before_comments', '') ?>

<?php load_layout('blog_comments') ?>

<?php phook('blog_post_after_comments', '') ?>

<!-- END display comments -->




<!-- START display same post -->

<?php phook('blog_post_before_same_post', '') ?>

<?php if (isset($list['same_posts'])): ?>

    <div class="detail_content">

        <div class="title border_blue">Cùng chuyên mục</div>

        <?php foreach ($list['same_posts'] as $same_post): ?>

            <div class="item">
                <?php echo icon('item') ?>
                <a href="<?php echo $same_post['full_url'] ?>" title="<?php echo $same_post['title'] ?>"><?php echo $same_post['name'] ?></a>
            </div>

        <?php endforeach; ?>

    </div>

<?php endif ?>

<?php phook('blog_post_after_same_post', '') ?>

<!-- END display same post -->



<!-- START display other cat -->

<?php phook('blog_post_before_other_cat', '') ?>

<?php if (isset($list['other_cats'])): ?>

    <div class="detail_content">

        <div class="title"><?php echo icon('cat') ?> Chuyên mục khác</div>

        <?php foreach ($list['other_cats'] as $other_cat): ?>

            <div class="item">
                <?php echo icon('item') ?>
                <a href="<?php echo $other_cat['full_url'] ?>" title="<?php echo $other_cat['title'] ?>"><?php echo $other_cat['name'] ?></a>
            </div>

        <?php endforeach; ?>

    </div>

<?php endif ?>

<?php phook('blog_post_after_other_cat', '') ?>

<!-- END display other cat -->

<?php load_footer() ?>