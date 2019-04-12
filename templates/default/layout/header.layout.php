<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo $page_title ?></title>
    <meta name="ROBOTS" content="ALL"/>
    <meta http-equiv="content-language" content="vi"/>
    <meta name="Generator" content="ZenCMS, http://zencms.vn" />
    <meta name="Googlebot" content="all"/>
    <meta name="keywords" content="<?php echo $page_keyword; ?>"/>
    <meta name="description" content="<?php echo $page_des; ?>"/>
    <meta name="revisit-after" content="1 days"/>
    <link rel="canonical" href="<?php echo $page_url ?>"/>
    <link rel="shortcut icon" href="<?php echo _URL_FILES_IMAGES ?>/favicon.ico"/>
    <link href="<?php echo _BASE_TEMPLATE ?>/theme/style.css?v=4" rel="stylesheet" type="text/css"/>
    <?php foreach ($page_more as $more): ?>
        <?php echo $more ?>
    <?php endforeach ?>

    <?php phook('public_inside_head', '') ?>

    <base href="<?php echo _HOME; ?>/"/>
</head>

<body <?php phook('public_inside_body_tag', '') ?>>

<?php phook('public_before_main_page', '') ?>

<div id="body">

    <?php phook('public_before_header', '') ?>

    <div class="header">

        <div id="logo">
            <a href="<?php echo _HOME; ?>" title="<?php echo get_config('title'); ?>">
                <img src="<?php echo _BASE_TEMPLATE; ?>/images/logo.png" title="<?php echo get_config('title'); ?>"/>
            </a>
        </div>

        <div id="menu">
            <ul>
                <li class="separator">
                    <a class="iconHomed" href="<?php echo _HOME; ?>" title="<?php echo get_config('title'); ?>">
                        <?php echo icon('home'); ?>
                    </a>
                </li>
                <li class="separator">
                    <a href="<?php echo _HOME; ?>/search" title="Tìm kiếm">Tìm kiếm</a>
                </li>
                <li class="separator">
                    <a href="<?php echo _HOME; ?>/chatbox" title="Chat">Chat</a>
                </li>
                <?php if (IS_MEMBER): ?>
                    <li class="separator">
                        <a href="<?php echo _HOME; ?>/account" title="Tài khoản">Tài khoản</a>
                    </li>
                    <li class="separator">
                        <a href="<?php echo _HOME; ?>/logout" title="Đăng xuất">Thoát</a>
                    </li>
                <?php else: ?>
                    <li class="separator">
                        <a href="<?php echo _HOME; ?>/login" title="Đăng nhập">Đăng nhập</a>
                    </li>
                    <li class="separator">
                        <a href="<?php echo _HOME; ?>/register" title="Đăng kí">Đăng kí</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <?php if (IS_MEMBER): ?>

            <div class="welcome">

                Xin chào <b><?php echo show_nick($_client, true); ?></b>!

                <?php if (model()->_count_new_message()): ?>

                    <a href="<?php echo _HOME ?>/account/messages/inbox" title = "Bạn có <?php echo model()->_count_new_message() ?> tin nhắn mới">
                        <?php echo icon('new_messages', 'vertical-align: middle') ?>
                    </a>

                <?php endif ?>

                <?php phook('public_inside_welcome', '') ?>

            </div>

        <?php endif; ?>
    </div>

<?php phook('public_after_header', '') ?>

<?php widget_group('header') ?>