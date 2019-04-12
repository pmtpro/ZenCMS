<?php
/**
 * name = Quản lí template
 * icon = fa fa-font
 * position = 20
 */
/**
 * ZenCMS Software
 * Copyright 2012-2014 ZenCMS Team
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
 * @copyright 2012-2014 ZenCMS Team
 * @author ZenCMS Team
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
 * get widget model
 */
$widgetModel = $obj->model->get('widget');

/**
 * get admin hook
 */
$hook = $obj->hook->get('admin');

$page_menu[] = array(
    'full_url' => HOME . '/admin/general/templates/config',
    'name' => 'Cấu hình giao diện',
    'title' => 'Cấu hình giao diện',
    'icon' => 'fa fa-cog'
);
$page_menu[] = array(
    'full_url' => HOME . '/admin/general/templates/install',
    'name' => 'Cài đặt giao diện',
    'icon' => 'fa fa-flash'
);
$page_menu[] = array(
    'full_url' => HOME . '/admin/general/templates',
    'name' => 'Danh sách giao diện',
    'title' => 'Danh sách giao diện',
    'icon' => 'fa fa-list-ol'
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

/**
 * defined base breadcrumb
 */
$tree[] = url(HOME . '/admin', 'Admin CP');
$tree[] = url(HOME . '/admin/general', 'Tổng quan');
$tree[] = url(HOME . '/admin/general/templates', 'Cài đặt giao diện');
ZenView::set_breadcrumb($tree);

$function_add_template_action = function($actions, $info) {
    $actions[] = array(
        'name' => 'Chỉnh sửa',
        'icon' => 'fa fa-pencil',
        'full_url' => HOME . '/admin/general/templates/edit/' . $info['package']
    );
    $actions[] = array(
        'name' => 'Widget',
        'icon' => 'fa fa-file-code-o',
        'full_url' => HOME . '/admin/general/templates/widget/' . $info['package']
    );
    if (!empty($info['options']) && is_array($info['options'])) {
        foreach ($info['options'] as $url => $optionData) {
            $actions[] = ZenView::gen_menu(array(
                'full_url' => $optionData['full_url'],
                'actID' => 'option-' . $url,
                'name' => $optionData['name'],
                'title' => $optionData['title'],
                'icon' => 'fa fa-cogs',
            ));
        }
    }
    $actions[] = array(
        'divider' => true,
        'name' => 'Gỡ bỏ',
        'icon' => 'fa fa-trash-o',
        'full_url' => HOME . '/admin/general/templates/uninstall/' . $info['package']
    );
    return $actions;
};

switch ($act) {
    default:
        run_hook('admin', 'template_actions', $function_add_template_action, 0);
        $data['templates'] = scan_templates();
        ZenView::set_title('Danh sách giao diện');
        ZenView::set_breadcrumb(url(HOME . '/admin/general/templates', 'Quản lí template'));
        ZenView::set_tip('Có tất cả <b>' . count($data['templates']) . '</b> template trong dữ liệu của bạn', 'template-number-temp');
        $obj->view->data = $data;
        $obj->view->show('admin/general/templates/index');
        break;
    case 'config':
        $data['templates'] = scan_templates();
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
        $obj->view->show('admin/general/templates/config');
        break;
    case 'detail':
        run_hook('admin', 'template_actions', $function_add_template_action, 0);
        $data['templates'] = scan_templates();
        $templates = $data['templates'];
        $temp_list = array_keys($templates);
        if (!in_array($act_id, $temp_list) || !isset($templates[$act_id]) || $templates[$act_id]['protected'] == true) {
            ZenView::set_error('Không tồn tại giao diện này', ZPUBLIC, HOME . '/admin/general/templates');
            exit;
        }
        $data['info'] = $data['templates'][$act_id];
        ZenView::set_title('Giao diện: ' . $data['info']['name']);
        ZenView::set_breadcrumb(url(HOME . '/admin/general/templates', 'Quản lí template'));
        ZenView::set_breadcrumb(url(HOME . '/admin/general/templates/detail/' . $act_id, $data['info']['name']));
        $obj->view->data = $data;
        $obj->view->show('admin/general/templates/detail');
        break;
    case 'setting':
        $templates = scan_templates();
        $listTemp = array_keys($templates);
        if (!isset($act_id) || !in_array($act_id, $listTemp)) {
            ZenView::set_notice('Không tồn tại giao diện này', ZPUBLIC, HOME . '/admin/general/templates');
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
        ZenView::set_breadcrumb(url(HOME . '/admin/general/templates', 'Quản lí template'));
        ZenView::set_breadcrumb(url(HOME . '/admin/general/templates/detail/' . $setTemplate, $templates[$setTemplate]['name']));
        ZenView::set_breadcrumb(url(HOME . '/admin/general/templates/setting/' . $setTemplate, 'Cài đặt: ' . $templates[$setTemplate]['name']));
        $obj->view->data = $data;
        $obj->view->show('admin/general/templates/setting');
        break;
    case 'edit':
        $data['templates'] = scan_templates();
        $templates = $data['templates'];
        $temp_list = array_keys($templates);
        if (!in_array($act_id, $temp_list) || !isset($templates[$act_id]) || $templates[$act_id]['protected'] == true) {
            redirect(HOME . '/admin/general/templates');
            exit;
        }
        $data['base_url'] = HOME . '/admin/general/templates/edit/' . $act_id;
        $data['info'] = $templates[$act_id];
        /**
         * load php_file_tree helper
         */
        load_helper('php_file_tree');

        /**
         * load security library
         */
        $security = load_library('security');

        $base_dir = __TEMPLATES_PATH . '/' . $act_id;
        $_get_file = ZenInput::get('file', true);
        if (!$_get_file) {
            $_get_file = 'page.php';
        }
        $file_edit = urldecode($_get_file);
        $file_ext = end(explode('.', $file_edit));
        $file_path = $base_dir . '/' . $file_edit;
        $data['file_name'] = basename($file_edit);
        if (file_exists($file_path)) {
            if (is_writeable($file_path)) {
                $data['is_writable'] = true;
            } else {
                $data['is_writable'] = false;
                ZenView::set_notice('Chú ý: File chỉ có thể đọc');
            }

            $file_protected = $model->list_file_valid_template($act_id);
            if (in_array($act_id . '/' . $file_edit, $file_protected)) {
                $data['allow_rename'] = false;
            } else {
                $data['allow_rename'] = true;
            }

            /**
             * on submit save
             */
            if (isset($_POST['submit-save'])) {
                $file_content = $_POST['file_content'];
                $file_name = $_POST['file_name'];

                $rename_complete = true;
                /**
                 * if allow rename
                 */
                if ($data['allow_rename']) {
                    if ($file_name != trim(basename($file_path), '/')) {
                        if (preg_match('/[^\/\?\*:;\{\}\\]+\.[^\/\?\*:;\{\}\\]+/i', $file_name)) {
                            ZenView::set_error('Tên file không đúng định dạng');
                            $rename_complete = false;
                        } else {
                            $parent_path = substr($file_path, 0, strlen($file_path) - strlen(basename($file_path)));
                            $parent_path = rtrim($parent_path, '/');
                            $new_path = $parent_path . '/' . $file_name;
                            if (file_exists($new_path)) {
                                ZenView::set_error('Tên file đã tồn tại');
                            }  else {
                                if (!rename($file_path, $new_path)) {
                                    ZenView::set_error('Không thể đổi tên');
                                    $rename_complete = false;
                                }
                            }
                        }
                    }
                }
                /**
                 * valid file name
                 */
                if ($rename_complete) {
                    if (isset($_POST['file_content'])) {
                        $php = load_library('phpcodechecker');
                        $php->set_code($file_content);
                        if ($php->load_api()) {
                            if ($php->checker()) {
                                if (!file_put_contents($file_path, $file_content)) {
                                    ZenView::set_error('Không thể ghi file. Vui lòng gửi lại');
                                } else {
                                    ZenView::set_success(1);
                                }
                            } else {
                                ZenView::set_error('Syntax error: ' . $php->get_error());
                            }
                        } else {
                            ZenView::set_error($php->get_error());
                        }
                    } else {
                        ZenView::set_success(1);
                    }
                }
            }

            /**
             * get file content and file name
             */
            $data['file_name'] = h(basename($file_edit));
            $allow_edit = array('txt', 'hta', 'htaccess', 'conf', 'ini', 'php', 'php4', 'php5', 'html', 'html5', 'htm', 'xml', 'xhtml', 'shtml', 'mhtml', 'css', 'css3', 'js', 'java', 'vb', 'c', 'cpp', 'basic', 'pas', 'p', 'update', 'struct', 'info', 'sql', 'mdb', 'db');
            $data['is_image'] = false;
            $data['is_download'] = false;
            if (in_array($file_ext, $allow_edit)) {
                /**
                 * load gadget helper
                 */
                load_helper('gadget');
                /**
                 * init edit area
                 */
                gadget_editarea('file_content', $file_ext);
                $data['file_content'] = h(file_get_contents($file_path));
            } else {
                $data['download_link'] = _URL_TEMPLATES . '/' . $act_id . '/' . $file_edit;
                $image_ext = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
                if (in_array($file_ext, $image_ext)) {
                    $data['is_image'] = true;
                }
            }
        } else {
            ZenView::set_error('Không tồn tại file này');
        }

        $data['tree'] = php_file_tree($base_dir, $data['base_url'] . '?file=[short_link]');
        ZenView::add_js(_URL_FILES_SYSTEMS . '/js/php_file_tree/php_file_tree_jquery.js', 'foot');
        ZenView::add_css(_URL_FILES_SYSTEMS . '/styles/php_file_tree/style.css');
        ZenView::set_title('Chỉnh sửa: ' . $data['info']['name']);
        ZenView::set_breadcrumb(url(HOME . '/admin/general/templates', 'Quản lí template'));
        ZenView::set_breadcrumb(url(HOME . '/admin/general/templates/detail/' . $data['info']['package'], $data['info']['name']));
        ZenView::set_breadcrumb(url($data['base_url'], 'Chỉnh sửa'));
        $obj->view->data = $data;
        $obj->view->show('admin/general/templates/edit');
        break;
    case 'widget':
        $data['templates'] = scan_templates();
        $templates = $data['templates'];
        $listTemp = array_keys($templates);
        if (!isset($act_id) || !in_array($act_id, $listTemp)) {
            ZenView::set_notice('Không tồn tại giao diện này', ZPUBLIC, HOME . '/admin/general/templates');
            exit;
        }
        $template_edit = $act_id;
        $data['info'] = $templates[$act_id];
        $list = $widgetModel->list_template_widget_group($act_id);
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
        if (empty($data['widget_groups'])) {
            ZenView::set_notice('Giao diện này không sử dụng widget');
        }
        $data['base_url'] = HOME . '/admin/general/templates/widget/' . $act_id;
        ZenView::set_breadcrumb(url(HOME . '/admin/general/templates', 'Quản lí template'));
        ZenView::set_breadcrumb(url(HOME . '/admin/general/templates/detail/' . $data['info']['package'], $data['info']['name']));
        /**
         * Load action
         */
        $_get_act = ZenInput::get('act');
        switch ($_get_act) {
            default:
                if (isset($_POST['submit-order']) && is_array($_POST['submit-order'])) {
                    foreach ($_POST['submit-order'] as $group => $val) {
                        foreach ($_POST['weight'] as $num => $weight) {
                            $update = array();
                            $widgetID = (int) $security->removeSQLI($num);
                            $update['weight'] = $weight;
                            if ($widgetModel->widget_exists($widgetID)) {
                                $widgetModel->update_widget($widgetID, $update);
                            }
                        }
                    }
                }

                /**
                 * renew data widget_groups_data
                 */
                $list = $widgetModel->list_template_widget_group($act_id);
                $data['widget_groups_data'] = $list;
                ZenView::set_title('Danh sách widget: ' . $templates[$act_id]['name']);
                ZenView::set_breadcrumb(url(HOME . '/admin/general/templates/widget/' . $act_id, 'Widget'));
                $obj->view->data = $data;
                $obj->view->show('admin/general/templates/widget/index');
                break;
            case 'edit':
                $_get_id = ZenInput::get('id');
                $id = $_get_id ? (int) $security->removeSQLI($_get_id) : 0;
                $data['widget_data'] = $widgetModel->get_widget_data($id);
                if (empty($id) || empty($data['widget_data'])) {
                    ZenView::set_error('Không tồn tại widget này', ZPUBLIC, $data['base_url']);
                    exit;
                }

                if (isset($_POST['submit-save'])) {
                    $updateWidget['title'] = isset($_POST['title']) ? h($_POST['title']) : '';
                    $updateWidget['content'] = isset($_POST['content']) ? h($_POST['content']) : '';
                    $updateWidget['callback'] = isset($_POST['content']) ? h($_POST['callback']) : '';
                    if (empty($updateWidget['content'])) {
                        ZenView::set_error('Chưa có nội dung widget');
                    } else {
                        if (!$widgetModel->update_widget($id, $updateWidget)) {
                            ZenView::set_error('Lỗi dữ liệu, vui lòng thử lại');
                        } else ZenView::set_success(1);
                    }
                }

                /**
                 * renew widget data
                 */
                $data['widget_data'] = $widgetModel->get_widget_data($id);
                ZenView::set_title('Chỉnh sửa widget: ' . ($data['widget_data']['title'] ? $data['widget_data'] : 'Không tiêu đề'));
                ZenView::set_breadcrumb(url(HOME . '/admin/general/templates/widget/' . $act_id, 'Widget: ' . $templates[$act_id]['name']));
                $obj->view->data = $data;
                $obj->view->show('admin/general/templates/widget/edit');
                break;
            case 'new':
                $_get_wg = ZenInput::get('wg', true);
                if (!$_get_wg || !in_array($_get_wg, array_keys($list_widget_group))) {
                    ZenView::set_error('Không tồn tại vị trí này', ZPUBLIC, $data['base_url']);
                    exit;
                }
                $position = urldecode($_get_wg);
                $data['group'] = $position;

                if (isset($_POST['submit-save'])) {
                    $insertWidget['title'] = isset($_POST['title']) ? h($_POST['title']) : '';
                    $insertWidget['content'] = isset($_POST['content']) ? h($_POST['content']) : '';
                    $insertWidget['callback'] = isset($_POST['content']) ? h($_POST['callback']) : '';
                    $insertWidget['wg'] = $position;
                    $insertWidget['template'] = $template_edit;
                    if (empty($insertWidget['content'])) {
                        ZenView::set_error('Chưa có nội dung widget');
                    } else {
                        if (!$widgetModel->insert_widget($insertWidget)) {
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
                $_get_id = ZenInput::get('id');
                $id = $_get_id ? (int) $security->removeSQLI($_get_id) : 0;
                $data['widget_data'] = $widgetModel->get_widget_data($id);
                if (empty($id) || empty($data['widget_data'])) {
                    ZenView::set_error('Không tồn tại widget này', ZPUBLIC, $data['base_url']);
                    exit;
                }
                if (isset($_POST['submit-delete'])) {
                    if (!$widgetModel->delete_widget($id) ) {
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
    case 'install':
        $data['templates'] = scan_templates();
        $_get_t = ZenInput::get('t');
        $filename = $_get_t ? base64_decode($_get_t) : '';
        if (ZenInput::get('_location') != 'tmp' || !$filename) {
            $data['install_process'] = false;
            $accept_format = array('zip');
            $data['accept_format'] = implode(', ', $accept_format);
            ZenView::set_title('Cài đặt template');
            ZenView::set_tip('Hỗ trợ định dạng ' . $data['accept_format'], 'template-accept-format');
            ZenView::set_tip('Bạn có thể tải lên template để cài đặt hoặc cài đặt trực tiếp template tại <a href="' . HOME . '/admin/general/modulescp?appFollow=browseAddOns/template">Tìm kiếm template mới</a>', 'install-notice');

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
                    $upload->file_overwrite = false;
                    $upload->allowed = $accept_format;
                    $uploadPath = __TMP_DIR;
                    $upload->process($uploadPath);

                    if ($upload->processed) {
                        /**
                         * get data up
                         */
                        $dataUp = $upload->data();
                        redirect(HOME . '/admin/general/templates/install?_location=tmp&t=' . base64_encode($dataUp['file_name']));
                    } else {
                        ZenView::set_error($upload->error);
                    }
                }
            }
        } else {
            $data['install_process'] = true;
            /**
             * set page title
             */
            ZenView::set_title('Kiểm tra cài đặt template');
            $location = __TMP_DIR;
            $file_path = $location . '/' . $filename;
            if (!file_exists($file_path)) {
                redirect(HOME . '/admin/general/templates');
                exit;
            }

            $templateObj = $model->read_package($file_path);

            if (!$model->is_valid_template($templateObj)) {
                ZenView::set_error('Đây không phải một template hợp lệ');
                unlink($file_path);
            } else {
                $package_info = $model->read_package_info($templateObj);
                if ($package_info === false) {
                    ZenView::set_error('Không thể đọc thông tin template này', ZPUBLIC, HOME . '/admin/general/templates');
                    exit;
                }
                $data['template_info'] = $package_info;
                $data['template_info']['file_size'] = size2text(filesize($file_path));

                $package_struct = $model->read_package_struct($templateObj);
                if ($package_struct !== false) {
                    $data['template_struct'] = $package_struct;
                }

                $data['template_existed'] = false;
                $data['update_info'] = false;
                $template_name = $model->read_package_name($templateObj);
                if ($model->template_exists($template_name)) {
                    $data['template_existed'] = true;
                    $data['updatable'] = 0;
                    $exists_template_info = $model->get_available_template_info($template_name);
                    $data['template_existed_info'] = $exists_template_info;
                    if ($package_info['version'] > $exists_template_info['version']) {
                        $data['updatable'] = 1;
                        $update_info = $model->read_package_update($templateObj, $exists_template_info['version'], $package_info['version']);
                        if ($update_info) {
                            $data['update_info'] = $update_info;
                            ZenView::set_tip('Đây là <b>phiên bản mới</b> của một template đã có trong hệ thống. Vui lòng kiểm tra lại để cập nhật!');
                        } else {
                            ZenView::set_tip('Đây là <b>phiên bản mới</b> của một template đã có trong hệ thống. Vui lòng kiểm tra lại để cài đặt!');
                        }
                    } elseif ($package_info['version'] < $exists_template_info['version']) {
                        $data['updatable'] = -1;
                        ZenView::set_tip('Đây là <b>phiên bản cũ</b> của một template đã có trong hệ thống. Vui lòng kiểm tra lại để cài đặt!');
                    } else {
                        ZenView::set_tip('Đã tồn tại template này. Hãy kiểm tra lại thông tin trước khi cài đặt');
                    }
                }

                $data['folder_install_already_exists'] = false;
                if (isset($_POST['submit-confirm-install'])) {
                    /**
                     * check package: error, code error...
                     */
                    $check_package = $model->check_package($templateObj);
                    if ($check_package === true) {
                        $check_folder_install = $model->template_dir_exists($template_name);
                        if (!$data['template_existed'] && $check_folder_install !== false && !isset($_POST['option-install'])) {
                            ZenView::set_notice('Đã tồn tại thư mục cài trong hệ thống. Vui lòng lựa chọn để tiếp tục cài đặt');
                            $data['folder_install_already_exists'] = true;
                        } else {
                            if (isset($_POST['option-install'])) {
                                if ($_POST['option-install'] == 'remove') {
                                    /**
                                     * remove all file and folder in exists dir
                                     */
                                    rrmdir($check_folder_install);
                                }
                            }
                            if (!$data['update_info']) {
                                /**
                                 * install template
                                 */
                                $results = $model->install_template($templateObj);
                            } else {
                                /**
                                 * update template
                                 */
                                $results = $model->update_template($templateObj, $data['update_info']);
                            }
                            if ($results !== true) {
                                ZenView::set_error($results);
                            } else {
                                unlink($file_path);
                                ZenView::set_success(1, ZPUBLIC, HOME . '/admin/general/templates');
                            }
                        }
                    } else {
                        ZenView::set_error('Template bạn vừa tải lên bị lỗi');
                        ZenView::set_notice($check_package, ZPUBLIC);
                    }
                } elseif (isset($_POST['submit-confirm-not-install'])) {
                    unlink($file_path);
                    ZenView::set_success('Đã hủy cài đặt', ZPUBLIC, HOME . '/admin/general/templates');
                }
            }
        }

        if (!$data['template_struct']) {
            ZenView::set_notice('Không tìm thấy thông tin cấu trúc cho template này', 'more-info');
        }
        if (!$data['template_info']) {
            ZenView::set_notice('Không tìm thấy thông tin update cho template này', 'more-info');
        }

        ZenView::set_breadcrumb(url(HOME . '/admin/general/templates/install', 'Cài đặt template'));
        $obj->view->data = $data;
        $obj->view->show('admin/general/templates/install');
        break;
    case 'uninstall':
        $data['templates'] = scan_templates();
        $templates = $data['templates'];
        $temp_list = array_keys($templates);
        if (!in_array($act_id, $temp_list) || !isset($templates[$act_id]) || $templates[$act_id]['protected'] == true) {
            redirect(HOME . '/admin/general/templates');
            exit;
        }

        $temp = $templates[$act_id];
        $data['info'] = $temp;
        $data['current'] = getActiveTemplate();
        if (in_array($act_id, $data['current'])) {
            ZenView::set_notice('Template này đang được sử dụng', ZPUBLIC, HOME . '/admin/general/templates');
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

                ZenView::set_success(1, ZPUBLIC, HOME . '/admin/general/templates');
            }
        }

        ZenView::set_title('Gỡ bỏ giao diện: ' . $temp['name']);
        ZenView::set_tip('Một khi đã gỡ bỏ giao diện, mọi thông tin về giao diện sẽ bị xóa khỏi hệ thống. Bạn có chắc chắn muốn gỡ bỏ giao diện này không?');
        ZenView::set_breadcrumb(url(HOME . '/admin/general/templates', 'Quản lí template'));
        ZenView::set_breadcrumb(url(HOME . '/admin/general/templates/detail/' . $temp['package'], $temp['name']));
        $obj->view->data = $data;
        $obj->view->show('admin/general/templates/uninstall');
        return;
        break;
}