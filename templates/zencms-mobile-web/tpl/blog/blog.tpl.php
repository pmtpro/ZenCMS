<?php ZenView::load_layout('center/zen-slider') ?>
<?php foreach (ZenView::$D['list']['new'] as $item): ?>
    <?php ZenView::load_layout('block/app-item', array('data' => $item)) ?>
<?php endforeach ?>

<?php $list_id = tplConfig('list_blog_cat_display') ?>
<?php if ($list_id) foreach ($list_id as $catID): ?>
    <?php $list = model('blog')->get_list_blog($catID, array('get' => 'url, name, title, time, view, icon', 'type' => 'post', 'limit' => tplConfig('num_post_per_box'), 'both_child' => true)) ?>
    <?php if (!empty($list)): ?>
        <?php $catData = model('blog')->get_blog_data($catID) ?>
        <div class="panel panel-default">
            <div class="panel-heading block-heading">
                <div class="box-tow">
                    <h3 class="panel-title block-title">
                        <a href="<?php echo $catData['full_url'] ?>" title="<?php echo $catData['title'] ?>"><?php echo $catData['name'] ?></a>
                    </h3>
                </div>
                <div class="box"></div>
                <!--after-->
            </div>
            <div class="panel-body">
                <?php foreach ($list as $item): ?>
                    <?php ZenView::load_layout('block/app-item-mini', array('data' => $item)) ?>
                <?php endforeach ?>
            </div>
        </div>
    <?php endif ?>
<?php endforeach ?>