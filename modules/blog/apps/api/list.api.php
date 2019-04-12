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

if (!is_ajax_request()) {
    exit;
}
$security = load_library('security');
$model = $obj->model->get('blog');
$_get_catID = ZenInput::get('catID');
$catID = $_get_catID ? (int)$security->cleanXSS($_get_catID) : 0;
$_get_type = ZenInput::get('type');
$type = $_get_type ? $_get_type : 'post';
$_get_num = ZenInput::get('num');
$num = $_get_num ? $_get_num : 10;
if (!$model->blog_exists($catID)) {
    exit;
}
run_hook('blog', 'list_blog_api_new_post_get', function($data) {
    return $data . ',content';
});
run_hook('blog', 'list_blog_api_new_post_gdata', function($row) {
    $text_content = removeTag($row['content']);
    $row['short_desc'] = subWords($text_content, 100);
    return $row;
});
if ($type == 'post') {
    $list = $model->list_custom_post(0, 'api_new_post', $num, 'page');
    ZenView::ajax_response($list);
} elseif ($type == 'cat') {
    $list = $model->list_custom_cat(0, 'api_get_cat', $num, 'page');
    ZenView::ajax_response($list);
} else exit;