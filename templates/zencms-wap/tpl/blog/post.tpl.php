<?php ZenView::display_breadcrumb() ?>
<div class="menu"><h1><?php echo ZenView::$D['blog']['name'] ?></h1></div>
<div class="row2">
	<div class="left">
		<img width="60" height="60" src="<?php echo ZenView::$D['blog']['full_icon'] ?>" alt="<?php echo ZenView::$D['blog']['title'] ?>" />
	</div>
	<div class="info">
		<h2><a href="<?php echo ZenView::$D['blog']['full_url'] ?>" title="<?php echo ZenView::$D['blog']['name'] ?>"><?php echo ZenView::$D['blog']['name'] ?></a></h2>
		<?php $user = model('account')->get_user_data(ZenView::$D['blog']['uid'], 'username, nickname, avatar') ?>
		<p>Đăng ngày <?php echo ZenView::$D['blog']['display_time'] ?></p>
		<p><i class="glyphicon glyphicon-eye-open"></i> <?php echo ZenView::$D['blog']['view'] ?>, Bởi <a href="<?php echo HOME ?>/account/wall/<?php echo $user['username'] ?>" class="guru_name"><b><?php echo $user['nickname'] ?></b></a></li></p>
		
	</div>
</div>
<div class="post-content">
    <!-- post content -->
	<?php echo ZenView::$D['blog']['content'] ?>
    <!-- end post content -->

    <!-- tags -->
	<?php if (!empty(ZenView::$D['blog']['tags'])): ?>
        <div class="blog_tags">
            <?php foreach (ZenView::$D['blog']['tags'] as $tag): ?>
                <a href="<?php echo $tag['full_url'] ?>" class="label label-default" title="<?php echo $tag['tag'] ?>"><?php echo $tag['tag'] ?></a>&nbsp;
            <?php endforeach ?>
        </div>
    <?php endif ?>
    <!-- end tags -->
</div>

<?php if (!empty(ZenView::$D['blog']['attachments'])): ?>
    <!-- download -->
    <div class="menu">Tải về</div>
	<?php if (!empty(ZenView::$D['blog']['attachments']['link'])) foreach(ZenView::$D['blog']['attachments']['link'] as $link): ?>
        <div class="row1"><i class="glyphicon glyphicon-download-alt"></i> <a href="<?php echo $link['link'] ?>" title="<?php echo $link['name'] ?>" rel="nofollow"><?php echo $link['name'] ?></a> <span class="smaller">(<?php echo $link['click'] ?> click)</span></div>
    <?php endforeach ?>
    <?php if (!empty(ZenView::$D['blog']['attachments']['file'])) foreach(ZenView::$D['blog']['attachments']['file'] as $file): ?>
        <div class="row1"><i class="glyphicon glyphicon-download-alt"></i> <a href="<?php echo $file['link'] ?>" title="<?php echo $file['name'] ?>" rel="nofollow"><?php echo $file['name'] ?></a> <span class="smaller">(<?php echo $file['down'] ?> lượt tải)</span></div>
    <?php endforeach ?>
    <!-- end download -->
<?php endif ?>


<!-- after_post_content widget -->
<?php widget_group('after_post_content') ?>
<!-- end after_post_content widget -->

<?php if (modConfig('allow_post_comment', 'blog')): ?>
    <!-- Blog comment -->
    <div class="menu">Thảo luận</div>
    <div class="row2">
                <?php ZenView::display_message('comment') ?>
                <form method="POST">
                    <?php if (!IS_MEMBER): ?>
                        <div class="form-group">
                            <label for="comment-name">Tên của bạn</label>
                            <input type="text" name="name" class="form-control" placeholder="Tên của bạn"/>
                        </div>
                    <?php endif ?>
                    <div class="form-group">
                        <label>Nội dung</label>
                        <textarea name="msg" class="form-control"></textarea>
                    </div>
                    <?php if (!IS_MEMBER): ?>
                        <div class="form-group">
                            <img src="<?php echo ZenView::$D['captcha_src'] ?>"/>
                            <input type="text" name="captcha_code" class="form-control" placeholder="Mã xác nhận"/>
                        </div>
                    <?php endif ?>
                    <div class="form-group">
                        <input type="hidden" name="token_comment" value="<?php echo ZenView::$D['token_comment'] ?>"/>
                        <input type="submit" name="submit-comment" class="btn btn-primary" value="Bình luận"/>
                    </div>
                </form>
        </div>
    <div class="row2">
        <?php ZenView::display_message('comments-list') ?>
        <?php if (ZenView::$D['blog']['comments']) foreach (ZenView::$D['blog']['comments'] as $cmt): ?>
            <div class="row1">
                <?php echo (empty($cmt['uid']) ? $cmt['name'] : '<a href="' . HOME . '/account/wall/' .$cmt['user']['username']. '">' . display_nick($cmt['user']['nickname'], $cmt['user']['perm']) . '</a>') ?>
                <i>( <?php echo hook('blog', 'post_comment_public_control', '<span>' . $cmt['display_time'] . '</span>', array('var' => array('cmt'=>$cmt))) ?> ) </i>
                :
                <?php echo $cmt['msg'] ?>
                <?php echo hook('blog', 'post_comment_private_control', '', array('var' => array('cmt'=>$cmt))) ?>
            </div>
        <?php endforeach; ?>
        <?php ZenView::display_paging('comment') ?>
    </div>
    <!-- End Blog comment -->
<?php endif ?>

<!-- Same post -->
    <?php if(ZenView::$D['list']['same_posts']): ?>
        <div class="menu">Bạn nên xem</div>
        <?php foreach (ZenView::$D['list']['same_posts'] as $item): ?>
            <?php ZenView::load_layout('block/app-item-mini', array('data' => $item)) ?>
        <?php endforeach ?>
    <?php endif ?>
<!-- End Same post -->

<!-- Rand post -->
    <?php if(ZenView::$D['list']['rand_posts']): ?>
        <div class="menu">Ngẫu nhiên</div>
        <?php foreach (ZenView::$D['list']['rand_posts'] as $item): ?>
            <?php ZenView::load_layout('block/app-item-mini', array('data' => $item)) ?>
        <?php endforeach ?>
    <?php endif ?>
<!-- End Rand post -->