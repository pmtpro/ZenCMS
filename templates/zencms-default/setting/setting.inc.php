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

$security = load_library('security');
$model = $obj->model->get('blog');
if (isset($_POST['submit-blog'])) {
    $updateFail = false;

    /**
     * num_post_in_folder
     */
    $num_post_folder = (int) $security->removeSQLI($_POST['num_post_in_folder']);
    if (empty($num_post_folder)) $num_post_folder = 10;
    $update['num_post_in_folder'] = $num_post_folder;
    $obj->config->updateTemplateConfig($data['template']['url'], array('num_post_in_folder'=>$num_post_folder));

    /**
     * num_same_post_in_folder
     */
    $num_rand_post_folder = (int) $security->removeSQLI($_POST['num_same_post_in_folder']);
    $update['num_same_post_in_folder'] = $num_rand_post_folder;
    $obj->config->updateTemplateConfig($data['template']['url'], array('num_same_post_in_folder'=>$num_rand_post_folder));

    /**
     * num_same_post_in_post
     */
    $num_same_post_post = (int) $security->removeSQLI($_POST['num_same_post_in_post']);
    $update['num_same_post_in_post'] = $num_same_post_post;
    $obj->config->updateTemplateConfig($data['template']['url'], array('num_same_post_in_post'=>$num_same_post_post));

    if (!empty($_POST['num_post_top_hot'])) {
        $num_post_hot = (int) $security->removeSQLI($_POST['num_post_top_hot']);
        $obj->config->updateTemplateConfig($data['template']['url'], array('num_post_top_hot'=>$num_post_hot));
    }

    if (!empty($_POST['index_num_post_top_new'])) {
        $num_post_new = (int) $security->removeSQLI($_POST['index_num_post_top_new']);
        $obj->config->updateTemplateConfig($data['template']['url'], array('index_num_post_top_new'=>$num_post_new));
    }
    if (!empty($_POST['index_display_top_new_paging'])) {
        $paging = 1;
    } else $paging = 0;
    $obj->config->updateTemplateConfig($data['template']['url'], array('index_display_top_new_paging'=>$paging));

    if (!$updateFail) ZenView::set_success(1);
}