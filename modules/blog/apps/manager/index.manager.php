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

$today = strtotime(get_date());
$timeline = $today;
$i = 1;
$between = array();
while ($i <= 7) {
    $i++;
    $start_day = $timeline;
    $end_day = $start_day + 86400;
    $between[] = array($start_day, $end_day);
    $data['stat']['post'][get_date($start_day, array('date_format' => 'Y-m-d'))] = $model->count(0, array('type' => 'post', 'where' => "`time` BETWEEN '$start_day' and $end_day"));
    $timeline -= 86400;
}

$data['stat']['number_today_post'] = $model->count(0, array('type' => 'post', 'where' => "`time` > $today"));

$obj->view->data = $data;
$obj->view->show('blog/manager/' . $app[0]);
