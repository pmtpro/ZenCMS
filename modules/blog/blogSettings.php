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

Class blogSettings Extends ZenSettings
{

    public function __construct()
    {

        $this->setting['type'] = APP;

        $this->setting['filter_access'] = array(
                                            'index' => '',
                                            'manager' => 'mod');
        $this->setting['extends'] = array('manager' => array('router' => 'admin/general/modulescp', 'name' => 'Quản lí blog'));
    }

}