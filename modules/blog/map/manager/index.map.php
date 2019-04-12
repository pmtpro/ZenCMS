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
    ZenView::display_message();
    echo '<ul class="action-nav-normal rounded">';
    $page_menu = ZenView::get_menu('page_menu');
    foreach ($page_menu['menu'] as $menu) {
        echo('<li class="action-nav-button">
        <a href="' . $menu['full_url'] . '" class="tip" title="' . $menu['title'] . '" data-original-title="' . $menu['title'] . '">
        <i class="' . $menu['icon'] . '"></i>
        </a>
        </li>');
    }
    echo '</ul>';
    ZenView::block('Thống kê bài viết', function() {
        ZenView::padded(function() {
            ZenView::col(function() {
                ZenView::col_item(4, function() {
                    ZenView::col(function() {
                        ZenView::col_item(12, function() {
                            echo('<div class="dashboard-stats">
                                  <div class="stats-label">Hôm nay đã viết</div>
                                  <ul class="list-inline">
                                    <li class="glyph"><i class="icon-pencil icon-2x"></i></li>
                                    <li class="count">' . ZenView::$D['stat']['number_today_post'] . '</li>
                                  </ul>
                                </div>');
                        });
                    });
                    ZenView::col(function() {
                        ZenView::col_item(6, function() {
                            echo('<div class="dashboard-stats small">
                                      <div class="stats-label">Tổng số bài đã viết</div>
                                      <ul class="list-inline">
                                        <li class="glyph"><i class="icon-pencil icon-2x"></i></li>
                                        <li class="count">' . ZenView::$D['stat']['number_post'] . '</li>
                                      </ul>
                                    </div>');
                        });
                        ZenView::col_item(6, function() {
                            echo('<div class="dashboard-stats small">
                                      <div class="stats-label">Tổng số thư mục</div>
                                      <ul class="list-inline">
                                        <li class="glyph"><i class="icon-folder-close-alt icon-2x"></i></li>
                                        <li class="count">' . ZenView::$D['stat']['number_folder'] . '</li>
                                      </ul>
                                    </div>');
                        });
                    });
                });
                ZenView::col_item(8, function() {
                    echo '<div class="sine-chart" id="example3"></div>';
                });
            });
            $xcharts_data = '';
            foreach(ZenView::$D['stat']['post'] as $date => $num_post) {
                $xcharts_data .= "{
                          \"x\": \"$date\",
                          \"y\": $num_post
                        },\n";
            }
            echo('<script>
                    (function () {
                        var tt = document.createElement("div"),
                      leftOffset = -(~~$("html").css("padding-left").replace("px", "") + ~~$("body").css("margin-left").replace("px", "")),
                      topOffset = -32;
                    tt.className = "ex-tooltip";
                    document.body.appendChild(tt);
                        var data = {
                      "xScale": "ordinal",
                      "yScale": "linear",
                      "yMin": 0,
                      "main": [
                        {
                          "className": ".pizza",
                          "data": [
                            ' . $xcharts_data . '
                          ]
                        }
                      ]
                    };
                    var opts = {
                      "dataFormatX": function (x) { return d3.time.format("%Y-%m-%d").parse(x); },
                      "tickFormatX": function (x) { return d3.time.format("%A")(x); },
                      "mouseover": function (d, i) {
                        var pos = $(this).offset();
                        $(tt).text(d3.time.format("%A")(d.x) + ": " + d.y)
                          .css({top: topOffset + pos.top, left: pos.left + leftOffset})
                          .show();
                      },
                      "mouseout": function (x) {
                        $(tt).hide();
                      }
                    };
                    var myChart = new xChart("line-dotted", data, "#example3", opts);
                    }());
                        </script>
            ');
        });
    });
});