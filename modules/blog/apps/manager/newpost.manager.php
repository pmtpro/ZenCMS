<?php
/**
 * name = Viết bài mới
 * icon = blog_manager_newpost
 * position = 20
 */
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

/**
 * get blog model
 */
$model = $obj->model->get('blog');
$user = $obj->user;

/**
 * load hook
 */
$obj->hook->get('blog');

/**
 * load gadget
 */
load_helper('gadget');
load_helper('blog_access_app');
load_helper('formCache');

/**
 * load library
 */
$security = load_library('security');
$blogValid = load_library('blogValid');
$seo = load_library('seo');
$upload = load_library('upload');
$ieditor = load_library('ImageEditor');

/**
 * check is allow access to app
 */
if (is_allow_access_blog_app(__FILE__) == false) {

    show_error(403);
}

/***
 * set page title
 */
$page_title = 'Viết bài mới';
$tree[] = url(_HOME . '/blog/manager', 'blog manager');
$tree[] = url(_HOME . '/blog/manager/newpost', $page_title);
$data['display_tree'] = display_tree_modulescp($tree);
$data['page_title'] = $page_title;


$step = '';
$data['cid'] = 0;
$type_data = '';


if (isset($app[1])) {
    $cid = $security->removeSQLI($app[1]);
    $cid = (int)$cid;
    $data['cid'] = $cid;
}
if (isset($app[2])) {
    $step = $security->cleanXSS($app[2]);
}
if (isset($app[3])) {
    $type_data = strtolower($security->cleanXSS($app[3]));
}

$data['id_not_found'] = false;

if (empty($cid)) {

    if (isset($_POST['sub_step1'])) {

        if (!empty($_POST['to']) && is_numeric($_POST['to'])) {

            $_POST['uri'] = $_POST['to'];
        }

        if (isset($_POST['uri'])) {

            if (!is_numeric($_POST['uri'])) {

                $cid = $blogValid->preg_match_url($_POST['uri']);
                $cid = (int)$cid;

            } else {

                $cid = $_POST['uri'];
            }

            if (!empty($cid)) {

                if ($model->blog_exists($cid) == false) {

                    $data['notices'] = 'Không tồn tại chuyên mục này';
                } else {

                    redirect(_HOME . '/blog/manager/newpost/' . $cid . '/step2');
                }
            } else {

                $data['notices'] = 'Không tồn tại chuyên mục này';
            }

        }
    }
    $data['page_title'] = 'Bước 1';
    $data['tree_folder'] = $model->get_tree_folder();
    $obj->view->data = $data;
    $obj->view->show('blog/manager/newpost/step1');
    return;
}

/**
 * check blog is exists,
 * if not exist, redriect to prew page
 */
if ($model->blog_exists($cid) == false) {

    redirect(_HOME . '/blog/manager/newpost');
}

/**
 * check type blog
 */
$blog = $model->get_blog_data($cid, 'type');

if ($blog['type'] != 'folder') {
    $data['notices'][] = 'Bạn cần nhập ID hoặc URL chuyên mục vào đây';
    $obj->view->data = $data;
    $obj->view->show('blog/manager/newpost/step1');
    return;
}


switch ($step) {

    case 'step2':

        if ($type_data == 'unset') {

            setcookie('cookie_type_data', '', time() - 1, "/");
        }
        if (!empty($_COOKIE['cookie_type_data']) && $type_data != 'unset') {

            redirect(_HOME . '/blog/manager/newpost/' . $cid . '/step3/' . $_COOKIE['cookie_type_data']);
        }

        if (isset($_POST['sub_type_data'])) {

            if (preg_match('/^html$/is', $_POST['sub_type_data']) or preg_match('/^bbcode$/is', $_POST['sub_type_data'])) {

                $type_data = strtolower($_POST['sub_type_data']);

                if (!empty($_POST['step2_dont_ask_again'])) {

                    setcookie('cookie_type_data', $type_data, time() + 3600 * 24 * 365, "/");
                }
                redirect(_HOME . '/blog/manager/newpost/' . $cid . '/step3/' . $type_data);
            }
        }

        $data['page_title'] = 'Bước 2';
        $obj->view->data = $data;
        $obj->view->show('blog/manager/newpost/step2');

        break;

    case 'step3':

        $data['page_title'] = 'Bước 3 - Viết bài';

        $data['type_data'] = $type_data;

        /**
         * only allow type data are html and bbcode
         */
        if ($type_data != 'html' && $type_data != 'bbcode') {

            redirect(_HOME . '/blog/manager/newpost/' . $cid . '/step2');
        }

        /**
         * set gadget
         */
        if ($type_data == 'html') {

            $data['page_more'] = gadget_TinymceEditer('html');

        } else {

            $data['page_more'] = gadget_TinymceEditer('bbcode');
        }

        /**
         * get data cat
         */
        $cat = $model->get_blog_data($cid);

        $data['cat'] = $cat;

        /**
         * Confirm the user click the submit button
         */
        if (isset($_POST['sub_newpost'])) {

            /**
             * set form cache for input tag: auto_watermark
             */
            sFormCache('auto_get_img');
            /**
             * set form cache for input tag: auto_resize
             */
            sFormCache('auto_resize');

            $InsertData['parent'] = $cid;
            $InsertData['uid'] = $obj->user['id'];
            $InsertData['name'] = h(trim($_POST['name']));
            $InsertData['title'] = $InsertData['name'];
            $InsertData['url'] = $seo->url($_POST['name']);
            $InsertData['keyword'] = h(trim($_POST['keyword']));
            $InsertData['des'] = h(trim($_POST['des']));
            $InsertData['content'] = h($_POST['content']);
            $InsertData['type'] = 'post';
            $InsertData['type_data'] = $type_data;

            /**
             * in_content hook *
             */
            $InsertData['content'] = $obj->hook->loader('in_content', $InsertData['content']);

            switch ($InsertData['type_data']) {

                case 'html':
                    /**
                     * in_html_content hook *
                     */
                    $InsertData['content'] = $obj->hook->loader('in_html_content', $InsertData['content']);
                    break;

                case 'bbcode':
                    /**
                     * in_bbcode_content hook *
                     */
                    $InsertData['content'] = $obj->hook->loader('in_bbcode_content', $InsertData['content']);
                    break;
            }

            /**
             * valid_name hook *
             */
            $InsertData['name'] = $obj->hook->loader('valid_name', $InsertData['name']);
            /**
             * valid_title hook *
             */
            $InsertData['title'] = $obj->hook->loader('valid_title', $InsertData['title']);
            /**
             * valid_keyword hook *
             */
            $InsertData['keyword'] = $obj->hook->loader('valid_keyword', $InsertData['keyword']);
            /**
             * valid_des hook *
             */
            $InsertData['des'] = $obj->hook->loader('valid_des', $InsertData['des']);

            if ($InsertData['name'] == false || !strlen($InsertData['content'])) {

                $data['errors'][] = 'Bạn chưa nhập tên hoặc nội dung bài viết';
                $obj->view->data = $data;
                $obj->view->show('blog/manager/newpost/newpost');
                return;
            }

            /**
             * check if blog name is exists
             */
            if ($model->blog_name_exists($InsertData['name'])) {

                $data['errors'][] = 'Đã có một bài viết cùng tên';
                $obj->view->data = $data;
                $obj->view->show('blog/manager/newpost/newpost');
                return;
            }

            if (empty($InsertData['url'])) {

                $data['errors'][] = 'Không thể tạo bài viết này';
                $obj->view->data = $data;
                $obj->view->show('blog/manager/newpost/newpost');
                return;
            }

            if (!empty($_POST['custom_title'])) {

                $_POST['type_title'] = 'custom';

            }

            $delimiter_title = ' - ';
            /**
             * delimiter_title hook *
             */
            $delimiter_title = $obj->hook->loader('delimiter_title', $delimiter_title);

            switch ($_POST['type_title']) {

                case 'with_parent':
                    $InsertData['title'] = $cat['title'] . $delimiter_title . $InsertData['title'];
                    $InsertData['type_title'] = 'with_parent';
                    break;

                case 'with_full_parent':
                    $list_title_parent = $model->list_parent($cid, 'title');
                    $InsertData['title'] = $InsertData['title'] . $delimiter_title . implode($delimiter_title, $list_title_parent);
                    $InsertData['type_title'] = 'with_full_parent';
                    break;

                case 'custom':
                    $InsertData['title'] = trim($_POST['custom_title']);
                    $InsertData['type_title'] = 'custom';
                    break;

                default:
                    break;
            }

            $delimiter_url = '/';
            /**
             * delimiter_title hook *
             */
            $delimiter_url = $obj->hook->loader('delimiter_url', $delimiter_url);

            switch ($_POST['type_url']) {

                case 'with_parent':
                    $InsertData['url'] = $cat['url'] . $delimiter_url . $InsertData['url'];
                    $InsertData['type_url'] = 'with_parent';
                    break;

                case 'with_full_parent':
                    $list_url_parent = $model->list_parent($cid, 'url');
                    $InsertData['url'] = implode($delimiter_url, $list_url_parent) . $delimiter_url . $InsertData['url'];
                    $InsertData['type_url'] = 'with_full_parent';
                    break;

                default:
                    break;
            }

            /**
             * set path upload icon
             */
            $dir = __FILES_PATH . '/posts/images';

            /**
             * auto make direction
             */
            $subdir = auto_mkdir($dir);

            $upload->upload_path = $dir . '/' . $subdir;

            $upload->set_file_name($InsertData['url']);
            
            /**
             * if upload is error
             */
            if (!$upload->do_upload('file_icon')) {

                $InsertData['icon'] = '';

            } else {

                $dataup = $upload->data();

                $InsertData['icon'] = $subdir . '/' . $dataup['file_name'];

                if (isset($_POST['auto_resize'])) {

                    $ieditor->load($dataup['full_path']);

                    $width = 80;
                    /**
                     * resize_width_icon hook *
                     */
                    $width = $obj->hook->loader('resize_width_icon', $width);

                    $height = 80;
                    /**
                     * resize_width_icon hook *
                     */
                    $height = $obj->hook->loader('resize_height_icon', $height);

                    $ieditor->resize($width, $height);

                    $ieditor->save();
                }
            }

            /**
             * blog_data_before_to_database hook *
             */
            $InsertData = $obj->hook->loader('blog_data_before_to_database', $InsertData);

            /**
             * insert blog
             */
            $ins_ok = $model->insert_blog($InsertData);

            if ($ins_ok == false) {

                $data['errors'][] = 'Không thể ghi dữ liệu. Vui lòng thử lại';
                $obj->view->data = $data;
                $obj->view->show('blog/manager/newpost/newpost');
                return;
            }

            /***
             * get insert id
             */
            $sid = $model->blog_insert_id();

            /**
             * if auto get image from blog content
             */
            if (!empty($_POST['auto_get_img'])) {

                /**
                 * load parse library
                 */
                $parse = load_library('parse');
                $list_image = $parse->image_url($InsertData['content']);
                $list_import = array();


                /**
                 * push file name to hook
                 */
                $obj->hook->push['import_image_file_name'] = $InsertData['name'];

                /**
                 * import_image hook *
                 */
                $arr = $obj->hook->loader('import_image', $list_image, true);


                $insert_img['uid'] = $user['id'];
                $insert_img['sid'] = $sid;
                $insert_img['type'] = 'content';

                foreach ($arr as $old_url => $new) {

                    $insert_img['url'] = $new['url'];

                    if (get_config('turn_on_watermark')) {

                        /**
                         * watermark_image hook *
                         */
                        $new['full_path'] = $obj->hook->loader('watermark_image', $new['full_path']);
                    }

                    if (!$model->insert_image($insert_img)) {

                        unset($arr[$old_url]);

                        @unlink($new['full_path']);

                    } else {

                        $replace[$old_url] = 'files/posts/images/' . $new['url'];
                    }
                }

                $updateData['content'] = strtr($InsertData['content'], $replace);

		if (!empty($updateData['content'])) {
		
                    $model->update_blog($updateData, $sid);
                }

            }


            if (isset($_POST['tags'])) {

                $list_tags = explode(',', $_POST['tags']);
                $list_tags = h($list_tags);

                /**
                 * add_tags hook *
                 */
                $list_tags = $obj->hook->loader('add_tags', $list_tags, true);

                $model->add_tags($list_tags, $sid);
            }

            redirect(_HOME . '/' . $InsertData['url'] . '-' . $sid . '.html');
        }

        $data['cat'] = $cat;
        $obj->view->data = $data;
        $obj->view->show('blog/manager/newpost/newpost');
        break;

    default :

        redirect(_HOME . '/blog/manager/newpost/' . $cid . '/step2/' . $type_data);

        break;
}