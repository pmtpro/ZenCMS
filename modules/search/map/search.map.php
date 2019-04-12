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
ZenView::section('Tìm kiếm', function() {
    ZenView::block('Tìm kiếm blog', function() {
        ZenView::display_breadcrumb();
        ZenView::padded(function() {
            ZenView::display_message();
            echo '<form method="POST" name="SearchPushUp">
            <div class="form-group">
            <label for="search-key">Nhập từ cần tìm</label>
            <input type="text" class="form-control" id="search-key" name="key" placeholder="Nhập từ cần tìm"/>
            </div>
            <input type="submit" class="btn btn-primary" name="submit-search" value="Tìm kiếm"/>
            </form>';
            ZenView::display_message('search-result');
            if (!empty(ZenView::$D['result'])){
                echo '<div class="search-result">
                    <h1 class="sub_title border_orange">Kết quả cho ' . ZenView::$D['key'] . '</h1>';
                    foreach (ZenView::$D['result'] as $s){
                        echo '<div class="feed_rc_channel_item">
                        <ul class="list-grid">
                          <li>
                            <a href="' . $s['full_url'] . '" title="' . $s['title'] . '">
                              <img src="' . $s['full_icon'] . '" class="icon_img" alt="' . $s['title'] . '">
                            </a>
                          </li>
                          <li style="width:100%;">
                            <a href="' . $s['full_url'] . '" title="' . $s['title'] . '">
                              <div class="title">' . $s['name'] . '</div>
                            </a>
                            <div class="subtitle">
                              <i class="glyphicon glyphicon-eye-open"></i>  ' . $s['view'] . ' Xem
                            </div>
                          </li>
                        </ul>
                      </div>';
                    }

                echo '</div>';
                ZenView::display_paging();
            }
        });
    });
});
