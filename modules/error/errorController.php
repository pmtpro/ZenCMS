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

Class errorController Extends ZenController
{
    public $msg;

    public function index($request_data = array())
    {
        $num_error = null;
        $e = null;

        if (!empty($request_data)) {
            $num_error = $request_data[0];
            $e = isset($request_data[1]) ? $request_data[1] : '';
        } else {
            $num_error = 404;
        }

        switch ($num_error) {
            case 403:
                $error_name = "Forbidden. You don't have permission to access on this page";
                break;

            case 404:
                $error_name = 'Sorry, This page does not exist';
                break;

            case 405:
                $error_name = 'Sorry, This file has been deleted by the manager';
                break;

            case 503:
                $error_name = 'Access denied';
                break;

            case 500:
                $error_name = 'Server error';
                break;

            case 505:
                $error_name = 'Code error';
                break;

            case 600:
                $error_name = 'Nhập mật khẩu cấp 2';
                break;

            default:
                $error_name = 'Sorry, this application does not exist';
                break;

            case 1000:
                $error_name = 'Template folder does not exists';
                break;

            case 1001:
                $error_name = 'Template file does not exists';
                break;

            case 1005:
                $error_name = 'Map file does not exists';
                break;

            case 2000:
                $error_name = 'Can not find the settings file';
                break;

            case 2001:
                $error_name = 'Class settings does not exists';
                break;

            case 3000:
                $error_name = 'Member does not exists';
                break;
        }

        if(!empty($this->msg)) {
            $error_name = $this->msg;
        }

        if ($num_error == 600) {
            ZenView::set_notice('Bạn cần nhập mật khẩu cấp 2.<br/>Được cung cấp khi bạn cài đặt code hoặc xem trong file <strong>ZenPRIVATE.php</strong>');
            ZenView::set_title($error_name);
            $this->view->show('error/verify_access', array('only_map' => true));
            return;
        }

        if (empty($_SERVER['HTTP_REFERER'])) {
            $url = HOME;
            $msg = 'trang chủ';
        } else {
            $url = $_SERVER['HTTP_REFERER'];
            $msg = 'trang trước';
        }
        //$this->view->data['notices'][] = wait_redirect($url, 'Bạn sẽ được chuyển đến ' . $msg . ' trong vòng {s} nữa', 3);
        ZenView::set_error($error_name);
        ZenView::set_title('Error ' . $num_error);
        $this->view->data['error_name'] = $error_name;
        $this->view->data['error_number'] = $num_error;
        $this->view->show('error', array('only_map' => true));
    }

    public function setSpecialMsg($msg) {

        $this->msg = $msg;
    }
}
