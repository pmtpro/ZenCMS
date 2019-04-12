<?php
/**
 * name = Cấu hình chính
 * icon = fa fa-cog
 * position = 1
 */
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
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');
ZenView::set_title('Cấu hình chính');

$model = $obj->model->get('admin');
$parse = load_library('parse');
$valid = load_library('validation');
$security = load_library('security');
load_helper('time');

if (isset($_POST['submit-main'])) {

    if (!$valid->isValid('url', $_POST['home'])) {
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

    if (!empty($_POST['image']) && !$valid->isValid('url', $_POST['image'])) {
        ZenView::set_error('Corver phải là một url hình ảnh', 'main-config');
    } else {
        $update['image'] = h($_POST['image']);
    }

    if (ZenView::is_success('main-config')) {
        $obj->config->updateConfig($update);
        ZenView::set_success('Thành công', 'main-config');
        $obj->config->reload();
    }
}

if (isset($_POST['submit-account-sync'])) {
    /**
     * set domain request
     */
    $domain_request = 'http://zencms.vn';
    $security = load_library('security');
    $username = $security->cleanXSS($_POST['zen-username']);
    $password = $security->cleanXSS($_POST['zen-password']);
    $password_md5 = md5(md5(md5(md5($password))));
    $response_username = $model->get_page_content($domain_request . '/accountAPI/encryptRequest&_s_=' . $username);
    $response_password = $model->get_page_content($domain_request . '/accountAPI/encryptRequest&_s_=' . $password_md5);
    $username_encrypted = json_decode($response_username);
    $password_encrypted = json_decode($response_password);
    if (empty($username_encrypted) || empty($password_encrypted)) {
        ZenView::set_error('Không thể mã hóa dữ liệu', 'account-sync-config');
    } else {
        $url_get_token = $domain_request . '/accountAPI/authorizedLogin?_t1_=' . urlencode($username_encrypted) . '&_t2_=' . urlencode($password_encrypted);
        $token = json_decode($model->get_page_content($url_get_token));
        if ($token) {
            $url_check_token = $domain_request . '/accountAPI/authorizedToken?_token_=' . urlencode($token);
            $checkToken = json_decode($model->get_page_content($url_check_token));
            if (isset($checkToken->status) && $checkToken->status == 0) {
                $updateSync['zencmsvnSync-user'] = serialize(array(
                    'token' => $token,
                    'username' => $username,
                    'nickname' => $checkToken->data->user->nickname,
                    'full_avatar' => $checkToken->data->user->full_avatar,
                    'time_connect' => time()
                ));
                if ($obj->config->updateConfig($updateSync, 'unserialize', 'serialize')) {
                    ZenView::set_success('Đăng nhập thành công', 'account-sync-config', true);
                } else {
                    ZenView::set_error('Lỗi dữ liệu, vui lòng thử lại', 'account-sync-config', true);
                }
            } else {
                ZenView::set_error('Đăng nhập thất bại, vui lòng thử lại', 'account-sync-config');
            }
        } else ZenView::set_error('Đăng nhập thất bại, vui lòng thử lại', 'account-sync-config');
    }
}

if (isset($_POST['submit-cancel-account-sync'])) {
    $updateSync['zencmsvnSync-user'] = '';
    if ($obj->config->updateConfig($updateSync)) {
        ZenView::set_success('Đã ngắt kết nối', 'account-sync-config', true);
    } else {
        ZenView::set_error('Lỗi dữ liệu, vui lòng thử lại', 'account-sync-config', true);
    }
}

$data['mail_config']['mail_smtp_secure'] = array('none' => 'Không mã hóa', 'tls' => 'TLS', 'ssl' => 'SSL');

if (isset($_POST['submit-mail'])) {

    if (empty($_POST['mail_type'])) $_POST['mail_type'] = 'php_mail';
    if (!in_array($_POST['mail_type'], array('php_mail', 'smtp'))) {
        ZenView::set_error('Không tồn tại kiểu gửi mail này', 'mail-config');
    } else {
        $update_mail['mail_type'] = $_POST['mail_type'];
    }

    if (isset($_POST['mail_host']) && strlen($_POST['mail_host']) > 0 && strlen($_POST['mail_host']) <= 255) {
        $update_mail['mail_host'] = $_POST['mail_host'];
    } else ZenView::set_error('Địa chỉ host mail không chính xác', 'mail-config');

    if (isset($_POST['mail_port']) && is_numeric($_POST['mail_port']) && !empty($_POST['mail_port'])) {
        $update_mail['mail_port'] = $_POST['mail_port'];
    } else ZenView::set_error('Cổng không chính xác', 'mail-config');

    if (isset($_POST['mail_smtp_secure']) && ($_POST['mail_smtp_secure'] == 'tls' || $_POST['mail_smtp_secure'] == 'ssl' || $_POST['mail_smtp_secure'] == 'none')) {
        $update_mail['mail_smtp_secure'] = $_POST['mail_smtp_secure'];
        if ($_POST['mail_smtp_secure'] == 'none') {
            $update_mail['mail_smtp_secure'] = '';
        }
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

if (isset($_POST['submit-test-mail'])) {
    if (!$valid->isValid('email', $_POST['test_mail_to'])) {
        ZenView::set_error('Định dạng email không hợp lệ', 'mail-config');
    } else {
        $send = send_mail($_POST['test_mail_to'], 'ZenCMS - Test mail', '
        Mail này được gửi từ website ' . HOME . ' để kiểm tra quá trình gửi mail của hệ thống.<br/>
        Nếu bạn nhận được mail này có nghĩa là quá trình gửi mail đã hoàn thành. Nếu không nhận được, bạn vui lòng kiểm tra lại cấu hình mail của mình.
        ');
        if ($send) {
            ZenView::set_success('Đã hoàn thành gửi mail. Vui lòng check mail để kiểm tra!', 'mail-config');
        } else {
            ZenView::set_error('Không thể gửi mail, vui lòng kiểm tra lại', 'mail-config');
            ZenView::set_error(get_global_msg('send_mail'), 'mail-config');

        }
    }
}

if (isset($_POST['submit-global-mail'])) {
    $content = h($security->cleanXSS($_POST['global_mail_content']));
    if ($obj->config->updateConfig(array('global_mail_content'=>$content))) {
        ZenView::set_success(1, 'global-mail-config', true);
    } else {
        ZenView::set_error('Lỗi dữ liệu. Vui lòng thử lại!', 'global-mail-config');
    }
}

$data['date_format'] = array(
    'm-d-Y'  => '07-26-2014',
    'd-m-Y'  => '26-07-2014',
    'm/d/Y'  => '07/26/2014',
    'd/m/Y'  => '26/07/2014',
    'M dS, Y' => 'July 26th, 2014',
    'M-d-Y'   => 'July-26-2014'
);

$data['time_format'] = array(
    'H:i'       =>  '16:35',
    'H:i:s'     =>  '16:35',
    'h a'       =>  '04 pm',
    'h:i a'     =>  '04:35 pm',
    'h:i:s a'   =>  '04:35:37 pm'
);

if (isset($_POST['submit-system-config'])) {
    $timezone_identifier = isset($_POST['timezone_identifier']) ? $_POST['timezone_identifier'] : 'Asia/Ho_Chi_Minh';
    $date_format = isset($_POST['date_format']) ? $_POST['date_format'] : '';
    $time_format = isset($_POST['time_format']) ? $_POST['time_format'] : '';
    $fail = false;
    $tzList = tz_list();
    $found = false;
    foreach ($tzList as $z) {
        if ($z['zone'] == $timezone_identifier) {
            $found = true;
            break;
        }
    }
    if (!$found) {
        ZenView::set_error('Định dạng múi giờ không đúng', 'system-config');
        $fail = true;
    }
    if (!in_array($date_format, array_keys($data['date_format']))) {
        ZenView::set_error('Không tồn tại định dạng ngày tháng này', 'system-config');
        $fail = true;
    }
    if (!in_array($time_format, array_keys($data['time_format']))) {
        ZenView::set_error('Không tồn tại định dạng giờ này', 'system-config');
        $fail = true;
    }
    if (!$fail) {
        load_helper('fhandle');
        if (write_user_config(array(
            array(
                'key'   => 'timezone_identifier',
                'value' => $timezone_identifier
            ),
            array(
                'key'   => 'date_format',
                'value' => $date_format
            ),
            array(
                'key'   => 'time_format',
                'value' => $time_format
            )
        ))) {
            ZenView::set_success(1, 'system-config', true);
        } else {
            ZenView::set_error(get_global_msg('write_user_config'), 'system-config', true);
        }
    }
}

$tree[] = url(HOME.'/admin', 'Admin CP');
$tree[] = url(HOME.'/admin/general', 'Tổng quan');
$tree[] = url(HOME.'/admin/general/config', 'Cấu hình chính');
ZenView::set_breadcrumb($tree);

$obj->view->data = $data;
$obj->view->show('admin/general/config');
