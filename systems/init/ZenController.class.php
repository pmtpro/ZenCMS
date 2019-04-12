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

Abstract Class ZenController
{
    /*
     * @registry object
     */

    public $registry;
    public $model;
    public $view;
    public $hook;
    public $config;
    public $settings;
    public $user;
    public $db;
    public $temp;

    /**
     *
     * @param object $registry
     */
    function __construct($registry)
    {

        $this->model = & ZenModel::getInstance($registry);
        $registry->model = $this->model;
        $this->temp = & Zentemplate::getInstance($registry);
        $registry->temp = $this->temp;
        $this->view = & ZenView::getInstance($registry);
        $registry->view = $this->view;
        $this->config = & ZenConfig::getInstance($registry);
        $registry->config = $this->config;
        $this->hook = & ZenHook::getInstance($registry);
        $registry->hook = $this->hook;
        $this->settings = & ZenSettings::getInstance();
        $registry->settings = $this->settings;
        $this->registry = $registry;
        $this->user = $registry->user;
    }

    /**
     * @all controllers must contain an index method
     */
}

?>
