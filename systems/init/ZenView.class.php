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

Class ZenView
{
    /*
     * @Variables array
     * @access public
     */

    public $data = array();
    private static $instance;
    private static $current_template;
    private $registry;

    /**
     * @param $registry
     */
    function __construct($registry)
    {

        $this->registry = $registry;

        self::$current_template = $registry->template;
    }

    /**
     * @param $registry
     * @return ZenView
     */
    public static function getInstance($registry)
    {

        if (!self::$instance) {

            self::$instance = new ZenView($registry);
        }
        return self::$instance;
    }

    /**
     *
     * @set undefined vars
     *
     * @param string $index
     *
     * @param mixed $value
     *
     * @return void
     *
     */
    public function __set($index, $value)
    {
        $this->vars[$index] = $value;
    }

    /**
     *
     * @param string $name
     */
    function show($name)
    {
        global $registry_data, $registry;

        $registry_data = array();

        $tempname = self::$current_template;

        /**
         * set template directory
         */
        $template_dir = __TEMPLATES_PATH . '/' . $tempname;

        /**
         * set tpl directory
         */
        $template_tpl = $template_dir . '/' . __FOLDER_TPL_NAME;

        /**
         * check if module has own template
         */
        $getsetting = $registry->settings->get(__MODULE_NAME);

        if (!empty($getsetting)) {

            if (!isset($getsetting->setting['own_template'])) {

                $getsetting->setting['own_template'] = null;
            }

            $own_template = $getsetting->setting['own_template'];

        } else {

            $own_template = null;
        }


        if (!is_null($own_template) && !empty($own_template)) {

            $template_tpl = __MODULES_PATH . '/' . __MODULE_NAME . '/templates/' . $own_template . '/' . __FOLDER_TPL_NAME;
        }

        /**
         * hash name
         */
        $name = trim($name, '/');

        $list_name = explode('/', $name);

        if (!isset($list_name[1])) {

            $name = $name . '/' . $name;

            $__path = $template_tpl . '/' . $name . '.tpl.php';

        } else {

            /**
             * file tpl
             */
            $tpl_file = $template_tpl . '/' . $name . '/' . $list_name[1] . '.tpl.php';

            if (file_exists($tpl_file)) {

                $__path = $tpl_file;

            } else {

                $__path = $template_tpl . '/' . $name . '.tpl.php';

            }
        }

        if (!file_exists($__path)) {

            $hash = explode('/', $name);

            $module = $hash[0];

            unset($hash[0]);

            $new_name = implode('/', $hash);

            $template_dir = __MODULES_PATH . '/' . $module . '/tpl';

            $__path = $template_dir . '/' . $new_name . '.tpl.php';
        }

        if (!is_dir($template_dir)) {

            show_error(1000);
        }

        if (file_exists($__path) == false) {

            show_error(1001);
        }

        $this->data['_client'] = $this->registry->user;

        $registry_data = $this->data;

        foreach ($registry_data as $key => $value) {

            $$key = $value;
        }

        include_once $__path;

        echo "\n<!--ZenCMS Software-->
<!--Author: ZenThang-->
<!--Email: thangangle@yahoo.com-->
<!--Website: http://zencms.vn or http://zenthang.com-->
<!--License: http://zencms.vn/license or read more license.txt-->
<!--Copyright: (C) 2012 - 2013 ZenCMS-->
<!--All Rights Reserved-->
<!--ZenCMS version: ".ZENCMS_VERSION . "-->";
    }

}