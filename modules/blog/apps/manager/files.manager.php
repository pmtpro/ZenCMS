<?php
/**
 * name = Quản lí files
 * icon = blog_manager_files
 * position = 100
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
 * get hook blog
 */
$obj->hook->get('blog');

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
load_helper('gadget');
load_helper('time');
load_helper('user');
load_helper('blog_access_app');

/**
 * load library
 */
$seo = load_library('seo');
$upload = load_library('upload');
$security = load_library('security');
$blogValid = load_library('blogValid');
$parse = load_library('parse');
$permission = load_library('permission');
$permission->set_user($obj->user);
$java = load_library('JavaEditor');

/**
 * check access
 */
if (is_allow_access_blog_app(__FILE__) == false) {
    show_error(403);
}

/**
 * set page title
 */
$page_title = 'Quản lí files';
$tree[] = url(_HOME . '/blog/manager', 'blog manager');
$tree[] = url(_HOME . '/blog/manager/files', $page_title);
$data['display_tree'] = display_tree_modulescp($tree);
$data['page_title'] = $page_title;

$sid = 0;
$step = '';
$act = '';
$act_id = 0;

if (isset($app[1])) {
    $sid = $security->removeSQLI($app[1]);
    $sid = (int)$sid;
}
if (isset($app[2])) {
    $step = $security->cleanXSS($app[2]);
}
if (isset($app[3])) {
    $act = $security->cleanXSS($app[3]);
}
if (isset($app[4])) {
    $act_id = $security->removeSQLI($app[4]);
    $act_id = (int)$act_id;
}

switch ($step) {
    default:

        if (empty($sid) or $model->blog_exists($sid) == false) {

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

                            $data['notices'] = 'Không tồn tại mục này';
                        } else {
                            $blog = $model->get_blog_data($cid, 'type');

                            if ($blog['type'] != 'post') {

                                $data['notices'] = 'Bạn chỉ có thể thêm file vào bài viết';
                            } else {

                                redirect(_HOME . '/blog/manager/files/' . $cid . '/step2');
                            }
                        }
                    } else {
                        $data['notices'] = 'Không tồn tại chuyên mục này';
                    }
                }
            }
            $obj->view->data = $data;
            $obj->view->show('blog/manager/files/step1');
            return;
        } else {

            redirect(_HOME . '/blog/manager/files/' . $sid . '/step2');
        }
        break;

    case 'step2':

        if (empty($sid) or $model->blog_exists($sid) == false) {
            redirect(_HOME . '/blog/manager/files');
        }

        $blog = $model->get_blog_data($sid);
        $blog['stat']['num_files'] = $model->count_files($sid);
        $blog['stat']['num_links'] = $model->count_links($sid);

        switch ($act) {

            default:

                $files = $model->get_files($sid);
                $data['blog'] = $blog;
                $data['blog']['files'] = $files;
                $data['java'] = $java;
                $obj->view->data = $data;
                $obj->view->show('blog/manager/files/step2');
                break;

            case 'add':

                $data['form_files'] = array();

                $extension_not_allow_upload = 'php|exe|html|js';
                /**
                 * extension_allow_upload hook *
                 */
                $extension_not_allow_upload = $obj->hook->loader('extension_not_allow_upload', $extension_not_allow_upload);

                $num_up = 5;
                /**
                 * number_file_per_upload hook *
                 */
                $num_up = $obj->hook->loader('number_file_per_upload', $num_up);

                for ($i = 1; $i <= $num_up; $i++) {

                    $data['form_files'][] = $i;
                }

                if (isset($_POST['sub_add'])) {

                    if (!$security->check_token('token_add_file')) {

                        redirect(_HOME . '/blog/manager/files/' . $sid . '/step2');

                    } else {

                        $result = $obj->hook->loader('upload_file', $sid, true);

                        $error = $obj->hook->upload_error;

                        if (empty($error)) {

                            redirect(_HOME . '/blog/manager/files/' . $sid . '/step2');
                        } else {

                            $data['errors'] = $error;
                        }
                    }
                }

                $data['files_allowed'] = 'Tất cả loại trừ: ' . $extension_not_allow_upload;
                $data['token'] = $security->get_token('token_add_file');
                $data['blog'] = $blog;
                $obj->view->data = $data;

                if (isset($_GET['remote'])) {

                    $obj->view->show('blog/manager/files/add_file_remote');
                } else {
                    $obj->view->show('blog/manager/files/add_file');
                }
                break;

            /**
             * edit file
             */
            case 'edit':

                if (empty($act_id)) {

                    redirect(_HOME . '/blog/manager/files/' . $sid . '/step2');
                }

                $file = $model->get_file_data($act_id);

                if (empty($file)) {

                    redirect(_HOME . '/blog/manager/files/' . $sid . '/step2');
                }

                if (isset($_POST['sub_edit'])) {

                    /**
                     * check if is lower levels of user file then blocked
                     */
                    if ($permission->is_lower_levels_of($file['uid'])) {

                        $data['errors'][] = 'Bạn không có quyền sửa file của cấp trên';

                    } else {

                        if (!$security->check_token('token_edit_file')) {

                            redirect(_HOME . '/blog/manager/files/' . $sid . '/step2');

                        } else {

                            $result = $obj->hook->loader('rename_file', $file, true);

                            if ($result) {

                                redirect(_HOME . '/blog/manager/files/' . $sid . '/step2');
                            } else {

                                $error = $obj->hook->upload_error;

                                $data['errors'] = $error;
                            }
                        }
                    }
                }

                $file['actions_editor'] = array();

                if (!empty($file['type'])) {

                    /**
                     * {type}_editor hook *
                     */
                    $file = $obj->hook->loader($file['type'] . '_editor', $file, true);

                }

                if (isset($file['process']['success'])) {
                    $data['success'] = $file['process']['success'];
                }
                if (isset($file['process']['notices'])) {
                    $data['notices'] = $file['process']['notices'];
                }
                if (isset($file['process']['success'])) {
                    $data['errors'] = $file['process']['errors'];
                }

                $data['file'] = $file;

                $data['token'] = $security->get_token('token_edit_file');
                $data['blog'] = $blog;

                $obj->view->data = $data;
                $obj->view->show('blog/manager/files/edit_file');
                break;

            case 'delete':

                if (empty($act_id)) {

                    redirect(_HOME . '/blog/manager/files/' . $sid . '/step2');
                }

                /**
                 * get file data
                 */
                $file = $model->get_file_data($act_id);

                if (empty($file)) {

                    redirect(_HOME . '/blog/manager/files/' . $sid . '/step2');
                }

                if (isset($_POST['sub_delete'])) {

                    /**
                     * check access
                     */
                    if ($permission->is_lower_levels_of($file['uid'])) {

                        $data['errors'][] = 'Bạn không có quyền xóa link của cấp trên';
                    } else {

                        if (!$security->check_token('token_delete_file')) {

                            redirect(_HOME . '/blog/manager/files/' . $sid . '/step2');

                        } else {

                            $result = $obj->hook->loader('delete_file', $file, true);

                            if (!$result) {

                                $data['notices'][] = 'Không thể xóa file. Vui lòng thử lại';
                            } else {

                                redirect(_HOME . '/blog/manager/files/' . $sid . '/step2');
                            }
                        }
                    }
                }

                $data['token'] = $security->get_token('token_delete_file');
                $data['blog'] = $blog;
                $data['file'] = $file;
                $obj->view->data = $data;
                $obj->view->show('blog/manager/files/delete_file');
                break;
        }
        break;
}
