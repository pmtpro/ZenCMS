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
ZenView::section('Tin nhắn', function() {
    ZenView::col(function() {
        ZenView::col_item(9, function() {
            ZenView::block('Cuộc trò chuyện', function() {
                ZenView::display_breadcrumb();
                ZenView::display_message();
                foreach(ZenView::$D['conversations'] as $msg) {
                    echo '<div class="media conversations ' . (!$msg['readed'] ? 'bg-info':'') . '">
                        <span class="pull-left">
                          <img class="media-object" data-src="' . $msg['full_avatar'] . '" alt="64x64" src="' . $msg['full_avatar'] . '" style="width: 64px; height: 64px;">
                        </span>
                        <div class="media-body">
                          <div class="media-heading conversations-from">' . display_nick($msg['nickname'], $msg['perm']) . '</div>
                          <div class="conversations-msg">
                            ' . hook('account', 'list_conversation_controls_before_sub_msg', '', array('var' => array('msg'=>$msg))) . '
                            <a href="' . HOME . '/account/messages/conversation/' . $msg['username'] . '">' . $msg['msg'] . ' ...</a>
                          </div>
                          <div class="conversations-meta">
                            <div class="detail-controls">' . hook('account', 'list_conversation_msg_control', '<span>' . $msg['display_time'] . '</span>', array('var' => array('msg'=>$msg))) . '</div>
                            <div class="private-controls">' . hook('account', 'list_conversation_private_control', '', array('var' => array('msg'=>$msg))) . '</div>
                          </div>
                        </div>
                      </div>';
                }
                ZenView::display_paging();
            });
        });
        ZenView::col_item(3, function() {
            $pageMenu = ZenView::get_menu('page');
            ZenView::block($pageMenu['name'], function() use ($pageMenu) {
                echo '<ul class="list-group">';
                foreach ($pageMenu['menu'] as $item) {
                    echo '<li class="list-group-item"><a href="' . $item['full_url'] . '"><span class="' . $item['icon'] . '"></span> ' . $item['name'] . '</a></li>';
                }
                echo '</ul>';
            });

            $objMenu = ZenView::get_menu('main');
            ZenView::block($objMenu['name'], function() use ($objMenu) {
                echo '<ul class="list-group">';
                foreach ($objMenu['menu'] as $item) {
                    echo '<li class="list-group-item"><a href="' . $item['full_url'] . '"><span class="' . $item['icon'] . '"></span> ' . $item['name'] . '</a></li>';
                }
                echo '</ul>';
            });
        });
    });
});