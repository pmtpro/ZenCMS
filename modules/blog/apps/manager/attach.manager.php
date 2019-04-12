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

$user = $obj->user;
/**
 * load library
 */
$seo = load_library('seo');
$security = load_library('security');
$libPerm = load_library('permission');
$libPerm->set_user($user);
/**
 * get blog model
 */
$model = $obj->model->get('blog');
$model->set_filter('status', array(0,1,2));

/**
 * get blog hook
 */
$hook = $obj->hook->get('blog');

/**
 * set base url
 */
$base_url = HOME . '/admin/general/modulescp?appFollow=blog/manager';


if (isset($_GET['id'])) {
    $id = (int) $security->removeSQLI($_GET['id']);
} else $id = 0;

if (empty($id) || !$model->blog_exists($id))  {
    ZenView::set_error('Không tồn tại bài này', ZPUBLIC, $base_url . '/cpanel');
    exit;
}

$current_url = $base_url . '/attach&id=' . $id;

$data['blog'] = $model->get_blog_data($id);

if (isset($_GET['editLink'])) {
    $linkID = (int) $security->removeSQLI($_GET['editLink']);
    if ($model->link_exists($linkID)) {
        $data['link'] = $model->get_link_data($linkID);
        if ($libPerm->is_lower_levels_of($data['link']['uid'])) {
            ZenView::set_error('Bạn không thể chỉnh sửa link của cấp cao hơn mình', 'link-editor');
            unset($data['link']);
        } else {
            $data['link_editor_id'] = $linkID;
            if (isset($_POST['submit-del-link'])) {
                if (!$model->delete_link($data['link_editor_id'])) {
                    ZenView::set_error('Không thể xóa link này. Vui lòng thử lại', 'link-editor');
                } else {
                    ZenView::set_success(1, 'link-editor', $current_url);
                }
            }
        }
    }
}
/**
 * Check user clicked button
 */
if (isset($_POST['submit-link'])) {
    $name = $security->cleanXSS($_POST['name']);
    if (empty($name)) {
        ZenView::set_error('Không được bỏ trống tên link', 'link-editor');
    }
    /**
     * valid_data_attach_name_link hook*
     */
    $name = $hook->loader('valid_data_attach_name_link', $name);

    $link = $security->cleanXSS($_POST['link']);
    if (empty($link)) {
        ZenView::set_error('Không được bỏ trống link', 'link-editor');
    }
    /**
     * valid_data_attach_link hook*
     */
    $link = $hook->loader('valid_data_attach_link', $link);
    if (ZenView::is_success('link-editor')) {
        if (!empty($_POST['link_editor_id'])) {
            $update_link['name'] = $name;
            $update_link['link'] = $link;
            if (!$model->update_link($data['link_editor_id'], $update_link)) {
                ZenView::set_error('Lỗi trong khi sửa link', 'link-editor');
            } else {
                ZenView::set_success(1, 'link-editor', $current_url);
            }
        } else {
            $insert_link['uid'] = $user['id'];
            $insert_link['sid'] = $id;
            $insert_link['name'] = $name;
            $insert_link['link'] = $link;
            $insert_link['time'] = time();
            if (!$model->insert_link($insert_link)) {
                ZenView::set_error('Lỗi trong khi thêm link', 'link-editor');
            } else ZenView::set_success(1, 'link-editor');
        }
    }
}

$data['links'] = $model->get_links($id);
if (empty($data['links'])) {
    ZenView::set_notice('Chưa có link đính kèm nào', 'link-list');
}

/**
 * Start file
 */
/**
 * set location upload file
 */
$location_file = __FILES_PATH . '/posts/files_upload';
if (isset($_GET['editFile'])) {
    $fileID = (int) $security->removeSQLI($_GET['editFile']);
    if ($model->file_exists($fileID)) {
        $data['file'] = $model->get_file_data($fileID);
        /**
         * get file name
         */
        $hash_url = explode('/', $data['file']['url']);
        $hash_dot = explode('.', end($hash_url));
        $real_ext = end($hash_dot);
        $sub_file_dir = $hash_url[0];
        $data['file']['file_name'] = basename($data['file']['url'], '.' . $real_ext);
        if ($libPerm->is_lower_levels_of($data['file']['uid'])) {
            ZenView::set_error('Bạn không thể chỉnh sửa file của cấp cao hơn mình', 'file-editor');
            unset($data['file']);
        } else {
            $data['file_editor_id'] = $fileID;
            if (isset($_POST['submit-file'])) {
                if (empty($_POST['name'])) {
                    ZenView::set_error('Không thể bỏ trống tên file', 'file-editor');
                } else {
                    /**
                     * valid_data_attach_name hook*
                     */
                    $name = $hook->loader('valid_data_attach_name', $security->cleanXSS($_POST['name']));
                }
                if (empty($_POST['file_name'])) {
                    ZenView::set_error('Không được để trống tên file', 'file-editor');
                } else {
                    /**
                     * valid_data_attach_file_name hook*
                     */
                    $file_name = $hook->loader('valid_data_attach_file_name', $security->cleanXSS($_POST['file_name']));
                }
                if (ZenView::is_success('file-editor') && !empty($file_name) && !empty($name)) {
                    $hash_file_name = explode('.', $file_name);
                    $ext = end($hash_file_name);
                    if ($ext != $data['file']['type']) {
                        $file_name = $file_name . '.' . $data['file']['type'];
                    }
                    $new_url =  $sub_file_dir . '/' . $file_name;
                    $old_path = $location_file . '/' . $data['file']['url'];
                    $new_path = $location_file . '/' . $new_url;
                    if (file_exists($old_path) && is_writable($old_path)) {
                        if (rename($old_path, $new_path)){
                            $update_file['name'] = $name;
                            $update_file['url'] = $new_url;
                            if ($model->update_file($fileID, $update_file)) {
                                ZenView::set_success(1, 'file-editor', $current_url);
                            } else {
                                ZenView::set_error('Không thể đổi tên file này', 'file-editor');
                                rename($new_path, $old_path);
                            }
                        }
                    } else ZenView::set_error('Không thể đổi tên file này', 'file-editor');
                }
            }
            if (isset($_POST['submit-del-file'])) {
                if (file_exists($data['file']['full_path']) && is_writable($data['file']['full_path'])) {
                    /**
                     * Delete file in host
                     */
                    if (unlink($data['file']['full_path'])) {
                        /**
                         * Delete data file in database
                         */
                        if (!$model->delete_file($data['file_editor_id'])) {
                            ZenView::set_error('Không thể xóa file này. Vui lòng thử lại', 'file-editor');
                        } else {
                            ZenView::set_success(1, 'file-editor', $current_url);
                        }
                    } else {
                        ZenView::set_error('ZenCMS không thể xóa file này. Vui lòng thử lại', 'file-editor');
                    }
                } else ZenView::set_error('ZenCMS có quyền xóa file này trên host. Vui lòng thử lại', 'file-editor');
            }
        }
    }
}

/**
 * number_file_per_upload hook*
 */
$data['number_file_per_upload'] = $hook->loader('number_file_per_upload', 3);

if (isset($_POST['submit-upload'])) {
    $upload_success = 0;
    $upload_fail = 0;
    foreach ($_FILES['file']['name'] as $key=>$fName) {
        if (!empty($fName)) {
            $dataFile['name'] = $fName;
            $dataFile['type'] = $_FILES['file']['type'][$key];
            $dataFile['tmp_name'] = $_FILES['file']['tmp_name'][$key];
            $dataFile['error'] = $_FILES['file']['error'][$key];
            $dataFile['size'] = $_FILES['file']['size'][$key];
            $upload = load_library('upload', array('init_data' => $dataFile));
            if ($upload->uploaded) {
                /**
                 * config upload
                 */
                $upload->file_overwrite = false;
                if (!empty ($_POST['file_name'][$key])) {
                    /**
                     * valid_data_attach_file_name hook*
                     */
                    $file_name = $hook->loader('valid_data_attach_file_name', $security->cleanXSS($_POST['file_name'][$key]), array('var' => array('blog' => $data['blog'])));
                } else {
                    $file_name = $data['blog']['url'];
                }
                $upload->file_new_name_body = $file_name;
                $subDir = autoMkSubDir($location_file);
                $upload->process($location_file . '/' . $subDir);
                if ($upload->processed) {
                    /**
                     * get data after upload
                     */
                    $dataUp = $upload->data();
                    if (!empty($_POST['name'][$key])) {
                        /**
                         * valid_data_attach_name hook*
                         */
                        $name = $hook->loader('valid_data_attach_name', $security->cleanXSS($_POST['name'][$key]), array('var' => array('blog' => $data['blog'])));
                    } else {
                        $name = $dataUp['file_name'];
                    }
                    if (!ZenView::is_success('file-editor')) {
                        unlink($dataUp['full_path']);
                        $upload_fail++;
                    } else {
                        $insert_file['uid'] = $user['id'];
                        $insert_file['sid'] = $id;
                        $insert_file['name'] = $name;
                        $insert_file['url'] = $subDir . '/' . $dataUp['file_name'];
                        $insert_file['size'] = $dataUp['file_size'];
                        $insert_file['type'] = $dataUp['file_name_ext'];
                        $insert_file['time'] = time();
                        if ($model->insert_file($insert_file)) {
                            $upload_success++;
                        } else {
                            $upload_fail++;
                        }
                    }
                } else ZenView::set_error($upload->error, 'file-editor');
            } else ZenView::set_error($upload->error, 'file-editor');
        }
    }
    if (empty($upload_success)) {
        ZenView::set_error('Upload file lỗi. Vui lòng thử lại', 'file-editor');
    } else {
        if (empty($upload_fail)) {
            ZenView::set_success('Upload thành công ' . $upload_success . ' file', 'file-editor');
        } else {
            ZenView::set_notice('Upload hoàn thành ' . $upload_success . ' file, upload lỗi ' . $upload_fail . ' file', 'file-editor');
        }
    }
}
$data['files'] = $model->get_files($id);
if (empty($data['files'])) {
    ZenView::set_notice('Chưa có file nào được upload', 'file-list');
}
$data['base_url'] = $base_url;
$data['current_url'] = $current_url;
/**
 * add breadcrumb
 */
$tree[] = url($base_url, 'Quản lí blog');
$tree[] = url($base_url . '/cpanel', 'Quản lí nội dung');
$tree[] = url($base_url . '/editor&id=' . $id, $data['blog']['name']?$data['blog']['name']:'Không tiêu đề');
ZenView::set_breadcrumb($tree);
ZenView::set_title('Quản lí đính kèm');
$obj->view->data = $data;
$obj->view->show('blog/manager/attach');