<?php
/**
 * name = Cấu hình chính
 * icon = icon-cog
 * position = 1
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
ZenView::set_title('Cấu hình chính');

$model = $obj->model->get('admin');
$parse = load_library('parse');

if (isset($_POST['submit-main'])) {

    if (!$parse->valid_url($_POST['home'])) {
        ZenView::set_error('Địa chỉ trang chủ không chính xác', 'main-config');
    }
    $update['home'] = $_POST['home'];

    if (!strlen($_POST['home'])) {
        ZenView::set_error('Bạn chưa nhập tiêu đề trang', 'main-config');
    }

    $update['title'] = h($_POST['title']);

    $update['keyword'] = h($_POST['keyword']);

    if (strlen($_POST['des']) > 250) {
        ZenView::set_notice('Chiều dài mô tả lớn hơn 250 kí tự không tốt cho seo! (Hiện tại: ' . strlen($_POST['des']) . ' kí tự)<br/>
                Chú ý: Chiều dài mô tả vào khoảng 160-250 kí tự', 'main-config');
    }

    $update['des'] = h($_POST['des']);

    if (ZenView::is_success('main-config')) {
        $obj->config->updateConfig($update);
        ZenView::set_success('Thành công', 'main-config');
        $obj->config->reload();
    }
}

$data['mail_config']['mail_smtp_secure'] = array('tls' => 'TLS', 'ssl' => 'SSL');

if (isset($_POST['submit-mail'])) {

    if (isset($_POST['mail_host']) && strlen($_POST['mail_host']) > 0 && strlen($_POST['mail_host']) <= 255) {
        $update_mail['mail_host'] = $_POST['mail_host'];
    } else ZenView::set_error('Địa chỉ host mail không chính xác', 'mail-config');

    if (isset($_POST['mail_port']) && is_numeric($_POST['mail_port']) && !empty($_POST['mail_port'])) {
        $update_mail['mail_port'] = $_POST['mail_port'];
    } else ZenView::set_error('Cổng không chính xác', 'mail-config');

    if (isset($_POST['mail_smtp_secure']) && ($_POST['mail_smtp_secure'] == 'tls' || $_POST['mail_smtp_secure'] == 'ssl')) {
        $update_mail['mail_smtp_secure'] = $_POST['mail_smtp_secure'];
    } else ZenView::set_error('Không tồn tại phương thức mã hóa này', 'mail-config');

    if (isset($_POST['mail_smtp_auth']) && !empty($_POST['mail_smtp_auth'])) {
        $update_mail['mail_smtp_auth'] = 1;
    } else {
        $update_mail['mail_smtp_auth'] = 0;
    }

    if (isset($_POST['mail_username'])) {
        $update_mail['mail_username'] = $_POST['mail_username'];
    } else {
        $update_mail['mail_username'] = '';
    }

    if (isset($_POST['mail_password'])) {
        $update_mail['mail_password'] = base64_encode($_POST['mail_password']);
    } else {
        $update_mail['mail_password'] = '';
    }

    if (isset($_POST['mail_setfrom']) && $parse->valid_email($_POST['mail_setfrom'])) {
        $update_mail['mail_setfrom'] = $_POST['mail_setfrom'];
    } else {
        ZenView::set_error('Email gửi không chính xác', 'mail-config');
    }

    if (isset($_POST['mail_name'])) {
        $update_mail['mail_name'] = $_POST['mail_name'];
    } else {
        $update_mail['mail_name'] = '';
    }

    if (ZenView::is_success('mail-config')) {
        $obj->config->updateConfig($update_mail);
        if (!empty($update_mail['mail_password'])) {
            $obj->config->updateConfig(array('mail_password'=>$update_mail['mail_password']), 'base64_decode', 'base64_encode');
        }
        ZenView::set_success('Thành công!', 'mail-config');
        $obj->config->reload();
    }
}

$tree[] = url(HOME.'/admin', 'Admin CP');
$tree[] = url(HOME.'/admin/general', 'Tổng quan');
$tree[] = url(HOME.'/admin/general/config', 'Cấu hình chính');
ZenView::set_breadcrumb($tree);

$obj->view->data = $data;
$obj->view->show('admin/general/config');
