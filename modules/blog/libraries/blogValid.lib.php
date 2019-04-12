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

Class blogValid
{
    public $error;
    public $pattern;

    public function __construct()
    {
        $home = str_replace('/', '\/', HOME);
        $this->pattern = "/^$home\/(.*)-([0-9]+)\.html$/is";
    }

    public function is_url_blog($url = '')
    {
        if (empty($url)) {
            return false;
        }
        if (preg_match($this->pattern, $url)) {
            return true;
        }
        return false;
    }

    public function preg_match_url($url = '')
    {
        if (!$this->is_url_blog($url)) {
            return false;
        }
        preg_match_all($this->pattern, $url, $matchs);
        if (isset($matchs[2][0])) {
            $sid = $matchs[2][0];
        } else {
            $sid = 0;
        }
        return $sid;
    }
}