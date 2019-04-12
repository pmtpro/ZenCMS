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
    'start' => '<ul class="zen-breadcrumb breadcrumb">',
    'end' => '</ul>',
    'item' => array(
        'start' => '<li class="breadcrumb-button" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">',
        'end' => '<i class="fa fa-angle-right"></i></li>'
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
    'start' => '<div class="zen-section panel panel-default">',
    'end' => '</div>',
    'title' => array(
        'start' => '<div class="panel-heading menu-title"><!--before--><h1 class="title">',
        'end' => '</h1><!--after--></div>',
        'before' => array(
            'start' => '',
            'end' => ''
        ),
        'after' => array(
            'start' => '<div class="section-after">',
            'end' => '</div>'
        ),
    ),
    'content' => array(
        'start' => '<div class="panel-body">',
        'end' => '</div>'
    ),
);

$template_config['map']['block'] = array(
    'start' => '<div class="zen-block panel panel-default">',
    'end' => '</div>',
    'title' => array(
        'start' => '<div class="panel-heading menu-title"><!--before--><span class="title">',
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
        'start' => '<div class="panel-body">',
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