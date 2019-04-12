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
ZenView::section(ZenView::get_title(true), function() {
    ZenView::display_breadcrumb();
    $menuAct = ZenView::get_menu('editor_action');
    $menuList = '';
    foreach ($menuAct['menu'] as $menu) {
        $menuList .= '<li class="toolbar-link">
        <a href="' . $menu['full_url'] . '" title="' . $menu['title'] . '">
        <i class="' . $menu['icon'] . '"></i> ' . $menu['name'] . '</a>
        </li>';
    }
    ZenView::block((ZenView::$D['blog']['type'] == 'post' ? '<span class="label label-green">Bài viết</span> ' : '<span class="label label-green">Thể loại</span> ') . '<a href="' . ZenView::$D['blog']['full_url'] . '?_review_" target="_blank">' . (!empty(ZenView::$D['blog']['name']) ? ZenView::$D['blog']['name'] : '<i class="smaller">(Chưa có tiêu đề)</i>') . '</a>', function() {
        echo '<form class="fill-up" id="post-editor" method="POST" enctype="multipart/form-data">';
        echo '<input type="hidden" name="input-request-token" id="input-request-token" value="' . getRequestToken() . '"/>';
        ZenView::padded(function() {
            ZenView::display_message();
            ZenView::col(function() {
                ZenView::col_item(8, function() {
                    echo '<ul class="separate-sections">';
                    echo('<li class="input">
                    <label>Tên</label>
                    <div class="input-group addon-left">
                    <a class="input-group-addon" id="action-auto-save" title="Auto Save">
                      <i class="icon-pencil"></i>
                    </a>
                    <input type="text" name="input-name" id="input-name" value="' . ZenView::$D['blog']['name'] . '"/>
                    </div>
                    <div class="note pull-right" id="note-name"></div>
                    </li>');
                    echo('<li class="input">
                    <label>URL</label>
                    <div class="input-group addon-left">
                    <a class="input-group-addon" id="action-edit-url" title="Chỉnh sửa">
                      <i class="icon-' . (ZenView::$D['blog']['url'] ? 'lock' : 'unlock') . '"></i>
                    </a>
                    <input type="text" name="input-url" ' . (ZenView::$D['blog']['url'] ? 'readonly' : '') . ' id="input-url" value="' . ZenView::$D['blog']['url'] . '"/>
                    </div>
                    <div class="note pull-right" id="note-url"></div>
                    </li>');
                    echo('<li class="input">
                    <label>Tiêu đề</label>
                    <div class="input-group addon-left">
                    <a class="input-group-addon" id="action-edit-title" title="Chỉnh sửa">
                      <i class="icon-' . (ZenView::$D['blog']['title'] ? 'lock' : 'unlock') . '"></i>
                    </a>
                    <input type="text" name="input-title" ' . (ZenView::$D['blog']['title'] ? 'readonly' : '') . ' id="input-title" value="' . ZenView::$D['blog']['title'] . '"/>
                    </div>
                    <div class="note pull-right" id="note-title"></div>
                    </li>');
                    echo('<li class="input">
                    <label>Nội dung:</label>
                    <textarea name="input-content" id="input-content" placeholder="Nội dung bài viết">' . ZenView::$D['blog']['content'] . '</textarea>
                    <div class="note pull-right" id="note-content"></div>
                    </li>');
                    echo '</ul>';//end list
                });
                ZenView::col_item(4, function() {
                    echo '<ul class="separate-sections">';
                    echo '<li class="input">';
                    echo '<label for="input-parent">Chọn thư mục:</label>';
                    echo '<select class="chzn-select" name="input-parent" id="input-parent">';
                    foreach (ZenView::$D['tree_folder'] as $id => $name) {
                        echo '<option value="' . $id . '" ' . (ZenView::$D['blog']['parent'] == $id ? 'selected':''). '>' . $name . '</option>';
                    }
                    echo '</select>';
                    echo '</li>';

                    echo('<li class="input">
                    <label>Keyword:</label><i class="editableform-loading"></i>
                    <textarea name="input-keyword" id="input-keyword" placeholder="Nhập keyword bài viết">' . ZenView::$D['blog']['keyword'] . '</textarea>
                    <div class="note pull-right" id="note-keyword"></div>
                    </li>');
                    echo('<li class="input">
                    <label>Mô tả:</label>
                    <textarea  name="input-des" id="input-des" placeholder="Mô tả bài viết">' . ZenView::$D['blog']['des'] . '</textarea>
                    <div class="note pull-right" id="note-des"></div>
                    </li>');
                    echo('<li class="input">
                    <label>Tags:</label>
                    <textarea class="tags" name="input-tags" id="input-tags" placeholder="Tag">' . ZenView::$D['blog']['tags'] . '</textarea>
                    <div class="note pull-right" id="note-tag"></div>
                    </li>');
                    echo('<li class="input">
                    <label>Icon:</label>
                    <div class="tab">
                    <ul class="nav nav-tabs nav-tabs-left">
                              <li class="active"><a href="#upload-icon-by-url" data-toggle="tab"><i class="icon-upload-alt"></i> <span>URL</span></a></li>
                              <li><a href="#upload-icon-by-file" data-toggle="tab"><i class="icon-paper-clip"></i> <span>File</span></a></li>
                              <li><a href="#upload-icon-settings" data-toggle="tab"><i class="icon-cog"></i> <span>Cài đặt</span></a></li>
                    </ul>
                    <div class="tab-content tab-box-content padded">
                        <div class="tab-pane active" id="upload-icon-by-url">
                            <div class="input-group addon-right">
                                <a class="input-group-addon" id="action-upload-icon-url" title="Upload">
                                  <i class="icon-upload-alt"></i>
                                </a>
                                <input type="text" name="input-upload-icon-url" id="input-upload-icon-url" placeholder="http://"/>
                            </div>
                        </div>
                        <div class="tab-pane" id="upload-icon-by-file">
                            <div class="input-group addon-left">
                                <input type="file" name="input-upload-icon" id="input-upload-icon"/>
                            </div>
                        </div>');
                    echo'<div class="tab-pane" id="upload-icon-settings">
                    <div class="input">
                    <div>
                    <input type="checkbox" class="icheck" name="input-upload-icon-keep-ratio" id="input-upload-icon-keep-ratio" value="1" ' . (formCacheGet('input-upload-icon-keep-ratio', 'checked')) . '/>
                    <label for="input-upload-icon-keep-ratio">Giữ nguyên tỉ lệ</label>
                    </div>
                    </div>
                    <div class="input">
                    <label>Thay đổi kích thước thành:</label>
                    <select class="uniform" name="input-upload-icon-resize" id="input-upload-icon-resize">';
                    $list_width = ZenView::get_menu('upload-icon-resize');
                    foreach($list_width['menu'] as $option) {
                        echo '<option value="' . $option['value'] . '" ' . (formCacheGet('input-upload-icon-resize') == $option['value'] ? 'selected' : '') . '>' . $option['name'] . '</option>';
                    }
                    echo('</select>
                    </div>
                    </div>');
                    echo('<div class="tab-control padded">
                    <div id="upload-icon-result" class="clearfix" style="' . (empty(ZenView::$D['blog']['icon']) ? 'display: none':'') . '">' . (!empty(ZenView::$D['blog']['icon']) ? '<img src="' . ZenView::$D['blog']['full_icon'] . '"/>':'') . '</div>
                    <div id="upload-icon-progress" class="progress progress-striped" style="display: none">
                    <div id="upload-icon-bar" class="progress-bar progress-blue tip" title="0" data-percent="0" style="width: 0%"><span id="upload-icon-percent" class="sr-only">0% Complete</span></div>
                    </div>
                    </div>
                    </div>
                    </div>
                    <div class="note pull-right" id="note-icon"></div>
                    </li>');
                    echo hook('blog', 'editor_form');
                    echo '</ul>';
                });
            });
        });
        echo('<div class="box-footer">
        ' . (ZenView::$D['blog']['type'] == 'folder' ? '' : '<input type="submit" name="submit-save" id="submit-save" value="Lưu nháp" class="btn btn-default rm-fill-up"/>') . '
        <input type="submit" name="submit-public" id="submit-public" value="Public" class="btn btn-blue rm-fill-up"/>
        </div>');
        echo '</form>';
    }, array('after' => '<ul class="box-toolbar">' . $menuList . '</ul>'));
});