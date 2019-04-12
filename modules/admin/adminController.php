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

Class adminController Extends ZenController
{

    protected $protected;

    public function index()
    {

        $parse = load_library('parse');

        $data['page_title'] = 'Admin cpanel';
        $data['menus'] = array();
        $tmp = array();

        $menus = glob(__MODULES_PATH . '/admin/apps/*', GLOB_ONLYDIR);

        foreach ($menus as $k => $menu) {

            $name = end(explode('/', $menu));

            $index_file = $menu . '/index.' . $name . '.php';

            if (!file_exists($index_file)) {

                unset ($menus[$k]);

            } else {

                $str = $parse->ini_php_file_comment($index_file);

                if (isset($str['position'])) {

                    $pos = $str['position'];
                } else {
                    $pos = 99999;
                }

                $tmp[$menu] = $pos;
            }
        }
        /**
         * sort menu by position value
         */
        asort($tmp);
        $menus = array_keys($tmp);

        foreach ($menus as $menu) {

            $name = end(explode('/', $menu));

            $index_file = $menu . '/index.' . $name . '.php';

            $str = $parse->ini_php_file_comment($index_file);

            if (isset ($str['folder_name'])) {

                $out['name'] = $str['folder_name'];

            } else {

                $out['name'] = $name;
            }

            $out['router'] = 'admin/' . $name;

            $out['full_url'] = _HOME . '/' . $out['router'];

            $out['sub_menus'] = get_apps($menu, $out['router']);

            $data['menus'][] = $out;
        }

        $tree[] = url(_HOME . '/admin', 'Admin CP');
        $data['display_tree'] = display_tree($tree);
        $this->view->data = $data;
        $this->view->show('admin');
    }

    public function general($app = array('index'))
    {

        load_apps(__MODULES_PATH . '/admin/apps/general', $app);

    }

    public function members($app = array('index'))
    {

        load_apps(__MODULES_PATH . '/admin/apps/members', $app);

    }

    public function settings($app = array('index'))
    {

        load_apps(__MODULES_PATH . '/admin/apps/settings', $app);
    }

    public function tools($app = array('index'))
    {

        load_apps(__MODULES_PATH . '/admin/apps/tools', $app);
    }

    public function caches($app = array('index'))
    {

        load_apps(__MODULES_PATH . '/admin/apps/caches', $app);
    }
    public function system($app = array('index'))
    {

        load_apps(__MODULES_PATH . '/admin/apps/system', $app);
    }



}

?>