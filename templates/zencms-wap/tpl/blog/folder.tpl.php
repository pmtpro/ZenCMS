<?php ZenView::display_breadcrumb(); ?>
<div class="menu"><h1><?php echo ZenView::$D['blog']['name'] ?></h1></div>

<?php if (!empty(ZenView::$D['blog']['content'])): ?>
    <div class="post-content">
        <?php echo ZenView::$D['blog']['content'] ?>
    </div>
    <div class="menu">Bài viết</div>
<?php endif ?>

    <!-- after_post_folder_content widget -->
<?php widget_group('after_post_folder_content') ?>
    <!-- end after_post_folder_content widget -->

<?php foreach (ZenView::$D['list']['posts'] as $item): ?>
    <?php ZenView::load_layout('block/app-item', array('data' => $item))?>
<?php endforeach ?>
<?php ZenView::display_paging('post') ?>

    <!-- after_list_post_in_folder widget -->
<?php widget_group('after_list_post_in_folder') ?>
    <!-- end after_list_post_in_folder widget -->

<?php if(ZenView::$D['list']['folders']): ?>
    <div class="menu">Chuyên mục</div>
    <?php foreach(ZenView::$D['list']['folders'] as $cat): ?>
        <?php ZenView::load_layout('block/app-item-mini',array('data' => $cat)) ?>
    <?php endforeach; ?>
    <?php ZenView::display_paging('folder') ?>
<?php endif ?>

    <!-- after_list_cat_in_folder widget -->
<?php widget_group('after_list_cat_in_folder') ?>
    <!-- end after_list_cat_in_folder widget -->

<?php if (ZenView::$D['list']['rand_posts']): ?>
    <div class="menu">Ngẫu nhiên</div>
    <?php foreach (ZenView::$D['list']['rand_posts'] as $item): ?>
        <?php ZenView::load_layout('block/app-item-mini', array('data' => $item))?>
    <?php endforeach ?>
<?php endif ?>