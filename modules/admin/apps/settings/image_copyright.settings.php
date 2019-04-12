<?php
/**
 * name = Bản quyền hình ảnh
 * icon = admin_settings_image_copyright
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
 * get admin model
 */
$model = $obj->model->get('admin');
/**
 * load sucurity library
 */
$security = load_library('security');
/**
 * load upload library
 */
$upload = load_library('upload');

$logo = get_config('logo_watermark');

$logo_watermark_path = __FILES_PATH.'/images/logo_watermark';

if (isset($_POST['sub_upload'])) {

    $upload->upload_path = $logo_watermark_path;

    if (!$upload->do_upload('logo')) {

        $data['errors'] = $upload->error;

    } else {

        $dataup = $upload->data();

        if (!$upload->is_image()) {

            @unlink($dataup['full_path']);

            $data['errors'][] = 'File bạn tải lên không phải ảnh';

        } else {

            if (!empty($logo)) {

                @unlink($logo_watermark_path.'/'.$logo);
            }

            $update['logo_watermark'] = $dataup['file_name'];
            $model->update_config($update);
            $data['success'] = 'Thành công';
            $obj->config->reload();
        }
    }
}

if (isset($_POST['sub_setting'])) {

    if (!empty($_POST['turn_on_watermark'])) {

        $update['turn_on_watermark'] = 1;
    } else {

        $update['turn_on_watermark'] = 0;
    }

    $model->update_config($update);

    $data['success'] = 'Thành công';

    $obj->config->reload();

}

$logo = get_config('logo_watermark');

if (!empty($logo)) {
    $data['logo_watermark'] = '<img src="'._URL_FILES_IMAGES.'/logo_watermark/'.$logo.'"/>';
} else {
    $data['logo_watermark'] = '';
}
$data['page_title'] = 'Cài đặt bản quyền hình ảnh';
$tree[] = url(_HOME.'/admin', 'Admin CP');
$tree[] = url(_HOME.'/admin/settings', 'Cài đặt');
$tree[] = url(_HOME.'/admin/settings/image_copyright', $data['page_title']);
$data['display_tree'] = display_tree($tree);

$obj->view->data = $data;
$obj->view->show('admin/settings/image_copyright');