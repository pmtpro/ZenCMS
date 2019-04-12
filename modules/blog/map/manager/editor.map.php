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
ZenView::section(ZenView::get_title(true), function() {
    ZenView::display_breadcrumb();
    $menuAct = ZenView::get_menu('editor_action');
    $menuList = '';
    $smMenu = '<div class="btn-group"><a class="btn grey-steel btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-angle-down"></i></a><ul class="dropdown-menu pull-right" role="menu">';
    foreach ($menuAct['menu'] as $menu) {
        $menuList .= '<a href="' . $menu['full_url'] . '" title="' . $menu['title'] . '" class="btn btn-default btn-xs"><i class="' . $menu['icon'] . '"></i> ' . $menu['name'] . '</a>';
        $smMenu .= '<li><a href="' . $menu['full_url'] . '" title="' . $menu['title'] . '"><i class="' . $menu['icon'] . '"></i> ' . $menu['name'] . '</a></li>';
    }
    $smMenu .= '</ul></div>';
    $menuList .= $smMenu;

    ZenView::block((ZenView::$D['blog']['type'] == 'post' ? '<span class="label label-success">Bài viết</span> ' : '<span class="label label-success">Thể loại</span> ') . '<a href="' . ZenView::$D['blog']['full_url'] . '?_review_" target="_blank">' . (!empty(ZenView::$D['blog']['name']) ? ZenView::$D['blog']['name'] : '<i class="smaller">(Chưa có tiêu đề)</i>') . '</a>', function() {

        echo '<ul class="nav nav-tabs">';
        echo '<li class="active"><a href="#main-content" data-toggle="tab">Nội dung</a></li>';
        echo '<li><a href="#images" data-toggle="tab"><span class="fa fa-photo"></span> Hình ảnh</a></li>';
        echo '</ul>';

        echo '<div class="tab-content">';
        echo '<div class="tab-pane active" id="main-content">';
        echo '<form class="fill-up" id="post-editor" method="POST" enctype="multipart/form-data">';
        echo '<input type="hidden" name="input-request-token" id="input-request-token" value="' . getRequestToken() . '"/>';
        ZenView::padded(function() {
            ZenView::display_message();
            ZenView::col(function() {
                ZenView::col_item(8, function() {
                    echo '<div class="form-group">
                    <label>Tên</label>
                    <div class="input-group addon-left">
                    <a class="input-group-addon" id="action-auto-save" title="Auto Save" style="cursor: pointer">
                      <i class="fa fa-pencil"></i>
                    </a>
                    <input class="form-control" type="text" name="input-name" id="input-name" value="' . ZenView::$D['blog']['name'] . '"/>
                    </div>
                    <div class="help-block pull-right" id="note-name"></div>
                    </div>';
                    echo '<div class="form-group" title="Click biểu tượng khóa để đóng/mở chỉnh sửa">
                    <label>URL</label>
                    <div class="input-group addon-left">
                    <a class="input-group-addon" id="action-edit-url" title="Click để đóng/mở chỉnh sửa" style="cursor: pointer">
                      <i class="fa fa-' . (ZenView::$D['blog']['url'] ? 'lock' : 'unlock') . '"></i>
                    </a>
                    <input class="form-control" type="text" name="input-url" ' . (ZenView::$D['blog']['url'] ? 'readonly' : '') . ' id="input-url" value="' . ZenView::$D['blog']['url'] . '"/>
                    </div>
                    <div class="help-block pull-right" id="note-url"></div>
                    </div>';
                    echo '<div class="form-group" title="Click biểu tượng khóa để đóng/mở chỉnh sửa">
                    <label>Tiêu đề</label>
                    <div class="input-group addon-left">
                    <a class="input-group-addon" id="action-edit-title" title="Click để đóng/mở chỉnh sửa" style="cursor: pointer">
                      <i class="fa fa-' . (ZenView::$D['blog']['title'] ? 'lock' : 'unlock') . '"></i>
                    </a>
                    <input class="form-control" type="text" name="input-title" ' . (ZenView::$D['blog']['title'] ? 'readonly' : '') . ' id="input-title" value="' . ZenView::$D['blog']['title'] . '"/>
                    </div>
                    <div class="help-block pull-right" id="note-title"></div>
                    </div>';
                    echo '<div class="form-group">
                    <label>Nội dung: (' . ZenView::$D['blog']['type_data'] . ')</label>
                    <textarea class="form-control" name="input-content" id="input-content" placeholder="Nội dung bài viết">' . ZenView::$D['blog']['content'] . '</textarea>
                    <div class="help-block pull-right" id="note-content"></div>
                    </div>';
                    /**
                     * editor_form_after_content_box hook*
                     */
                    echo hook('blog', 'editor_form_after_content_box');
                });
                ZenView::col_item(4, function() {
                    echo '<div class="form-group">';
                    echo '<label for="input-parent">Chọn thư mục:</label>';
                    echo '<select class="form-control" name="input-parent" id="input-parent">';
                    foreach (ZenView::$D['tree_folder'] as $id => $name) {
                        echo '<option value="' . $id . '" ' . (ZenView::$D['blog']['parent'] == $id ? 'selected':''). '>' . $name . '</option>';
                    }
                    echo '</select>';
                    echo '</div>';

                    echo '<div class="form-group">
                    <label>Keyword:</label><i class="editableform-loading"></i>
                    <textarea class="form-control" name="input-keyword" id="input-keyword" placeholder="Nhập keyword bài viết">' . ZenView::$D['blog']['keyword'] . '</textarea>
                    <div class="help-block pull-right" id="note-keyword"></div>
                    </div>';
                    echo '<div class="form-group">
                    <label>Mô tả:</label>
                    <textarea class="form-control" name="input-des" id="input-des" placeholder="Mô tả bài viết" rows="4">' . ZenView::$D['blog']['des'] . '</textarea>
                    <div class="help-block pull-right" id="note-des"></div>
                    </div>';
                    echo '<div class="form-group">
                    <label>Tags:</label>
                    <textarea class="form-control tags" name="input-tags" id="input-tags" placeholder="Tag">' . ZenView::$D['blog']['tags'] . '</textarea>
                    <div class="help-block pull-right" id="note-tag"></div>
                    </div>';
                    echo '<div class="form-group">
                    <label>Icon:</label>
                    <div class="tab">
                    <ul class="nav nav-tabs nav-tabs-left">
                        <li class="active"><a href="#upload-icon-by-url" data-toggle="tab"><i class="fa fa-upload"></i> <span>URL</span></a></li>
                        <li><a href="#upload-icon-by-file" data-toggle="tab"><i class="fa fa-paperclip"></i> <span>File</span></a></li>
                        <li><a href="#upload-icon-settings" data-toggle="tab"><i class="fa fa-cog"></i> <span>Cài đặt</span></a></li>
                    </ul>
                    <div class="tab-content tab-box-content padded">
                        <div class="tab-pane active" id="upload-icon-by-url">
                            <div class="form-group">
                            <div class="input-group addon-right">
                                <input type="text" class="form-control" name="input-upload-icon-url" id="input-upload-icon-url" placeholder="http://"/>
                                <a class="input-group-addon" id="action-upload-icon-url" title="Upload" style="cursor: pointer">
                                  <i class="fa fa-upload"></i>
                                </a>
                            </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="upload-icon-by-file">
                            <div class="form-group">
                                <label>Chọn file</label>
                                <input type="file" class="form-control" name="input-upload-icon" id="input-upload-icon"/>
                            </div>
                        </div>';
                    echo'<div class="tab-pane" id="upload-icon-settings">
                    <div class="form-group">
                    <div>
                    <input type="checkbox" name="input-upload-icon-keep-ratio" id="input-upload-icon-keep-ratio" value="1" ' . (formCacheGet('input-upload-icon-keep-ratio', 'checked')) . '/>
                    <label for="input-upload-icon-keep-ratio">Giữ nguyên tỉ lệ</label>
                    </div>
                    </div>
                    <div class="form-group">
                    <label>Thay đổi kích thước thành</label>
                    <select class="form-control" name="input-upload-icon-resize" id="input-upload-icon-resize">';
                    $list_width = ZenView::get_menu('upload-icon-resize');
                    foreach($list_width['menu'] as $option) {
                        echo '<option value="' . $option['value'] . '" ' . (formCacheGet('input-upload-icon-resize') == $option['value'] ? 'selected' : '') . '>' . $option['name'] . '</option>';
                    }
                    echo '</select>
                    </div>
                    </div>';
                    echo '<div class="tab-control padded">
                    <div class="thumbnail text-center">
                        <div id="upload-icon-result" class="clearfix" style="' . (empty(ZenView::$D['blog']['icon']) ? 'display: none':'') . '">' . (!empty(ZenView::$D['blog']['icon']) ? '<img src="' . ZenView::$D['blog']['full_icon'] . '" style="max-width: 100%"/>':'') . '</div>
                        ' . (empty(ZenView::$D['blog']['icon']) ? '<p>Chưa có ảnh</p>':'') . '
                    </div>
                    <div id="upload-icon-progress" class="progress progress-striped" style="display: none">
                    <div id="upload-icon-bar" class="progress-bar progress-blue tip" title="0" data-percent="0" style="width: 0%"><span id="upload-icon-percent" class="sr-only">0% Complete</span></div>
                    </div>
                    </div>
                    </div>
                    </div>
                    <div class="help-block pull-right" id="note-icon"></div>
                    </div>';
                    /**
                     * editor_form hook*
                     */
                    echo hook('blog', 'editor_form');
                    echo '<div class="form-group"><label>Tùy chọn lưu</label>';
                    echo '<div><label><input type="checkbox" name="save-option-update-time" value="1" checked/> Thay đổi thời gian cập nhật</label></div>';
                    /**
                     * editor_form_option_save hook*
                     */
                    echo hook('blog', 'editor_form_option_save');
                    echo '</div>';
                });
            });
        });
        ZenView::col(function() {
            ZenView::col_item(12, function() {
                echo '<div class="pull-right">
                ' . (ZenView::$D['blog']['type'] == 'folder' ? '' : '<input type="submit" name="submit-save" id="submit-save" value="Lưu nháp" class="btn btn-default rm-fill-up"/>') . '
                <input type="submit" name="submit-public" id="submit-public" value="Public" class="btn btn-primary rm-fill-up"/>
                </div>';
            });
        });
        echo '</form>';
        echo '</div>';

        echo '<div class="tab-pane" id="images">';
        echo '<form method="POST" id="background-form" enctype="multipart/form-data">
        <input type="hidden" name="request-token-upload" id="request-token-upload" value="' . getRequestToken() . '"/>
        <input type="hidden" name="post-id" id="post-id" value="' . ZenView::$D['blog']['id'] . '"/>
        </form>';
        ZenView::col(function() {
            ZenView::col_item(4, function() {
                ZenView::block('Tải ảnh lên', function() {
                    ZenView::display_message('upload-image');
                    echo '<form class="fill-up" method="POST" id="form-upload-image" enctype="multipart/form-data">';
                    echo '<div class="form-group">
                    <label>Upload qua URL</label>
                    <textarea name="images-url" class="form-control" rows="6"></textarea>
                    <div class="help-block pull-right">Mỗi URL ảnh để ở 1 dòng</div>
                    </div>';
                    echo '<div class="form-group">
                    <label>Hoặc chọn ảnh</label>
                    <input type="file" name="images[]" id="images" class="form-control" multiple/>
                    </div>';
                    echo '<div class="form-group">
                    <a id="action-upload-images" class="btn btn-primary" data-loading-text="Đang tải lên..."><span class="fa fa-cloud-upload"></span> Tải lên</a>
                    </div>';
                    echo '</form>';
                });
            });
            ZenView::col_item(8, function() {
                ZenView::block('Danh sách ảnh đã tải lên', function() {
                    ZenView::display_message('list-images');
                    echo '<div id="list-images" class="text-center">Đang tải danh sách ảnh...</div>';
                }, array('after' => '<a title="Tải lại danh sách ảnh" class="btn btn-default btn-xs" id="reload-list-images"><span class="fa fa-refresh"></span> Tải lại</a>'));
            });
        });
        echo '</div>';

        echo '</div>';
    }, array('after' => $menuList));
});