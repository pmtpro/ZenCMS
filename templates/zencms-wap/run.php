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
    'desc' => 'Nội dung trên đầu website',
    'start' => '',
    'end' => '',
    'title' => array(
        'start' => '<div class="menu">',
        'end' => '</div>'
    ),
    'content' => array(
        'start' => '',
        'end' => ''
    )
));

register_widget_group(array(
    'name' => 'footer',
    'desc' => 'Nội dung phần dưới cùng website',
    'start' => '',
    'end' => '',
    'title' => array(
        'start' => '',
        'end' => ''
    ),
    'content' => array(
        'start' => '',
        'end' => ''
    )
));

register_widget_group(array(
    'name' => 'header_main_menu',
    'desc' => 'Thanh menu trên đầu website',
    'start' => '',
    'end' => '',
    'title' => array(
        'start' => '',
        'end' => ''
    ),
    'content' => array(
        'start' => '',
        'end' => ''
    )
));

register_widget_group(array(
    'name' => 'after_post_content',
    'desc' => 'Dưới nội dung bài viết',
    'start' => '',
    'end' => '',
    'title' => array(
        'start' => '<div class="menu">',
        'end' => '</div>'
    ),
    'content' => array(
        'start' => '',
        'end' => ''
    )
));

register_widget_group(array(
    'name' => 'after_post_folder_content',
    'desc' => 'Dưới nội dung mô tả thư mục',
    'start' => '',
    'end' => '',
    'title' => array(
        'start' => '<div class="menu">',
        'end' => '</div>'
    ),
    'content' => array(
        'start' => '',
        'end' => ''
    )
));

register_widget_group(array(
    'name' => 'after_list_post_in_folder',
    'desc' => 'Dưới danh sách bài viết trong thư mục',
    'start' => '',
    'end' => '',
    'title' => array(
        'start' => '<div class="menu">',
        'end' => '</div>'
    ),
    'content' => array(
        'start' => '',
        'end' => ''
    )
));

register_widget_group(array(
    'name' => 'after_list_cat_in_folder',
    'desc' => 'Dưới danh sách bài viết trong thư mục',
    'start' => '',
    'end' => '',
    'title' => array(
        'start' => '<div class="menu">',
        'end' => '</div>'
    ),
    'content' => array(
        'start' => '',
        'end' => ''
    )
));

register_widget_group(array(
    'name' => 'bottom_menu',
    'desc' => 'Thanh menu phía dưới cùng trang',
    'start' => '<div class="row1">',
    'end' => '</div>',
    'title' => array(
        'start' => '',
        'end' => ''
    ),
    'content' => array(
        'start' => '',
        'end' => ''
    )
));

run_hook('blog', 'index_top_new_get', function($data) {
    return 'id, parent, uid, url, name, title, time, view, icon, des';
});