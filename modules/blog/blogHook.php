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

class blogHook extends ZenHook
{

    public $upload_error;

    /**
     *
     * @param array $data
     * @return array
     */
    function lists_in_folder($data)
    {
        /**
         * get blog model
         */
        $model = $this->model->get('blog');
        /**
         * load pagination library
         */
        $p = load_library('pagination');
        /**
         * get hook blog
         */
        $this->get('blog');
        /**
         * start pagination
         */
        $limit = 10;
        /**
         * num_post_display_in_folder hook *
         */
        $limit = $this->loader('num_post_display_in_folder', $limit);

        $p->setLimit($limit);
        $p->SetGetPage('page');
        $start = $p->getStart();
        $sql_limit = $start.','.$limit;

        $data['posts'] = $model->get_list_blog($data['sid'], 'post', array('weight' => 'ASC', 'time' => 'DESC'), $sql_limit);

        $total = $model->total_result;
        $p->setTotal($total);
        $data['posts_pagination'] = $p->navi_page();


        $limit_folder = 10;

        /**
         * num_folder_display_in_folder hook *
         */
        $limit_folder = $this->loader('num_folder_display_in_folder', $limit_folder);

        $p->setLimit($limit_folder);
        $p->SetGetPage('fp');
        $start = $p->getStart();
        $sql_limit = $start.','.$limit_folder;

        $data['folders'] = $model->get_list_blog($data['sid'], 'folder', array('weight' => 'ASC', 'time' => 'DESC'), $sql_limit);

        $total = $model->total_result;
        $p->setTotal($total);

        hook_data(_PUBLIC, 'blog_folder_after_list_folder', $p->navi_page('?fp={fg}'));

        $data['same_cats'] = $model->get_list_blog($data['parent'], 'folder', array('time' => 'DESC'), 5);

        $limit_rand = 10;
        /**
         * num_rand_post_display_in_folder hook *
         */
        $limit_rand = $this->loader('num_rand_post_display_in_folder', $limit_rand);

        $data['rand_posts'] = $model->get_list_blog(null, 'post', array('RAND()' => ''), $limit_rand);

        return $data;
    }

    /**
     *
     * @param array $data
     * @return array
     */
    function lists_in_post($data)
    {
        $model = $this->model->get('blog');

        $data['other_cats'] = $model->get_list_blog(null, 'folder', array('time' => 'DESC'), 5);

        $data['same_posts'] = $model->get_list_blog($data['parent'], 'post', array('time' => 'DESC'), 5);

        return $data;
    }

    /**
     *
     * @param array $data
     * @return array
     */
    function lists_in_index($data)
    {
        $model = $this->model->get('blog');
        $model->what_gets('url, name, title, time, view, icon');
        $data['new_posts'] = $model->get_list_blog(null, 'post', array('time' => 'DESC'), 10);
        $data['hot_posts'] = $model->get_list_blog(null, 'post', array('view' => 'DESC'), 5);
        $data['rand_posts'] = $model->get_list_blog(null, 'post', array('RAND()' => ''));
        return $data;
    }

    /**
     *
     * @param array $data
     * @return array
     */
    function lists_in_other($data)
    {
        $model = $this->model->get('blog');

        $data['rand_posts'] = $model->get_list_blog(null, 'post', array('RAND()' => ''));

        return $data;
    }

    /**
     * @param $sid
     * @return mixed
     */
    function post_manager_bar($sid) {

        $arr[_HOME . '/blog/manager/editpost/' . $sid . '/step3'] = 'Sửa';
        $arr[_HOME . '/blog/manager/links/' . $sid . '/step2'] = 'Links';
        $arr[_HOME . '/blog/manager/files/' . $sid . '/step2'] = 'Files';
        $arr[_HOME . '/blog/manager/images/' . $sid . '/step2'] = 'Hình ảnh';
        $arr[_HOME . '/blog/manager/delete/' . $sid . '/step2'] = 'Xóa';
        $arr[_HOME . '/blog/manager'] = 'Đến trang quản lí';

        return $arr;
    }

    /**
     * @param $sid
     * @return mixed
     */
    function folder_manager_bar($sid) {

        $arr[_HOME . '/blog/manager/newpost/'.$sid.'/step2'] = 'Viết bài';

        $arr[_HOME . '/blog/manager/cpanel/'.$sid] = 'Đến trang quản lí và cài đặt';

        return $arr;
    }

    /**
     *
     * @param array $tags
     * @return array
     */
    function add_tags($tags)
    {
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
    function out_content($content)
    {
        return $content;
    }

    /**
     * @param $content
     * @return mixed
     */
    function out_bbcode_content($content)
    {
        return $content;
    }

    /**
     * @param $content
     * @return mixed
     */

    function out_html_content($content)
    {
        return $content;
    }

    /**
     * @param $content
     * @return mixed
     */
    function in_content($content)
    {
        return $content;
    }

    /**
     * @param $content
     * @return bool|mixed
     */
    function in_bbcode_content($content)
    {
        $content = br2nl($content);
        return $content;
    }

    /**
     *
     * @param string $content
     * @return string
     */
    function in_html_content($content)
    {
        if (is_mobile()) {
            $content = nl2br($content);
        }
        return $content;
    }

    /**
     * @param $tag
     * @return array
     */
    function out_tag($tag) {
        return $tag;
    }

    function valid_name($name)
    {
        $len = strlen($name);
        if ($len < 3) {
            return false;
        } else {
            return $name;
        }
    }

    public function valid_title($title)
    {
        return $title;
    }

    public function valid_keyword($keyword)
    {
        return $keyword;
    }

    public function valid_des($des)
    {
        return $des;
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
            $crack_title = 'Click file này';
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


    public function import_image($image_list) {

        /**
         * load upload library
         */
        $upload = load_library('upload');

        /**
         * get blog hook
         */
        $this->get('blog');

        /**
         * set directory upload icon
         */
        $dir = __FILES_PATH . '/posts/images';

        $subdir = auto_mkdir($dir);

        $upload->upload_path = $dir . '/' . $subdir;

        $out = array();

        $file_name = '';

        if (isset($this->push['import_image_file_name'])) {

            $file_name = $this->push['import_image_file_name'];

        }

        foreach($image_list as $url) {

            $upload->set_file_name($file_name);

            $upload->set_data($url);

            if (!$upload->do_upload()) {

                return false;

            } else {

                $dataImport = $upload->data();

                $out_url['url'] = $subdir . '/' . $dataImport['file_name'];

                $out_url['full_url'] = _URL_FILES_POSTS . '/images/' . $subdir . '/' . $dataImport['file_name'];

                $out_url['full_path'] = $dataImport['full_path'];

                $out[$url] = $out_url;
            }
        }
        return $out;
    }

    public function watermark_image($image_path)
    {
        /**
         * load library
         */
        $wm = load_library('watermark');

        $wm->load_src($image_path);

        $wm->load_wm(__FILES_PATH . '/images/logo_watermark/'.get_config('logo_watermark'));


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

        $subdir = auto_mkdir($dir);

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
                    $InsertData['type'] = get_ext($dataup['file_name']);

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

            $ext = get_ext($filename);

            if (preg_match('/'.$ext.'$/is', $_POST['name'])) {

                $_POST['name'] = preg_replace('/'.$ext.'$/is', '', $_POST['name']);
            }

            $new_name = $seo->url($_POST['name']);

            $new_name = $new_name . '-' . time() . '.' . $ext;

            $new_url = get_time_dir($file['url']) . '/' . $new_name;

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

        $subdir = auto_mkdir($dir);

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

        $act[] = url(_HOME . '/blog/manager/recycleBin?reblog='.$sid, 'Khôi phục', cfm('Bạn chắc chắn khôi bài này?'));
        $act[] = url(_HOME . '/blog/manager/recycleBin?move='.$sid, 'Di chuyển');
        $act[] = url(_HOME . '/blog/manager/recycleBin?delete='.$sid, 'Xóa', cfm('Bạn chắc chắn xóa bài này?'));

        return $act;
    }
}