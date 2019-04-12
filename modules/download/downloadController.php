<?php
/**
 * ZenCMS Software
 * Copyright 2012-2014 ZenThang, ZenCMS Team
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
 * @copyright 2012-2014 ZenThang, ZenCMS Team
 * @author ZenThang
 * @email info@zencms.vn
 * @link http://zencms.vn/ ZenCMS
 * @license http://www.gnu.org/licenses/ or read more license.txt
 */
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');

Class downloadController Extends ZenController
{

    public function index()
    {
        /**
         * load security library
         */
        $security = load_library('security');

        /**
         * get model
         */
        $model = $this->model->get('download');

        /**
         * load time helper
         */
        load_helper('time');

        $products = $model->get_product_released(false);
        if (isset($_POST['sub_download'])) {
            $list_id = array_keys($_POST['sub_download']);
            $dlid = base64_decode(hexToStr($list_id[0]));
            $pro = $products[$dlid];
            redirect($pro['full_url_endcode']);
        }

        $data['products'] = $products;
        $data['download_security_key'] = $security->get_token('download_security_key');
        $data['page_title'] = 'Download ZenCMS';
        $this->view->data = $data;
        $this->view->show('download/index');
    }

    function file($arg = array()) {
        if (empty($arg[0])) {
            show_error(404);
        }
        $security = load_library('security');
        $fid = $security->removeSQLI($arg[0]);
        $type = '';
        $file_name = '';

        if (isset($arg[1])) {
            $type = $security->cleanXSS($arg[1]);
        }
        if (isset($arg[2])) {
            $file_name = $security->cleanXSS($arg[2]);
        }
        $mine_type = get_mime_type($type);
        $model = $this->model->get('blog');
        $data = $model->get_file_data($fid);
        if ($file_name != $data['file_name'] || !file_exists($data['full_path'])) {
            show_error(405);
        } else {
            $model->update_down($fid);
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private", false);
            header('Content-Type: ' . $mine_type . ';');
            header("Content-Disposition: attachment; filename=" . $file_name . ";");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: " . filesize($data['full_path']));
            readfile($data['full_path']);
        }
    }

    function link($arg = array()) {
        if (empty($arg[0])) {
            show_error(404);
        }
        /**
         * Load security library
         */
        $security = load_library('security');
        $lid = $security->removeSQLI($arg[0]);
        /**
         * Get blog model
         */
        $model = $this->model->get('blog');
        $data = $model->get_link_data($lid);
        if (!empty($data['link'])) {
            $model->update_click($lid);
            redirect($data['link']);
        } else {
            show_error(405);
        }
    }

    function get() {
        $file = ZenInput::get('_file_', true);
        if ($file == 'index.php') {
            exit;
        }
        $filepath = __FILES_PATH . '/' . $file;
        if (!file_exists($filepath)) {
            show_error(404);
        }
        if (!is_file($filepath)) {
            show_error(404);
        }
        $file_name = basename($file);
        $hash_file = explode('.', $file);
        $type = end($hash_file);
        $mine_type = get_mime_type($type);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        header('Content-Type: ' . $mine_type . ';');
        header("Content-Disposition: attachment; filename=" . $file_name . ";");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . filesize($filepath));
        readfile($filepath);
    }
}