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

Class searchController Extends ZenController
{

    function index($arg = array()) {
        /**
         * load gadget
         */
        load_helper('gadget');
        /**
         * get blog model
         */
        $model = $this->model->get('search');
        /**
         * Load library
         */
        $security = load_library('security');
        $p = load_library('pagination');
        $seo = load_library('seo');

        ZenView::append_head(gadget_search_push());

        $data['search_pagination'] = '';
        $data['result'] = array();

        ZenView::set_title('Tìm kiếm');
        ZenView::set_breadcrumb(url(HOME . '/search', 'Tìm kiếm'));

        if (empty($arg[0]) || !isset($arg[0])) {
            $this->view->data = $data;
            $this->view->show('search');
            return;
        }

        if (isset($arg[0])) {
            $key = h($security->cleanXSS($arg[0]));
            $key_back = str_replace(array('-', '_'), array(' ', ' '), $key);
            $url = $seo->url($key);
            $data['key'] = $key_back;
            $data['keyword'] = $key_back;
            /**
             * Set page title
             */
            ZenView::set_title('Kết quả cho: ' . $key_back);
            $count_tag = $model->count_tag($url);

            if (empty($count_tag)) {
                $ins_tag['sid'] = 0;
                $ins_tag['url'] = $url;
                $ins_tag['tag'] = $key_back;
                $model->insert_tag($ins_tag);
            }

            if ($model->count_like_tag($key_back)) {
                $data_tag = $model->keyword_like_tag($key_back, 10);
                /**
                 * Set page keyword
                 */
                ZenView::set_keyword(implode(', ', $data_tag));
            }

            $like['name'] = $key_back;
            $like['title'] = $like['name'];
            $like['content'] = $like['name'];
            $like['url'] = $url;
            $count_blog_like_tag = $model->count_blog_like_tag($like);
            if (!$count_blog_like_tag) {
                ZenView::set_notice('Không có kết quả nào phù hợp', 'search-result');
            } else {
                $limit = 10;
                $p->setLimit($limit);
                $p->SetGetPage('page');
                $start = $p->getStart();
                $sql_limit = $start.','.$limit;
                $result = $model->result_blog_like_tag($like, $sql_limit);
                $p->setTotal($count_blog_like_tag);
                ZenView::set_paging($p->navi_page());
                $data['result'] = $result;
            }

            ZenView::set_breadcrumb(url(HOME . '/search-' . $key, $key));
            $this->view->data = $data;
            $this->view->show('search');
        }
    }
}