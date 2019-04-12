<div class="panel panel-default">
    <div class="panel-heading breadcrumb-heading"><?php ZenView::display_breadcrumb() ?></div>
    <div class="panel-body">
        <div class="row channel_item">
            <div class="col-xs-12">
                <ul class="u_table u_table_top">
                    <li style="padding:10px;">
                        <a href="<?php echo ZenView::$D['blog']['full_url'] ?>">
                            <div class="thumb">
                                <div style="display:block; position:relative;">
                                    <div class="overlay"></div>
                                    <img src="<?php echo ZenView::$D['blog']['full_icon'] ?>" alt="thumb" class="thumb_img">
                                </div>
                            </div>
                            <!-- /.thumb -->
                        </a>
                    </li>
                    <li style="position:relative; padding:10px 10px 10px 0px; width:100%;vertical-align: top;">
                        <div class="title">
                            <a href="<?php echo ZenView::$D['blog']['full_url'] ?>"><?php echo ZenView::$D['blog']['name'] ?></a>
                            <span class="paragraph-end" style="height:20px; top:20px;"></span>
                        </div>
                        <div class="info14">
                            <a href="<?php echo ZenView::$D['blog']['full_url'] ?>#download" class="download_btn" id="dlapp_btn">Tải về</a>
                        </div>
                    </li>
                    <li style="padding-top:10px;">
                    </li>
                </ul>
                <div class="info14" style="padding:0px 10px 6px 10px">
                    <?php $user = model('account')->get_user_data(ZenView::$D['blog']['uid'], 'username, nickname, avatar') ?>
                    <i class="glyphicon glyphicon-eye-open"></i> <?php echo ZenView::$D['blog']['view'] ?>, <?php echo ZenView::$D['blog']['display_time'] ?>, Bởi <a href="<?php echo HOME ?>/account/wall/<?php echo $user['username'] ?>" class="guru_name"><b><?php echo $user['nickname'] ?></b></a></li>
                </div>
            </div>
            <!-- /.col-xs-12 -->
        </div>
        <div class="padded">
            <div class="content_block_2">
                <div class="app_desc">
                    <div class="exp_content">
                        <?php echo ZenView::$D['blog']['content'] ?>
                    </div>
                </div>
            </div>
            <!-- Blog tags -->
            <?php if (!empty(ZenView::$D['blog']['tags'])): ?>
                <div class="blog_tags">
                    <?php foreach (ZenView::$D['blog']['tags'] as $tag): ?>
                        <a href="<?php echo $tag['full_url'] ?>" class="label label-default" title="<?php echo $tag['tag'] ?>"><?php echo $tag['tag'] ?></a>&nbsp;
                    <?php endforeach ?>
                </div>
            <?php endif ?>
            <!-- end blog tags -->
        </div>
    </div>
</div>

<?php if (!empty(ZenView::$D['blog']['attachments'])): ?>
    <!-- Blog attachments -->
    <div class="panel panel-default"><div class="panel-heading block-heading">
            <div class="box-tow">
                <h3 class="panel-title block-title"><a name="download">Tải về</a></h3>
            </div>
            <div class="box"></div>
        </div>
        <div class="panel-body">
            <?php if (!empty(ZenView::$D['blog']['attachments']['link'])) foreach(ZenView::$D['blog']['attachments']['link'] as $link): ?>
                <div class="download-item"><i class="glyphicon glyphicon-download-alt"></i> <a href="<?php echo $link['link'] ?>" title="<?php echo $link['name'] ?>" rel="nofollow"><?php echo $link['name'] ?></a> <span class="smaller">(<?php echo $link['click'] ?> click)</span></div>
            <?php endforeach ?>
            <?php if (!empty(ZenView::$D['blog']['attachments']['file'])) foreach(ZenView::$D['blog']['attachments']['file'] as $file): ?>
                <div class="download-item"><i class="glyphicon glyphicon-download-alt"></i> <a href="<?php echo $file['link'] ?>" title="<?php echo $file['name'] ?>" rel="nofollow"><?php echo $file['name'] ?></a> <span class="smaller">(<?php echo $file['down'] ?> lượt tải)</span></div>
            <?php endforeach ?>
        </div>
    </div>
    <!-- end blog attachments -->
<?php endif ?>

<!-- blog_after_post_content widget -->
<?php widget_group('blog_after_post_content') ?>
<!-- end blog_after_post_content widget -->

<?php if (modConfig('allow_post_comment', 'blog')): ?>
    <!-- Blog comments -->
    <div class="panel panel-default"><div class="panel-heading block-heading">
            <div class="box-tow">
                <h3 class="panel-title block-title">Thảo luận</h3>
            </div>
            <div class="box"></div>
        </div>
        <div class="panel-body">
            <div class="padded">
                <?php ZenView::display_message('comment') ?>
                <form method="POST">
                    <?php if (!IS_MEMBER): ?>
                        <div class="form-group">
                            <label for="comment-name">Tên của bạn</label>
                            <input type="text" name="name" class="form-control" placeholder="Tên của bạn"/>
                        </div>
                    <?php endif ?>
                    <div class="form-group">
                        <label for="comment-msg">Nội dung</label>
                        <textarea name="msg" id="comment-msg" class="form-control"></textarea>
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
                <?php ZenView::display_message('comments-list') ?>
                <?php if (ZenView::$D['blog']['comments']) foreach (ZenView::$D['blog']['comments'] as $cmt): ?>
                    <div class="media post-comment">
                        <?php echo ($cmt['uid'] ? '<a class="pull-left" href="' . HOME . '/account/wall/' .$cmt['user']['username']. '">
                            <img class="media-object img-responsive post-comment-avatar" alt="64x64" src="' .$cmt['user']['full_avatar']. '" style="width: 48px; height: 48px;">
                        </a>': '') ?>
                        <div class="media-body">
                            <div class="post-comment-msg">
                                <b class="media-heading">
                                    <?php echo (empty($cmt['uid']) ? $cmt['name'] : '<a href="' . HOME . '/account/wall/' .$cmt['user']['username']. '">' . display_nick($cmt['user']['nickname'], $cmt['user']['perm']) . '</a>') ?>
                                </b>
                                <article><?php echo $cmt['msg'] ?></article>
                            </div>
                            <div class="post-comment-meta">
                                <div class="public-controls">
                                    <?php echo hook('blog', 'post_comment_public_control', '<span>' . $cmt['display_time'] . '</span>', array('var' => array('cmt'=>$cmt))) ?>
                                </div>
                                <div class="private-controls">
                                    <?php echo hook('blog', 'post_comment_private_control', '', array('var' => array('cmt'=>$cmt))) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
                <?php ZenView::display_paging('comment') ?>
            </div>
        </div>
    </div>
    <!-- end blog comments -->
<?php endif ?>

<?php if(ZenView::$D['list']['same_posts']): ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-heading">
                <h1 class="panel-title title-info">Bài viết tương tự</h1>
                <!--after-->
            </div>
        </div>
        <div class="panel-body">
            <?php foreach (ZenView::$D['list']['same_posts'] as $item): ?>
                <?php ZenView::load_layout('block/app-item-mini', array('data' => $item)) ?>
            <?php endforeach ?>
        </div>
    </div>
<?php endif ?>

<?php if (ZenView::$D['list']['rand_posts']): ?>
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
<?php endif ?>