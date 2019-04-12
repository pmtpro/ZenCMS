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

Class sitemapController Extends ZenController
{
    function index() {

    }

    function xml()
    {
        $model = $this->model->get('sitemap');

        $data['path_sitemap_xsl'] = _BASE_TEMPLATE_TPL . '/sitemap/sitemap.xsl';

        $data['last_update'] = $model->get_last_update();

        $data['folders'] = $model->get_folders();

        $data['posts'] = $model->get_posts();

        $this->view->data = $data;

        $this->view->show('sitemap/xml');
    }

}

?>