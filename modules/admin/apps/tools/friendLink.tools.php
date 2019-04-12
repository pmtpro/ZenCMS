<?php
/**
 * name = Liên kết website
 * icon = admin_tools_friend_link
 * position = 1
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
 * load admin model
 */
$model = $obj->model->get('admin');
/**
 * load libraries
 */
$security = load_library('security');

/**
 * load helpers
 */
load_helper('time');
load_helper('formCache');

/**
 * set page title
 */
$data['page_title'] = 'Liên kết website';
$tree[] = url(_HOME.'/admin', 'Admin CP');
$tree[] = url(_HOME.'/admin/tools', 'Tools');

$act = '';
$actid = 0;

if (isset($app[1])) {

    $act = $security->cleanXSS($app[1]);
}
if (isset($app[2])) {

    $actid = $security->removeSQLI($app[2]);
}

/**
 * filter link
 */
if (isset($_POST['sub_filter'])) {

    sFormCache('filter_link');
}

switch ($act) {

    default:

        /**
         * get link list
         */
        $data['link_list'] = $model->get_link_list(gFormCache('filter_link'));

        $data['display_tree'] = display_tree($tree);
        $obj->view->data = $data;
        $obj->view->show('admin/tools/friendLink/index');

        break;

    case 'new':

        if (isset($_POST['sub_new'])) {

            if (empty($_POST['name']) || empty($_POST['link'])) {

                $data['notices'] = 'Bạn chưa nhập đầy đủ thông tin';

            } else {

                $ins['name'] = h($_POST['name']);
                $ins['title'] = h($_POST['title']);
                $ins['link'] = h($_POST['link']);
                $ins['rel'] = h($_POST['rel']);
                $ins['type'] = h($_POST['type']);
                $ins['style'] = h($_POST['style']);
                $ins['tags'] = serialize(array_merge(array('tag_start' => h($_POST['tag_start'])), array('tag_end' => h($_POST['tag_end']))));
                $ins['time'] = time();

                if (!$model->insert_link($ins)){

                    $data['errors'] = 'Lỗi dữ liệu';

                } else {

                    redirect(_HOME . '/admin/tools/friendLink');
                }
            }
        }

        $data['page_title'] = "Thêm link";
        $tree[] = url(_HOME.'/admin/tools/friendLink', $data['page_title']);
        $data['display_tree'] = display_tree($tree);
        $obj->view->data = $data;
        $obj->view->show('admin/tools/friendLink/new');
        return;

        beak;

    case 'edit':

        $data['link'] = $model->get_link_data($actid);

        if (empty($data['link'])) {

            redirect(_HOME . '/admin/tools/friendLink');
        }

        if (isset($_POST['sub_edit'])) {

            if (empty($_POST['name']) || empty($_POST['link'])) {

                $data['notices'] = 'Bạn chưa nhập đầy đủ thông tin';

            } else {

                $update['name'] = h($_POST['name']);
                $update['title'] = h($_POST['title']);
                $update['link'] = h($_POST['link']);
                $update['rel'] = h($_POST['rel']);
                $update['style'] = h($_POST['style']);
                $update['tags'] = serialize(array_merge(array('tag_start' => h($_POST['tag_start'])), array('tag_end' => h($_POST['tag_end']))));

                if (!$model->update_link($actid, $update)){

                    $data['errors'] = 'Lỗi dữ liệu';

                } else {

                    redirect(_HOME . '/admin/tools/friendLink');
                }
            }

        }

        $data['page_title'] = "Sửa link";
        $tree[] = url(_HOME.'/admin/tools/friendLink', $data['page_title']);
        $data['display_tree'] = display_tree($tree);
        $obj->view->data = $data;
        $obj->view->show('admin/tools/friendLink/edit');
        return;
        break;

    case 'delete':

        $data['link'] = $model->get_link_data($actid);

        if (empty($data['link'])) {

            redirect(_HOME . '/admin/tools/friendLink');
        }

        $model->delete_link($actid);

        /**
         * clean cache
         */
        ZenCaching::clean();

        redirect(_HOME . '/admin/tools/friendLink');

        break;

}