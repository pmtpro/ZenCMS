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

Class blogValid
{

    public $error;
    public $pattern;

    public function __construct()
    {

        $home = str_replace('/', '\/', _HOME);

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

?>