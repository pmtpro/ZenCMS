<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title title-info"><?php echo ZenView::$D['blog']['name'] ?></h1>
        <!--after-->
    </div>
    <div class="panel-body">
        <?php ZenView::display_breadcrumb(); ?>
        <?php if (!empty(ZenView::$D['blog']['content'])): ?>
            <div class="app_desc">
                <?php echo ZenView::$D['blog']['content'] ?>
            </div>
        <?php endif ?>
        <?php foreach (ZenView::$D['list']['posts'] as $item): ?>
            <?php ZenView::load_layout('block/app-item', array('data' => $item))?>
        <?php endforeach ?>
        <div class="padded"><?php ZenView::display_paging('post') ?></div>
    </div>
</div>

<!-- blog_after_list_post_in_folder widget -->
<?php widget_group('blog_after_list_post_in_folder') ?>
<!-- end blog_after_list_post_in_folder widget -->

<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title title-info">Ngẫu nhiên</h1>
    </div>
    <div class="panel-body">
        <?php foreach (ZenView::$D['list']['rand_posts'] as $item): ?>
            <?php ZenView::load_layout('block/app-item-mini', array('data' => $item))?>
        <?php endforeach ?>
    </div>
</div>