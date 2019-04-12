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

register_widget_group(array(
    'name' => 'header',
    'start_title' => '<div class="title">',
    'end_title' => '</div>',
    'start_content' => '<div class="detail_content">',
    'end_content' => '</div>',
));

register_widget_group(array(
    'name' => 'footer',
    'start_title' => '<div class="title">',
    'end_title' => '</div>',
    'start_content' => '<div class="detail_content">',
    'end_content' => '</div>',
));
