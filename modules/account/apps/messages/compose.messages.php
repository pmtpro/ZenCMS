<?php
/**
 * name = Soạn thư mới
 * icon = compose_message
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

$security = load_library('security');
$gadget = load_helper('gadget');
$model = $obj->model->get('account');
$user = $obj->user;

$data['page_title'] = 'Soạn thư';
$data['user'] = $obj->user;

$data['page_more'] = gadget_TinymceEditer('bbcode_mini', true);


$data['message']['to'] = '';
$data['message']['message'] = '';

if (isset($_POST['sub_send'])) {

    if (empty($_POST['to'])) {

        $data['notices'][] = 'Chưa có người nhận';

    } else {

        $to = $model->get_user_data($_POST['to']);

        if (empty($to) || $to['username'] == $user['username']) {

            $data['notices'][] = 'Không tồn tại người này';

        } else {

            $data['message']['to'] = h($_POST['to']);

            if(empty($_POST['message'])) {

                $data['notices'][] = 'Bạn không thế tạo tin nhắn trống';

            } else {

                $data['message']['message'] = h($_POST['message']);

                $insert['from'] = $user['username'];
                $insert['to'] = $to['username'];
                $insert['msg'] = $data['message']['message'];
                $insert['time'] = time();

                if ($model->insert_message($insert)) {

                    redirect(_HOME.'/account/messages/inbox/'.$model->insert_id());
                } else {

                    $data['notices'][] = 'Không thể gửi tin nhắn. Vui lòng thử lại!';
                }
            }
        }
    }
}

if (isset($app[1])) {
    $data['message']['to'] = $security->cleanXSS($app[1]);
}

$tree[] = url(_HOME.'/account', 'Tài khoản');
$tree[] = url(_HOME.'/account/messages', 'Tin nhắn');
$data['display_tree'] = display_tree($tree);

$obj->view->data = $data;
$obj->view->show('account/messages/compose');


?>