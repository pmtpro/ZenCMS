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
 * load helpers
 */
load_helper('gadget');

/**
 * load library
 */
$security = load_library('security');
/**
 * load library
 */
$p = load_library('pagination');
/**
 * get account model
 */
$model = $obj->model->get('account');
/**
 * get hook account
 */
$hook = $obj->hook->get('account');

$user = $obj->user;

if (isset($app[1])) {
    $sent_to = $security->cleanXSS($app[1]);
}
$data['create_new_conversation'] = false;
if (!empty($sent_to)) {
    if (!$model->user_is_exists($sent_to)) {
        ZenView::set_error('Người dùng không tồn tại', ZPUBLIC, HOME . '/account/messages');
    } else {
        $partner = $model->get_user_data($sent_to, 'username, nickname, avatar, perm');
        $data['partner'] = $partner;
    }
} else {
    $data['create_new_conversation'] = true;
}

if (isset($_POST['submit-conversation'])) {
    $insertMsg = array();
    if (!empty($_POST['conversation-to'])) {
        $post_sent_to = $security->cleanXSS($_POST['conversation-to']);
        if (!$model->user_is_exists($post_sent_to)) {
            ZenView::set_error('Không tồn tại người dùng này');
        } else {
            $sent_to = $post_sent_to;
        }
    }
    if (empty($sent_to)) ZenView::set_error('Không tồn tại người nhận');
    else {
        $insertMsg['to'] = $sent_to;
        $insertMsg['from'] = $user['username'];
        $insertMsg['type'] = 'message';
        $post_msg = $security->cleanXSS($_POST['conversation-msg']);
        if (empty($post_msg)) {
            ZenView::set_notice('Bạn không thể gửi 1 tin nhắn trống');
        } else {
            /**
             * message_before_save hook*
             */
            $insertMsg['msg'] = h($hook->loader('message_before_save', $post_msg));
            $insertMsg['time'] = time();
            if (!$model->insert_message($insertMsg)) {
                ZenView::set_error('Lỗi trong khi gửi tin nhắn. Vui lòng thử lại');
            } else {
                if ($data['create_new_conversation'] == true) {
                    redirect(HOME . '/account/messages/conversation/' . $sent_to);
                }
            }
        }
    }
}

/**
 * mark read for new message
 */
if (!$data['create_new_conversation']) {
    $model->mark_read_conversation($sent_to);
}

/**
 * config_ckeditor_inbox hook*
 */
$ck_set = $hook->loader('config_ckeditor_conversation', array('type' => 'mini-bbcode'));
/**
 * load gadget for message form
 */
gadget_ckeditor('conversation-msg', $ck_set);

/**
 * num_msg_conversations_display hook *
 */
$limit = $hook->loader('num_conversations_display', 15);
$p->setLimit($limit);
$p->SetGetPage('page');
$start = $p->getStart();
$sql_limit = $start.','.$limit;
$data['list_messages'] = $model->get_conversation($sent_to, $sql_limit);
$p->setTotal($model->get_total_result());
ZenView::set_paging($p->navi_page());

if (empty($data['list_messages'])) {
    ZenView::set_notice('Chưa có tin nhắn nào trong cuộc trò chuyện này!', 'display-message');
}
ZenView::set_title($data['create_new_conversation']?'Tạo cuộc trò chuyện':$partner['nickname']);
$tree[] = url(HOME . '/account', 'Tài khoản');
$tree[] = url(HOME . '/account/messages', 'Tin nhắn');
ZenView::set_breadcrumb($tree);
$obj->view->data = $data;
$obj->view->show('account/messages/conversation');