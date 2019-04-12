<?php
/**
 * name = Hộp thư đến
 * icon = inbox
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
 * load helpers
 */
load_helper('time');
load_helper('gadget');

/**
 * load library
 */
$p = load_library('pagination');
$bbcode = load_library('bbcode');
$security = load_library('security');

/**
 * get model
 */
$model = $obj->model->get('account');

/**
 * load account hook
 */
$obj->hook->get('account');

/**
 * get user data
 */
$user = $obj->user;
$data['user'] = $user;

$act_id = 0;
$data['page_title'] = 'Cuộc trò truyện';

$data['page_more'] = gadget_TinymceEditer('bbcode_mini', true);

if (isset($app[1])) {

    $act_id = $security->removeSQLI($app[1]);
}

$tree[] = url(_HOME.'/account', 'Tài khoản');

if (empty ($act_id)) {

    $limit = 10;
    /**
     * num_conversations_display hook *
     */
    $limit = $obj->hook->loader('num_conversations_display', $limit);

    $p->setLimit($limit);
    $p->SetGetPage('page');
    $start = $p->getStart();
    $sql_limit = $start.','.$limit;

    $inboxs = $model->get_inboxs($sql_limit);

    $data['inboxs'] = $inboxs;

    $total = $model->msg_total_result;

    $p->setTotal($total);

    $data['inboxs_pagination'] = $p->navi_page();

    $tree[] = url(_HOME.'/account/messages', 'Tin nhắn');
    $data['display_tree'] = display_tree($tree);

    $obj->view->data = $data;
    $obj->view->show('account/messages/inbox');

} else {

    $data['conversations'] = array();

    $limit = 3;
    /**
     * num_messages_display hook *
     */
    $limit = $obj->hook->loader('num_messages_display', $limit, true);

    $p->setLimit($limit);
    $p->SetGetPage('page');
    $start = $p->getStart();
    $sql_limit = $start.','.$limit;

    $conversations = $model->get_conversations($act_id, $sql_limit);

    $total = $model->msg_total_result;

    $p->setTotal($total);

    $data['conversations_pagination'] = $p->navi_page();

    if (empty($conversations)) {

        $data['notices'][] = 'Cuộc trò truyện này không tồn tại';

    } else {

        $list_partner = $model->list_user_partner();

        if (!in_array($user['username'], $list_partner)) {

            $data['errors'][] = 'Lỗi dữ liệu';

        } else {

            foreach ($list_partner as $partner) {

                if ($partner != $user['username']) {

                    $data['conversations_partner'] = $model->get_user_data($partner);
                }
            }

            $model->mark_read_conversation($data['conversations_partner']['username']);

            $data['conversations'] = $conversations;

            $data['partner'] = $model->get_conversation_partner();
        }
    }

    $tree[] = url(_HOME.'/account/messages/inbox', 'Hộp thư đến');
    $data['display_tree'] = display_tree($tree);

    $obj->view->data = $data;
    $obj->view->show('account/messages/conversations');
}

?>