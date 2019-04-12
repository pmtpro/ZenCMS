<?php ZenView::add_css(REAL_HOME . '/files/systems/templates/admin-flat/theme/admin/pages/css/blog.css') ?>

<div class="row">
    <?php if (is_module_activate('blog')): ?>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat blue-madison">
            <div class="visual">
                <i class="fa fa-folder-open"></i>
            </div>
            <div class="details">
                <div class="number">
                    <?php echo model('blog')->count() ?> bài
                </div>
                <div class="desc">
                    bài đã được viết
                </div>
            </div>
            <a class="more" href="<?php echo genUrlAppFollow('blog/manager') ?>">
                Quản lí blog <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
        <?php $n = 3; $m = 6?>
    <?php else: ?>
        <?php $n = 4; $m = 12 ?>
    <?php endif ?>
    <div class="col-lg-<?php echo $n ?> col-md-<?php echo $n ?> col-sm-6 col-xs-12">
        <div class="dashboard-stat red-intense">
            <div class="visual">
                <i class="fa fa-puzzle-piece"></i>
            </div>
            <div class="details">
                <div class="number">
                    <?php echo model()->count_module() ?>
                </div>
                <div class="desc">
                    module đã cài đặt
                </div>
            </div>
            <a class="more" href="<?php echo HOME ?>/admin/general/modules">
                Danh sách <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-<?php echo $n ?> col-md-<?php echo $n ?> col-sm-6 col-xs-12">
        <div class="dashboard-stat green-haze">
            <div class="visual">
                <i class="fa fa-font"></i>
            </div>
            <div class="details">
                <div class="number">
                    <?php echo model()->count_template() ?>
                </div>
                <div class="desc">
                    giao diện đã cài đặt
                </div>
            </div>
            <a class="more" href="<?php echo HOME ?>/admin/general/templates">
                Danh sách <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-<?php echo $n ?> col-md-<?php echo $n ?> col-sm-<?php echo $m ?> col-xs-12">
        <div class="dashboard-stat purple-plum">
            <div class="visual">
                <i class="fa fa-users"></i>
            </div>
            <div class="details">
                <div class="number">
                    <?php echo model('account')->count_users() ?>
                </div>
                <div class="desc">
                    thành viên đã đăng kí
                </div>
            </div>
            <a class="more" href="<?php echo HOME ?>/admin/members/list">
                Danh sách <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
</div>


<div class="row">
<div class="col-md-12 blog-page">
<div class="row">
<div class="col-md-8 col-sm-8 article-block">
    <?php ZenView::display_message() ?>
    <?php ZenView::display_message('new_feed') ?>
    <?php if (ZenView::$D['new_feeds']) foreach (ZenView::$D['new_feeds'] as $item): ?>
        <div class="row">
            <div class="col-md-2 blog-img">
                <img src="<?php echo $item->full_icon ?>" class="img-responsive">
            </div>
            <div class="col-md-10 blog-article">
                <h3>
                    <a href="<?php echo $item->full_url ?>" title="<?php echo $item->title ?>" target="_blank"><?php echo $item->name ?></a>
                </h3>
                <p><?php echo $item->short_desc ?></p>
                <div class="blog-tag-data">
                    <ul class="list-inline">
                        <li>
                            <i class="fa fa-calendar"></i>
                            <a><?php echo $item->display_time ?></a>
                        </li>
                    </ul>
                </div>
                <a class="btn btn-danger" href="<?php echo $item->full_url ?>" title="<?php echo $item->title ?>" target="_blank">Đọc tiếp <i class="m-icon-swapright m-icon-white"></i></a>
            </div>
        </div>
        <hr>
    <?php endforeach ?>
</div>
<!--end col-md-9-->
<div class="col-md-4 col-sm-4 blog-sidebar">
    <div class="top-news">
        <?php $list_class = array('red', 'green', 'blue', 'yellow', 'purple') ?>
        <?php $i = 0?>
        <?php if (ZenView::$D['list_cat']) foreach(ZenView::$D['list_cat'] as $item): ?>
            <?php if ($i > 4) $i = 0 ?>
            <a href="<?php echo $item->full_url ?>" class="btn <?php echo $list_class[$i++] ?>" target="_blank">
                <span class="text-dot"><?php echo $item->name ?></span>
                <em><i class="fa fa-link"></i> Xem thêm</em>
                <i class="fa fa-globe top-news-icon"></i>
            </a>
        <?php endforeach ?>
    </div>
    <div class="space20"></div>
    <h3>Hỗ trợ</h3>
    <div class="tabbable tabbable-custom">
        <ul class="nav nav-tabs">
            <li class="active">
                <a data-toggle="tab" href="#tab_1_1"><i class="fa fa-share-alt"></i>Channels</a>
            </li>
            <li>
                <a data-toggle="tab" href="#tab_1_2"><i class="fa fa-envelope blue"></i>Mail</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="tab_1_1" class="tab-pane active">
                <p>
                    Các kênh hỗ trợ
                </p>
                <ul class="social-icons margin-bottom-10">
                    <li>
                        <a href="https://www.facebook.com/zencms" data-original-title="Fanpage ZenCMS trên Facebook" title="Fanpage ZenCMS trên Facebook" target="_blank" class="facebook">
                        </a>
                    </li>
                    <li>
                        <a href="https://www.facebook.com/groups/zencms" data-original-title="Group ZenCMS trên Facebook" title="Group ZenCMS trên Facebook" target="_blank" class="facebook">
                        </a>
                    </li>
                    <li>
                        <a href="https://twitter.com/zencms" data-original-title="ZenCMS trên Twitter" title="ZenCMS trên Twitter" target="_blank" class="twitter">
                        </a>
                    </li>
                    <li>
                        <a href="http://google.com/+zencmsvnpage" data-original-title="ZenCMS trên Google Plus" title="ZenCMS trên Google Plus" target="_blank" class="googleplus">
                        </a>
                    </li>
                    <li>
                        <a href="http://zencms.vn/rss" data-original-title="ZenCMS RSS" title="ZenCMS RSS" target="_blank" class="rss">
                        </a>
                    </li>
                    <li>
                        <a href="https://github.com/zencmsvn" data-original-title="github" title="ZenCMS trên RSS" target="_blank" class="github"></a>
                    </li>
                </ul>
            </div>
            <div id="tab_1_2" class="tab-pane">
                <p>
                    Hỗ trợ qua mail
                </p>
                <table class="table">
                    <thead>
                    <tr><td>Mail</td><td>Hỗ trợ</td></tr>
                    </thead>
                    <tr><td><a href="mailto:support@zencms.vn">support@zencms.vn</a></td><td>Chung</td></tr>
                    <tr><td><a href="mailto:techs@zencms.vn">techs@zencms.vn</a></td><td>Kĩ thuật</td></tr>
                    <tr><td><a href="mailto:billing@zencms.vn">billing@zencms.vn</a></td><td>Thanh toán</td></tr>
                    <tr><td><a href="mailto:techs.hosting@zencms.vn">techs.hosting@zencms.vn</a></td><td>Kĩ thuật hosting</td></tr>
                    <tr><td><a href="mailto:billing.hosting@zencms.vn">billing.hosting@zencms.vn</a></td><td>Thanh toán hosting</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="space20"></div>
    <h3>Facebook</h3>
    <div class="blog-facebook">
        <div class="fb-like" data-href="https://www.facebook.com/zencms" data-width="400px" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
        <div class="space20"></div>
        <div class="fb-comments" data-href="http://zencms.vn" data-width="400px" data-numposts="5" data-colorscheme="light"></div>
    </div>
</div>
<!--end col-md-3-->
</div>
</div>
</div>