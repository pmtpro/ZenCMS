<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php ZenView::get_title() ?></title>
    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" />
    <meta http-equiv="content-language" content="vi"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="<?php ZenView::get_keyword() ?>">
    <meta name="description" content="<?php ZenView::get_desc() ?>">
    <meta name="revisit-after" content="1 days" />
    <meta name="robots" content="index,follow" />
    <link rel="canonical" href="<?php ZenView::get_url() ?>">
    <link href="<?php echo _URL_FILES_SYSTEMS ?>/images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
    <!-- Bootstrap -->
    <link href="<?php echo _URL_FILES_SYSTEMS ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="<?php echo _BASE_TEMPLATE ?>/theme/css/style.css" rel="stylesheet" type="text/css" media="all,handheld" />
    <?php ZenView::get_head() ?>
</head>
<body>
<div id="wrapper">
	<?php ZenView::load_layout('header/zen-top-menu') ?>

    <!-- widget header -->
    <?php widget_group('header') ?>
    <!-- end widget header -->

	<div style="clean: both;"></div>
	<?php ZenView::display_content() ?>
	<div style="clean: both;"></div>

    <!-- widget footer -->
    <?php widget_group('footer') ?>
    <!-- end widget footer -->

    <div class="menu">Danh mục trang</div>
    <div class="row1"><a href="<?php echo HOME ?>" title="<?php echo dbConfig('title') ?>">Trang chủ</a></div>
    <?php if (is(ROLE_MANAGER)): ?>
        <div class="row1"><a href="<?php echo HOME ?>/admin" rel="nofollow">ADMIN CP</a></div>
    <?php endif ?>
    <?php echo phook('bottom_menu', '', array('callback' => function($item) { return '<div class="row1">' . $item . '</div>';})) ?>
    <?php widget_group('bottom_menu') ?>
    <div class="footer">
        <?php echo phook('footer_controls', '', array('callback' => function($item) { return '<p>' . $item . '</p>';})) ?>
        <p>Power by <a href="http://zencms.vn" title="ZenCMS - Web developers">ZenCMS</a></p>
        <p>Version <?php echo ZENCMS_VERSION ?></p>
        <?php echo phook('copyright', '', array('callback' => function($item) { return '<p>' . $item . '</p>';})) ?>
    </div>

	<?php ZenView::get_foot() ?>
</div>
</body>
</html>