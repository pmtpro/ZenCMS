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

Class adminController Extends ZenController
{
    protected $protected;
    /**
     * this method will auto start when router is activated
     * auto load menu
     */
    public function _launcher() {
        ZenView::set_menu(array(
            'pos' => 'application',
            'menu' => get_apps('admin/apps', 'admin')
        ));
    }
    /**
     * admin dashboard
     */
    public function index() {
        ZenView::set_title('Admin CPanel');
        $tree[] = url(HOME . '/admin', 'Admin CP');
        $data['display_tree'] = display_tree($tree);
        $this->view->data = $data;
        $this->view->show('admin');
    }
    /**
     * @param array $app
     */
    public function general($app = array('index')) {
        load_apps('admin/apps/general', $app);
    }

    public function members($app = array('index'))
    {
        load_apps('admin/apps/members', $app);
    }

    public function settings($app = array('index'))
    {
        load_apps('admin/apps/settings', $app);
    }

    public function tools($app = array('index'))
    {
        load_apps('admin/apps/tools', $app);
    }

    public function caches($app = array('index'))
    {
        load_apps('admin/apps/caches', $app);
    }
    public function system($app = array('index'))
    {
        load_apps('admin/apps/system', $app);
    }
}
