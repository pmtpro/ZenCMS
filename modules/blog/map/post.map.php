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
ZenView::section('Nội dung bài viết', function() {
    ZenView::block(ZenView::$D['blog']['name'], function() {
        ZenView::display_breadcrumb();
        ZenView::padded(function() {
            echo '<div class="app_info">
                <ul class="zen-table">
                  <li>
                    <img itemprop="image" src="' . ZenView::$D['blog']['full_icon'] . '" class="icon" alt="' . ZenView::$D['blog']['title'] . '"/>
                  </li>
                  <li style="width:100%; padding-left:10px;">
                    <div style="position: relative;">
                      <h1 class="title">' . ZenView::$D['blog']['name'] . '</h1>
                      <div>
                        <i class="glyphicon glyphicon-eye-open"></i> ' . ZenView::$D['blog']['view'] . ', Bởi: <b><a href="' . ZenView::$D['blog']['user']['full_url'] . '">' . display_nick(ZenView::$D['blog']['user']['nickname'], ZenView::$D['blog']['user']['perm']) . '</a></b>
                      </div>
                    </div>
                  </li>
                  <li>
                    <div>
                      <a href="' . ZenView::$D['blog']['full_url'] . '#download-attach" class="downloadfree">Download</a>
                    </div>
                  </li>
                </ul>
              </div>';
            echo '<div class="well mota"><strong>Mô Tả</strong></div>';
            echo '<div class="app_desc"><div class="exp_content">' .  ZenView::$D['blog']['content'] . '</div></div>';
            if (!empty(ZenView::$D['blog']['tags'])) {
                echo '<div class="blog_tags">';
                foreach (ZenView::$D['blog']['tags'] as $tag) {
                    echo '<a href="' . $tag['full_url'] . '" class="label label-default" title="' . $tag['tag'] . '">' . $tag['tag'] . '</a> ';
                }
                echo '</div>';
            }
        });
    });
    if (!empty(ZenView::$D['blog']['attachments'])) {
        ZenView::block('<a name="download-attach">Tải về</a>', function() {
            if (!empty(ZenView::$D['blog']['attachments']['link'])) foreach(ZenView::$D['blog']['attachments']['link'] as $link) {
                echo '<div class="download-item"><i class="glyphicon glyphicon-download-alt"></i> <a href="' . $link['link'] . '" title="' . $link['name'] . '" rel="nofollow">' . $link['name'] . '</a> <span class="smaller">(' . $link['click'] . ' click)</span></div>';
            }
            if (!empty(ZenView::$D['blog']['attachments']['file'])) foreach(ZenView::$D['blog']['attachments']['file'] as $file) {
                echo '<div class="download-item"><i class="glyphicon glyphicon-download-alt"></i> <a href="' . $file['link'] . '" title="' . $file['name'] . '" rel="nofollow">' . $file['name'] . '</a> <span class="smaller">(' . $file['down'] . ' lượt tải)</span></div>';
            }
        });
    }
    ZenView::block('Thảo luận', function() {
        ZenView::padded(function() {
            ZenView::display_message('comment');
            echo '<form method="POST">';
            if (!IS_MEMBER) {
                echo '<div class="form-group">
                    <label for="comment-name">Tên của bạn</label>
                    <input type="text" name="name" class="form-control" placeholder="Tên của bạn"/>
                </div>';
            }
            echo '<div class="form-group">
                <label for="comment-msg">Nội dung</label>
                <textarea name="msg" id="comment-msg" class="form-control"></textarea>
            </div>';
            if (!IS_MEMBER) {
                echo '<div class="form-group">
                    <img src="' . ZenView::$D['captcha_src'] . '"/>
                    <input type="text" name="captcha_code" class="form-control" placeholder="Mã xác nhận"/>
                </div>';
            }
            echo '<div class="form-group">
                <input type="hidden" name="token_comment" value="' . ZenView::$D['token_comment'] . '"/>
                <input type="submit" name="submit-comment" class="btn btn-primary" value="Bình luận"/>
            </div>';
            echo '</form>';
            ZenView::display_message('comments-list');
            if (ZenView::$D['blog']['comments']) foreach (ZenView::$D['blog']['comments'] as $cmt) {
                echo '<div class="media post-comment">
                  ' . ($cmt['uid'] ? '<a class="pull-left" href="' . HOME . '/account/wall/' .$cmt['user']['username']. '">
                    <img class="media-object img-responsive post-comment-avatar" alt="64x64" src="' .$cmt['user']['full_avatar']. '" style="width: 48px; height: 48px;">
                  </a>': '') . '
                  <div class="media-body">
                    <div class="post-comment-msg">
                    <b class="media-heading">
                        ' . (empty($cmt['uid']) ? $cmt['name'] : '<a href="' . HOME . '/account/wall/' .$cmt['user']['username']. '">' . display_nick($cmt['user']['nickname'], $cmt['user']['perm']) . '</a>') . '
                    </b>
                    <article>' . $cmt['msg'] . '</article>
                    </div>
                    <div class="post-comment-meta">
                        <div class="public-controls">
                            ' . hook('blog', 'post_comment_public_control', '<span>' . $cmt['display_time'] . '</span>', array('var' => array('cmt'=>$cmt))) . '
                        </div>
                        <div class="private-controls">
                            ' . hook('blog', 'post_comment_private_control', '', array('var' => array('cmt'=>$cmt))) . '
                        </div>
                    </div>
                  </div>
                </div>';
            }
            ZenView::display_paging('comment');
        });
    });
});