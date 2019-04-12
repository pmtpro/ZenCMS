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
$model = $obj->model->get('account');
$hook = $obj->hook->get('account');
$user = $obj->user;
$security = load_library('security');
$userPerm = load_library('permission');
$userPerm->set_user($user);
load_helper('time');
if (isset($_GET['act'])) {
    $act = $_GET['act'];
} else {
    $act = '';
}
switch ($act) {
    case 'delete':
        if (isset($_GET['id'])) {
            $id = $security->cleanXSS($_GET['id']);
        } else $id = 0;
        if (empty($id) || !$model->message_is_exists($id)) {
            redirect(HOME . '/account');
        }
        $msgData = $model->get_message_data($id);
        if ($msgData['type'] == 'wall') {
            $redirect = HOME . '/account/wall/' . $user['username'];
        } elseif ($msgData['type'] == 'message') {
            $redirect = HOME . '/account/messages' . ($user['username'] == $msgData['from']?'/' . $msgData['to']:'/' . $msgData['from']);
        }
        if (($user['username'] != $msgData['to'] && !$userPerm->is_manager()) || $userPerm->is_lower_levels_of($msgData['from']) || $userPerm->is_lower_levels_of($msgData['to'])) {
            ZenView::set_error('Bạn không thể xóa tin nhắn này!', ZPUBLIC, $redirect);
        } else {
            $msgData['display_time'] = m_timetostr($msgData['time']);
            $msgData['display_msg'] = $hook->loader('message_before_display', $msgData['msg']);
            $data['msg'] = $msgData;

            ZenView::set_tip('Tin nhắn được gửi bởi <b><a href="' . HOME . '/account/' . $data['msg']['from'] . '">' . $data['msg']['from'] . '</a></b> vào <i>' . $data['msg']['display_time'] . '</i> với nội dung:<br/>
            <p>' . $data['msg']['display_msg'] . '</p>
            Bạn có muốn xóa tin nhắn này?');

            if (isset($_POST['submit-delete'])) {
                if (!$model->delete_message($id)) {
                    ZenView::set_error('Không thể xóa tin nhắn này. Vui lòng thử lại!');
                } else {
                    ZenView::set_success(1, ZPUBLIC, $redirect);
                }
            }
        }
        break;
    /*
    case 'delete-conversation':
        if (isset($_GET['id'])) {
            $id = $security->cleanXSS($_GET['id']);
        } else $id = 0;
        if (empty($id) || !$model->message_is_exists($id)) {
            redirect(HOME . '/account');
        }
        $msgData = $model->get_message_data($id);
        if ($msgData['type'] != 'message') {
            ZenView::set_error('Bạn không thể xóa tin nhắn này', ZPUBLIC, HOME . '/account/messages');
        }
        $u1 = $msgData['from'];
        $u2 = $msgData['to'];
        $data['user1'] = $model->get_user_data($u1, 'nickname, perm');
        $data['user2'] = $model->get_user_data($u2, 'nickname, perm');
        $data['number_messages'] = $model->count_conversation_msg($u1, $u2);
        ZenView::set_tip('Cuộc trò chuyện được tạo bởi <b><a href="' . HOME . '/account/wall/' . $u1 . '" target="_blank">' . $data['user1']['nickname'] . '</a></b> và <b><a href="' . HOME . '/account/wall/' . $u2 . '" target="_blank">' . $data['user2']['nickname'] . '</a></b>.<br/>
        Có tất cả <b>' . $data['number_messages'] . '</b> tin nhắn trong cuộc trò chuyện này. Sau khi xóa, tất cả dữ liệu sẽ không thể khôi phục lại. Bạn chắc chắn muốn xóa?');
        if (isset($_POST['submit-delete'])) {
            if (!$model->delete_conversation($u1, $u2)) {
                ZenView::set_error('Không thể xóa cuộc trò chuyện này. Vui lòng thử lại!');
            } else {
                ZenView::set_success(1, ZPUBLIC, HOME . '/account/messages');
            }
        }
        break;
    */
}
$page_title = 'Quản lí tin nhắn';
ZenView::set_title($page_title);
$tree[] = url(HOME . '/account', 'Tài khoản');
$tree[] = url(HOME . '/account/manager', 'Quản lí tin nhắn');
ZenView::set_breadcrumb($tree);
$obj->view->data = $data;
$obj->view->show('account/manager/message');