<?php
/**
 * name = Cài đặt bookmark java
 * icon = admin_settings_java_bookmark
 * position = 40
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

$model = $obj->model->get('admin');
$obj->hook->get('admin');

$security = load_library('security');

$data['length_url_bookmark'] = 20;
/**
 * length_url_bookmark hook *
 */
$data['length_url_bookmark'] = $obj->hook->loader('length_url_bookmark', $data['length_url_bookmark']);

if (isset($_POST['sub'])) {

    if (empty($_POST['url_bookmark_java']) || empty($_POST['title_bookmark_java'])) {

        $data['notices'][] = 'Bạn chưa nhập đầy đủ';

    } else {

        $_POST['url_bookmark_java'] = trim($_POST['url_bookmark_java']);

        $_POST['title_bookmark_java'] = trim($_POST['title_bookmark_java']);

        if (strlen($_POST['url_bookmark_java']) != $data['length_url_bookmark']) {

            $data['notices'][] = 'Url bookmark bắt buộc phải '.$data['length_url_bookmark'].' kí tự';

        } else {

            $update['url_bookmark_java'] = h($_POST['url_bookmark_java']);

            $update['title_bookmark_java'] = h($_POST['title_bookmark_java']);

            if ($update['url_bookmark_java'] != get_config('url_bookmark_java')) {

                $bookmark_file = __FILES_PATH . "/systems/java/bookmark/MobileAds.original.class";

                $out_file = __FILES_PATH . "/systems/java/bookmark/MobileAds.class";

                $handle = fopen($bookmark_file, "rb");

                $contents = bin2hex(fread($handle, filesize($bookmark_file)));

                $hex = str_replace(strToHex('{url_bookmark}'), strtolower(strToHex($_POST['url_bookmark_java'])), $contents);

                $ok = file_put_contents($out_file, pack('H*', $hex));

                fclose($handle);

            } else {

                $ok = true;
            }

            if ($ok == false) {

                $data['errors'] = 'Không thể tạo file Bookmark';

            } else {

                $model->update_config($update);

                $data['success'] = 'Thành công ';

                $obj->config->reload();
            }
        }
    }
}

$data['page_title'] = 'Cài đặt bookmark java';
$tree[] = url(_HOME . '/admin', 'Admin CP');
$tree[] = url(_HOME . '/admin/settings', 'Cài đặt');
$tree[] = url(_HOME . '/admin/settings/java_bookmark', $data['page_title']);
$data['display_tree'] = display_tree($tree);

$obj->view->data = $data;
$obj->view->show('admin/settings/java_bookmark');