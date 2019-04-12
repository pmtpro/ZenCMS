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
ZenView::section('Trang cá nhân', function() {
    ZenView::col(function() {
        ZenView::col_item(9, function() {
            ZenView::block(ZenView::$D['wall']['nickname'], function() {
                ZenView::display_breadcrumb();
                ZenView::display_message();
                ZenView::load_layout('block/user', array('data' => ZenView::$D['wall']));
                ZenView::display_message('wall-comment');
                echo '<form method="POST" role="form">';
                if (ZenView::$D['set']['allow_wall_comment']) {
                    echo '<div class="form-group">
                    <textarea class="form-control" id="wall-comment" name="wall-comment" placeholder="Viết điều gì đó..."></textarea>
                    </div>';
                    echo '<div class="form-group">
                    <input type="submit" name="submit-comment" value="Đăng lên tường" class="btn btn-primary"/>
                    <input type="submit" name="submit-reset" value="Tải lại" class="btn btn-default"/>
                    </div>';
                }
                echo '</form>';
                echo '<div class="profile-post">';
                ZenView::display_message('view-comment');
                if (ZenView::$D['set']['allow_view_wall_comment']) {
                    foreach (ZenView::$D['list_message'] as $msg) {
                        echo '<div class="media message">
                          <a class="pull-left" href="' . HOME . '/account/wall/' . $msg['from'] . '">
                            <img class="media-object img-responsive message-avatar" alt="64x64" src="' . $msg['full_avatar'] . '" style="width: 48px; height: 48px;"/>
                          </a>
                          <div class="media-body">
                            <div class="message-content">
                            <b class="media-heading"><a href="' . HOME . '/account/wall/' . $msg['from'] . '">' . display_nick($msg['nickname'], $msg['perm']) . '</a></b>
                            <article>' . $msg['display_msg'] . '</article>
                            </div>
                            <div class="message-meta">
                                <div class="public-controls pull-left">
                                    ' . hook('account', 'wall_comment_public_control', '<span>' . $msg['display_time'] . '</span>', array('var' => array('msg'=>$msg))) . '
                                </div>
                                <div class="private-controls pull-right">
                                    ' . hook('account', 'wall_comment_private_control', '', array('var' => array('msg'=>$msg))) . '
                                </div>
                            </div>
                          </div>
                        </div>';
                    }
                }
                echo '</div>';
                ZenView::display_paging('list-comment');
            });
        });
        ZenView::col_item(3, function() {
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