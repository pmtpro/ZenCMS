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
if ((is_ajax_request() && !confirmRequest($_GET['token'])) || !is_ajax_request()) {
    exit;
}
/**
 * get hook api
 */
$hook = $obj->hook->get('api');

/************* upload image **************/
$dirStore = 'posts/images';
/**
 * set directory upload image
 */
$imageUploadDir = __FILES_PATH . '/' . $dirStore;

if (!empty($_FILES['upload']['name'])) {

    /**
     * hook data_before_upload hook*
     */
    $_FILES = $hook->loader('ckeditor_data_before_upload', $_FILES);

    /**
     * load upload library
     */
    $upload = load_library('upload', array('init_data' => $_FILES['upload']));

    /**
     * check allow upload
     */
    if ($upload->uploaded) {

        $upload->allowed = array('image/*');
        /**
         * auto make directory by month-year
         */
        $subDir = autoMkSubDir($imageUploadDir);
        $uploadPath = $imageUploadDir . '/' . $subDir;

        /**
         * ckeditor_init_upload hook*
         */
        $upload = $hook->loader('ckeditor_init_upload', $upload);

        $upload->process($uploadPath);
        /**
         * upload complete
         */
        if ($upload->processed) {
            $dataUp = $upload->data();
            if (file_exists($dataUp['full_path'])) {
                $InsertData = $dataUp;
                $InsertData['url'] = $subDir . '/' . $dataUp['file_name'];
                $InsertData['base_path'] = '/files/posts/images/' . $InsertData['url'];
                /**
                 * hook data_after_upload hook*
                 */
                $InsertData = $hook->loader('ckeditor_data_after_upload', $InsertData);

                ZenView::ajax_response('<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction('.$_GET['CKEditorFuncNum'].', "' . $InsertData['base_path'] . '");</script>', null);
            }
        }
    }
    $upload->clean();
}