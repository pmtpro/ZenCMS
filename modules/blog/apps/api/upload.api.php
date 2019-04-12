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
if (!confirmRequest($_POST['input-request-token'])) {
    exit;
}
$security = load_library('security');
if (!empty($app[1])) {
    $act = $security->cleanXSS($app[1]);
}
if (empty($act)) exit;

switch ($act) {
    case 'upload-icon':
        $upload = load_library('upload');
        if (!empty($_POST['input-url'])) {
            $file_name = $security->cleanXSS($_POST['input-url']);
        } elseif (!empty($_POST['input-name'])) {
            $file_name = $security->cleanXSS($_POST['input-name']);
        } else {
            $file_name = randStr(10);
        }
        if (!empty($_POST['input-upload-icon-url'])) {
            $field = 'input-upload-icon-url';
        } else {
            $field = 'input-upload-icon';
        }
        /**
         * set directory upload icon
         */
        $dir = __SITE_PATH . '/files/posts/images';
        $subdir = autoMkSubDir($dir);
        $upload->upload_path = $dir . '/' . $subdir;
        $upload->set_file_name($file_name);
        if (!$upload->do_upload($field)) {
            echo $field;
        } else {
            $dataup = $upload->data();
            if (file_exists($dataup['full_path'])) {
                $icon = $subdir . '/' . $dataup['file_name'];
                if (empty($_POST['is_ajax_request'])) {
                    return $icon;
                } else {
                    header('Content-type: text/json');
                    echo $icon;
                }
            }
        }
        break;
    case 'gen-url':
        $seo = load_library('seo');
        if (isset($_POST['key'])) {
            $key = $seo->url(urldecode($_POST['key']));
            if (empty($_POST['is_ajax_request'])) {
                return $key;
            } else {
                header('Content-type: text/json');
                echo $key;
            }
        }
        break;
}
