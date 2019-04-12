<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- Head BEGIN -->
<head>
    <meta charset="utf-8"/>
    <title><?php ZenView::get_title() ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content="<?php ZenView::get_desc() ?>" name="description" />
    <meta content="<?php ZenView::get_keyword() ?>" name="keywords" />
    <meta content="ZenCMS" name="author" />
    <meta property="og:site_name" content="<?php ZenView::get_title() ?>" />
    <meta property="og:title" content="<?php ZenView::get_title() ?>" />
    <meta property="og:description" content="<?php ZenView::get_desc() ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="<?php ZenView::get_image() ?>" /><!-- link to image for socio -->
    <meta property="og:url" content="<?php ZenView::get_url() ?>" />
    <link rel="shortcut icon" href="<?php echo HOME ?>/files/systems/images/favicon.ico" />
    <!-- Fonts START -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|PT+Sans+Narrow|Source+Sans+Pro:200,300,400,600,700,900&amp;subset=all" rel="stylesheet" type="text/css"/>
    <!-- Fonts END -->
    <!-- Global styles START -->
    <link href="<?php echo _BASE_TEMPLATE ?>/theme/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <?php ZenView::load_bootstrap_css() ?>
    <!-- Global styles END -->
    <!-- Theme styles START -->
    <link href="<?php echo _BASE_TEMPLATE ?>/theme/assets/global/css/components.css" rel="stylesheet"/>
    <link href="<?php echo _BASE_TEMPLATE ?>/theme/assets/frontend/layout/css/style.css" rel="stylesheet"/>
    <link href="<?php echo _BASE_TEMPLATE ?>/theme/assets/frontend/layout/css/style-responsive.css" rel="stylesheet"/>
    <link href="<?php echo _BASE_TEMPLATE ?>/theme/assets/frontend/layout/css/themes/red.css" rel="stylesheet" id="style-color"/>
    <link href="<?php echo _BASE_TEMPLATE ?>/theme/assets/frontend/layout/css/custom.css" rel="stylesheet"/>
    <!-- Theme styles END -->
    <?php ZenView::get_head() ?>
</head>
<!-- Head END -->

<!-- Body BEGIN -->
<body class="corporate">

<?php ZenView::load_layout('top-bar') ?>

<?php ZenView::load_layout('header-bar') ?>

<div class="main">
    <div class="container">
        <?php ZenView::display_content() ?>
    </div>
</div>

<?php ZenView::load_layout('footer') ?>

<!-- Load javascripts at bottom, this will reduce page load time -->
<!-- BEGIN CORE PLUGINS (REQUIRED FOR ALL PAGES) -->
<!--[if lt IE 9]>
<script src="<?php echo _BASE_TEMPLATE ?>/theme/assets/global/plugins/respond.min.js"></script>
<![endif]-->
<?php ZenView::load_global_jquery('jquery-1.11.0.min.js') ?>
<?php ZenView::load_global_jquery('jquery-migrate-1.1.1.min.js') ?>
<?php ZenView::load_bootstrap_js() ?>
<script src="<?php echo _BASE_TEMPLATE ?>/theme/assets/frontend/layout/scripts/back-to-top.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<script src="<?php echo _BASE_TEMPLATE ?>/theme/assets/frontend/layout/scripts/layout.js" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        Layout.init();
    });
</script>
<!-- END PAGE LEVEL JAVASCRIPTS -->
<?php ZenView::get_foot() ?>
</body>
<!-- END BODY -->
</html>