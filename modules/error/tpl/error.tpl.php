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
    <?php echo phook('public_inside_head') ?>
</head>
<body>
<nav class="navbar navbar-default navbar-inverse navbar-static-top" role="navigation">
    <div class="navbar-header">
        <a class="navbar-brand" href="<?php echo REAL_HOME ?>"><img src="<?php echo REAL_HOME ?>/files/systems/images/zen-cp-logo.png"/></a>
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse-primary">
            <span class="sr-only">Toggle Side Navigation</span>
            <i class="icon-th-list"></i>
        </button>
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse-top">
            <span class="sr-only">Toggle Top Navigation</span>
            <i class="icon-align-justify"></i>
        </button>
    </div>
</nav>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="error-box">
                <div class="message-big"><?php echo $error_number ?></div>
                <div class="message-small"><?php echo $error_name ?></div>
            </div>
        </div>
    </div>
</div>
</body>
</html>