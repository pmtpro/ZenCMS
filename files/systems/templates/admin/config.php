<?php
/**
 * ZenCMS Software
 * Author: ZenThang
 * Email: thangangle@yahoo.com
 * Website: http://zencms.vn or http://zenthang.com
 * License: http://zencms.vn/license or read more license.txt
 * Copyright: (C) 2012 - 2013 ZenCMS
 * All Rights Reserved.
 */
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');
/**
 * This config only use in this template
 */

/**
 * config map
 */
$template_config['map']['breadcrumb'] = array(
    'start' => '<div id="zen-breadcrumb"><div class="breadcrumb-button blue" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><span class="breadcrumb-label"><i class="icon-home"></i> Home</span><span class="breadcrumb-arrow"><span></span></span></div>',
    'end' => '</div>',
    'item' => array(
        'start' => '<div class="breadcrumb-button" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><span class="breadcrumb-label">',
        'end' => '</span><span class="breadcrumb-arrow"><span></span></span></div>'
    )
);

/**
 * please note:
 * use:
 * %1$s to get url
 * %2$s to get name
 * %3$s to get title
 * %4$s to get added
 */
$template_config['map']['pagination'] = array(
    'start' => '<div class="paging">',
    'end' => '</div>',
    'item' => '<a href="%1$s" class="paginate_button %4$s" title="%3$s">%2$s</a>',
    'status' => array(
        'active' => 'paginate_active',
        'disable' => 'paginate_button_disabled'
    )
);

$template_config['map']['message']['error'] = array(
    'start' => '<div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>Lỗi!</strong> ',
    'end' => '</div>',
);

$template_config['map']['message']['notice'] = array(
    'start' => '<div class="alert alert-warning">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>Chú ý:</strong> ',
    'end' => '</div>',
);

$template_config['map']['message']['success'] = array(
    'start' => '<div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">×</button>',
    'end' => '</div>',
);

$template_config['map']['message']['info'] = array(
    'start' => '<div class="alert alert-info">
        <button type="button" class="close" data-dismiss="alert">×</button>',
    'end' => '</div>',
);

$template_config['map']['message']['tip'] = array(
    'start' => '<div class="alert alert-info">
        <button type="button" class="close" data-dismiss="alert">×</button>',
    'end' => '</div>',
);

$template_config['map']['section'] = array(
    'start' => '<div class="zen-section">',
    'end' => '</div>',
    'title' => array(
        'start' => '<div class="container zen-section-title"><div class="row"><div class="area-top clearfix"><!--before--><div class="pull-left header"><h1 class="title">',
        'end' => '</h1></div><!--after--></div></div></div>',
        'before' => array(
            'start' => '<div class="pull-left sparkline-box">',
            'end' => '</div>'
        ),
        'after' => array(
            'start' => '<div class="pull-right sparkline-box">',
            'end' => '</div>'
        ),
    ),
    'content' => array(
        'start' => '<div class="container zen-section-content">',
        'end' => '</div>'
    ),
);

$template_config['map']['block'] = array(
    'start' => '<div class="box">',
    'end' => '</div>',
    'title' => array(
        'start' => '<div class="box-header"><!--before--><span class="title">',
        'end' => '</span><!--after--></div>',
        'before' => array(
            'start' => '',
            'end' => ''
        ),
        'after' => array(
            'start' => '',
            'end' => ''
        ),
    ),
    'content' => array(
        'start' => '<div class="box-content">',
        'end' => '</div>'
    ),
);

$template_config['map']['padded'] = array(
    'start' => '<div class="padded">',
    'end' => '</div>'
);

$template_config['map']['row'] = array(
    'start' => '<div class="row box-section">',
    'end' => '</div>'
);

$template_config['map']['col'] = array(
    'start' => '<div class="row">',
    'end' => '</div>',
    'item' => array(
        '1' => array(
            'start' => '<div class="col-md-1">',
            'end' => '</div>'
        ),
        '2' => array(
            'start' => '<div class="col-md-2">',
            'end' => '</div>'
        ),
        '3' => array(
            'start' => '<div class="col-md-3">',
            'end' => '</div>'
        ),
        '4' => array(
            'start' => '<div class="col-md-4">',
            'end' => '</div>'
        ),
        '5' => array(
            'start' => '<div class="col-md-5">',
            'end' => '</div>'
        ),
        '6' => array(
            'start' => '<div class="col-md-6">',
            'end' => '</div>'
        ),
        '7' => array(
            'start' => '<div class="col-md-7">',
            'end' => '</div>'
        ),
        '8' => array(
            'start' => '<div class="col-md-8">',
            'end' => '</div>'
        ),
        '9' => array(
            'start' => '<div class="col-md-9">',
            'end' => '</div>'
        ),
        '10' => array(
            'start' => '<div class="col-md-10">',
            'end' => '</div>'
        ),
        '11' => array(
            'start' => '<div class="col-md-11">',
            'end' => '</div>'
        ),
        '12' => array(
            'start' => '<div class="col-md-12">',
            'end' => '</div>'
        )
    )
);