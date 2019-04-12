<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php ZenView::get_title() ?></title>
    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" />
    <meta http-equiv="content-language" content="vi"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="Generator" content="ZenCMS, http://zencms.vn" />
    <meta name="keywords" content="<?php ZenView::get_keyword() ?>"/>
    <meta name="description" content="<?php ZenView::get_desc() ?>"/>
    <meta name="revisit-after" content="1 days" />
    <meta name="robots" content="index,follow" />
    <meta property="og:title" content="<?php ZenView::get_title() ?>" />
    <meta property="og:image" content="<?php ZenView::get_image() ?>" />
    <meta property="og:description" content="<?php ZenView::get_desc() ?>" />
    <link rel="canonical" href="<?php ZenView::get_url() ?>"/>
    <link href="<?php echo _URL_FILES_SYSTEMS ?>/images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
    <!-- Bootstrap -->
    <link href="<?php echo _URL_FILES_SYSTEMS ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="<?php echo _BASE_TEMPLATE ?>/theme/css/style.css" rel="stylesheet"/>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php ZenView::get_head() ?>
</head>
<body class="zen">
<div class="navbar navbar-fixed-top navbar-inverse" role="navigation">
    <?php ZenView::load_layout('header/zen-top-menu') ?>
</div>
<div style="display: block; height: 71px; float: none;"></div>
<div class="container">
    <?php ZenView::display_content() ?>
</div> <!-- /.container -->
<div id="footer">
    <div style="padding:10px 0px;">
        <?php echo phook('footer_controls', '', array('callback' => function($item) { return '<p>' . $item . '</p>';})) ?>
        <p>Power by <a href="http://zencms.vn" title="ZenCMS - Web developers">ZenCMS</a></p>
        <p>Version <?php echo ZENCMS_VERSION ?></p>
        <?php echo phook('copyright', '', array('callback' => function($item) { return '<p>' . $item . '</p>';})) ?>
    </div>
</div> <!-- /.footer -->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="<?php echo _URL_FILES_SYSTEMS ?>/js/jquery/jquery-1.9.1.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?php echo _URL_FILES_SYSTEMS ?>/bootstrap/js/bootstrap.min.js"></script>
<?php ZenView::get_foot() ?>
</body>
</html>