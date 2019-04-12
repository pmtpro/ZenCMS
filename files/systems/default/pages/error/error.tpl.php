<!DOCTYPE html>
<html lang="en" class="no-js">
<!-- BEGIN HEAD -->
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>

    <base href="<?php echo REAL_HOME ?>/"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo $error['name'] ?></title>
    <meta name="ROBOTS" content="ALL"/>
    <meta http-equiv="content-language" content="vi"/>
    <meta name="Generator" content="ZenCMS, http://zencms.vn" />
    <meta name="Googlebot" content="all"/>
    <link rel="shortcut icon" href="<?php echo REAL_HOME ?>/files/systems/images/favicon.ico"/>

    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
    <link href="<?php echo REAL_HOME ?>/files/systems/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

    <!-- BEGIN THEME STYLES -->
    <link href="<?php echo REAL_HOME ?>/files/systems/default/pages/error/css/error.css" rel="stylesheet" type="text/css"/>
    <!-- END THEME STYLES -->
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-404-full-page">
<!-- BEGIN HEADER -->
<div class="page-header navbar">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="<?php echo REAL_HOME ?>/admin">
                <img src="<?php echo REAL_HOME ?>/files/systems/images/zen-cp-logo.png" alt="logo" class="logo-default"/>
            </a>
        </div>
        <!-- END LOGO -->
    </div>
    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->

<div class="clearfix"></div>

<div class="row" style="margin-top: 10%;margin-bottom: 10%;">
    <div class="col-md-12 page-404">
        <div class="number">
            <?php echo $error['number'] ?>
        </div>
        <div class="details">
            <h3><?php echo $error['name'] ?></h3>
            <p>
                <?php echo $error['desc'] ?>
            </p>
            <?php echo $error['html'] ?>
        </div>
    </div>
</div>

</body>
<!-- END BODY -->
</html>