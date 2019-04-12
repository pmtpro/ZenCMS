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

/**
 * validation token if is a ajax request
 */
if (is_ajax_request() && !confirmRequest($_POST['input-request-token'])) {
    ZenView::set_error('Invalid token');
    ZenView::ajax_response(false);
    exit;
}

$model = $obj->model->get('blog'); //get blog model
$model->set_filter('status', array(0, 1, 2));
$user = $obj->user; //load user data
$hook = $obj->hook->get('blog'); //get hook

/**
 * load helpers
 */
load_helper('blog_access_app', array('module' => 'blog'));
load_helper('gadget');
load_helper('formCache');

/**
 * load library
 */
$seo = load_library('seo');
$imgEditor = load_library('ImageEditor');
$security = load_library('security');
$valid = load_library('validation');
$blogValid = load_library('blogValid', array('module' => 'blog'));

/**
 * check access
 */
if (is_allow_access_blog_app(__FILE__) == false) {
    show_error(403);
}
/**
 * set base url for this page
 */
$base_url = HOME . '/admin/general/modulescp?appFollow=blog/manager';

/**
 * add js file
 */
ZenView::add_jquery('jquery.liteuploader.min.js', 'foot');
ZenView::add_jquery('jquery.form.min.js', 'foot');
ZenView::add_jquery('jquery.timer.min.js', 'foot');
ZenView::add_js(_URL_MODULES . '/blog/js/manager/editor.manager.js?v=1', 'foot');
ZenView::add_js(_URL_MODULES . '/blog/js/manager/images.manager.js', 'foot');

ZenView::add_js('ZeroClipboard/ZeroClipboard.js', 'foot');
ZenView::add_js(_URL_MODULES . '/blog/js/manager/copy-short-link-image.js', 'foot');

/**
 * receive action ID
 */
$editorID = 0;
$type_data = '';
$_get_id = ZenInput::get('id');
$editorID = $_get_id ? (int)$security->removeSQLI($_get_id) : 0;

/**
 * check blog is exist
 * if blog is not exist, redirect to cpanel page
 */
if ($model->blog_exists($editorID) == false && !is_ajax_request()) {
    ZenView::set_error('Không tồn tại bài viết này', ZPUBLIC, $base_url . '/cpanel');
    exit;
}

/**
 * get blog data
 */
if (!empty($editorID)) {
    $blogActData = $model->get_blog_data($editorID);
} else {
    $blogActData = array(
        'id' => 0,
        'name' => 'Trang chủ',
        'title' => 'Trang chủ',
        'type' => 'folder'
    );
}

/**
 * receive GET action
 */
$_get_act = ZenInput::get('act');
if ($_get_act == 'new') {
    $editorAct = 'new';
    $_get_type = ZenInput::get('type');
    if ($_get_type == 'folder') {
        $editorType = 'folder';
    } else $editorType = 'post';
} else {
    $editorAct = 'edit';
    $editorType = $blogActData['type'];
}

/**
 * check if this blog is folder and editorAct is new,
 * create new blog
 */
if ($editorAct == 'new') {
    if ($blogActData['type'] != 'folder') {
        ZenView::set_error('Bạn không thể viết bài mới trong mục này!' . $blogActData['type'], ZPUBLIC, $base_url . '/cpanel/' . $editorID);
    } else {
        $lastPost = $model->gets('id, name, url, title, content, type', "WHERE `uid`='" . $user['id'] . "' AND `type`='$editorType' AND `parent`='$editorID' AND `content` = '' AND `status`='1'", array("id" => "DESC"), 1);
        if (
            empty($lastPost)
            || (!empty($lastPost[0]['name'])
                && !empty($lastPost[0]['url'])
                && !empty($lastPost[0]['content'])
                && !empty($lastPost[0]['type']))
        ) {
            $insID = $model->insert_blog(array(
                    'parent' => $editorID,
                    'type' => $editorType,
                    'status' => 1)
            );
            if ($insID) {
                redirect(modQueryUrl(curPageURL(), 'id=' . $insID . '&act=edit'));
            } else ZenView::set_error('Không thể tạo bài viết!', ZPUBLIC, $base_url . '/cpanel/' . $editorID);
        } else redirect(modQueryUrl(curPageURL(), 'id=' . $lastPost[0]['id'] . '&act=edit'));
    }
}

$blog = $blogActData;

/**
 * set current base url
 */
curBaseURL($base_url . '/editor&id=' . $editorID);

/**
 * type data
 */
$typeData_list = array('html', 'bbcode');
$_get_setTypeData = ZenInput::get('setTypeData');

$_cookie_blog = ZenInput::cookie('blog', true);

if ($_get_setTypeData && in_array(strtolower($_get_setTypeData), $typeData_list)) {
    $blogUpdate['type_data'] = strtolower($_get_setTypeData);
} elseif (!empty($_cookie_blog['type-data']) && in_array(strtolower($_cookie_blog['type-data']), $typeData_list)) {
    $blogUpdate['type_data'] = strtolower($_cookie_blog['type-data']);
} else $blogUpdate['type_data'] = 'html';

ZenInput::set_cookie('blog[type-data]', $blogUpdate['type_data'], time() + 365 * 24 * 60 * 60);

/**
 * set menu action
 */
$menuAct[] = array(
    'full_url' => $base_url . '/attach&id=' . $editorID,
    'name' => 'Đính kèm',
    'icon' => 'fa fa-paperclip'
);
$menuAct[] = array(
    'full_url' => $base_url . '/images&id=' . $editorID,
    'name' => 'Hình ảnh',
    'icon' => 'fa fa-photo'
);
if ($blogUpdate['type_data'] == 'bbcode') {
    $menuAct[] = array(
        'full_url' => curBaseURL() . '&setTypeData=html',
        'name' => 'Đổi sang HTML',
        'icon' => 'fa fa-refresh'
    );
} else {
    $menuAct[] = array(
        'full_url' => curBaseURL() . '&setTypeData=bbcode',
        'name' => 'Đổi sang BBCode',
        'icon' => 'fa fa-refresh'
    );
}
$menuAct[] = array(
    'full_url' => $base_url . '/cpanel/' . $blog['parent'],
    'name' => 'Đóng',
    'title' => 'Trở về thư mục trước',
    'icon' => 'fa fa-times'
);
ZenView::set_menu(array(
    'pos' => 'editor_action',
    'menu' => $menuAct
));

/**
 * get blog tag
 */
$tags = $model->get_tags($editorID);
$blog['tags'] = implode(', ', $tags);

$blog['type_data'] = $blogUpdate['type_data'];
$data['blog'] = $blog;

/**
 * load gadget
 */
$gadget_config = array();
if ($blogUpdate['type_data'] == 'bbcode') {
    $gadget_config['type'] = 'bbcode';
}
if ($blog['type'] == 'folder') {
    $gadget_config['config']['height'] = '150px';
}
$gadget_config['config']['filebrowserImageUploadUrl'] = HOME . "/api/ckeditor/upload?type=image&blogID=" . $editorID . "&token=" . genRequestToken() . "&is-ajax-request";
$gadget_config['config']['filebrowserImageBrowseUrl'] = HOME . "/api/ckeditor/browser?type=image&blogID=" . $editorID . "&token=" . genRequestToken() . "&is-ajax-request";
/**
 * form_editor_ckeditor_config hook*
 */
$gadget_config = $hook->loader('form_editor_ckeditor_config', $gadget_config, array('var' => $editorID));

gadget_ckeditor('input-content', $gadget_config);


/************************** start edit action ***********************************/

if (isset($_POST['submit-save']) || isset($_POST['submit-public'])) {
    /**
     * run formCache
     */
    formCacheSave('input-auto-import-img');
    formCacheSave('input-upload-icon-keep-ratio');
    formCacheSave('input-upload-icon-resize');

    $blogUpdate['id'] = $data['blog']['id'];

    /**
     * move folder action
     */
    if (isset($_POST['input-parent'])) {
        if (is_numeric($_POST['input-parent'])) {
            $parent = (int)$security->removeSQLI($_POST['input-parent']);
            if ($model->blog_exists($parent) == false) {
                ZenView::set_notice('Không tồn tại mục này');
            } else $blogUpdate['parent'] = $parent;
        }
    }

    /************* name **************/
    if (!empty($_POST['input-name'])) {
        $blogUpdate['name'] = h($security->cleanXSS($_POST['input-name']));
        /**
         * valid_name hook *
         */
        $blogUpdate['name'] = $hook->loader('valid_name', $blogUpdate['name']);
    }

    /************* url **************/
    if (!empty($_POST['input-url'])) {
        $post_url = $security->cleanXSS($_POST['input-url']);
        if (strpos($post_url, '/') !== false) {
            $hash_url = explode('/', $post_url);
            foreach ($hash_url as $key => $url_item) {
                if (empty($url_item)) unset($hash_url[$key]);
                else {
                    $hash_url[$key] = $seo->url($url_item);
                }
            }
            $url = implode('/', $hash_url);
        } else $url = $seo->url($post_url);
        $blogUpdate['url'] = $url;
    } elseif (!empty($blogUpdate['name'])) {
        $blogUpdate['url'] = $seo->url($blogUpdate['name']);
    }

    /************* title **************/
    if (!empty($_POST['input-title'])) {
        $blogUpdate['title'] = h($security->cleanXSS($_POST['input-title']));
        /**
         * valid_title hook *
         */
        $blogUpdate['title'] = $hook->loader('valid_title', $blogUpdate['title']);
    } elseif (!empty($blogUpdate['name'])) {
        $blogUpdate['title'] = $blogUpdate['name'];
        /**
         * valid_title hook *
         */
        $blogUpdate['title'] = $hook->loader('valid_title', $blogUpdate['title']);
    }

    /************* keyword **************/
    if (!empty($_POST['input-keyword'])) {
        $blogUpdate['keyword'] = h($security->cleanXSS($_POST['input-keyword']));
        /**
         * valid_keyword hook *
         */
        $blogUpdate['keyword'] = $hook->loader('valid_keyword', $blogUpdate['keyword']);
    }

    /************* description **************/
    if (!empty($_POST['input-des'])) {
        $blogUpdate['des'] = h($security->cleanXSS($_POST['input-des']));
        /**
         * valid_des hook *
         */
        $blogUpdate['des'] = $hook->loader('valid_des', $blogUpdate['des']);
    }

    /************* content **************/
    /**
     * make sure blog is a post
     * if blog is a folder, can need the content
     */
    if ((!empty($_POST['input-content']) && $editorType == 'post') || $editorType == 'folder') {

        $blogUpdate['content'] = h($_POST['input-content']);
        /**
         * in_content hook *
         */
        $blogUpdate['content'] = $hook->loader('in_content', $blogUpdate['content'], array('var' => $data['blog']));
        switch ($blogUpdate['type_data']) {
            case 'html':
                /**
                 * in_html_content hook *
                 */
                $blogUpdate['content'] = $hook->loader('in_html_content', $blogUpdate['content'], array('var' => $data['blog']));
                break;
            case 'bbcode':
                /**
                 * in_bbcode_content hook *
                 */
                $blogUpdate['content'] = $hook->loader('in_bbcode_content', $blogUpdate['content'], array('var' => $data['blog']));
                break;
        }
    }

    /************* upload icon **************/
    /**
     * set directory upload icon
     */
    $imageUploadDir = __FILES_PATH . '/posts/images';
    if (!empty($_POST['input-icon'])) {

        $blogUpdate['icon'] = $security->cleanXSS($_POST['input-icon']);
        $full_path_image = $imageUploadDir . '/' . $blogUpdate['icon'];
        /**
         * make sure this image is exist
         */
        if (!file_exists($full_path_image)) {
            unset($blogUpdate['icon']);
        }
    } elseif (!empty($_POST['input-upload-icon-url']) || !empty($_FILES['input-upload-icon']['name'])) {

        /**
         * get icon filename
         */
        if (!empty($_POST['input-icon-name'])) {
            $file_name = $security->cleanXSS($_POST['input-icon-name']);
        } elseif (isset($blogUpdate['url'])) {
            $file_name = $blogUpdate['url'];
        } elseif (isset($blogUpdate['name'])) {
            $file_name = $blogUpdate['name'];
        } else $file_name = randStr(10);

        /**
         * select field to upload (by url or file)
         */
        if (!empty($_POST['input-upload-icon-url']) && $valid->isValid('url', $_POST['input-upload-icon-url'])) {
            $field = 'input-upload-icon-url';
            $file_data = $_POST['input-upload-icon-url'];
        } else {
            $field = 'input-upload-icon';
            $file_data = $_FILES['input-upload-icon'];
        }

        $updateIcon['icon'] = $hook->loader('upload_icon', '', array(
            'var' => array(
                'file_data' => $file_data,
                'file_name' => $file_name,
                'image_ratio' => $_POST['input-upload-icon-keep-ratio'],
                'image_x' => $_POST['input-upload-icon-resize'],
                'image_y' => $_POST['input-upload-icon-resize'],
                'blog' => $blog,
                'pos_message' => ZPUBLIC
            )
        ));

        if (!empty($updateIcon['icon'])) {
            $blogUpdate['icon'] = $updateIcon['icon'];
            if (!$model->update_blog($updateIcon, $blog['id'])) {
                ZenView::set_error('Không thể thay đổi icon');
            }
        }
    }


    /************* tags **************/
    if (!empty($_POST['input-tags']) && !empty($blog['id'])) {
        $tags_content = $security->cleanXSS($_POST['input-tags']);
        $list_tags = explode(',', $tags_content);
        $list_tags = h($list_tags);
        /**
         * add_tags hook *
         */
        $list_tags = $hook->loader('add_tags', $list_tags, array('var' => $data['blog']));
        $model->add_tags($list_tags, $blog['id']);
    }

    /**
     * response data if is ajax request
     */
    if (is_ajax_request() && is_array($_POST['response'])) {
        $json_arr = array();
        foreach ($_POST['response'] as $item) {
            $match = preg_replace('/^input\-/', '', $item);
            if (isset($blogUpdate[$match])) {
                $json_arr[$item] = $blogUpdate[$match];
            }
        }
        ZenView::ajax_response($json_arr);
    }


    /************************* Start save post ***********************/

    if (empty($blogUpdate['name'])) {
        /**
         * move to draft
         */
        $blogUpdate['status'] = 1;
        ZenView::set_error('Bạn cần nhập tên mục');
    }

    if (empty($blogUpdate['url'])) {
        /**
         * move to draft
         */
        $blogUpdate['status'] = 1;
        ZenView::set_error('Không xác định URL bài viết');
    }

    /**
     * if select parent is error
     */
    if (!isset($blogUpdate['parent'])) {
        /**
         * move to draft
         */
        $blogUpdate['status'] = 1;
        ZenView::set_error('Không xác định thư mục chứa');
    }

    if (empty($blogUpdate['content']) && $editorType == 'post') {
        /**
         * move to draft
         */
        $blogUpdate['status'] = 1;
        ZenView::set_error('Chưa có nội dung bài viết');
    }

    /**
     * Save to draft
     */
    if (!empty($_POST['submit-save'])) {
        $blogUpdate['status'] = 1;
    }
    /**
     * Public this post/folder
     */
    if (!empty($_POST['submit-public'])) {
        $blogUpdate['status'] = 0;
    }

    if (empty($blog['time']) && $blogUpdate['status'] == 0) {
        $blogUpdate['time'] = time();
    }

    if (!empty($_POST['save-option-update-time']) && !is_ajax_request()) {
        $blogUpdate['time_update'] = time();
    }

    if ($blog['status'] == 0 && empty($_POST['submit-public']) && !empty($_POST['submit-save']) && is_ajax_request()) {
        $blogUpdate['status'] = 0;
    }

    /**
     * blog_data_before_to_database hook*
     */
    $blogUpdate = $hook->loader('blog_data_before_to_database', $blogUpdate);

    /**
     * update blog
     */
    $update_ok = $model->update_blog($blogUpdate, $blog['id']);
    if (!$update_ok) {
        ZenView::set_error('Không thể ghi dữ liệu. Vui lòng thử lại!');
        if (is_ajax_request()) ZenView::ajax_response(0);
    } else {
        if (ZenView::is_success(null)) {
            ZenView::set_success('Lưu thành công <a href="' . HOME . '/' . $blogUpdate['url'] . '-' . $blog['id'] . '.html?_review_" title="Xem bài viết" target="_blank"><span>Xem bài viết</span></a>');
            $data['blog'] = $model->get_blog_data($editorID);
        }
        if (is_ajax_request()) {
            ZenView::ajax_response(1);
        }
    }
}

/**
 * set icon resize option
 */
$iconSet[] = array('value' => '', 'name' => 'Giữ nguyên kích thước');
$iconSet[] = array('value' => '32', 'name' => '32px');
$iconSet[] = array('value' => '48', 'name' => '48px');
$iconSet[] = array('value' => '64', 'name' => '64px');
$iconSet[] = array('value' => '100', 'name' => '100px');
$iconSet[] = array('value' => '240', 'name' => '240px');
$iconSet[] = array('value' => '300', 'name' => '300px');
$iconSet[] = array('value' => '480', 'name' => '480px');
/**
 * icon-resize-list hook*
 */
$iconSet = $hook->loader('icon-resize-list', $iconSet);
ZenView::set_menu(array(
    'pos' => 'upload-icon-resize',
    'menu' => $iconSet
));

$data['base_url'] = $base_url;
$data['images_upload_url'] = $base_url . '/images&id=' . $blog['id'];
/**
 * set page title
 */
$page_title = 'Chỉnh sửa nội dung';
ZenView::set_title($page_title);
$tree[] = url($base_url, 'Quản lí blog');
$tree[] = url($base_url . '/cpanel/' . $blog['parent'], 'Quản lí nội dung');
ZenView::set_breadcrumb($tree);

$model->anti_flood();
$data['tree_folder'] = $model->get_tree_folder();
$obj->view->data = $data;
$obj->view->show('blog/manager/editor');
return;