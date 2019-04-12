<?php
/**
 * ZenCMS Software
 * Author: ZenThang
 * Email: thangangle@yahoo.com
 * Website: http://zencms.vn or http://zenthang.com
 * License: http://zencms.vn/license or read more license.txt
 * Copyright: (C) 2012 - 2013 ZenCMS
 * All Rights Reserved.
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

            case 505:
                $error_name = 'Access denied';
                break;

            case 500:
                $error_name = 'Server error';
                break;

            case 505:
                $error_name = 'Code error';
                break;

            case 600:
                $error_name = 'You need verify access';
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

            $this->view->data['notices'][] = 'Bạn cần nhập mã xác thực truy cập (Được cung cấp khi bạn cài đặt code hoặc xem trong file ZenPRIVATE.php)';
            $this->view->data['page_title'] = $error_name;
            $this->view->show('error/verify_access');
            return;
        }

        if (empty($_SERVER['HTTP_REFERER'])) {
            $url = _HOME;
            $msg = 'trang chủ';
        } else {
            $url = $_SERVER['HTTP_REFERER'];
            $msg = 'trang trước';
        }
        //$this->view->data['notices'][] = wait_redirect($url, 'Bạn sẽ được chuyển đến ' . $msg . ' trong vòng {s} nữa', 3);
        $this->view->data['errors'][] = $error_name;
        $this->view->data['page_title'] = 'Error ' . $num_error;
        $this->view->show('error');
    }

    public function setSpecialMsg($msg) {

        $this->msg = $msg;
    }

}
