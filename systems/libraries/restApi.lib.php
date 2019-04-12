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

Class restApi
{
    public $api;
    public $result;
    public $error;
    public $status;

    public function rest() {
        if (function_exists('curl_init')) {
            $curl = curl_init($this->api);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLINFO_HEADER_OUT, 1);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST | CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            $this->result = curl_exec($curl);
            $this->status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $this->error = curl_error($curl);
        } else {
            $this->result = file_get_contents($this->api);
        }
    }
    public function set_api($api) {
        $this->api = $api;
    }

    public function get_result() {
        return $this->result;
    }

    public function get_status() {
        return $this->status;
    }

    public function get_error() {
        return $this->error;
    }
}