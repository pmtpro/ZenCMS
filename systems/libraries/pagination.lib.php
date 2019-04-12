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

class pagination
{

    var $url;
    var $url_arg;
    var $limit;
    var $total;
    var $maxpage;
    var $pagecurrent;
    var $now_page;
    var $getPage = 0;
    var $start;
    public $tempOBJ;

    function __construct() {
        global $registry;
        $this->limit = 0;
        $this->maxpage = 0;
        $this->total = 0;
        $this->pagecurrent = 1;
        $this->tempOBJ = $registry->templateOBJ;
    }

    public function setGetPage($get) {
        if (!isset($_GET[$get])) {
            $_GET[$get] = 0;
        }
        $_GET[$get] = (int)$_GET[$get];
        if (isset($_GET[$get])) {
            $this->getPage = $_GET[$get];
        } else {
            if (isset($_GET['page'])) {
                $this->getPage = $_GET['page'];
            }
        }
        if (empty($this->getPage)) {
            $page = 1;
        } else {
            $page = $this->getPage;
        }
        if ($page <= 0) {
            $page = 1;
        }
        $this->now_page = $page;
    }

    public function setLimit($limit = 10) {
        $this->limit = $limit;
    }

    public function setTotal($total) {
        $this->total = $total;
        $this->setMaxPage();
    }

    public function setMaxPage() {
        if ($this->total <= $this->limit) {
            $this->maxpage = 1;
        } else {
            if ($this->total % $this->limit != 0) {
                $this->maxpage = ceil($this->total / $this->limit);
            } else {
                $this->maxpage = $this->total / $this->limit;
            }
        }
    }

    function getStart()
    {
        if (empty($this->getPage) || ($this->getPage == "1")) {
            $start = 0;
            $this->getPage = 1;
        } else {
            $start = ($this->getPage - 1) * $this->limit;
        }
        $this->start = $start;
        return (int)$start;
    }

    public function getMaxPage() {
        return $this->maxpage;
    }

    public function navi_page($url = 'page=', $sts = 5)
    {
        global $registry;
        $tplOBJ = $registry->templateOBJ;
        $map = $tplOBJ->getMap('pagination');

        if (!$url) {
            $url = 'page=';
        }
        $this->url_arg = $url;
        //$this->url = add_arg2url(curPageURL(), $url);
        $this->url = curPageURL();
        $old_sts = $sts;
        $sts = round($sts / 2);
        if ($this->maxpage == 1) {
            return '';
        }
        if ($this->now_page <= $sts) {
            $start = 1;
            $end = $sts * 2;
            $prew_fast = '';
            $next_fast = $end + 1;
            $next_fast = sprintf($map['item'], $this->createUrl($next_fast), "" . $next_fast . "", 'Trang ' . $next_fast, 'next');
        } else {
            $start = $this->now_page - $sts;
            $end = $this->now_page + $sts;
            $prew_fast = $start - 1;
            $next_fast = $end + 1;
            if ($prew_fast == 0) {
                $prew_fast = 1;
            }
            $prew_fast = sprintf($map['item'], $this->createUrl($prew_fast), "Trước", 'Trang ' . $prew_fast, 'previous');
            $next_fast = sprintf($map['item'], $this->createUrl($next_fast), "Sau", 'Trang ' . $next_fast, 'next');
        }
        if ($end >= $this->maxpage) {
            $end = $this->maxpage;
            $next_fast = '';
        }
        $navi = $map['start'];
        if (!empty($prew_fast)) {
            $navi .= sprintf($map['item'], $this->createUrl(1), 'Đầu', 'Trang 1', '') . $prew_fast;
        }
        for ($t = $start; $t <= $end; $t++) {
            if ($t == $this->now_page) {
                $navi .= sprintf($map['item'], $this->createUrl($t), "" .$t . "", 'Trang ' . $t, $map['status']['active']);
            } else {
                $navi .= sprintf($map['item'], $this->createUrl($t), "" .$t  . "", 'Trang ' . $t, "");
            }
        }
        $navi .= $next_fast . sprintf($map['item'], $this->createUrl($this->maxpage), 'Cuối', 'Trang cuối', 'last');
        $navi .= $map['end'];
        return $navi;
    }

    private function createUrl($num) {
        /*
        $url = $this->url;
        $match = array();
        $patt = '/\{([a-zA-Z0-9_\-]+)\}/is';
        if (preg_match($patt, $url)) {
            preg_match_all($patt, $url, $match);
            if (isset($match[1][0])) {
                $name = $match[1][0];
            }
            if (!empty($name)) {
                $url = preg_replace($patt, $num, $url);
            } else {
                $url = $url . $num;
            }
        } else $url = $url . $num;
        $base = HOME . '/' . ROUTER_BEFORE_REWRITE;
        if (ROUTER_BEFORE_REWRITE == '') {
            $base = HOME;
        }
        return $base . $url;
        */
        return add_arg2url($this->url, $this->url_arg . $num);
        //return $this->url . $num;
    }
}