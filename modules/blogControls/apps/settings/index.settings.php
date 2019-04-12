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
defined('__ZEN_KEY_ACCESS') or die('No direct script access allowed');

/**
 * load library
 */
$security = load_library('security');

$tree[] = url(HOME . '', 'Setting blog controls');
ZenView::set_breadcrumb($tree);
$moduleConfig = $obj->config->getModuleConfig('blogControls');
if (isset($_POST['submit'])) {
    $moduleConfig['turn_on_import'] = isset($_POST['turn_on_import']) ? 1 : 0;
    $moduleConfig['import_local'] = isset($_POST['import_local']) ? 1 : 0;
    $moduleConfig['turn_on_watermark'] = isset($_POST['turn_on_watermark']) ? 1 : 0;
    $moduleConfig['turn_on_auto_gen_desc'] = isset($_POST['turn_on_auto_gen_desc']) ? 1 : 0;
    if ($obj->config->updateModuleConfig('blogControls', $moduleConfig)) {
        ZenView::set_success(1);
        $obj->config->reload();
    } else ZenView::set_error('Lỗi dữ liệu');
}
if (isset($_POST['submit-watermark'])) {
    $moduleConfig = array();
    $moduleConfig['text_watermark'] = h($security->cleanXSS($_POST['text_watermark']));
    if ($obj->config->updateModuleConfig('blogControls', $moduleConfig)) {
        ZenView::set_success(1);
        $obj->config->reload();
    } else ZenView::set_error('Lỗi dữ liệu');
}

if (isset($_POST['submit-desc'])) {
    $moduleConfig = array();
    $moduleConfig['num_word_desc_auto_cut'] = (int) $security->removeSQLI($_POST['num_word_desc_auto_cut']);
    if ($obj->config->updateModuleConfig('blogControls', $moduleConfig)) {
        ZenView::set_success(1);
        $obj->config->reload();
    } else ZenView::set_error('Lỗi dữ liệu');
}

$data['config'] = $obj->config->getModuleConfig('blogControls');
ZenView::set_title('Cài đặt blog');
$obj->view->data = $data;
$obj->view->show('blogControls/settings');