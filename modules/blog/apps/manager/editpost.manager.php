<?php
/**
 * name = Sửa bài viết
 * icon = blog_manager_editpost
 * position = 40
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

/**
 * load user data
 */
$user = $obj->user;

/**
 * get hook
 */
$obj->hook->get('blog');

/**
 * load helpers
 */
load_helper('blog_access_app');
load_helper('gadget');
load_helper('formCache');

/**
 * load library
 */
$seo = load_library('seo');
$upload = load_library('upload');
$ieditor = load_library('ImageEditor');
$security = load_library('security');
$blogValid = load_library('blogValid');

/**
 * check access
 */
if (is_allow_access_blog_app(__FILE__) == false) {
    show_error(403);
}

/**
 * set page title
 */
$page_title = 'Sửa bài viết';
$tree[] = url(_HOME . '/blog/manager', 'blog manager');
$tree[] = url(_HOME . '/blog/manager/editpost', $page_title);
$data['display_tree'] = display_tree_modulescp($tree);
$data['page_title'] = $page_title;


$ActID = 0;
$step = '';
$type_data = '';

if (isset($app[1])) {
    $ActID = $security->removeSQLI($app[1]);
    $ActID = (int)$ActID;
}
if (isset($app[2])) {
    $step = $security->cleanXSS($app[2]);
}
if (isset($app[3])) {
    $type_data = strtolower($security->cleanXSS($app[3]));
}


if (empty($ActID) or $model->blog_exists($ActID) == false) {

    if (isset($_POST['sub_step1'])) {

        if (isset($_POST['uri'])) {

            if (!is_numeric($_POST['uri'])) {

                $cid = $blogValid->preg_match_url($_POST['uri']);

                $cid = (int)$cid;

            } else {

                $cid = $security->removeSQLI($_POST['uri']);
            }
            if (!empty($cid)) {

                if ($model->blog_exists($cid) == false) {

                    $data['notices'] = 'Không tồn tại chuyên mục này';

                } else {

                    $cblog = $model->get_blog_data($cid, 'type_data');

                    redirect(_HOME . '/blog/manager/editpost/' . $cid . '/step3/' . $cblog['type_data']);
                }
            } else {

                $data['notices'] = 'Không tồn tại chuyên mục này';
            }
        }
    }
    $obj->view->data = $data;
    $obj->view->show('blog/manager/editpost/step1');
    return;
}

/**
 * get blog data
 */
$blog = $model->get_blog_data($ActID);

/**
 * make sure blog type is post
 */
if ($blog['type'] != 'post') {

    $data['notices'] = 'Bạn chỉ có thể sửa bài viết ở đây';
    $obj->view->data = $data;
    $obj->view->show('blog/manager/editpost/step1');
    return;
}


$blog['check_url_only_me'] = '';
$blog['check_url_with_parent'] = '';
$blog['check_url_with_full_parent'] = '';

switch ($blog['type_url']) {

    case 'only_me':

        $blog['check_url_only_me'] = 'checked';
        break;

    case 'with_parent':

        $blog['check_url_with_parent'] = 'checked';
        break;

    case 'with_full_parent':

        $blog['check_url_with_full_parent'] = 'checked';
        break;
}


$blog['check_title_only_me'] = '';
$blog['check_title_with_parent'] = '';
$blog['check_title_with_full_parent'] = '';
$blog['check_title_custom'] = '';

switch ($blog['type_title']) {
    case 'only_me':
        $blog['check_title_only_me'] = 'checked';
        break;
    case 'with_parent':
        $blog['check_title_with_parent'] = 'checked';
        break;
    case 'with_full_parent':
        $blog['check_title_with_full_parent'] = 'checked';
        break;
    case 'custom':
        $blog['check_title_custom'] = 'checked';
        break;
}


$blog['custom_title'] = '';

if ($blog['check_title_custom']) {

    $blog['custom_title'] = $blog['title'];
}

$blog['content'] = h($blog['content']);


if (!empty($type_data)) {

    $blog['type_data'] = $type_data;

} else {

    $type_data = $blog['type_data'];
}

/**
 * insert link and image to tinymce
 */
$import2tiny = array();

$image_list = array_merge($model->get_images($ActID), $model->get_images($ActID, 'content'));

$link_list = array_merge($model->get_links($ActID), $model->get_files($ActID));

foreach ($image_list as $_img) {

    $import2tiny['image_list'][] = array('title' => $_img['url'], 'value' => $_img['short_url']);
}

foreach ($link_list as $_link) {

    $import2tiny['link_list'][] = array('title' => $_link['name'], 'value' => $_link['short_link']);
}

/**
 * load gadget
 */
if ($blog['type_data'] == 'html') {

    $data['page_more'] = gadget_TinymceEditer('html', false, $import2tiny);

} else {

    $data['page_more'] = gadget_TinymceEditer('bbcode', false, $import2tiny);
}


$tags = $model->get_tags($ActID);
$blog['tags'] = implode(', ', $tags);

$data['blog'] = $blog;

switch ($step) {

    case 'step2':

        if ($type_data == 'unset') {

            setcookie('cookie_type_data', '', time() - 1, "/");
        }

        if (!empty($_COOKIE['cookie_type_data']) && $type_data != 'unset') {

            redirect(_HOME . '/blog/manager/editpost/' . $ActID . '/step3/' . $_COOKIE['cookie_type_data']);
        }

        if (isset($_POST['sub_type_data'])) {

            if (preg_match('/^html$/is', $_POST['sub_type_data']) or preg_match('/^bbcode$/is', $_POST['sub_type_data'])) {

                $type_data = strtolower($_POST['sub_type_data']);

                if (!empty($_POST['step2_dont_ask_again'])) {

                    setcookie('cookie_type_data', $type_data, time() + 3600 * 24 * 365, "/");
                }
                redirect(_HOME . '/blog/manager/editpost/' . $ActID . '/step3/' . $type_data);
            }
        }

        $data['page_title'] = 'Bước 2';
        $obj->view->data = $data;
        $obj->view->show('blog/manager/editpost/step2');
        return;
        break;

    case 'step3':

        if (isset($_POST['sub_move'])) {

            $data_moveto = $model->get_blog_data($_POST['to']);

            if (empty($data_moveto)) {

                $data['notices'][] = 'Không tồn tại mục này';

            } else {

                $update['parent'] = $_POST['to'];

                if (!$model->update_blog($update, $blog['id'])) {

                    $data['notices'][] = 'Lỗi dữ liệu';

                } else {

                    redirect($blog['full_url']);
                }
            }
        }

        if (isset($_POST['sub_editpost'])) {

            /**
             * set form cache for input tag: auto_watermark
             */
            sFormCache('auto_get_img');
            /**
             * set form cache for input tag: auto_resize
             */
            sFormCache('auto_resize');

            $cat = $model->get_blog_data($blog['parent']);

            $updateData['parent'] = $blog['parent'];
            $updateData['name'] = h(trim($_POST['name']));
            $updateData['title'] = h($updateData['name']);
            $updateData['url'] = $seo->url($_POST['name']);
            $updateData['keyword'] = h(trim($_POST['keyword']));
            $updateData['des'] = h(trim($_POST['des']));
            $updateData['content'] = h($_POST['content']);
            $updateData['type'] = 'post';
            $updateData['type_data'] = $type_data;

            /**
             * in_content hook *
             */
            $updateData['content'] = $obj->hook->loader('in_content', $updateData['content']);

            switch ($updateData['type_data']) {

                case 'html':
                    /**
                     * in_html_content hook *
                     */
                    $updateData['content'] = $obj->hook->loader('in_html_content', $updateData['content']);
                    break;

                case 'bbcode':
                    /**
                     * in_bbcode_content hook *
                     */
                    $updateData['content'] = $obj->hook->loader('in_bbcode_content', $updateData['content']);
                    break;
            }

            /**
             * valid_name hook *
             */
            $updateData['name'] = $obj->hook->loader('valid_name', $updateData['name']);
            /**
             * valid_title hook *
             */
            $updateData['title'] = $obj->hook->loader('valid_title', $updateData['title']);
            /**
             * valid_keyword hook *
             */
            $updateData['keyword'] = $obj->hook->loader('valid_keyword', $updateData['keyword']);
            /**
             * valid_des hook *
             */
            $updateData['des'] = $obj->hook->loader('valid_des', $updateData['des']);

            if ($updateData['name'] == false || !strlen($updateData['content'])) {

                $data['errors'][] = 'Bạn chưa nhập tên hoặc nội dung bài viết';
                $obj->view->data = $data;
                $obj->view->show('blog/manager/editpost/editpost');
                return;
            }


            if ($model->blog_name_exists($updateData['name'], $ActID)) {

                $data['errors'][] = 'Đã có một bài viết cùng tên';
                $obj->view->data = $data;
                $obj->view->show('blog/manager/editpost/editpost');
                return;
            }

            if (empty($updateData['url'])) {

                $data['errors'][] = 'Không thể tạo bài viết này';
                $obj->view->data = $data;
                $obj->view->show('blog/manager/editpost/editpost');
                return;
            }

            $delimiter_title = ' - ';
            /**
             * delimiter_title hook *
             */
            $delimiter_title = $obj->hook->loader('delimiter_title', $delimiter_title);

            switch ($_POST['type_title']) {

                case 'with_parent':
                    $updateData['title'] = $cat['title'] . $delimiter_title . $updateData['title'];
                    $updateData['type_title'] = 'with_parent';
                    break;

                case 'with_full_parent':
                    $list_title_parent = $model->list_parent($cat['id'], 'title');
                    $updateData['title'] = $updateData['title'] . $delimiter_title . implode($delimiter_title, $list_title_parent);
                    $updateData['type_title'] = 'with_full_parent';
                    break;

                case 'custom':
                    $updateData['title'] = h(trim($_POST['custom_title']));
                    $updateData['type_title'] = 'custom';
                    break;

                default:
                    break;
            }

            $delimiter_url = '/';
            /**
             * delimiter_url hook *
             */
            $delimiter_url = $obj->hook->loader('delimiter_url', $delimiter_url);

            switch ($_POST['type_url']) {

                case 'with_parent':
                    $updateData['url'] = $cat['url'] . $delimiter_url . $updateData['url'];
                    $updateData['type_url'] = 'with_parent';
                    break;

                case 'with_full_parent':
                    $list_url_parent = $model->list_parent($cat['id'], 'url');
                    $updateData['url'] = implode($delimiter_url, $list_url_parent) . $delimiter_url . $updateData['url'];
                    $updateData['type_url'] = 'with_full_parent';
                    break;

                default:
                    break;
            }

            /**
             * set directory upload icon
             */
            $dir = __SITE_PATH . '/files/posts/images';

            $subdir = auto_mkdir($dir);

            $upload->upload_path = $dir . '/' . $subdir;

            $upload->set_file_name($updateData['url']);

            if (!$upload->do_upload('file_icon')) {

                $updateData['icon'] = $blog['icon'];

            } else {

                $dataup = $upload->data();

                if (file_exists($dataup['full_path'])) {

                    @unlink($dir . '/' . $blog['icon']);

                    $updateData['icon'] = $subdir . '/' . $dataup['file_name'];

                    if (isset($_POST['auto_resize'])) {

                        $ieditor->load($dataup['full_path']);

                        $width = 80;
                        /**
                         * resize_width_icon hook *
                         */
                        $width = $obj->hook->loader('resize_width_icon', $width);

                        $height = 80;
                        /**
                         * resize_height_icon hook *
                         */
                        $height = $obj->hook->loader('resize_height_icon', $height);

                        $ieditor->resize($width, $height);

                        $ieditor->save();
                    }

                } else {

                    $updateData['icon'] = $blog['icon'];
                }
            }

            /**
             * blog_data_before_to_database hook *
             */
            $updateData = $obj->hook->loader('blog_data_before_to_database', $updateData);

            /**
             * update data
             */
            $update_ok = $model->update_blog($updateData, $ActID);

            if ($update_ok == false) {

                $data['errors'][] = 'Không thể ghi dữ liệu. Vui lòng thử lại';
                $obj->view->data = $data;
                $obj->view->show('blog/manager/newpost/editpost');
                return;
            }

            /**
             * start auto get images
             */
            if (!empty($_POST['auto_get_img'])) {

                $parse = load_library('parse');

                $list_image = $parse->image_url($updateData['content']);

                $list_import = array();

                /**
                 * push file name to hook
                 */
                $obj->hook->push['import_image_file_name'] = $updateData['url'];

                /**
                 * import_image hook *
                 */
                $arr = $obj->hook->loader('import_image', $list_image, true);


                $insert_img['uid'] = $user['id'];
                $insert_img['sid'] = $ActID;
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

                $updatecontent['content'] = strtr($updateData['content'], $replace);

		if (!empty($updatecontent['content'])) {
		
                    $model->update_blog($updatecontent, $ActID);
                }
            }

            if (isset($_POST['tags'])) {

                $list_tags = explode(',', $_POST['tags']);

                $list_tags = h($list_tags);
                /**
                 * add_tags hook *
                 */
                $list_tags = $obj->hook->loader('add_tags', $list_tags);

                $model->add_tags($list_tags, $ActID);
            }

            $data['errors'] = $upload->error;

            redirect(_HOME . '/' . $updateData['url'] . '-' . $ActID . '.html');
        }
        $model->anti_flood();
        $data['tree_folder'] = $model->get_tree_folder();
        $obj->view->data = $data;
        $obj->view->show('blog/manager/editpost/editpost');
        break;

    default :

        redirect(_HOME . '/blog/manager/editpost/' . $ActID . '/step2/' . $type_data);

        break;
}
$obj->view->data = $data;
$obj->view->show('blog/manager/editpost/editpost');