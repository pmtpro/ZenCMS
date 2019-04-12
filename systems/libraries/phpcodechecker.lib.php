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
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');

Class phpcodechecker
{
    protected $api = 'http://zencms.vn/phpcodechecker.php';
    protected $code = '';
    protected $file;
    protected $error = '';
    protected $checker = false;
    public function set_code($code) {
        $this->code = $code;
    }

    public function set_file($file) {
        $this->file = $file;
        if (file_exists($this->file)) {
            $this->code = file_get_contents($this->file);
        } else {
            $this->set_error('File does not exists!');
        }
    }

    public function checker() {
        return $this->checker;
    }

    public function load_api() {
        if (empty($this->code)) {
            $this->set_error('Code is empty');
            return false;
        } else {
            $response = $this->post_data($this->api, array('php' => urlencode($this->code)));
            if ($response == 'NO ERROR') {
                $this->checker = true;
            } else {
                $this->checker = false;
                $this->set_error($response);
            }
        }
        return true;
    }

    public function post_data($url, $data) {
        $fields = '';
        foreach ($data as $key => $value) {
            $fields .= $key . '=' . $value . '&';
        }
        rtrim($fields, '&');
        $post = curl_init();
        curl_setopt($post, CURLOPT_URL, $url);
        curl_setopt($post, CURLOPT_POST, count($data));
        curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($post);
        curl_close($post);
        return $result;
    }

    public function set_error($msg) {
        $this->error = $msg;
    }

    public function get_error() {
        return $this->error;
    }
}