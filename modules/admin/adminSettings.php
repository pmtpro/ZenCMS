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

Class adminSettings Extends ZenSettings
{

    public function __construct()
    {
        $this->setting['type'] = APP;

        $this->setting['filter_access'] = array(
            'index' => 'admin',
            'general' => 'admin',
            'caches' => 'admin',
            'members' => 'admin',
            'settings' => 'admin',
            'tools' => 'admin',
            'system' => 'admin');

        $this->setting['verify_access'] = array(
            'admin',
            'admin/tools/fileManager',
            'admin/general/modules/upload',
            'admin/general/templates/import');

        $this->setting['own_template'] = 'admin_default';
    }

}