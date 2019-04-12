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
if (!defined('__ZEN_KEY_ACCESS'))
    exit('No direct script access allowed');

ZenView::set_title('Quản lí blog');

/**
 * get blog model
 */
$model = $obj->model->get('blog');
/**
 * load time helper
 */
load_helper('time');

$data['stat']['number_post'] = $model->count(0, array('type' => 'post'));
$data['stat']['number_folder'] = $model->count(0, array('type' => 'folder'));

$today = strtotime(get_date(time(), array('date_format' => 'd-m-Y')));
$timeline = $today;
$i = 1;
$between = array();
while ($i <= 7) {
    $i++;
    $start_day = $timeline;
    $end_day = $start_day + 86400;
    $between[] = array($start_day, $end_day);
    $data['stat']['post'][get_date($start_day, array('date_format' => 'Y,m,d'))] = $model->count(0, array('type' => 'post', 'where' => "`time` BETWEEN '$start_day' and $end_day"));
    $timeline -= 86400;
}
asort($data['stat']['post']);
$data['stat']['number_today_post'] = $model->count(0, array('type' => 'post', 'where' => "`time` > $today"));

ZenView::add_js('http://code.highcharts.com/highcharts.js', 'foot');
$charts_data_arr = array();
$count_day = count($data['stat']['post']);
$last_day = '';
$i = 0;
foreach ($data['stat']['post'] as $date => $num_post) {
    $i++;
    if ($i == 1) {
        $last_day = $date;
    }
    $charts_data_arr[] = $num_post;
}
$charts_data = '[' . implode(',', $charts_data_arr) . ']';

if ($last_day)
    ZenView::append_foot("<script>
$(function () {
    $('#post-stat-chart').highcharts({
        chart: {type: 'column'},
        title: {text: 'Thống kê bài viết theo ngày'},
        xAxis: {
            type: 'datetime',
            dateTimeLabelFormats: {day: '%e of %b'}
        },
        yAxis: {
            min: 0,
            title: {text: 'Số lượng (bài)'}
        },
        tooltip: {
            headerFormat: '<span style=\"font-size:10px\">{point.key}</span><table>',
            pointFormat: '<tr><td style=\"color:{series.color};padding:0\">{series.name}: </td>' +
                '<td style=\"padding:0\"><b>{point.y} bài</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Số lượng bài viết',
            data: " . $charts_data . ",
            pointStart: Date.UTC(" . $last_day . "),
            pointInterval: 24 * 3600 * 1000 // one day
        }]
    });
    });
</script>");
$obj->view->data = $data;
$obj->view->show('blog/manager/' . $app[0]);
