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

class ZenConfig
{

    private static $instance;

    /**
     * contruct
     */
    function __construct()
    {
    }

    /**
     * get instance
     *
     * @return object
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new ZenConfig();
        }
        return self::$instance;
    }

    /**
     * load config
     *
     * @return string array boolean
     */
    function loader()
    {
        global $db, $system_config;

        $table_prefix = $system_config['table_prefix'];

        $re_sys_config = $db->query("SELECT * FROM " . $table_prefix . "config");

        while ($ro_sys_config = $db->fetch_array($re_sys_config)) {
            $system_config[$ro_sys_config['key']] = $ro_sys_config['value'];
        }
        return $system_config;
    }

    /**
     * reload config when change
     */
    function reload()
    {
        global $system_config;

        $system_config['from_db'] = $this->loader();

    }

    /**
     * @all controllers must contain an index method
     */
}

?>
