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
ZenView::section('Quản lí module', function() {
    ZenView::display_breadcrumb();
    ZenView::display_message();

    $content_after = '<ul class="box-toolbar">
      <li><span class="label label-blue">' . count(ZenView::$D['modules']) . '</span></li>
    </ul>';
    ZenView::block('Danh sách module', function() {
        echo '<table class="dTable ZenModuleTable">';
        echo('<thead><tr>
        <th><div></div></th>
        <th><div>Tên</div></th>
        <th><div>URL</div></th>
        <th><div>Phiên bản</div></th>
        <th><div>Tác giả</div></th>
        <th><div>Mô tả</div></th>
        </tr></thead>');
        $modal = '';
        foreach (ZenView::$D['modules'] as $name => $mod) {
            echo('<tr>
            <td>
                <div class="btn-group">
                    <button class="btn btn-xs btn-' . ($mod['activated'] ? 'green':'default') . ' dropdown-toggle" data-toggle="dropdown"><i class="icon-ok"></i></button>
                    <ul class="dropdown-menu">');
                    $info_content = '<div class="modal-body nopadding">
                                        <div class="box-content">
                                        <table class="table table-normal">
                                            <tr><td class="icon"><i class="icon-adjust"></i></td><td>Tên</td><td>' . $mod['name'] . '</td></tr>
                                            <tr><td class="icon"><i class="icon-user"></i></td><td>Tác giả</td><td>' . $mod['author'] . '</td></tr>
                                            <tr><td class="icon"><i class="icon-umbrella"></i></td><td>Version</td><td>' . $mod['version'] . '</td></tr>
                                            <tr><td class="icon"><i class="icon-comment"></i></td><td>Mô tả</td><td>' . $mod['des'] . '</td></tr>
                                        </table>
                                        </div>
                                  </div>';
                    foreach ($mod['actions'] as $act) {
                        switch ($act['actID']) {
                            case 'uninstall':
                                $item_url = '#' . $act['id'];
                                $toggle_attr = ' data-toggle="modal"';
                                $modal_title = 'Gỡ bỏ module';
                                $modal_content = $info_content;
                                $modal_action = '<input type="submit" name="submit-uninstall" name="submit-uninstall" value="Gỡ bỏ" class="btn btn-default"/>
                                            <button type="button" class="btn btn-red" data-dismiss="modal">Đóng</button>';
                                break;
                            case 'info':
                                $item_url = '#' . $act['id'];
                                $toggle_attr = ' data-toggle="modal"';
                                $modal_title = 'Thông tin module';
                                $modal_content = $info_content;
                                $modal_action = '<button type="button" class="btn btn-red" data-dismiss="modal">Đóng</button>';
                                break;
                            case 'readme':
                                $item_url = '#' . $act['id'];
                                $toggle_attr = ' data-toggle="modal"';
                                $modal_title = 'Readme';
                                $modal_content = '<div class="modal-body"><div class="box-content">' . $mod['readme'] . '</div></div>';
                                $modal_action = '<button type="button" class="btn btn-red" data-dismiss="modal">Đã hiểu</button>';
                                break;
                            default:
                                $item_url = $act['full_url'];
                                break;
                        }

                        echo '<li><a href="' . $item_url . '" title="' . $act['title'] . '"' . $toggle_attr . '>' .(!empty($act['icon']) ? '<i class="' . $act['icon'] . '"></i> ' : '') . $act['name'] . '</a></li>';

                        if ($toggle_attr) {
                            $modal .= '<div id="' . $act['id'] . '" class="modal fade">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title">' . $modal_title . '</h4>
                                      </div>
                                        ' . $modal_content . '
                                      <div class="modal-footer">
                                        <form action="' . $act['full_url'] . '" method="POST">
                                            ' . $modal_action . '
                                        </form>
                                      </div>
                                    </div><!-- /.modal-content -->
                                  </div><!-- /.modal-dialog -->
                              </div><!-- /.modal -->';
                        }
                    }
            echo '</ul>';
            echo '</div>';
            echo('</td>
            <td>' . $mod['name'] . '</td>
            <td>' . $mod['url'] . '</td>
            <td>' . $mod['version'] . '</td>
            <td>' . $mod['author'] . '</td>
            <td>' . $mod['des'] . '</td>
            </tr>');
        }
        echo '</table>';
        echo $modal;
        echo('<div class="box-footer">
        <form method="POST">
        <input type="submit" name="reloadAllModule" id="reloadAllModule" value="Reload" class="btn btn-blue rm-fill-up"/>
        </form>
        </div>');
    }, array('after' => $content_after));

}, array('after' => $menu));