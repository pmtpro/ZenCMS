<?php
/**
 * ZenCMS Software
 * Copyright 2012-2014 ZenThang, ZenCMS Team
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
 * @copyright 2012-2014 ZenThang, ZenCMS Team
 * @author ZenThang
 * @email info@zencms.vn
 * @link http://zencms.vn/ ZenCMS
 * @license http://www.gnu.org/licenses/ or read more license.txt
 */
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');

register_widget_group(array(
    'name' => 'header_main_menu',
    'desc' => 'Thanh menu chính trên đầu website',
    'start' => '<li class="link">',
    'end' => '</li>',
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
    'name' => 'bottom_menu',
    'desc' => 'Thanh menu phía dưới cùng trang',
    'start' => '<li>',
    'end' => '</li>',
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
    'name' => 'app',
    'desc' => 'Nội dung bên trái website',
    'start' => '<div class="panel panel-default">',
    'end' => '</div>',
    'title' => array(
        'start' => '<div class="panel-heading block-heading">
        <div class="box-tow">
            <h3 class="panel-title block-title">',
        'end' => '</h3>
            </div>
            <div class="box"></div>
        </div>'
    ),
    'content' => array(
        'start' => '<div class="panel-body">',
        'end' => '</div>'
    )
));

register_widget_group(array(
    'name' => 'blog_after_post_content',
    'desc' => 'Dưới nội dung bài viết',
    'start' => '<div class="panel panel-default">',
    'end' => '</div>',
    'title' => array(
        'start' => '<div class="panel-heading block-heading">
        <div class="box-tow">
            <h3 class="panel-title block-title">',
        'end' => '</h3>
            </div>
            <div class="box"></div>
        </div>'
    ),
    'content' => array(
        'start' => '<div class="panel-body">',
        'end' => '</div>'
    )
));

run_hook('blog', 'list_blog_top_new_limit', function($limit) {
    $num = tplConfig('index_num_post_top_new');
    if (!$num) $num = $limit;
    return $num;
});

run_hook('blog', 'list_blog_top_hot_limit', function($limit) {
    $num = tplConfig('num_post_top_hot');
    if (!$num) $num = $limit;
    return $num;
});

run_hook('blog', 'list_blog_posts_in_folder_limit', function($limit) {
    $num = tplConfig('num_post_in_folder');
    if (!$num) $num = $limit;
    return $num;
});

run_hook('blog', 'list_blog_same_post_in_folder_limit', function($limit) {
    $num = tplConfig('num_same_post_in_folder');
    if (!$num) $num = $limit;
    return $num;
});
run_hook('blog', 'list_blog_same_post_in_post_limit', function($limit) {
    $num = tplConfig('num_same_post_in_post');
    if (!$num) $num = $limit;
    return $num;
});

$_get_page = ZenInput::get('page');
if ($_get_page) {
    $get_page = (int) $_get_page;
    if ($get_page && $get_page != 1) {
        $add = 'Trang ' . $get_page;
        run_hook('blog', 'blog_title', function($title) use ($add) {
            return  $add . ' - ' . $title;
        });
        run_hook('blog', 'blog_desc', function($desc) use ($add) {
            return  $add . ' - ' . $desc;
        });
    }
}