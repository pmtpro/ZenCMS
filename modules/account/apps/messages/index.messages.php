<?php
/**
 * name = Hộp thư đến
 * icon = glyphicon glyphicon-send
 */
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

/**
 * load library
 */
$p = load_library('pagination');
$security = load_library('security');

/**
 * get model
 */
$model = $obj->model->get('account');

/**
 * load account hook
 */
$hook = $obj->hook->get('account');

/**
 * get user data
 */
$user = $obj->user;
$data['user'] = $user;

/**
 * num_conversations_display hook *
 */
$limit = $hook->loader('num_conversations_display', 15);

$p->setLimit($limit);
$p->SetGetPage('page');
$start = $p->getStart();
$sql_limit = $start.','.$limit;
$data['conversations'] = $model->get_list_conversation($user['username'], $sql_limit);
$p->setTotal($model->get_total_result());
ZenView::set_paging($p->navi_page());

if (empty($data['conversations'])) {
    ZenView::set_notice('Bạn chưa có cuộc trò chuyện nào!');
}

ZenView::set_title('Tin nhắn');
ZenView::set_breadcrumb(url(HOME.'/account', 'Tài khoản'));
$obj->view->data = $data;
$obj->view->show('account/messages/index');