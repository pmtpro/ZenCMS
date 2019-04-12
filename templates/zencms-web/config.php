<?php
/**
 * ZenCMS Software
 * Copyright 2012-2014 ZenThang
 * All Rights Reserved.
 *
 * This file is part of ZenCMS.
 * ZenCMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License.
 *
 * ZenCMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with ZenCMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package ZenCMS
 * @copyright 2012-2014 ZenThang
 * @author ZenThang
 * @email thangangle@yahoo.com
 * @link http://zencms.vn/ ZenCMS
 * @license http://www.gnu.org/licenses/ or read more license.txt
 */
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');
/**
 * This config only use in this template
 */
/**
 * config map
 */
$template_config['map']['section'] = array(
    'start' => '',
    'end' => '',
    'title' => array(
        'start' => '',
        'end' => '',
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
        'start' => '',
        'end' => ''
    ),
);

$template_config['map']['block'] = array(
    'start' => '<div class="panel panel-default">',
    'end' => '</div>',
    'title' => array(
        'start' => '<div class="panel-heading block-heading">
        <div class="box-tow">
            <h3 class="panel-title block-title">',
        'end' => '</h3>
            </div>
            <div class="box"></div>
            <!--after-->
        </div>',
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
    )
);

$template_config['map']['padded'] = array(
    'start' => '<div class="padded">',
    'end' => '</div>'
);

$template_config['map']['row'] = array(
    'start' => '<div class="zen-row-item">',
    'end' => '</div>'
);

$template_config['map']['col'] = array(
    'start' => '<div class="zen-row">',
    'end' => '</div>',
    'item' => array(
        '1' => array(
            'start' => '<div class="zen-col-md-1">',
            'end' => '</div>'
        ),
        '2' => array(
            'start' => '<div class="zen-col-md-2">',
            'end' => '</div>'
        ),
        '3' => array(
            'start' => '<div class="zen-col-md-3">',
            'end' => '</div>'
        ),
        '4' => array(
            'start' => '<div class="zen-col-md-4">',
            'end' => '</div>'
        ),
        '5' => array(
            'start' => '<div class="zen-col-md-5">',
            'end' => '</div>'
        ),
        '6' => array(
            'start' => '<div class="zen-col-md-6">',
            'end' => '</div>'
        ),
        '7' => array(
            'start' => '<div class="zen-col-md-7">',
            'end' => '</div>'
        ),
        '8' => array(
            'start' => '<div class="zen-col-md-8">',
            'end' => '</div>'
        ),
        '9' => array(
            'start' => '<div class="zen-col-md-9">',
            'end' => '</div>'
        ),
        '10' => array(
            'start' => '<div class="zen-col-md-10">',
            'end' => '</div>'
        ),
        '11' => array(
            'start' => '<div class="zen-col-md-11">',
            'end' => '</div>'
        ),
        '12' => array(
            'start' => '<div class="zen-col-md-12">',
            'end' => '</div>'
        )
    )
);

$template_config['map']['breadcrumb'] = array(
    'start' => '<ol class="breadcrumb"><li itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . HOME . '" title="' . dbConfig('title') . '"><i class="glyphicon glyphicon-home"></i></a></li>',
    'end' => '</ol>',
    'item' => array(
        'start' => '<li itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">',
        'end' => '</li>'
    )
);

/**
 * please note:
 * use:
 * %1$s to get url
 * %2$s to get name
 * %3$s to get title
 * %4$s to get status
 */
$template_config['map']['pagination'] = array(
    'start' => '<ul class="pagination">',
    'end' => '</ul>',
    'item' => '<li class="%4$s"><a href="%1$s" title="%3$s">%2$s</a></li>',
    'status' => array(
        'active' => 'active',
        'disable' => 'disabled'
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
        <strong>Chú ý:</strong>',
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