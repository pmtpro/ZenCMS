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

class blogHook extends ZenHook
{

    public $upload_error;
    /**
     *
     * @param array $tags
     * @return array
     */
    function add_tags($tags) {
        foreach ($tags as $keytag => $tag) {
            $tag = trim($tag);
            if (strlen($tag) < 1) {
                unset($tags[$keytag]);
            }
        }
        return $tags;
    }

    /**
     * @param $content
     * @return mixed
     */
    function out_content($content) {
        return $content;
    }

    /**
     * @param $content
     * @return mixed
     */
    function out_bbcode_content($content) {
        return $content;
    }

    /**
     * @param $content
     * @return mixed
     */

    function out_html_content($content) {
        return $content;
    }

    /**
     * @param $content
     * @param $blogData
     * @return mixed
     */
    function in_content($content, $blogData) {
        return $content;
    }

    /**
     * @param $content
     * @return bool|mixed
     */
    function in_bbcode_content($content) {
        $content = br2nl($content);
        return $content;
    }

    /**
     *
     * @param string $content
     * @return string
     */
    function in_html_content($content) {
        if (is_mobile()) {
            $content = nl2br($content);
        }
        return $content;
    }

    /**
     * @param $tag
     * @return array
     */
    public function out_tag($tag) {
        return $tag;
    }

    public function valid_data_comment_name($name) {
        $len = strlen($name);
        if ($len < 2 || $len > 30) {
            ZenView::set_error('Tên bạn nhập quá dài', 'comment');
        }
        return $name;
    }

    public function valid_data_comment_msg($msg) {
        if (strlen($msg) > 200) {
            ZenView::set_error('Tin nhắn bạn quá dài', 'comment');
        }
        return $msg;
    }
    /**
     * Hook out_comment_msg
     * @param $msg
     * @return mixed
     */
    public function out_comment_msg($msg) {
        /**
         * load bbcode library
         */
        $bbCode = load_library('bbcode');
        return $bbCode->parse(parse_smile($msg));
    }

    public function valid_name($name) {
        $len = strlen($name);
        if ($len < 3) {
            return false;
        } else {
            return $name;
        }
    }

    public function valid_url($url) {
        return $url;
    }

    public function valid_title($title) {
        return $title;
    }

    public function valid_keyword($keyword) {
        return $keyword;
    }

    public function valid_des($des) {
        return $des;
    }

    public function valid_data_attach_name_link($name) {
        if (strlen($name) > 100) {
            ZenView::set_error('Tên link quá dài', 'link-editor');
        }
        return $name;
    }

    public function valid_data_attach_link($link) {
        $valid = load_library('validation');
        if (!$valid->isValid('url', $link)) {
            ZenView::set_error('Không đúng định dạng link', 'link-editor');
        }
        return $link;
    }

    public function valid_data_attach_file_name($file_name) {
        $seo = load_library('seo');
        return $seo->url($file_name);
    }

    public function valid_data_attach_name($name) {
        if (strlen($name) > 100) {
            ZenView::set_error('Tên file không được quá 100 kí tự', 'file-editor');
        }
        return $name;
    }

    public function jar_editor($file) {

        $java = load_library('JavaEditor');
        $model = $this->model->get('blog');
        if (isset($_POST['sub_copyright']) || isset($_POST['sub_copyright_ticked'])) {
            $java->loader($file['full_path']);
            if(!$java->edit_mf()){
                $process['notices'][] = 'Xin lỗi. Hệ thống không thể chỉnh sửa file này';
            } else {
                $updateStt['status'] = $file['status'];
                $updateStt['status']['copyright'] = 1;
                $model->update_file($file['id'], $updateStt);
                $process['success'] = 'Gắn bản quyền vào file <b>MANIFEST.MF</b> thành công!';
            }
        }
        if (isset($_POST['sub_crack']) || isset($_POST['sub_crack_ticked'])) {
            $java->loader($file['full_path']);
            if(!$java->crack()){
                $process['notices'][] = 'Xin lỗi. Hệ thống không thể chỉnh sửa file này';
            } else {
                if(!$java->sms_exist()) {
                    $updateStt['status'] = $file['status'];
                    $updateStt['status']['crack'] = 1;
                    $model->update_file($file['id'], $updateStt);
                    $process['success'] = 'File này không chứa thanh toán sms!';
                } else {
                    $updateStt['status'] = $file['status'];
                    $updateStt['status']['crack'] = 1;
                    $model->update_file($file['id'], $updateStt);
                    $process['success'] = 'Crack thành công!';
                }
            }
        }

        if (isset($_POST['sub_bookmark']) || isset($_POST['sub_bookmark_ticked'])) {
            $java->loader($file['full_path']);
            if(!$java->setBookmark()){
                $process['notices'][] = 'Xin lỗi. Hệ thống không thể chỉnh sửa file này';
            } else {
                $updateStt['status'] = $file['status'];
                $updateStt['status']['bookmark'] = 1;
                $model->update_file($file['id'], $updateStt);
                $process['success'] = 'Gắn bookmark thành công!';
            }
        }

        $file = $model->get_file_data($file['id']);

        if(isset($file['status']['copyright']) && $file['status']['copyright'] ) {
            $copyright = 'copyright_ticked';
            $copyright_title = 'Click để gắn lại bản quyền file MANIFEST.MF';
        } else {
            $copyright = 'copyright';
            $copyright_title = 'Gắn bản quyền file MANIFEST.MF';
        }
        if(isset($file['status']['crack']) && $file['status']['crack'] ) {
            $crack = 'crack_ticked';
            $crack_title = 'Click để crack lại';
        } else {
            $crack = 'crack';
            $crack_title = 'Crack file này';
        }
        if(isset($file['status']['bookmark']) && $file['status']['bookmark'] ) {
            $bookmark = 'bookmark_ticked';
            $bookmark_title = 'Click để gắn bookmark lại';
        } else {
            $bookmark = 'bookmark';
            $bookmark_title = 'Gắn bookmark';
        }

        $file['actions_editor'] = array($copyright => $copyright_title, $crack => $crack_title, $bookmark => $bookmark_title);
        if(isset($process)) $file['process']  = $process;
        return $file;
    }

    public function upload_icon($in_data, $stream) {

        /**
         * set directory upload icon
         */
        $imageUploadDir = __FILES_PATH . '/posts/images';
        /**
         * init library
         */
        $upload = load_library('upload', array('init_data' => $stream['file_data']));

        if ($upload->uploaded) {

            /**
             * set filename
             */
            $upload->file_new_name_body = $stream['file_name'];
            $upload->allowed = array('image/*');

            /**
             * init upload config
             */
            $upload->image_resize = true;
            $upload->image_ratio = true;
            $upload->image_x = 100;

            /**
             * load upload icon config from stream var
             */

            $upload->image_resize = isset($stream['image_resize']) ? $stream['image_resize'] : $upload->image_resize;
            if ($upload->image_resize) {
                $upload->image_x = isset($stream['image_x']) ? $stream['image_x'] : $upload->image_x;
                if (empty($stream['image_ratio'])) {
                    $upload->image_ratio = false;
                    $upload->image_y = $upload->image_x;
                }
            }

            /**
             * auto make directory by month-year
             */
            $subDir = autoMkSubDir($imageUploadDir);
            $upload->process($imageUploadDir . '/' . $subDir);
            /**
             * upload icon
             */
            if ($upload->processed) {
                $dataUp = $upload->data();
                if (file_exists($dataUp['full_path'])) {
                    if (!empty($stream['blog'])) {
                        $old_icon = $imageUploadDir . '/' . $stream['blog']['icon'];
                        if (file_exists($old_icon) && is_readable($old_icon) && $old_icon != $dataUp['full_path']) unlink($old_icon);
                    }
                    $icon = $subDir . '/' . $dataUp['file_name'];
                    return $icon;
                }
            } else ZenView::set_error($upload->error, isset($stream['pos_message'])? $stream['pos_message'] : ZPUBLIC);
        } else ZenView::set_error($upload->error, isset($stream['pos_message'])? $stream['pos_message'] : ZPUBLIC);
        return $in_data;
    }

    public function watermark_image($image_path)
    {
        /**
         * load library
         */
        $wm = load_library('watermark');

        $wm->load_src($image_path);

        $wm->load_wm(__FILES_PATH . '/images/logo_watermark/'.dbConfig('logo_watermark'));


        if ($wm->do_watermark()) {

            $wm->save($image_path);

        }
        return $image_path;
    }

    function upload_file($sid) {

        global $registry;

        /**
         * get blog model
         */
        $model = $registry->model->get('blog');

        $registry->hook->get('blog');

        $extension_not_allow_upload = $registry->hook->get_result('extension_not_allow_upload');

        $num_up = $registry->hook->get_result('number_file_per_upload');

        /**
         * upload library
         */
        $upload = load_library('upload');

        $blog = $model->get_blog_data($sid);

        $dir = __FILES_PATH . '/posts/files_upload';

        $subdir = autoMkSubDir($dir);

        $upload->upload_path = $dir . '/' . $subdir;

        $upload->not_allowed_types = $extension_not_allow_upload;

        $num_has = 0;
        $result = array();

        for ($i = 1; $i <= $num_up; $i++) {

            $num_has++;

            if (isset($_GET['remote'])) {

                $check_what = $_POST['link' . $i];
                $get = 'link' . $i;

            } else {
                $check_what = $_FILES['file' . $i]['tmp_name'];
                $get = 'file' . $i;
            }

            if (empty($check_what)) {

                $num_has--;

            } else {

                if (isset($_POST['name' . $i]) && strlen($_POST['name' . $i])) {

                    $upload->set_file_name($blog['name'] . ' ' . $_POST['name' . $i]);
                }

                if (!$upload->do_upload($get)) {

                    $registry->hook->upload_error[] = implode(', ', array_unique($upload->error));

                } else {

                    $dataup = $upload->data();

                    $InsertData['url'] = $subdir . '/' . $dataup['file_name'];
                    $InsertData['uid'] = $registry->user['id'];
                    $InsertData['sid'] = $sid;
                    $InsertData['size'] = $dataup['file_size'];
                    $InsertData['type'] = getExt($dataup['file_name']);

                    if (isset($_POST['name' . $i]) && strlen($_POST['name' . $i])) {

                        $InsertData['name'] = h($_POST['name' . $i]);
                    } else {

                        $InsertData['name'] = h($dataup['file_name']);
                    }

                    if (!$model->insert_file($InsertData)) {

                        @unlink($dir . '/' . $InsertData['url']);

                        $registry->hook->upload_error[] = 'Không thể ghi dữ liệu';

                    } else {

                        $result[] = $InsertData;
                    }
                }
            }
        }

        return $result;
    }

    function rename_file($file) {

        global $registry;

        /**
         * get blog model
         */
        $model = $registry->model->get('blog');

        $registry->hook->get('blog');

        $seo = load_library('seo');

        $rename = true;

        if (empty($_POST['name']) or $_POST['name'] == $file['name']) {

            $name = end(explode('/', $file['url']));

        } else {

            $name = $_POST['name'];

            $dir = __FILES_PATH . '/posts/files_upload';

            $filename = basename($file['url']);

            $ext = getExt($filename);

            if (preg_match('/'.$ext.'$/is', $_POST['name'])) {

                $_POST['name'] = preg_replace('/'.$ext.'$/is', '', $_POST['name']);
            }

            $new_name = $seo->url($_POST['name']);

            $new_name = $new_name . '-' . time() . '.' . $ext;

            /**
             * get sub directory
             */
            $listHash =  explode('/', $file['url']);
            $num = count($listHash);
            $timeDir = $listHash[$num - 2];
            $new_url = $timeDir . '/' . $new_name;

            $old_file = $file['full_path'];

            $new_file = $dir . '/' . $new_url;

            $rename = @rename($old_file, $new_file);
        }

        $update['name'] = h($name);

        if ($rename) {

            if (!empty($new_url)) {

                $update['url'] = $new_url;
            }
            if (!empty($new_file)) {

                $update['size'] = @filesize($new_file);
            }

            if (!$model->update_file($file['id'], $update)) {

                $registry->hook->upload_error[] = 'Lỗi dữ liệu';

                return false;

            } else {

                return true;
            }

        } else {

            $msg = '';

            if (preg_match('/^https?:\/\//is', $old_file)) {

                $msg = 'do file này thuộc server khác<br/>' . $old_file;
            }

            $registry->hook->upload_error[] = 'Không thể đổi tên file ' . $msg;

            return false;
        }

        return true;
    }

    function delete_file($file) {

        global $registry;

        $model = $registry->model->get('blog');

        if (@unlink($file['full_path']) || !file_exists($file['full_path'])) {

            if ($model->delete_file($file['id'])) {

                return true;

            } else {

                return false;
            }
        }

        return false;
    }


    function upload_image($sid) {

        global $registry;
        /**
         * get blog model
         */
        $model = $registry->model->get('blog');

        $registry->hook->get('blog');

        /**
         * load upload library
         */
        $upload = load_library('upload');

        $blog = $model->get_blog_data($sid);

        $result = array();

        $upload->set_file_name($blog['url']);

        $dir = __SITE_PATH . '/files/posts/images';

        $subdir = autoMkSubDir($dir);

        $upload->upload_path = $dir . '/' . $subdir;

        if (isset($_GET['remote'])) {

            $uploaded_files = $upload->multiple('link', TEXT_INPUT);
        } else {

            $uploaded_files = $upload->multiple('file');
        }

        foreach ($uploaded_files as $file) {

            if (!$upload->do_upload($file)) {

                $registry->hook->upload_error[] = implode(', ', array_unique($upload->error));

            } else {

                $dataup = $upload->data();
                $InsertData['url'] = $subdir . '/' . $dataup['file_name'];
                $InsertData['uid'] = $registry->user['id'];
                $InsertData['sid'] = $sid;
                $InsertData['type'] = '';

                if (!$upload->is_image()) {

                    @unlink($dir . '/' . $InsertData['url']);

                    $registry->hook->upload_error[] = 'Không phải ảnh';

                } else {

                    if (!empty($_POST['auto_watermark'])) {

                        /**
                         * watermark_image hook *
                         */
                        $dataup['full_path'] = $registry->hook->loader('watermark_image', $dataup['full_path'], true);

                    }

                    if (!$model->insert_image($InsertData)) {

                        @unlink($dir . '/' . $InsertData['url']);

                        $registry->hook->upload_error[] = 'Không thể ghi dữ liệu';
                    }
                }

                $result[] = $dataup;
            }
        }
        return $result;
    }

    function delete_image($image) {

        global $registry;

        $model = $registry->model->get('blog');

        if (@unlink($image['full_path']) || !file_exists($image['full_path'])) {

            if ($model->delete_image($image['id'])) {

                return true;

            } else {

                return false;
            }
        }

        return false;
    }

    function delete_all_image($sid) {

        global $registry;

        $model = $registry->model->get('blog');

        $registry->hook->get('blog');

        $images = array_merge($model->get_images($sid), $model->get_images($sid, 'content'));

        foreach ($images as $img) {

            $registry->hook->loader('delete_image', $img);

            $model->delete_image($img['id']);
        }
        return true;
    }

    function delete_all_file($sid) {

        global $registry;

        $model = $registry->model->get('blog');

        $registry->hook->get('blog');

        $files = $model->get_files($sid);

        foreach ($files as $f) {

            $registry->hook->loader('delete_image', $f);

            $model->delete_file($f['id']);
        }
        return true;
    }

    function delete($sid) {

        global $registry;

        $model = $registry->model->get('blog');

        $registry->hook->get('blog');

        $registry->hook->loader('delete_all_file', $sid, true);
        $registry->hook->loader('delete_all_image', $sid, true);

        $model->delete($sid);

        return true;
    }

    function recycleBin_manager_bar($sid) {

        $act[] = url(HOME . '/blog/manager/recycleBin?reblog='.$sid, 'Khôi phục', cfm('Bạn chắc chắn khôi bài này?'));
        $act[] = url(HOME . '/blog/manager/recycleBin?move='.$sid, 'Di chuyển');
        $act[] = url(HOME . '/blog/manager/recycleBin?delete='.$sid, 'Xóa', cfm('Bạn chắc chắn xóa bài này?'));

        return $act;
    }
}
