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
ZenView::section('Cuộc trò chuyện', function() {
    ZenView::col(function() {
        ZenView::col_item(9, function() {
            ZenView::block(ZenView::$D['create_new_conversation']?'Tạo cuộc trò chuyện' : ZenView::$D['partner']['nickname'], function() {
                ZenView::display_breadcrumb();
                ZenView::display_message();
                echo '<form role="form" method="POST">';
                if (ZenView::$D['create_new_conversation']) {
                    echo '<div class="form-group">
                            <label for="conversation-to">Username người nhận</label>
                            <input type="text" class="form-control" id="conversation-to" name="conversation-to" placeholder="Nhập người nhận"/>
                        </div>';
                }
                echo '<div class="form-group">
                        <label for="conversation-msg">Nội dung tin nhắn</label>
                        <textarea class="form-control" id="conversation-msg" name="conversation-msg" placeholder="Nội dung tin nhắn"></textarea>
                    </div>';
                echo '<div class="form-group">
                        <input type="submit" name="submit-conversation" value="Gửi tin nhắn" class="btn btn-primary"/>
                        <input type="submit" name="submit-reload" value="Tải lại" class="btn btn-default"/>
                    </div>';
                echo '</form>';
                ZenView::display_message('display-message');
                foreach (ZenView::$D['list_messages'] as $msg) {
                    echo '<div class="media conversations">
                        <span class="pull-left">
                          <img class="media-object" data-src="' . $msg['full_avatar'] . '" alt="48x48" src="' . $msg['full_avatar'] . '" style="width: 48px; height: 48px;">
                        </span>
                        <div class="media-body">
                            <div class="conversations-msg">
                              <b class="media-heading conversations-from">' . display_nick($msg['nickname'], $msg['perm']) . '</b>
                              <article>' . $msg['msg'] . '</article>
                            </div>
                          <div class="conversations-meta">
                            <div class="detail-controls">' . hook('account', 'conversation_msg_control', '<span>' . $msg['display_time'] . '</span>', array('var' => array('msg'=>$msg))) . '</div>
                            <div class="private-controls">' . hook('account', 'conversation_private_control', '', array('var' => array('msg'=>$msg))) . '</div>
                          </div>
                        </div>
                      </div>';
                }
                ZenView::display_paging();
            });
        });
        ZenView::col_item(3, function() {
            $pageMenu = ZenView::get_menu('page');
            if (isset($pageMenu['name'])) ZenView::block($pageMenu['name'], function() use ($pageMenu) {
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