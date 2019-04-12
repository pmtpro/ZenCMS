<?php
/**
 * name = Templates
 * icon = icon-adjust
 * position = 20
 */
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

/**
 * load helper
 */
load_helper('fhandle');
load_helper('time');
/**
 * load library
 */
$security = load_library('security');
$validation = load_library('validation');
$security = load_library('security');

/**
 * load library
 */
$parse = load_library('parse');

/**
 * load pclzip library
 */
$zip = load_library('pclzip');

/**
 * get admin model
 */
$model = $obj->model->get('admin');

/**
 * get admin hook
 */
$obj->hook->get('admin');

$page_menu[] = array(
    'full_url' => HOME . '/admin/general/templates',
    'name' => 'Cài đặt giao diện',
    'title' => 'Cài đặt giao diện',
    'icon' => 'icon-cog'
);
$page_menu[] = array(
    'full_url' => HOME . '/admin/general/templates/import',
    'name' => 'Tải lên giao diện',
    'title' => 'Tải lên giao diện',
    'icon' => 'icon-upload-alt'
);
$page_menu[] = array(
    'full_url' => HOME . '/admin/general/templates/list',
    'name' => 'Danh sách giao diện',
    'title' => 'Danh sách giao diện',
    'icon' => 'icon-list'
);
ZenView::set_menu(array(
    'pos' => 'page_menu',
    'menu' => $page_menu
));

$data['current'] = getActiveTemplate();
$list_os[] = 'iOS';
$list_os[] = 'AndroidOS';
$list_os[] = 'JavaOS';
$list_os[] = 'SymbianOS';
$list_os[] = 'WindowsPhoneOS';
$data['device_os'] = $list_os;

$act = '';
$act_id = '';

if (isset($app[1])) {
    $act = $security->cleanXSS($app[1]);
}
if (isset($app[2])) {
    $act_id = $security->cleanXSS($app[2]);
}

$data['templates'] = scan_templates();

foreach ($data['templates'] as $key => $value) {

    if ($key == 'default') {
        $data['templates'][$key]['protected'] = true;
    } else {
        $data['templates'][$key]['protected'] = false;
    }
}

/**
 * defined base breadcrumb
 */
$tree[] = url(HOME . '/admin', 'Admin CP');
$tree[] = url(HOME . '/admin/general', 'Tổng quan');
$tree[] = url(HOME . '/admin/general/templates', 'Cài đặt giao diện');
ZenView::set_breadcrumb($tree);

switch ($act) {

    default:
        if (isset($_POST['submit-general']) || isset($_POST['submit-os'])) {
            $error = '';
            if (isset($_POST['submit-os'])) {
                $list_general = $list_os;
                $do = 'os';
            } elseif (isset($_POST['submit-general'])) {
                $list_general[] = 'Mobile';
                $list_general[] = 'other';
                $do = 'general';
            }
            $db_set = $data['current'];

            foreach ($list_general as $general) {
                if (isset($_POST[$general])) {
                    if ((isset($data['templates'][$_POST[$general]]) && is_dir($data['templates'][$_POST[$general]]['full_path'])) || empty($_POST[$general])) {
                        $db_set[$general] = $_POST[$general];
                        $update['templates'] = serialize($db_set);
                        $doSave = file_put_contents(__TEMPLATES_PATH . '/templates.dat', serialize($db_set));
                        if ($doSave) {
                            $data['current'] = getActiveTemplate(true);
                        } else {
                            $error[$do] = 'Lỗi dữ liệu';
                        }
                    } else {
                        $error[$do] = 'Không tồn tại giao diện này';
                    }
                } else {
                    $error[$do] = 'Bạn chưa chọn giao diện của mình';
                }
            }

            if (empty($error)) {
                ZenView::set_success(1, $do . '-template-setting');
            } else {
                ZenView::set_error($error[$do], $do . '-template-setting');
            }
        }
        $data['current'] = getActiveTemplate();
        ZenView::set_title('Quản lí giao diện');
        ZenView::set_tip('Đây là 2 cài đặt cơ bản cho website', 'general-template-setting');
        ZenView::set_tip('Nếu không chọn một trong các mục ở đây, template MOBILE hoặc PC hoặc DEFAULT sẽ được kích hoạt tùy từng thiết bị truy cập', 'os-template-setting');
        $obj->view->data = $data;
        $obj->view->show('admin/general/templates/index');
        break;
    case 'list':
        ZenView::set_title('Danh sách giao diện');
        ZenView::set_breadcrumb(url(HOME . '/admin/general/templates/list', 'Danh sách giao diện'));
        ZenView::set_tip('Có tất cả <b>' . count($data['templates']) . '</b> template trong dữ liệu của bạn', 'template-number-temp');
        $obj->view->data = $data;
        $obj->view->show('admin/general/templates/list');
        break;
    case 'import':
        /**
         * load upload library
         */
        $accept_format = array('rar', 'zip');
        $data['accept_format'] = implode(', ', $accept_format);
        if (isset($_POST['submit-upload'])) {
            /**
             * load upload library
             */
            $upload = load_library('upload', array('init_data' => $_FILES['template']));
            /**
             * check uploaded
             */
            if ($upload->uploaded) {
                /**
                 * config upload
                 */
                $upload->file_overwrite = true;
                $upload->allowed = $accept_format;
                $uploadPath = __TMP_DIR;
                $upload->process($uploadPath);

                if ($upload->processed) {
                    /**
                     * get data up
                     */
                    $dataUp = $upload->data();
                    $zipName = preg_replace('/' . $dataUp['file_ext'] . '$/is', '', $dataUp['file_name']);
                    $file = $zip->PclZip($dataUp['full_path']);
                    $list = $zip->listContent();
                    if (strpos($list[0]['filename'], '/') !== false) {
                        $hash_file_name = explode('/', $list[0]['filename']);
                        $tempName = $hash_file_name[0] . '/';
                        $list[] = array(
                            'filename' => $tempName,
                            'stored_filename' => $tempName,
                            'size' => 0,
                            'compressed_size' => 0,
                            'folder' => true,
                            'index' => 0,
                            'status' => 0,
                            'crc' => 0
                        );
                    } else {
                        $tempName = $list[0]['filename'];
                    }
                    $template_name = rtrim($tempName, '/');
                    $check = array(
                        $tempName,
                        $tempName . $template_name . '.info',
                        $tempName . 'run.php',
                        $tempName . 'config.php'
                    );
                    if (empty($list)) {
                        ZenView::set_error('Không thể đọc file này<br/>' . $zip->error_string, ZPUBLIC);
                        unlink($dataUp['full_path']);
                    } else {
                        $fail = FALSE;
                        foreach ($check as $checkFile) {
                            $found = false;
                            foreach ($list as $zipFile) {
                                if ($checkFile == $zipFile['filename']) {
                                    $found = true;
                                    break;
                                }
                            }
                            if ($found == false) {
                                ZenView::set_notice('Template này không đúng định dạng', ZPUBLIC);
                                $fail = TRUE;
                                break;
                            }
                        }

                        if ($fail) {
                            unlink($dataUp['full_path']);
                        } else {
                            if (in_array($template_name, array_keys($data['templates']))) {
                                ZenView::set_notice('Gói giao diện này đã tồn tại trong hệ thống!', ZPUBLIC, HOME . '/admin/general/templates/log?_location=tmp&_t=' . base64_encode($dataUp['file_name']));
                                return;
                            } else {
                                $old_perm = fileperms(__TEMPLATES_PATH);
                                $perm_read = 0755;
                                changeMod(__TEMPLATES_PATH, $perm_read);
                                if ($zip->extract(PCLZIP_OPT_PATH, __TEMPLATES_PATH)) {

                                    unlink($dataUp['full_path']);
                                    changeMod(__TEMPLATES_PATH, $old_perm);
                                    ZenView::set_success('Đã tải lên giao diện', ZPUBLIC, HOME . '/admin/general/templates/list');
                                } else {

                                    ZenView::set_notice('Không thể giải nén file này', ZPUBLIC);
                                    changeMod(__TEMPLATES_PATH, $old_perm);
                                    unlink($dataUp['full_path']);
                                }
                            }
                        }
                    }
                } else {
                    ZenView::set_error($upload->error, ZPUBLIC);
                }
            }
        }

        ZenView::set_title('Tải lên giao diện');
        ZenView::set_breadcrumb(url(HOME . '/admin/general/templates/import', 'Tải lên giao diện'));
        ZenView::set_tip('Hỗ trợ định dạng ' . $data['accept_format'], 'template-accept-format');
        $obj->view->data = $data;
        $obj->view->show('admin/general/templates/import');
        break;
    case 'log':
        $templates = scan_templates();
        if ($_GET['_location'] == 'tmp') {
            $temp = '';
            if (isset($_GET['_t'])) {
                $temp = __TMP_DIR . '/' . base64_decode($_GET['_t']);
            }
            $file = $zip->PclZip($temp);
            $list = $zip->listContent();

            if (empty($list)) {
                @unlink($temp);
                ZenView::set_error('Không thể đọc file này', ZPUBLIC, HOME . '/admin/general/templates/list');
            } else {

                $tmpdir = tempDir();
                if (strpos($list[0]['filename'], '/') !== false) {
                    $hash_file_name = explode('/', $list[0]['filename']);
                    $name = $hash_file_name[0];
                    $list[] = array(
                        'filename' => $tempName,
                        'stored_filename' => $tempName,
                        'size' => 0,
                        'compressed_size' => 0,
                        'folder' => true,
                        'index' => 0,
                        'status' => 0,
                        'crc' => 0
                    );
                } else {
                    $name = rtrim($list[0]['filename'], '/');
                }
                $result = $zip->extract(
                    PCLZIP_OPT_PATH, $tmpdir,
                    PCLZIP_OPT_BY_NAME, $name . '/' . $name . '.info'
                );
                if (empty ($result)) {
                    rrmdir($tmpdir);
                    unlink($temp);
                    ZenView::set_error('Không thể giải nén gói giao diện này', ZPUBLIC, HOME . '/admin/general/templates/list');
                } else {

                    $file_tmp = $result[0]['filename'];
                    $info = $parse->ini_file($file_tmp);

                    if (empty($info['name'])) {
                        $info['name'] = 'Unknown';
                    }
                    if (empty($info['version'])) {
                        $info['version'] = '0.0';
                    }
                    if (empty($info['author'])) {
                        $info['author'] = 'Unknown';
                    }
                    if (empty($info['des'])) {
                        $info['des'] = 'none';
                    }

                    $info['url'] = $name;
                    $data['updatable'] = false;
                    $data['is_exists'] = false;

                    if (in_array($name, array_keys($templates))) {

                        $o_temp = __TEMPLATES_PATH . '/' . $name . '/' . $name . '.info';

                        if (file_exists($o_temp)) {
                            $data['is_exists'] = true;
                            $o_temp_info = $parse->ini_file($o_temp);
                            $o_temp_info['url'] = $name;
                            if ($info['version'] > $o_temp_info['version']) {
                                $data['updatable'] = true;
                                ZenView::set_tip('Đây là một <b>phiên bản mới</b> của một giao diện đã có trong hệ thống. Vui lòng kiểm tra lại và cập nhật!', 'template-updatable');
                            } elseif ($info['version'] < $o_temp_info['version']) {
                                ZenView::set_tip('Đây là một <b>phiên bản cũ</b> của một giao diện đã có trong hệ thống!', 'template-updatable');
                            } else {
                                ZenView::set_tip('Đã tồn tại giao diện này. Hãy kiểm tra lại thông tin trước khi cập nhật');
                            }
                        }
                    }

                    rrmdir($tmpdir);

                    if (isset($_POST['submit-update'])) {

                        $old_perm = fileperms(__TEMPLATES_PATH);
                        $perm_read = 0755;
                        changeMod(__TEMPLATES_PATH, $perm_read);
                        if ($zip->extract(PCLZIP_OPT_PATH, __TEMPLATES_PATH)) {
                            @unlink($temp);
                            changeMod(__TEMPLATES_PATH, $old_perm);
                            ZenView::set_success(1, ZPUBLIC, HOME . '/admin/general/templates/list');
                        } else {
                            changeMod(__TEMPLATES_PATH, $old_perm);
                            ZenView::set_notice('Không thể cập nhật template này');
                        }
                    }

                    if (isset($_POST['submit-delete'])) {
                        @unlink($temp);
                        redirect(HOME . '/admin/general/templates/list');
                    }

                    ZenView::set_title('Thông tin template');
                    $data['temp'] = $info;
                    $data['o_temp'] = $o_temp_info;
                    ZenView::set_breadcrumb(url(HOME . '/admin/general/templates/import', 'Tải lên'));
                    $obj->view->data = $data;
                    $obj->view->show('admin/general/templates/log');
                    return;
                }
            }

        } else
            redirect(HOME . '/admin/general/templates/list');
        break;
    case 'uninstall':
        $templates = $data['templates'];
        $temp_list = array_keys($templates);
        if (!in_array($act_id, $temp_list) || !isset($templates[$act_id]) || $templates[$act_id]['protected'] == true) {
            redirect(HOME . '/admin/general/templates/list');
            exit;
        }

        $temp = $templates[$act_id];
        $data['current'] = getActiveTemplate();
        if (in_array($act_id, $data['current'])) {
            ZenView::set_notice('Template này đang được sử dụng', ZPUBLIC, HOME . '/admin/general/templates/list');
        } else {
            if (isset($_POST['submit-uninstall'])) {
                $temp_path = __TEMPLATES_PATH . '/' . $act_id;
                $old_perm = fileperms(__TEMPLATES_PATH);
                $perm_read = 0755;
                changeMod(__TEMPLATES_PATH, $perm_read);
                changeMod($temp_path, $perm_read);
                /**
                 * remove locate file
                 */
                rrmdir($temp_path);
                changeMod(__TEMPLATES_PATH, $old_perm);
                /**
                 * remove template config from database
                 */
                $model->uninstall_template($act_id);

                ZenView::set_success(1, ZPUBLIC, HOME . '/admin/general/templates/list');
            }
        }

        ZenView::set_title('Uninstall ' . $temp['name']);

        ZenView::set_breadcrumb(url(HOME . '/admin/general/templates/list', 'Danh sách'));
        $obj->view->data = $data;
        $obj->view->show('admin/general/templates/uninstall');
        return;
        break;
    case 'widget':
        $templates = scan_templates();
        $listTemp = array_keys($templates);
        if (!isset($act_id) || !in_array($act_id, $listTemp)) {
            ZenView::set_notice('Không tồn tại giao diện này', ZPUBLIC, HOME . '/admin/general/templates/list');
            exit;
        }
        $template_edit = $act_id;
        $list = $model->list_template_widget_group($act_id);
        $data['widget_groups_data'] = $list;

        $list_template = $data['templates'];
        /**
         * Create private region data to get $GLOBALS['widgets']
         * @param array $widget_s
         * @return array
         */
        $get_widget_callback = function($widget_s) {
            $before_widget = $GLOBALS['widgets'];
            unset($GLOBALS['widgets']);
            include $widget_s['full_path'] . '/run.php';
            $after_widget = $GLOBALS['widgets'];
            $GLOBALS['widgets'] = $before_widget;
            return $after_widget;
        };
        $out = call_user_func($get_widget_callback, $list_template[$template_edit]);
        $data['widget_list'] = $out;
        $list_widget_group = array_keys($out);
        $data['widget_groups'] = $list_widget_group;

        $data['base_url'] = HOME . '/admin/general/templates/widget/' . $act_id;
        ZenView::set_breadcrumb(url(HOME . '/admin/general/templates/list', 'Danh sách'));
        /**
         * Load action
         */
        switch ($_GET['act']) {
            default:
                if (isset($_POST['submit-order']) && is_array($_POST['submit-order'])) {
                    foreach ($_POST['submit-order'] as $group => $val) {
                        foreach ($_POST['weight'] as $num => $weight) {
                            $update = array();
                            $widgetID = (int) $security->removeSQLI($num);
                            $update['weight'] = $weight;
                            if ($model->widget_exists($widgetID)) {
                                $model->update_widget($widgetID, $update);
                            }
                        }
                    }
                }

                /**
                 * renew data widget_groups_data
                 */
                $list = $model->list_template_widget_group($act_id);
                $data['widget_groups_data'] = $list;
                ZenView::set_title('Danh sách widget: ' . $templates[$act_id]['name']);
                ZenView::set_breadcrumb(url(HOME . '/admin/general/templates/widget/' . $act_id, 'Widget: ' . $templates[$act_id]['name']));
                $obj->view->data = $data;
                $obj->view->show('admin/general/templates/widget/widget');
                break;
            case 'edit':
                $id = isset($_GET['id']) ? (int) $security->removeSQLI($_GET['id']) : 0;
                $data['widget_data'] = $model->get_widget_data($id);
                if (empty($id) || empty($data['widget_data'])) {
                    ZenView::set_error('Không tồn tại widget này', ZPUBLIC, $data['base_url']);
                    exit;
                }

                if (isset($_POST['submit-save'])) {
                    $updateWidget['title'] = isset($_POST['title']) ? h($_POST['title']) : '';
                    $updateWidget['content'] = isset($_POST['content']) ? h($_POST['content']) : '';
                    if (empty($updateWidget['content'])) {
                        ZenView::set_error('Chưa có nội dung widget');
                    } else {
                        if (!$model->update_widget($id, $updateWidget)) {
                            ZenView::set_error('Lỗi dữ liệu, vui lòng thử lại');
                        } else ZenView::set_success(1);
                    }
                }

                /**
                 * renew widget data
                 */
                $data['widget_data'] = $model->get_widget_data($id);
                ZenView::set_title('Chỉnh sửa widget: ' . ($data['widget_data']['title'] ? $data['widget_data'] : 'Không tiêu đề'));
                ZenView::set_breadcrumb(url(HOME . '/admin/general/templates/widget/' . $act_id, 'Widget: ' . $templates[$act_id]['name']));
                $obj->view->data = $data;
                $obj->view->show('admin/general/templates/widget/edit');
                break;
            case 'new':
                if (empty($_GET['wg']) || !in_array($_GET['wg'], array_keys($list_widget_group))) {
                    ZenView::set_error('Không tồn tại vị trí này', ZPUBLIC, $data['base_url']);
                    exit;
                }
                $position = $security->cleanXSS(urldecode($_GET['wg']));
                $data['group'] = $position;

                if (isset($_POST['submit-save'])) {
                    $insertWidget['title'] = isset($_POST['title']) ? h($_POST['title']) : '';
                    $insertWidget['content'] = isset($_POST['content']) ? h($_POST['content']) : '';
                    $insertWidget['wg'] = $position;
                    $insertWidget['template'] = $template_edit;
                    if (empty($insertWidget['content'])) {
                        ZenView::set_error('Chưa có nội dung widget');
                    } else {
                        if (!$model->insert_widget($insertWidget)) {
                            ZenView::set_error('Lỗi dữ liệu, vui lòng thử lại');
                        } else ZenView::set_success(1, ZPUBLIC, $data['base_url']);
                    }
                }

                ZenView::set_title('Tạo widget');
                ZenView::set_breadcrumb(url(HOME . '/admin/general/templates/widget/' . $act_id, 'Widget: ' . $templates[$act_id]['name']));
                $obj->view->data = $data;
                $obj->view->show('admin/general/templates/widget/new');
                break;
            case 'delete':
                $id = isset($_GET['id']) ? (int) $security->removeSQLI($_GET['id']) : 0;
                $data['widget_data'] = $model->get_widget_data($id);
                if (empty($id) || empty($data['widget_data'])) {
                    ZenView::set_error('Không tồn tại widget này', ZPUBLIC, $data['base_url']);
                    exit;
                }
                if (isset($_POST['submit-delete'])) {
                    if (!$model->delete_widget($id) ) {
                        ZenView::set_error('Không thể xóa widget này. Vui lòng thử lại');
                    } else ZenView::set_success(1, ZPUBLIC, $data['base_url']);
                }
                ZenView::set_tip('Widget này thuộc nhóm <b>'  . $data['widget_data']['wg'] . '</b> của template <b>' . $data['templates'][$template_edit]['name'] . ' (' . $template_edit . ')</b>. Bạn chắc chắn muốn xóa widget này?');
                ZenView::set_title('Xóa widget');
                ZenView::set_breadcrumb(url(HOME . '/admin/general/templates/widget/' . $act_id, 'Widget: ' . $templates[$act_id]['name']));
                $obj->view->data = $data;
                $obj->view->show('admin/general/templates/widget/delete');
                break;
        }
        break;
    case 'setting':
        $templates = scan_templates();
        $listTemp = array_keys($templates);
        if (!isset($act_id) || !in_array($act_id, $listTemp)) {
            ZenView::set_notice('Không tồn tại giao diện này', ZPUBLIC, HOME . '/admin/general/templates/list');
            exit;
        }
        $setTemplate = $act_id;
        $settingFile = __TEMPLATES_PATH . '/' . $setTemplate . '/setting/setting.inc.php';
        $mapFile = __TEMPLATES_PATH . '/' . $setTemplate . '/setting/setting.map.php';
        if (!file_exists($settingFile)) {
            ZenView::set_notice('Không có cài đặt nào', ZPUBLIC, HOME . '/admin/general/templates');
            exit;
        }
        $data['template'] = $templates[$setTemplate];
        include $settingFile;
        $data['call_map'] = function() use ($mapFile) {
            global $registry;
            include $mapFile;
        };
        ZenView::set_title('Cài đặt: ' . $templates[$setTemplate]['name']);
        ZenView::set_breadcrumb(url(HOME . '/admin/general/templates/setting/' . $setTemplate, 'Cài đặt: ' . $templates[$setTemplate]['name']));
        $obj->view->data = $data;
        $obj->view->show('admin/general/templates/setting');
        break;
}
