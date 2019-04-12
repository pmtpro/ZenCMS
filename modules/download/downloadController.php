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

Class downloadController Extends ZenController
{

    function index()
    {
        redirect(_HOME);
    }

    function file($arg = array())
    {

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

        $model = $this->model->get('download');

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

    function link($arg = array())
    {

        if (empty($arg[0])) {

            show_error(404);
        }

        $security = load_library('security');

        $lid = $security->removeSQLI($arg[0]);

        $model = $this->model->get('download');
        $data = $model->get_link_data($lid);

        if (!empty($data['link'])) {

            $model->update_click($lid);

            redirect($data['link']);
        } else {
            show_error(405);
        }
    }

    function get()
    {

        $file = $_GET['_file_'];

        if ($_GET['_file_'] == 'index.php') {

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

        $type = get_ext($file);

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