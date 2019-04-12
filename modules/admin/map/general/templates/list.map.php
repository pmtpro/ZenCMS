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
ZenView::section('Danh sách giao diện', function () {
    ZenView::display_breadcrumb();
    ZenView::block('Giao diện đã cài đặt', function () {
        ZenView::padded(function () {
            ZenView::display_message();
            ZenView::display_message('template-number-temp');
            echo '<ul class="thumbnails padded">';
            foreach (ZenView::$D['templates'] as $key => $temp) {
                $info_content = '<table class="table table-normal">
                        <tr><td class="icon"><i class="icon-adjust"></i></td><td>Tên</td><td>' . $temp['name'] . '</td></tr>
                        <tr><td class="icon"><i class="icon-user"></i></td><td>Tác giả</td><td>' . $temp['author'] . '</td></tr>
                        <tr><td class="icon"><i class="icon-umbrella"></i></td><td>Version</td><td>' . $temp['version'] . '</td></tr>
                        <tr><td class="icon"><i class="icon-comment"></i></td><td>Mô tả</td><td>' . $temp['des'] . '</td></tr>
                    </table>';
                echo'<li class="col-md-3 zen-template-screenshot">
                    <a href="' . $temp['actions']['edit']['full_url'] . '" class="thumbnail zen-template-thumbnail" title="Chỉnh sửa ' . $temp['name'] . '">
                        <img src="' . $temp['screenshot'] . '" style="height: 200px;"/>
                        <div class="thumbnail-title">' . $temp['name'] . '</div>
                    </a>
                    <div class="action">
                        <div class="btn-group">
                            <a href="' . HOME . '?template=' . $key . '" target="_blank" class="btn btn-blue"><i class="icon-eye-open"></i> Review</a>
                            <button class="btn btn-blue dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li><a href="' . $temp['actions']['edit']['full_url'] . '">' . $temp['actions']['edit']['name'] . '</a></li>
                                ' . (isset($temp['actions']['setting'])? '<li><a href="' . $temp['actions']['setting']['full_url'] . '">' . $temp['actions']['setting']['name'] . '</a></li>':'') . '
                                <li><a href="' . $temp['actions']['widget']['full_url'] . '">' . $temp['actions']['widget']['name'] . '</a></li>
                                <li><a href="#view-info-' . $key . '" data-toggle="modal">Xem thông tin</a></li>
                                ' . hook('admin', 'template_list_controls', '', array('var' => $temp, 'callback' => function($ele) {return '<li>' . $ele . '</li>';}, 'end_callback' => function($data) {if($data) $data = '<li class="divider"></li>' . $data; return $data;})) . '
                                <li class="divider"></li>
                                <li><a href="#uninstall-template-' . $key . '" data-toggle="modal"><i class="icon-ban-circle"></i> Hủy cài đặt</a></li>
                            </ul>
                        </div>
                        <div id="view-info-' . $key . '" class="modal fade">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title">Thông tin giao diện</h4>
                              </div>
                              <div class="modal-body nopadding">
                                <div class="box-content">
                                ' . $info_content . '
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng lại</button>
                              </div>
                            </div><!-- /.modal-content -->
                          </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->

                        <div id="uninstall-template-' . $key . '" class="modal fade">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title">Hủy cài đặt template</h4>
                              </div>
                              <div class="modal-body nopadding">
                                <div class="box-content">
                                ' . $info_content . '
                                </div>
                              </div>
                              <div class="modal-footer">
                                <form method="POST" action="' . $temp['actions']['uninstall']['full_url'] . '">
                                    <input type="submit" name="submit-uninstall" value="Hủy cài đặt" class="btn btn-red"/>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng lại</button>
                                </form>
                              </div>
                            </div><!-- /.modal-content -->
                          </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                    </div>
                </li>';
            }
            echo '</ul>';
        });
    });
}, array('after' => $menu));