<?php ZenView::display_breadcrumb(); ?>
<?php $hot_post = tplConfig('id_post_hot'); ?>
<?php if(!empty($hot_post)): ?>
	<div class="menu">Bạn nên xem</div>
		<?php foreach($hot_post as  $id): ?>
			<?php $data = model('blog')->get_blog_data($id) ?>
			<?php ZenView::load_layout('block/app-item',array('data' => $data)) ?>
		<?php endforeach; ?>
<?php endif; ?>
<div class="menu">Bài viết mới</div>
<?php foreach (ZenView::$D['list']['new'] as $item): ?>
    <?php ZenView::load_layout('block/app-item', array('data' => $item)) ?>
<?php endforeach ?>

<?php $list_id = tplConfig('list_blog_cat_display') ?>
<?php $num_post = tplConfig('num_post_per_box') ?>
<?php if ($list_id) foreach ($list_id as $catID): ?>
    <?php $list = model('blog')->get_list_blog($catID, array('get' => 'uid, parent, url, name, title, des, time, view, icon', 'both_child' => false, 'limit' => $num_post)) ?>
    <?php if (!empty($list)): ?>
        <?php $catData = model('blog')->get_blog_data($catID) ?>
		<div class="menu"><h2><a href="<?php echo $catData['full_url'] ?>" title="<?php echo $catData['title'] ?>"><?php echo $catData['name'] ?></a></h2></div>
                <?php foreach ($list as $item): ?>
                    <?php ZenView::load_layout('block/app-item', array('data' => $item)) ?>
                <?php endforeach ?>
    <?php endif ?>
<?php endforeach ?>
<div class="menu">Chuyên mục</div>
<?php $list = model('blog')->get_list_blog(0, array('get' => 'url, name, title, time, view, icon', 'both_child' => false)) ?>
<?php foreach($list as $cat): ?>
	<?php ZenView::load_layout('block/app-item-mini',array('data' => $cat)) ?>
<?php endforeach; ?>
