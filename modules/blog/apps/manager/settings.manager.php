<?php
/**
 * name = Cài đặt
 * icon = icon-cog
 * position = 280
 */
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

if (isset($_POST['submit-setting'])) {
    if ($_POST['allow_post_comment']) {
        $update['allow_post_comment'] = 1;
    } else $update['allow_post_comment'] = 0;
    if ($obj->config->updateModuleConfig('blog', $update)) {
        ZenView::set_success(1);
        $obj->config->reload();
    } else {
        ZenView::set_error('Lỗi dữ liệu');
    }
}
/**
 * get module config from database
 */
$registerConfig = $obj->config->getModuleConfig('blog');
$data['config'] = $registerConfig;

ZenView::set_title('Cài đặt blog');
$obj->view->data = $data;
$obj->view->show('blog/manager/settings');