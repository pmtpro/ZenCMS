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

class pagination
{

    var $url;

    var $limit;

    var $total;

    var $maxpage;

    var $pagecurrent;

    var $now_page;

    var $getPage = 0;

    var $start;

    function __construct()
    {
        $this->limit = 0;
        $this->maxpage = 0;
        $this->total = 0;
        $this->pagecurrent = 1;
    }

    public function setGetPage($get)
    {
        if (!isset($_GET[$get])) {

            $_GET[$get] = 0;
        }
        $get = $_GET[$get];

        if (isset($get)) {
            $this->getPage = $get;
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

    public function setLimit($limit = 10)
    {
        $this->limit = $limit;
    }

    public function setTotal($total)
    {
        $this->total = $total;
        $this->setMaxPage();
    }

    public function setMaxPage()
    {
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
            $this->getPage == 1;
        } else {
            $start = ($this->getPage - 1) * $this->limit;
        }
        $this->start = $start;
        return $start;
    }

    public function getMaxPage()
    {
        return $this->maxpage;
    }

    public function navi_page($url = '?page=', $sts = 5)
    {
        if (!$url) {
            $url = '?page=';
        }

        $this->url = $url;

        $old_sts = $sts;

        $sts = round($sts / 2);

        if ($this->maxpage == 1) {

            return;
        }

        if ($this->now_page <= $sts) {

            $start = 1;
            $end = $sts * 2;
            $prew_fast = '';
            $next_fast = $end + 1;
            $next_fast = "<span class=\"page nextFastPage\"><a href='" . $this->createUrl($next_fast) . "' title='Trang " . $next_fast . "'>Sau</a></span>";

        } else {

            $start = $this->now_page - $sts;
            $end = $this->now_page + $sts;
            $prew_fast = $start - 1;
            $next_fast = $end + 1;
            if ($prew_fast == 0) {
                $prew_fast = 1;
            }

            $prew_fast = "<span class=\"page prewFastPage\"><a href='" . $this->createUrl($prew_fast) . "' title='Trang " . $prew_fast . "'>Trước</a></span>";
            $next_fast = "<span class=\"page nextFastPage\"><a href='" . $this->createUrl($next_fast) . "' title='Trang " . $next_fast . "'>Sau</a></span>";
        }

        if ($end >= $this->maxpage) {

            $end = $this->maxpage;
            $next_fast = '';
        }

        $navi = '<div class="list_page">';

        if (!empty($prew_fast)) {

            $navi .= "<span class=\"page fistPage\"><a href='" . $this->createUrl(1) . "' title='Trang 1'>Đầu</a></span>" . $prew_fast;
        }

        for ($t = $start; $t <= $end; $t++) {

            if ($t == $this->now_page) {

                $navi .= '<span class="page currentPage"><b>' . $t . '</b></span>';

            } else {

                $navi .= "<span class=\"page\"><a href='" . $this->createUrl($t) . "'>" . $t . "</a></span>";
            }
        }
        $navi .= $next_fast . "<span class=\"page endPage\"><a href='" . $this->createUrl($this->maxpage) . "' title='Trang " . $this->maxpage . "'>Cuối</a></span>";

        $navi .= "</div>";

        return $navi;
    }

    private function createUrl($num)
    {

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

        } else {
            $url = $url . $num;
        }

        $base = _HOME . '/' . ROUTER_BEFORE_REWRITE;

        if (ROUTER_BEFORE_REWRITE == '') {

            $base = _HOME;
        }

        return $base . $url;
    }
}

?>