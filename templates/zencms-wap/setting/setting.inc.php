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

$security = load_library('security');
$model = $obj->model->get('blog');
if (isset($_POST['submit-blog'])) {
    $updateFail = false;
	$id = $num_post = (int) $security->removeSQLI($_POST['num_new_post']);
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
    if (empty($num_post)) $num_post = 5;
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

    if (!empty($_POST['num_post_top_new'])) {
        $num_post_new = (int) $security->removeSQLI($_POST['num_post_top_new']);
        $obj->config->updateTemplateConfig($data['template']['url'], array('num_post_top_new'=>$num_post_new));
    }

    if (!$updateFail) ZenView::set_success(1);
}

