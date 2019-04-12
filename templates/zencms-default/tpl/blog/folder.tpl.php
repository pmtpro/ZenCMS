<div class="row margin-bottom-40">
    <h1 class="blog-title"><?php echo ZenView::$D['blog']['name'] ?></h1>
    <div class="col-md-12 col-sm-12">
        <div class="content-page">
            <?php if (ZenView::$D['blog']['content']): ?>
                <div class="blog-content">
                    <?php echo ZenView::$D['blog']['content'] ?>
                </div><!--/ .blog-content -->
            <?php endif ?>
            <div class="row">
                <div class="col-md-9 col-sm-9 blog-posts">
                    <?php $list = model()->list_custom_post(ZenView::$D['blog']['id'], 'posts_in_folder', 10, 'page') ?>
                    <?php foreach ($list as $item): ?>
                        <?php $cat = model()->get_blog_data($item['parent'], 'name, url') ?>
                        <div class="row">
                            <div class="col-md-3 col-sm-3">
                                <img src="<?php echo $item['full_icon'] ?>" alt="<?php echo $item['title'] ?>" class="img-responsive"/>
                            </div>
                            <div class="col-md-9 col-sm-9">
                                <h2><a href="<?php echo $item['full_url'] ?>" title="<?php echo $item['title'] ?>"><?php echo $item['name'] ?></a></h2>
                                <ul class="blog-info">
                                    <li><i class="fa fa-calendar"></i> <?php echo m_timetostr($item['time']) ?></li>
                                    <li><i class="fa fa-tags"></i> <a href="<?php echo $cat['full_url'] ?>" title="<?php echo $cat['title'] ?>"><?php echo $cat['name'] ?></a></li>
                                </ul>
                                <p><?php echo $item['des'] ?></p>
                                <a href="<?php echo $item['full_url']?>" class="more" title="Xem thêm">Xem thêm</a>
                            </div>
                        </div>
                        <hr class="blog-post-sep"/>
                    <?php endforeach ?>

                    <div class="pull-right"><?php ZenView::display_paging('posts_in_folder') ?></div>

                </div><!--/ .blog-posts -->

                <div class="col-md-3 col-sm-3 blog-sidebar">

                    <?php ZenView::load_layout('list-cat') ?>

                    <?php $cats = model()->list_custom_cat(ZenView::$D['blog']['id']) ?>
                    <?php if ($cats): ?>
                        <h2 class="no-top-space">Chuyên mục con</h2>
                        <ul class="nav sidebar-categories margin-bottom-40">
                            <?php foreach ($cats as $item): ?>
                                <li><a href="<?php echo $item['full_url'] ?>" title="<?php echo $item['title'] ?>"><?php echo $item['name'] ?></a></li>
                            <?php endforeach ?>
                        </ul>
                    <?php endif ?>

                    <h2 class="no-top-space">Bài viết tương tự</h2>
                    <div class="recent-news margin-bottom-10">
                        <?php $list_same = model()->list_same_post(ZenView::$D['blog']['id'], 'same_post_in_folder') ?>
                        <?php foreach ($list_same as $item): ?>
                            <div class="row margin-bottom-10">
                                <div class="col-md-3"><img src="<?php echo $item['full_icon'] ?>" class="img-responsive" alt="<?php echo $item['title'] ?>" /></div>
                                <div class="col-md-9 recent-news-inner">
                                    <h3><a href="<?php echo $item['full_url'] ?>" title="<?php echo $item['title'] ?>"><?php echo $item['name'] ?></a></h3>
                                    <p>Xem <?php echo $item['view'] ?></p>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>

                    <?php ZenView::load_layout('top-hot') ?>
                </div><!--/ .blog-sidebar -->
            </div>
        </div><!--/ .content-page -->
    </div>
</div>