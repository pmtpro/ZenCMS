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

Class ZenTemplate
{
    public $template_config = array();
    private $template = '';
    public static $instance;
    public static $registry;

    function __construct($registry)
    {
        self::$registry = $registry;
    }

    /**
     *
     * @param object $registry
     * @return object
     */
    public static function getInstance($registry)
    {

        self::$registry = $registry;
        if (!self::$instance) {
            self::$instance = new ZenTemplate($registry);
        }
        return self::$instance;
    }

    function setTempDir()
    {

    }

    /**
     *
     * @param string $temp
     */
    function setTemp($temp)
    {

        if (!is_dir(__TEMPLATES_PATH . '/' . $temp)) {

            exit('Template does not exists!');
        } else {

            $this->template = $temp;
        }
    }

    /**
     * Load template
     */
    function loader()
    {
        global $registry, $template_config;

        $registry = self::$registry;

        if (isset($this->template)) {

            $temp = $this->template;

            $temp_path_config = __TEMPLATES_PATH . '/' . $temp . '/config.php';
            $temp_path_run = __TEMPLATES_PATH . '/' . $temp . '/run.php';

            if (file_exists($temp_path_config) && is_readable($temp_path_config)) {

                /*                 * * include the config template file ** */
                include_once $temp_path_config;

                $this->template_config = $template_config;
            }

            if (file_exists($temp_path_run) && is_readable($temp_path_run)) {

                /* * * include the config template file ** */
                include_once $temp_path_run;
            }
        }
    }
}

?>