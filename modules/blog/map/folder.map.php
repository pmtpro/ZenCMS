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
ZenView::section('Chuyên mục', function() {
    ZenView::block(ZenView::$D['blog']['name'], function() {
        ZenView::display_breadcrumb();
        ZenView::display_message();
        echo '<ul class="list-grid">';
        foreach (ZenView::$D['list']['posts'] as $item) {
            echo '<li class="col-xs-6 col-sm-3 col-md-2">
                <span class="grid-item">
                  <a href="' . $item['full_url'] . '">
                    <span class="icon">
                      <img class="img-responsive" src="' . $item['full_icon'] . '" alt="' . $item['name'] . '">
                    </span>
                  </a>
                  <span class="info">
                    <span class="title">
                      <a href="' . $item['full_url'] . '" title="' . $item['title'] . '">' . $item['name'] . '</a>
                    </span>
                  </span>
                  <span class="bottom">
                    <i class="glyphicon glyphicon-eye-open"></i>&nbsp;<span title="Lượt tải">' . $item['view'] . '</span>
                  </span>
                </span>
              </li>';
        }
        echo '</ul>';
        ZenView::display_paging('post');
    });
    ZenView::block('Bài viết ngẫu nhiên', function() {
        ZenView::display_breadcrumb();
        ZenView::display_message();
        echo '<ul class="list-grid">';
        foreach (ZenView::$D['list']['rand_posts'] as $item) {
            echo '<li class="col-xs-6 col-sm-3 col-md-2">
                <span class="grid-item">
                  <a href="' . $item['full_url'] . '">
                    <span class="icon">
                      <img class="img-responsive" src="' . $item['full_icon'] . '" alt="' . $item['name'] . '">
                    </span>
                  </a>
                  <span class="info">
                    <span class="title">
                      <a href="' . $item['full_url'] . '" title="' . $item['title'] . '">' . $item['name'] . '</a>
                    </span>
                  </span>
                  <span class="bottom">
                    <i class="glyphicon glyphicon-eye-open"></i>&nbsp;<span title="Lượt tải">' . $item['view'] . '</span>
                  </span>
                </span>
              </li>';
        }
        echo '</ul>';
    });
});