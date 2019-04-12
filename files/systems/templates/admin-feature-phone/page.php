<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0"/>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible"/>

    <base href="<?php echo REAL_HOME ?>/"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php ZenView::get_title() ?></title>
    <meta name="ROBOTS" content="ALL"/>
    <meta http-equiv="content-language" content="vi"/>
    <meta name="Generator" content="ZenCMS, http://zencms.vn" />
    <meta name="robots" content="noindex" />
    <meta name="keywords" content="<?php ZenView::get_keyword() ?>"/>
    <meta name="description" content="<?php ZenView::get_desc() ?>"/>
    <meta property="og:title" content="<?php ZenView::get_title() ?>" />
    <meta property="og:image" content="<?php ZenView::get_image() ?>" />
    <meta property="og:description" content="<?php ZenView::get_desc() ?>" />
    <link rel="canonical" href="<?php ZenView::get_url() ?>"/>
    <link rel="shortcut icon" href="<?php echo REAL_HOME ?>/files/systems/images/favicon.ico"/>

    <!-- start: CSS -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,800&subset=latin,vietnamese' rel='stylesheet' type='text/css'/>
    <link href="<?php echo REAL_HOME ?>/files/systems/bootstrap/css/bootstrap.min.css" media="screen" rel="stylesheet" type="text/css" />
    <link href="<?php echo REAL_HOME ?>/files/systems/templates/admin-feature-phone/theme/css/style.css" media="screen" rel="stylesheet" type="text/css" />
    <!-- end: CSS -->
    <!-- start: JS -->
    <script src="<?php echo REAL_HOME ?>/files/systems/js/jquery/jquery-1.11.0.min.js"></script>
    <!-- end: JS -->
    <?php ZenView::get_head() ?>
    <?php phook('public_inside_head') ?>
</head>
<body <?php phook('public_inside_body_tag') ?>>
<?php phook('public_before_main_page') ?>

<div class="wrapper">
    <div class="header">
        <div class="container">
            <div class="logo pull-left">
                <a href="<?php echo REAL_HOME ?>/admin"><img class="img-responsive" src="<?php echo REAL_HOME ?>/files/systems/images/zen-cp-logo.png" alt="logo"></a>
            </div><!-- /.logo -->
            <div class="menu pull-right">
                <?php ZenView::load_layout('main-menu') ?>
            </div><!-- /.menu -->
        </div><!-- /.container -->
    </div><!-- /.header -->
    <div class="content">
        <?php ZenView::display_content() ?>
    </div><!-- /.content -->
    <div class="footer">
        <div class="container">
            <p>Power by ZenCMS</p>
            <p>Version: <?php echo ZENCMS_VERSION ?></p>
        </div><!-- /.container -->
    </div><!-- /.footer -->
</div>
<script type="text/javascript">
    $(".btn-group").filter(function() {
        var content = '';
        var tmp;
        var matchResult;
        var listOption = [];
        var pattern = /<(a)[^>]*?href="([^"]+)"[^>]*?>(((?!<\/\1>).|\n|\r)*)<\/\1>/g;
        var selectEle = '';
        $( this, "ul.dropdown-menu li" ).each(function(index) {
            tmp = $(this).html();
            if (tmp) {
                content += tmp;
                matchResult = tmp.match(pattern);
                for (var key = 0; key < matchResult.length; key++) {
                    if ($(matchResult[key]).text()) listOption.push([$(matchResult[key]).attr('href'), $(matchResult[key]).text()]);
                }
            }
        });
        selectEle = '<select style="width: 40px" onchange="if (this.value) window.location.href=this.value"><option>O</option>';
        $.each(listOption, function(index, value) {
            selectEle += '<option value="' + value[0] + '">' + value[1] + '</option>';
        });
        selectEle += '</select>';
        $(this).replaceWith(selectEle);
        $(this).hide();
    });
    /**
     * edit tab panel
     */
    $(".nav.nav-tabs").hide();
    $(".tab-content .tab-pane").addClass('active');
</script>

<?php phook('public_after_main_page', '') ?>
<?php ZenView::get_foot() ?>
</body>
</html>