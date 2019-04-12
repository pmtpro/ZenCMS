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
if ((is_ajax_request() && !confirmRequest($_POST['input-request-token'])) || !is_ajax_request()) {
    exit;
}
/************* upload icon **************/
$dirStore = 'posts/images';
/**
 * set directory upload icon
 */
$imageUploadDir = __FILES_PATH . '/' . $dirStore;

if (!empty($_POST['input-upload-image-url']) || !empty($_FILES['input-upload-image']['name'])) {
    /**
     * get icon filename
     */
    if (!empty($_POST['input-icon-name'])) {
        $file_name = $security->cleanXSS($_POST['input-icon-name']);
    } else $file_name = randStr(10);

    /**
     * select field to upload (by url or file)
     */
    if (!empty($_POST['input-upload-image-url']) && $valid->isValid('url', $_POST['input-upload-image-url'])) {
        $field = 'input-upload-image-url';
    } else $field = 'input-upload-image';

    /**
     * auto make directory by month-year
     */
    $subDir = autoMkSubDir($imageUploadDir);
    $upload->upload_path = $imageUploadDir . '/' . $subDir;
    $upload->set_file_name($file_name);
    /**
     * do upload image
     */
    if ($upload->do_upload($field)) {
        $dataUp = $upload->data();
        if (file_exists($dataUp['full_path'])) {
            if (!empty($_POST['input-upload-image-resize'])) {
                $width = 100;
                if (!empty($_POST['input-upload-image-resize'])) {
                    $width = $_POST['input-upload-image-resize'];
                    if (empty($_POST['input-upload-image-keep-ratio'])) {
                        $height = $_POST['input-upload-image-resize'];
                    }
                }
                $imgEditor->load($dataUp['full_path']);
                if (empty($height)) $imgEditor->resizeToWidth($width);
                else $imgEditor->resize($width, $height);
                $imgEditor->save();
            }
            $file_size = filesize($dataUp['full_path']);
            $display_size = size2text($file_size);
            $response['file_name'] = $dataUp['file_name'];
            $response['file_ext'] = $dataUp['file_ext'];
            $response['file_size'] = $file_size;
            $response['display_size'] = $display_size;
            $response['url'] = $subDir . '/' . $dataUp['file_name'];
            $response['full_url'] = _URL_FILES . '/' . $dirStore . '/' . $response['url'];
            $response['full_path'] = $dataUp['full_path'];
            ZenView::ajax_json_response($response);
        }
    }
}