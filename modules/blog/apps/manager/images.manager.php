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

/**
 * validation token if is a ajax request
 */
if (is_ajax_request() && !confirmRequest($_POST['request-token-upload'])) exit;

ZenView::set_title('Quản lí hình ảnh');

/**
 * set base url
 */
$base_url = HOME . '/admin/general/modulescp?appFollow=blog/manager';
$data['base_url'] = $base_url;

$user = $obj->user;
$model = $obj->model->get('blog');
$model->set_filter('status', array(0, 1, 2));
$hook = $obj->hook->get('blog');

$security = load_library('security');

$_get_id = ZenInput::get('id');
$id = $_get_id ? (int) $security->removeSQLI($_get_id) : 0;

$current_url = $base_url . '/images&id=' . $id;

$data['main_url'] = $data['base_url'] . '/images&id=' . $id;

/************* upload image **************/
$dirStore = 'posts/images';
/**
 * set directory upload image
 */
$imageUploadDir = __FILES_PATH . '/' . $dirStore;

if (empty($id) || !$model->blog_exists($id)) {
    ZenView::set_error('Không tồn tại bài này');
} else {
    $blog = $model->get_blog_data($id);
    $data['blog'] = $blog;

    if (isset($_POST['submit-upload'])) {
        $list_url = explode("\n", $_POST['images-url']);
        $list_url = array_filter($list_url, function($url) {
            $url = trim($url);
            if ($url) return $url;
        });
        $file_arr = array();
        foreach ($_FILES['images'] as $keyName => $arr) {
            foreach ($arr as $k => $val) {
                $file_arr[$k][$keyName] = $arr[$k];
            }
        }
        $file_arr = array_merge($file_arr, $list_url);
        $file_arr = array_filter($file_arr, function($item){
            if (is_array($item)) {
                if ($item['tmp_name']) return $item;
            } else return $item;
        });
        $successUpload = array();
        foreach ($file_arr as $file) {
            /**
             * load upload library
             */
            $upload = load_library('upload', array('init_data' => $file));
            /**
             * check allow upload
             */
            if ($upload->uploaded) {

                $upload->file_new_name_body = $blog['url'];
                $upload->allowed = array('image/*');
                /**
                 * auto make directory by month-year
                 */
                $subDir = autoMkSubDir($imageUploadDir);
                $uploadPath = $imageUploadDir . '/' . $subDir;

                /**
                 * init_upload_image hook*
                 */
                $upload = $hook->loader('init_upload_image', $upload);

                $upload->process($uploadPath);
                /**
                 * upload complete
                 */
                if ($upload->processed) {
                    $dataUp = $upload->data();
                    /**
                     * image_data_after_upload hook*
                     */
                    $dataUp = $hook->loader('image_data_after_upload', $dataUp);

                    if (file_exists($dataUp['full_path'])) {

                        $insertImage['uid'] = $user['id'];
                        $insertImage['sid'] = $id;
                        $insertImage['url'] = $subDir . '/' . $dataUp['file_name'];
                        $insertImage['type'] = $dataUp['file_name_ext'];
                        $insertImage['time'] = time();
                        if (!$model->insert_image($insertImage)) {
                            @unlink($dataUp['full_path']);
                        } else {
                            $successUpload[] = 'files/posts/images/' . $insertImage['url'];
                        }
                    }
                }
            };
            $upload->clean();
        }
        if (is_ajax_request()) {
            $redirect = false;
        } else $redirect = $data['main_url'];

        $countUploadSuccess = count($successUpload);
        if ($countUploadSuccess == count($file_arr) && $countUploadSuccess != 0) {
            ZenView::set_success(1, 'upload-image', $redirect);
        } elseif ($countUploadSuccess != 0) {
            ZenView::set_notice('Một vài file không thể upload', 'upload-image', $redirect);
        } else {
            ZenView::set_error('Lỗi khi upload file. Vui lòng thử lại', 'upload-image', $redirect);
        }
        /**
         * check if is ajax request
         */
        if (is_ajax_request()) {
            ZenView::ajax_response($successUpload);
        }
    }

    $_get_act = ZenInput::get('act');
    switch ($_get_act) {
        case 'delete':
            $_get_imgID = ZenInput::get('imgID');
            $imgID = $_get_imgID ? $security->removeSQLI($_get_imgID) : 0;
            $image = $model->get_image_data($imgID);
            if (is_ajax_request()) {
                $redirect = false;
            } else $redirect = $data['main_url'];
            if (!$image) {
                ZenView::set_error('Không tồn tại ảnh này', ZPUBLIC, $redirect);
            } else {
                $model->delete_image($imgID);
                ZenView::set_success(1, ZPUBLIC, $redirect);
            }
            if (is_ajax_request()) {
                ZenView::ajax_response(1);
            }
            break;
        case 'delete_all_images':
            $data['show_form_delete_all_images'] = true;
            ZenView::set_notice('Một khi đã xóa tất cả hình ảnh của bài viết này, bạn sẽ không thể khôi phục. Bạn có muốn tiếp tục?', 'delete-all-images');
            if (isset($_POST['submit-delete-all-images'])) {
                $model->delete_all_images($id);
                ZenView::set_success(1, ZPUBLIC, $data['main_url']);
            }
            break;
    }

    $data['list_images'] = $model->get_images($id);
    $data['list_images'] = array_map(function($img) use ($current_url, $hook) {
        $img['actions'][] = array(
            'name' => 'Mở hình ảnh',
            'icon' => 'fa fa-eye',
            'full_url' => _URL_FILES_POSTS . '/images/' . $img['url'],
            'attr' => 'target="_blank"'
        );
        $img['actions'][] = array(
            'name' => 'Xóa ảnh',
            'icon' => 'fa fa-times',
            'full_url' => $current_url . '&imgID=' . $img['id'] . '&act=delete',
            'attr' => cfm('Bạn chắc chắn muốn xóa ảnh này?'),
            'divider' => true
        );
        /**
         * list_images_actions hook*
         */
        $img['actions'] = $hook->loader('list_images_actions', $img['actions']);
        return $img;
    }, $data['list_images']);
    if (empty($data['list_images'])) ZenView::set_tip('Chưa có ảnh nào đươc tải lên', 'list-images');
    if (is_ajax_request() && isset($_POST['get-list-images'])) {
        ZenView::ajax_response($data['list_images']);
    }

    ZenView::set_title('Quản lí ảnh: ' . $blog['name']);
    ZenView::add_js('ZeroClipboard/ZeroClipboard.js', 'foot');
    ZenView::add_js(_URL_MODULES . '/blog/js/manager/copy-short-link-image.js', 'foot');
}

/**
 * add breadcrumb
 */
$tree[] = url($base_url, 'Quản lí blog');
$tree[] = url($base_url . '/cpanel', 'Quản lí nội dung');
$tree[] = url($base_url . '/editor&id=' . $id, $data['blog']['name'] ? $data['blog']['name'] : 'Không tiêu đề');
ZenView::set_breadcrumb($tree);

$obj->view->data = $data;
$obj->view->show('blog/manager/images');