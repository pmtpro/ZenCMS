<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php ZenView::get_title() ?></title>
    <meta name="ROBOTS" content="ALL"/>
    <meta http-equiv="content-language" content="vi"/>
    <meta name="Generator" content="ZenCMS, http://zencms.vn" />
    <meta name="Googlebot" content="all"/>
    <meta name="keywords" content="<?php ZenView::get_keyword() ?>"/>
    <meta name="description" content="<?php ZenView::get_desc() ?>"/>
    <meta name="revisit-after" content="1 days"/>
    <meta property="og:title" content="<?php ZenView::get_title() ?>" />
    <meta property="og:image" content="<?php ZenView::get_image() ?>" />
    <meta property="og:description" content="<?php ZenView::get_desc() ?>" />
    <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0"/>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible"/>
    <link rel="canonical" href="<?php ZenView::get_url() ?>"/>
    <link rel="shortcut icon" href="<?php echo REAL_HOME ?>/files/systems/images/favicon.ico"/>
    <!-- start: CSS -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,800&subset=latin,vietnamese' rel='stylesheet' type='text/css'/>
    <link href="<?php echo REAL_HOME ?>/files/systems/templates/admin/theme/css/application.css" media="screen" rel="stylesheet" type="text/css" />
    <script src="<?php echo REAL_HOME ?>/files/systems/js/jquery/jquery-1.9.1.min.js"></script>
    <script src="<?php echo REAL_HOME ?>/files/systems/js/jquery/jquery-ui-1.10.2.js"></script>
    <script src="<?php echo REAL_HOME ?>/files/systems/templates/admin/js/bootstrap.zencms.js" type="text/javascript"></script>
    <script src="<?php echo REAL_HOME ?>/files/systems/templates/admin/js/jquery.zencms.js" type="text/javascript"></script>
    <!-- end: CSS -->
    <?php ZenView::get_head() ?>
    <?php phook('public_inside_head') ?>
</head>
<body <?php phook('public_inside_body_tag') ?>>
<?php phook('public_before_main_page') ?>
<div class="zen-wrapper">
    <?php phook('public_before_header') ?>
    <nav class="navbar navbar-default navbar-inverse navbar-static-top">
        <div class="navbar-header">
            <a class="navbar-brand" href="<?php echo REAL_HOME ?>/admin"><img src="<?php echo REAL_HOME ?>/files/systems/images/zen-cp-logo.png"/></a>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse-primary">
                <span class="sr-only">Toggle Side Navigation</span>
                <i class="icon-th-list"></i>
            </button>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse-top">
                <span class="sr-only">Toggle Top Navigation</span>
                <i class="icon-align-justify"></i>
            </button>
        </div>
        <div class="collapse navbar-collapse navbar-collapse-top">
            <div class="navbar-right">
                <ul class="nav navbar-nav navbar-left">
                    <?php $topMenu = ZenView::get_menu('stick-actions') ?>
                    <?php if (is_array($topMenu['menu'])) foreach ($topMenu['menu'] as $item): ?>
                        <li class="cdrop active"><a href="<?php echo $item['full_url'] ?>" <?php echo $item['attr'] ?>><i class="<?php echo $item['icon'] ?>"></i> <?php echo $item['name'] ?></a></li>
                    <?php endforeach ?>
                    <li class="cdrop active"><a href="<?php echo HOME ?>" target="_blank"><i class="icon-home"></i> Trang chính</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-left">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle dropdown-avatar" data-toggle="dropdown">
                          <span>
                            <img class="menu-avatar" src="<?php echo $_client['full_avatar'] ?>"> <span><?php echo $_client['nickname'] ?></span>
                          </span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- the first element is the one with the big avatar, add a with-image class to it -->
                            <li class="with-image">
                                <div class="avatar">
                                    <img src="<?php echo $_client['full_avatar'] ?>">
                                </div>
                                <span><?php echo $_client['nickname'] ?></span>
                            </li>
                            <li class="divider"></li>
                            <li><a href="<?php echo HOME ?>/account"><i class="icon-user"></i> <span>Profile</span></a></li>
                            <li><a href="<?php echo REAL_HOME ?>/admin/members/editor&id=<?php echo $_client['id'] ?>"><i class="icon-cog"></i> <span>Chỉnh sửa</span></a></li>
                            <li><a href="<?php echo HOME ?>/logout"><i class="icon-off"></i> <span>Thoát</span></a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php phook('public_after_header') ?>
    <div class="sidebar-background">
        <div class="primary-sidebar-background"></div>
    </div>
    <div class="primary-sidebar">
        <?php ZenView::load_layout('zen-left-sidebar') ?>
    </div><!--/zen-left-sidebar-->
    <div class="main-content">
        <?php ZenView::display_content() ?>
    </div>
</div><!--/zen-wrapper-->
<?php phook('public_after_main_page', '') ?>
<!--<script type="text/javascript">
    $(function(){
        $.extend($.gritter.options, {
            class_name: 'gritter-light',
            position: 'bottom-right' // defaults to 'top-right' but can be 'bottom-left', 'bottom-right', 'top-left', 'top-right' (added in 1.7.1)
        });

        Growl.success({
            title: 'Có một cập nhật mới',
            text: 'ZenCMS 5 đã sẵn sàng, hãy tải và nâng cấp ngay! <b><a href="http://zencms.vn" target="_blank">Download</a></b>',
            image: 'http://localhost/files/systems/images/zen-cp-logo.png'
        });
    });
</script>-->
<?php ZenView::get_foot() ?>
</body>
</html>
