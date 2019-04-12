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
ZenView::section('Trang chủ', function() {
    ZenView::block('Top mới nhất', function() {
        echo '<ul class="list-grid">';
        foreach (ZenView::$D['list']['new'] as $new) {
            echo '<li class="col-xs-6 col-sm-3 col-md-2">
                <span class="grid-item">
                  <a href="' . $new['full_url'] . '">
                    <span class="icon">
                      <img class="img-responsive" src="' . $new['full_icon'] . '" alt="' . $new['name'] . '">
                    </span>
                  </a>
                  <span class="info">
                    <span class="title">
                      <a href="' . $new['full_url'] . '" title="' . $new['title'] . '">' . $new['name'] . '</a>
                    </span>
                  </span>
                  <span class="bottom">
                    <i class="glyphicon glyphicon-eye-open"></i>&nbsp;<span title="Lượt tải">' . $new['view'] . '</span>
                  </span>
                </span>
              </li>';
        }
        echo '</ul>';
    });
    $list_id = tplConfig('list_blog_cat_display');
    foreach ($list_id as $catID) {
        $list = model('blog')->get_list_blog($catID, array('get' => 'url, name, title, time, view, icon', 'both_child' => false));
        if (!empty($list)) {
            $catData = model('blog')->get_blog_data($catID);
            ZenView::block('<a href="' . $catData['full_url'] . '" title="' . $catData['title'] . '">' . $catData['name'] . '</a>', function() use ($list) {
                echo '<ul class="list-grid">';
                foreach ($list as $item) {
                    echo '<li class="col-xs-6 col-sm-3 col-md-2">
                    <span class="grid-item">
                      <a href="' . $item['full_url'] . '" title="' . $item['title'] . '">
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
        }
    }
});