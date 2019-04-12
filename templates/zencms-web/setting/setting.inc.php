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

$security = load_library('security');
$model = $obj->model->get('blog');
if (isset($_POST['submit-blog'])) {
    $updateFail = false;

    $id_event =  (int) $security->removeSQLI($_POST['id_box_event']);
    if ($model->blog_exists($id_event)) {
        $update['id_box_event'] = $id_event;
        $obj->config->updateTemplateConfig($data['template']['url'], array('id_box_event'=>$id_event));
    } else {
        ZenView::set_error('Mục này không tồn tại');
        $updateFail = true;
    }

    $fail = false;
    foreach($_POST['list_blog_cat_display'] as $catId) {
        if (!$model->blog_exists($catId)) {
            $fail = true;
        } else {
            $listCat[] = $catId;
        }
    }
    if (!$fail) {
        $obj->config->updateTemplateConfig($data['template']['url'], array('list_blog_cat_display'=>serialize($listCat)), 'unserialize', 'serialize');
    } else {
        ZenView::set_error('Bạn phải chọn đúng box');
        $updateFail = true;
    }

    /**
     * num_post_per_box
     */
    $num_post = (int) $security->removeSQLI($_POST['num_post_per_box']);
    if (empty($num_post)) $num_post = 12;
    $update['num_post_per_box'] = $num_post;
    $obj->config->updateTemplateConfig($data['template']['url'], array('num_post_per_box'=>$num_post));

    /**
     * num_post_in_folder
     */
    $num_post_folder = (int) $security->removeSQLI($_POST['num_post_in_folder']);
    if (empty($num_post_folder)) $num_post_folder = 10;
    $update['num_post_in_folder'] = $num_post_folder;
    $obj->config->updateTemplateConfig($data['template']['url'], array('num_post_in_folder'=>$num_post_folder));

    /**
     * num_rand_post_in_folder
     */
    $num_rand_post_folder = (int) $security->removeSQLI($_POST['num_rand_post_in_folder']);
    $update['num_rand_post_in_folder'] = $num_rand_post_folder;
    $obj->config->updateTemplateConfig($data['template']['url'], array('num_rand_post_in_folder'=>$num_rand_post_folder));

    /**
     * num_same_post_in_post
     */
    $num_same_post_post = (int) $security->removeSQLI($_POST['num_same_post_in_post']);
    $update['num_same_post_in_post'] = $num_same_post_post;
    $obj->config->updateTemplateConfig($data['template']['url'], array('num_same_post_in_post'=>$num_same_post_post));

    /**
     * num_rand_post_in_post
     */
    $num_rand_post_post = (int) $security->removeSQLI($_POST['num_rand_post_in_post']);
    $update['num_rand_post_in_post'] = $num_rand_post_post;
    $obj->config->updateTemplateConfig($data['template']['url'], array('num_rand_post_in_post'=>$num_rand_post_post));

    if (!empty($_POST['id_post_hot'])) {
        $hash_id = explode(',', $_POST['id_post_hot']);
        $id_valid = true;
        foreach($hash_id as $id_hot) {
            $id_hot = (int) $security->removeSQLI(trim($id_hot));
            if ($model->blog_exists($id_hot)){
                $list_id[] = $id_hot;
            } else {
                $id_valid = false;
            }
        }
        if (!$id_valid) {
            ZenView::set_notice('Một vài ID hot nhất không tồn tại');
            $updateFail = true;
        }
        if (!empty($list_id)) {
            $obj->config->updateTemplateConfig($data['template']['url'], array('id_post_hot'=>serialize($list_id)), 'unserialize', 'serialize');
        }
    }

    if (!empty($_POST['num_post_top_hot'])) {
        $num_post_hot = (int) $security->removeSQLI($_POST['num_post_top_hot']);
        $obj->config->updateTemplateConfig($data['template']['url'], array('num_post_top_hot'=>$num_post_hot));
    }

    if (!empty($_POST['num_post_top_new'])) {
        $num_post_new = (int) $security->removeSQLI($_POST['num_post_top_new']);
        $obj->config->updateTemplateConfig($data['template']['url'], array('num_post_top_new'=>$num_post_new));
    }

    if (!$updateFail) ZenView::set_success(1);
}

$data['number_slider'] = 5;
if (isset($_POST['submit-slider'])) {
    $fail = false;
    for($i=0; $i<$data['number_slider']; $i++) {
        if (!empty($_POST['url'][$i]) && !empty($_POST['img'][$i])) {
            if (strlen($_POST['url'][$i]) < 255 && strlen($_POST['img'][$i])<255) {
                $insertData[] = array(
                    'url' => $security->cleanXSS($_POST['url'][$i]),
                    'img' => $security->cleanXSS($_POST['img'][$i])
                );
            } else {
                $fail = true;
            }
        }
    }
    if (!$fail) {
        $obj->config->updateTemplateConfig($data['template']['url'], array('slider_config'=>serialize($insertData)), 'unserialize', 'serialize');
        ZenView::set_success(1, 'slider');
    } else {
        ZenView::set_error('Có lỗi, vui lòng thử lại', 'slider');
    }
}