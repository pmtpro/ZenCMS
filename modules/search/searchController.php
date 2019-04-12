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

Class searchController Extends ZenController
{

    function index($arg = array())
    {

        load_helper('gadget');
        $model = $this->model->get('search');
        $security = load_library('security');
        $p = load_library('pagination');
        $seo = load_library('seo');

        $data['page_more'] = gadget_search_push();
        $data['search_pagination'] = '';
        $data['result'] = array();

        $data['page_title'] = 'Tìm kiếm';
        $tree[] = url(_HOME . '/search', 'Tìm kiếm');
        $data['display_tree'] = display_tree($tree);

        if (empty($arg[0]) || !isset($arg[0])) {

            $this->view->data = $data;
            $this->view->show('search');
            return;
        }

        if (isset($arg[0])) {

            $key = $security->cleanXSS($arg[0]);

            $key_back = str_replace(array('-', '_'), array(' ', ' '), $key);
            
            $url = $seo->url($key);

            $data['page_title'] = $key_back;

            $count_tag = $model->count_tag($url);

            if (empty($count_tag)) {

                $ins_tag['sid'] = 0;
                $ins_tag['url'] = $url;
                $ins_tag['tag'] = $key_back;

                $model->insert_tag($ins_tag);
            }

            if ($model->count_like_tag($key_back)) {

                $data_tag = $model->keyword_like_tag($key_back, 10);

                $data['page_keyword'] = implode(', ', $data_tag);

            }

            $like['name'] = $key_back;
            $like['title'] = $like['name'];
            $like['content'] = $like['name'];
            $like['url'] = $url;

            $count_blog_like_tag = $model->count_blog_like_tag($like);

            if (!$count_blog_like_tag) {

                $data['notices'][] = 'Không có kết quả nào phù hợp';

            } else {

                $limit = 10;
                $p->setLimit($limit);
                $p->SetGetPage('page');
                $start = $p->getStart();
                $sql_limit = $start.','.$limit;

                $result = $model->result_blog_like_tag($like, $sql_limit);

                $p->setTotal($count_blog_like_tag);

                $data['search_pagination'] = $p->navi_page();

                $data['result'] = $result;
            }

            $tree[] = url(_HOME . '/search-' . $key, $key);
            $data['display_tree'] = display_tree($tree);

            $this->view->data = $data;
            $this->view->show('search');
        }
    }

}

?>