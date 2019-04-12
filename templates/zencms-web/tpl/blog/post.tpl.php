<div class="panel panel-default">
    <div class="panel-heading breadcrumb-heading"><?php ZenView::display_breadcrumb() ?></div>
    <div class="panel-body">

        <div class="padded">
            <div class="app_info">
                <ul class="zen-table">
                    <li>
                        <img itemprop="image" src="<?php echo ZenView::$D['blog']['full_icon'] ?>" class="icon" alt="<?php echo ZenView::$D['blog']['title'] ?>"/>
                    </li>
                    <li style="width:100%; padding-left:10px; vertical-align: top;">
                        <div style="position: relative;">
                            <h1 class="title"><?php echo ZenView::$D['blog']['name'] ?></h1>
                        </div>
                    </li>
                    <li>
                        <div>
                            <a href="<?php echo ZenView::$D['blog']['full_url'] ?>#download" class="downloadfree">Download</a>
                        </div>
                    </li>
                </ul>
                <ul class="zen-table info_items">
                    <li>
                        <span class="info_items_icon"><i class="glyphicon glyphicon-user"></i></span>
                        <span class="info_items_title">Người viết</span>
                        <span class="info_items_val"><b><a href="<?php echo ZenView::$D['blog']['user']['full_url'] ?>"><?php echo display_nick(ZenView::$D['blog']['user']['nickname'], ZenView::$D['blog']['user']['perm']) ?></a></b></span>
                    </li>
                    <li>
                        <span class="info_items_icon"><i class="glyphicon glyphicon-time"></i></span>
                        <span class="info_items_title">Ngày viết</span>
                        <span class="info_items_val"><?php echo ZenView::$D['blog']['display_time'] ?></span>
                    </li>
                    <li>
                        <span class="info_items_icon"><i class="glyphicon glyphicon-eye-open"></i></span>
                        <span class="info_items_title">Lượt xem</span>
                        <span class="info_items_val"><?php echo ZenView::$D['blog']['view'] ?></span>
                    </li>
                    <li class="act_link">
                        <div><a href="<?php echo ZenView::$D['blog']['full_url'] ?>#download"><i class="glyphicon glyphicon-download-alt"></i> Tải về</a></div>
                    </li>

                </ul>
            </div>
            <div class="app_desc">
                <div class="exp_content">
                    <?php echo ZenView::$D['blog']['content'] ?>
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

<?php if (ZenView::$D['list']['same_posts']): ?>
    <!-- List same post -->
    <div class="panel panel-default">
        <div class="panel-heading block-heading">
            <div class="box-tow">
                <h3 class="panel-title block-title">Bài viết tương tự</h3>
            </div>
            <div class="box"></div>
        </div>
        <div class="panel-body">
            <ul class="list-grid">
                <?php foreach (ZenView::$D['list']['same_posts'] as $item): ?>
                    <li class="col-xs-6 col-sm-3 col-md-2">
                    <span class="grid-item">
                      <a href="<?php echo $item['full_url'] ?>">
                        <span class="icon">
                          <img class="img-responsive" src="<?php echo $item['full_icon'] ?>" alt="<?php echo $item['title'] ?>">
                        </span>
                      </a>
                      <span class="info">
                        <span class="title">
                          <a href="<?php echo $item['full_url'] ?>" title="<?php echo $item['title'] ?>"><?php echo $item['name'] ?></a>
                        </span>
                      </span>
                      <span class="bottom">
                        <i class="glyphicon glyphicon-eye-open"></i>&nbsp;<span title="Lượt tải"><?php echo $item['view'] ?></span>
                      </span>
                    </span>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>
    <!-- End List same post -->
<?php endif ?>

<?php if (ZenView::$D['list']['rand_posts']): ?>
    <!-- List rand post -->
    <div class="panel panel-default">
        <div class="panel-heading block-heading">
            <div class="box-tow">
                <h3 class="panel-title block-title">Bài viết ngẫu nhiên</h3>
            </div>
            <div class="box"></div>
        </div>
        <div class="panel-body">
            <ul class="list-grid">
                <?php foreach (ZenView::$D['list']['rand_posts'] as $item): ?>
                    <li class="col-xs-6 col-sm-3 col-md-2">
                    <span class="grid-item">
                      <a href="<?php echo $item['full_url'] ?>">
                        <span class="icon">
                          <img class="img-responsive" src="<?php echo $item['full_icon'] ?>" alt="<?php echo $item['title'] ?>">
                        </span>
                      </a>
                      <span class="info">
                        <span class="title">
                          <a href="<?php echo $item['full_url'] ?>" title="<?php echo $item['title'] ?>"><?php echo $item['name'] ?></a>
                        </span>
                      </span>
                      <span class="bottom">
                        <i class="glyphicon glyphicon-eye-open"></i>&nbsp;<span title="Lượt tải"><?php echo $item['view'] ?></span>
                      </span>
                    </span>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>
    <!-- End List rand post -->
<?php endif ?>