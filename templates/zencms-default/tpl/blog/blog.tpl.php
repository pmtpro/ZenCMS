<div class="row margin-bottom-40">
    <h1 class="blog-title"><?php ZenView::get_title() ?></h1>
    <div class="col-md-12 col-sm-12">
        <div class="content-page">
            <div class="row">
                <div class="col-md-9 col-sm-9 blog-posts">
                    <?php $list = model()->list_custom_post(0, 'top_new', 10, 'page') ?>
                    <?php if ($list): ?>
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
                        <?php if (tplConfig('index_display_top_new_paging')): ?>
                            <div class="pull-right"><?php ZenView::display_paging('top_new') ?></div>
                        <?php endif ?>
                    <?php else: ?>
                        <div class="alert alert-success">Chưa có bài viết nào</div>
                    <?php endif ?>
                </div>
                <div class="col-md-3 col-sm-3 blog-sidebar">
                    <?php $cats = model()->list_custom_cat(0, 'list_cat') ?>
                    <?php if ($cats): ?>
                        <h2 class="no-top-space">Danh mục</h2>
                        <ul class="nav sidebar-categories margin-bottom-40">
                            <?php foreach ($cats as $item): ?>
                                <li><a href="<?php echo $item['full_url'] ?>" title="<?php echo $item['title'] ?>"><?php echo $item['name'] ?></a></li>
                            <?php endforeach ?>
                        </ul>
                    <?php endif ?>
                    <?php if ($list): ?>
                        <?php ZenView::load_layout('top-hot') ?>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>