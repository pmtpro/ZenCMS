<?php
/**
 * name = Quản lí hình ảnh
 * icon = blog_manager_image
 * position = 120
 */
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
 * get blog model
 */
$model = $obj->model->get('blog');
/**
 * load user data
 */
$user = $obj->user;
/**
 * get hook
 */
$obj->hook->get('blog');
/**
 * load helpers
 */
load_helper('gadget');
load_helper('time');
load_helper('user');
load_helper('blog_access_app');
load_helper('formCache');
/**
 * load library
 */
$seo = load_library('seo');
$upload = load_library('upload');
$security = load_library('security');
$blogValid = load_library('blogValid');
$parse = load_library('parse');
$permission = load_library('permission');
$permission->set_user($obj->user);

/**
 * check allow access
 */
if (is_allow_access_blog_app(__FILE__) == false) {
    show_error(403);
}
/**
 * get page title
 */
$page_title = 'Quản lí ảnh';
$tree[] = url(_HOME . '/blog/manager', 'blog manager');
$tree[] = url(_HOME . '/blog/manager/files', $page_title);
$data['display_tree'] = display_tree_modulescp($tree);
$data['page_title'] = $page_title;

$sid = 0;
$step = '';
$act = '';
$act_id = 0;

if (isset($app[1])) {
    $sid = $security->removeSQLI($app[1]);
    $sid = (int)$sid;
}
if (isset($app[2])) {
    $step = $security->cleanXSS($app[2]);
}
if (isset($app[3])) {
    $act = $security->cleanXSS($app[3]);
}
if (isset($app[4])) {
    $act_id = $security->removeSQLI($app[4]);
    $act_id = (int)$act_id;
}


switch ($step) {
    default:

        if (empty($sid) or $model->blog_exists($sid) == false) {

            if (isset($_POST['sub_step1'])) {

                if (isset($_POST['uri'])) {

                    if (!is_numeric($_POST['uri'])) {

                        $cid = $blogValid->preg_match_url($_POST['uri']);

                        $cid = (int)$cid;
                    } else {
                        $cid = $security->removeSQLI($_POST['uri']);
                    }
                    if (!empty($cid)) {

                        if ($model->blog_exists($cid) == false) {

                            $data['notices'] = 'Không tồn tại mục này';
                        } else {
                            $blog = $model->get_blog_data($cid, 'type');

                            if ($blog['type'] != 'post') {

                                $data['notices'] = 'Bạn chỉ có thể quản lí ảnh của bài viết';
                            } else {

                                redirect(_HOME . '/blog/manager/images/' . $cid . '/step2');
                            }
                        }
                    } else {
                        $data['notices'] = 'Không tồn tại chuyên mục này';
                    }
                }
            }

            $obj->view->data = $data;
            $obj->view->show('blog/manager/images/step1');
            return;
        } else {
            redirect(_HOME . '/blog/manager/images/' . $sid . '/step2');
        }
        break;

    case 'step2':

        /**
         * make sure blog is exits
         */
        if (empty($sid) or $model->blog_exists($sid) == false) {

            redirect(_HOME . '/blog/manager/images');
        }

        $blog = $model->get_blog_data($sid);

        switch ($act) {

            default:

                if (isset($_GET['content'])) {

                    $what_get = 'content';

                } else {

                    $what_get = '';
                }

                if (isset($_POST['sub_delete'])) {

                    foreach ($_POST['delete'] as $imgid) {

                        $image = $model->get_image_data($imgid);

                        $obj->hook->loader('delete_image', $image, true);
                    }
                }

                $images = $model->get_images($sid, $what_get);
                $data['blog'] = $blog;
                $data['blog']['images'] = $images;
                $obj->view->data = $data;
                $obj->view->show('blog/manager/images/step2');
                break;

            case 'add':

                $data['form_images'] = array();

                $num_up = 5;
                /**
                 * number_image_per_upload hook *
                 */
                $num_up = $obj->hook->loader('number_image_per_upload', $num_up);

                for ($i = 1; $i <= $num_up; $i++) {

                    $data['form_images'][] = $i;
                }

                if (isset($_POST['sub_add'])) {

                    if (!$security->check_token('token_add_image')) {

                        redirect(_HOME . '/blog/manager/images/' . $sid . '/step2');

                    } else {

                        /**
                         * set form cache for input tag: auto_watermark
                         */
                        sFormCache('auto_watermark');

                        $result = $obj->hook->loader('upload_image', $sid, true);

                        if ($result) {

                            redirect(_HOME . '/blog/manager/images/' . $sid . '/step2');

                        } else {

                            $error = $obj->hook->upload_error;

                            $data['errors'] = $error;
                        }
                    }
                }

                $data['token'] = $security->get_token('token_add_image');
                $data['blog'] = $blog;
                $obj->view->data = $data;
                if (isset($_GET['remote'])) {

                    $obj->view->show('blog/manager/images/add_image_remote');
                } else {

                    $obj->view->show('blog/manager/images/add_image');
                }
                break;

        }
        break;
}
