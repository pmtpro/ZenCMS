<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php ZenView::get_title() ?></title>

    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" />
    <meta http-equiv="content-language" content="vi"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="Generator" content="ZenCMS, http://zencms.vn" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="keywords" content="<?php ZenView::get_keyword() ?>"/>
    <meta name="description" content="<?php ZenView::get_desc() ?>"/>
    <meta name="revisit-after" content="1 days" />
    <meta name="robots" content="index,follow" />
    <meta property="og:title" content="<?php ZenView::get_title() ?>" />
    <meta property="og:image" content="<?php ZenView::get_image() ?>" />
    <meta property="og:description" content="<?php ZenView::get_desc() ?>" />
    <link rel="canonical" href="<?php ZenView::get_url() ?>">
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
<div id="header">
    <div class="navbar navbar-inverse logo-box" role="navigation">
        <div class="container">
            <?php ZenView::load_layout('header/zen-logo') ?>
        </div>
    </div>
    <div class="top-bar">
        <div class="container">
            <?php ZenView::load_layout('header/zen-main-menu') ?>
        </div>
    </div> <!-- /.top-bar -->
</div> <!-- /#header -->
<div class="container">
    <div class="row">
        <div class="col-sm-4 col-lg-3 content-left">
            <?php ZenView::load_layout('zen-left-sidebar') ?>
        </div>
        <div class="col-sm-8 col-lg-9 content-center">
            <?php ZenView::display_content() ?>
        </div>
    </div> <!-- /.row -->
</div> <!-- /.container -->
<div id="footer">
    <div class="copyright">
        <?php ZenView::load_layout('footer/copyright') ?>
    </div>
    <div class="contact">
        <?php ZenView::load_layout('footer/contact') ?>
    </div> <!-- /.bottom-bar -->
</div> <!-- /.footer -->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="<?php echo _URL_FILES_SYSTEMS ?>/js/jquery/jquery-1.9.1.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?php echo _URL_FILES_SYSTEMS ?>/bootstrap/js/bootstrap.min.js"></script>
<?php ZenView::get_foot() ?>
</body>
</html>