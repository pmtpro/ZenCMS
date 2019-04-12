<?php
/**
 * name = Quản lí module
 * icon = fa fa-puzzle-piece
 * position = 40
 */
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
 * load helper
 */
load_helper('fhandle');
/**
 * load pclzip library
 */
$zip = load_library('pclzip');
/**
 * load library
 */
$parse = load_library('parse');
$security = load_library('security');
/**
 * get admin model
 */
$model = $obj->model->get('admin');

/**
 * get admin hook
 */
$hook = $obj->hook->get('admin');

$cache_file = __MODULES_PATH . '/modules.dat';

$act = '';
$act_id = '';

if (isset($app[1])) {
    $act = $security->cleanXSS($app[1]);
}
if (isset($app[2])) {
    $act_id = $security->cleanXSS($app[2]);
}

/**
 * set page menu
 */
$page_menu[] = array(
    'full_url' => HOME . '/admin/general/modules',
    'name' => 'Quản lí module',
    'title' => 'Quản lí module',
    'icon' => 'fa fa-puzzle-piece'
);
$page_menu[] = array(
    'full_url' => HOME . '/admin/general/modules/install',
    'name' => 'Cài đặt module',
    'title' => 'Cài đặt module',
    'icon' => 'fa fa-flash'
);
ZenView::set_menu(array(
    'pos' => 'page_menu',
    'menu' => $page_menu
));

/**
 * defined base breadcrumb
 */
$tree[] = url(HOME . '/admin', 'Admin CP');
$tree[] = url(HOME . '/admin/general', 'Tổng quan');
$tree[] = url(HOME . '/admin/general/modules', 'Modules');
ZenView::set_breadcrumb($tree);

switch ($act) {
    default:
        $list_protected = sysConfig('modules_protected');
        $protected = $list_protected;
        $activatedList = getActiveModule();
        $data['module_activated'] = array_keys($activatedList);

        run_hook('admin', 'module_actions', function($actions, $info) use ($data) {
            if ($info['activated']) {
                $actions[] = ZenView::gen_menu(array(
                    'full_url' => HOME . '/admin/general/modules?module=' . $info['package'] . '&action=deactivate',
                    'actID' => 'deactivate',
                    'name' => 'Hủy kích hoạt',
                    'icon' => 'fa fa-minus-square-o',
                ));
            } else {
                $actions[] = ZenView::gen_menu(array(
                    'full_url' => HOME . '/admin/general/modules?module=' . $info['package'] . '&action=activate',
                    'actID' => 'activate',
                    'name' => 'Kích hoạt',
                    'icon' => 'fa fa-check',
                ));
            }
            if ($info['readme_file']) $info['readme'] = file_get_contents($info['readme_file']);
            if (!empty($info['readme'])) {
                $actions[] = ZenView::gen_menu(array(
                    'full_url' => HOME . '/admin/general/modules/readme/' . $info['package'],
                    'actID' => 'readme',
                    'name' => 'Read me',
                    'icon' => 'fa fa-legal',
                ));
            }
            $actions[] = ZenView::gen_menu(array(
                'full_url' => HOME . '/admin/general/modules/info/' . $info['package'],
                'actID' => 'info',
                'name' => 'Xem thông tin',
                'icon' => 'fa fa-info',
            ));
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
            $actions[] = ZenView::gen_menu(array(
                'divider' => true,
                'full_url' => HOME . '/admin/general/modules/uninstall/' . $info['url'],
                'actID' => 'uninstall',
                'name' => 'Gỡ bỏ',
                'icon' => 'fa fa-trash-o',
            ));
            return $actions;
        }, 0);

        $list = scan_modules();
        $data['modules'] = $list;

        $data['module_list'] = array_keys($data['modules']);
        /**
         * get available module
         */
        $data['available_module'] = $data['module_list'];

        if (isset($_REQUEST['submit-modules']) || isset($_REQUEST['reloadAllModule'])) {

            if (isset($_REQUEST['reloadAllModule'])) {
                $_REQUEST['modules'] = $data['module_activated'];
            }
            if (!empty($_REQUEST['modules']) && is_array($_REQUEST['modules'])) {
                /**
                 * add module protected to active list
                 */
                foreach ($list_protected as $modProtected) {
                    if (!in_array($modProtected, $_REQUEST['modules'])) {
                        $_REQUEST['modules'][] = $modProtected;
                    }
                }
                $out = $_REQUEST['modules'];
                $cache = array();
                foreach ($out as $gMod) {
                    if (isset($data['modules'][$gMod])) {
                        if (isset($data['modules'][$gMod]['setting']['run'])) {
                            $cache[$gMod] = $data['modules'][$gMod]['setting']['run'];
                        } else {
                            $cache[$gMod] = array();
                        }
                    }
                }
                if (file_exists($cache_file)) {
                    changeMod($cache_file, 0644);
                }
                if (file_put_contents($cache_file, serialize($cache))) {
                    ZenView::set_success('Đã kích hoạt <b>' . count($out) . '</b> module', ZPUBLIC, true);
                } else {
                    ZenView::set_error('Không thế ghi file ' . $cache_file);
                }
            }
        } elseif (isset($_REQUEST['action'])) {

            if (!empty($_REQUEST['module'])) {
                /**
                 * clean xss
                 */
                $_REQUEST['module'] = $security->cleanXSS($_REQUEST['module']);
                /**
                 * make sure this mod is available
                 */
                if (in_array($_REQUEST['module'], $data['available_module'])) {

                    if (in_array($_REQUEST['module'], $list_protected)) {
                        ZenView::set_notice('Bạn không thể thực hiện thao tác này', ZPUBLIC, true);
                    } else {
                        /**
                         * activation
                         */
                        if ($_REQUEST['action'] == 'activate') {
                            if (in_array($_REQUEST['module'], $data['module_activated'])) {
                                ZenView::set_error('Module này đã được kích hoat trước đó!', ZPUBLIC, true);
                            } else {
                                if (!isset($list[$_REQUEST['module']]['setting']['run']) || !is_array($list[$_REQUEST['module']]['setting']['run'])) {
                                    $list[$_REQUEST['module']]['setting']['run'] = array();
                                }
                                $activatedList[$_REQUEST['module']] = $list[$_REQUEST['module']]['setting']['run'];
                                $msg_success = 'Đã kích hoạt module!';
                            }
                        } elseif ($_REQUEST['action'] == 'deactivate') {
                            if (!in_array($_REQUEST['module'], $data['module_activated'])) {
                                ZenView::set_error('Module này chưa hoạt động!', ZPUBLIC, true);
                            } else {
                                unset($activatedList[$_REQUEST['module']]);
                                $msg_success = 'Đã dừng hoạt động module!';
                            }
                        }
                        if ($msg_success) {
                            $cache = $activatedList;
                            if (file_exists($cache_file)) {
                                changeMod($cache_file, 0644);
                            }
                            if (file_put_contents($cache_file, serialize($cache))) {
                                ZenView::set_success($msg_success, ZPUBLIC, true);
                            } else {
                                ZenView::set_error('Không thể ghi dữ liệu', ZPUBLIC, true);
                            }
                        }
                    }
                } else {
                    ZenView::set_notice('Không tồn tại module này!', ZPUBLIC, true);
                }
            }
        }

        ZenView::set_title('Quản lí module');
        $obj->view->data = $data;
        $obj->view->show('admin/general/modules/index');
        break;
    case 'readme':
        $module_list = scan_modules();
        if (empty($act_id) || !in_array($act_id, array_keys($module_list))) {
            ZenView::set_error('Không tồn tại module này!', ZPUBLIC, HOME . '/admin/general/modules');
            exit;
        }
        $data['module'] = $module_list[$act_id];
        if ($data['module']['readme_file']) $data['module']['readme'] = file_get_contents($data['module']['readme_file']);
        else ZenView::set_notice('Không tìm thấy file readme.txt', ZPUBLIC, HOME . '/admin/general/modules');
        ZenView::set_title($act_id . ' | Readme');
        $obj->view->data = $data;
        $obj->view->show('admin/general/modules/readme');
        break;
    case 'info':
        $module_list = scan_modules();
        if (empty($act_id) || !in_array($act_id, array_keys($module_list))) {
            ZenView::set_error('Không tồn tại module này!', ZPUBLIC, HOME . '/admin/general/modules');
            exit;
        }
        $data['module'] = $module_list[$act_id];
        ZenView::set_title($act_id . ' | Thông tin module');
        $obj->view->data = $data;
        $obj->view->show('admin/general/modules/info');
        break;
    case 'install':
        $data = array();
        $get_filename = ZenInput::get('m');
        $filename = $get_filename ? base64_decode($get_filename) : '';
        if (ZenInput::get('_location') != 'tmp' || !$filename) {
            $data['install_process'] = false;
            $accept_format = array('zip');
            $data['accept_format'] = implode(', ', $accept_format);
            ZenView::set_title('Cài đặt module');
            ZenView::set_tip('Hỗ trợ định dạng ' . $data['accept_format'], 'module-accept-format');
            ZenView::set_tip('Bạn có thể tải lên module để cài đặt hoặc cài đặt trực tiếp module tại <a href="' . HOME . '/admin/general/modulescp?appFollow=browseAddOns/module">Tìm kiếm module mới</a>', 'install-notice');

            if (isset($_POST['submit-upload'])) {
                /**
                 * load upload library
                 */
                $upload = load_library('upload', array('init_data' => $_FILES['module']));
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
                        redirect(HOME . '/admin/general/modules/install?_location=tmp&m=' . base64_encode($dataUp['file_name']));
                    } else {
                        ZenView::set_error($upload->error);
                    }
                }
            }
        } else {
            $url_back = HOME . '/admin/general/modules';
            $get_back = ZenInput::get('back');
            if ($get_back) {
                $get_back = urldecode($get_back);
                $valid = load_library('validation');
                if ($valid->isValid('url', $get_back)) {
                    $url_back = $get_back;
                }
            }
            $data['install_process'] = true;
            /**
             * set page title
             */
            ZenView::set_title('Kiểm tra cài đặt module');
            $location = __TMP_DIR;
            $file_path = $location . '/' . $filename;
            if (!file_exists($file_path)) {
                redirect(HOME . '/admin/general/modules');
                exit;
            }

            $moduleObj = $model->read_package($file_path);

            if (!$model->is_valid_module($moduleObj)) {
                ZenView::set_error('Đây không phải một module hợp lệ');
                unlink($file_path);
            } else {
                $package_info = $model->read_package_info($moduleObj);
                if ($package_info === false) {
                    ZenView::set_error('Không thể đọc thông tin module này', ZPUBLIC, HOME . '/admin/general/modules');
                    exit;
                }
                $data['module_info'] = $package_info;
                $data['module_info']['file_size'] = size2text(filesize($file_path));

                $package_struct = $model->read_package_struct($moduleObj);
                if ($package_struct !== false) {
                    $data['module_struct'] = $package_struct;
                }

                $data['module_existed'] = false;
                $data['update_info'] = false;
                $module_name = $model->read_package_name($moduleObj);
                if ($model->module_exists($module_name)) {
                    $data['module_existed'] = true;
                    $data['updatable'] = 0;
                    $exists_module_info = $model->get_available_module_info($module_name);
                    $data['module_existed_info'] = $exists_module_info;
                    if ($package_info['version'] > $exists_module_info['version']) {
                        $data['updatable'] = 1;
                        $update_info = $model->read_package_update($moduleObj, $exists_module_info['version'], $package_info['version']);
                        if ($update_info) {
                            $data['update_info'] = $update_info;
                            ZenView::set_tip('Đây là <b>phiên bản mới</b> của một module đã có trong hệ thống. Vui lòng kiểm tra lại để cập nhật!');
                        } else {
                            ZenView::set_tip('Đây là <b>phiên bản mới</b> của một module đã có trong hệ thống. Vui lòng kiểm tra lại để cài đặt!');
                        }
                    } elseif ($package_info['version'] < $exists_module_info['version']) {
                        $data['updatable'] = -1;
                        ZenView::set_tip('Đây là <b>phiên bản cũ</b> của một module đã có trong hệ thống. Vui lòng kiểm tra lại để cài đặt!');
                    } else {
                        ZenView::set_tip('Đã tồn tại module này. Hãy kiểm tra lại thông tin trước khi cài đặt');
                    }
                }

                $data['folder_install_already_exists'] = false;
                if (isset($_POST['submit-confirm-install'])) {
                    /**
                     * check package: error, code error...
                     */
                    $check_package = $model->check_package($moduleObj);
                    if ($check_package === true) {
                        $check_folder_install = $model->module_dir_exists($module_name);
                        if (!$data['module_existed'] && $check_folder_install !== false && !isset($_POST['option-install'])) {
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
                                 * install module
                                 */
                                $results = $model->install_module($moduleObj);
                            } else {
                                /**
                                 * update module
                                 */
                                $results = $model->update_module($moduleObj, $data['update_info']);
                            }
                            if ($results !== true) {
                                ZenView::set_error($results);
                            } else {
                                unlink($file_path);
                                $set = $obj->settings->get($module_name);
                                if (method_exists($set, 'installer')) {
                                    if (!$set->installer()) {
                                        $installer = false;
                                    }
                                } else $installer = true;
                                if ($installer) {
                                    ZenView::set_success(1, ZPUBLIC, $url_back);
                                } else ZenView::set_notice('Đã cài đặt module. Tuy nhiên quá trình cài đặt không thành công', ZPUBLIC, $url_back);
                            }
                        }
                    } else {
                        ZenView::set_error('Module bạn vừa tải lên bị lỗi');
                        ZenView::set_notice($check_package, ZPUBLIC);
                    }
                } elseif (isset($_POST['submit-confirm-not-install'])) {
                    unlink($file_path);
                    ZenView::set_success('Đã hủy cài đặt', ZPUBLIC, $url_back);
                }
            }
        }

        if (!$data['module_struct']) {
            ZenView::set_notice('Không tìm thấy thông tin cấu trúc cho module này', 'more-info');
        }
        if (!$data['update_info']) {
            ZenView::set_notice('Không tìm thấy thông tin update cho module này', 'more-info');
        }

        ZenView::set_breadcrumb(url(HOME . '/admin/general/modules/install', 'Cài đặt module'));
        $obj->view->data = $data;
        $obj->view->show('admin/general/modules/install');
        break;
    case 'uninstall':
        $modules = scan_modules();
        $list_protected = sysConfig('modules_protected');
        if (empty($act_id) || !in_array($act_id, array_keys($modules))) {
            ZenView::set_error('Không tồn tại module này!', ZPUBLIC, HOME . '/admin/general/modules');
            exit;
        }
        if (in_array($act_id, $list_protected)) {
            ZenView::set_error('Bạn không thể gỡ bỏ module này vì lí do hệ thống', ZPUBLIC, HOME . '/admin/general/modules');
            exit;
        }
        if (isset($_POST['submit-cancel'])) {
            redirect(HOME . '/admin/general/modules');
            exit;
        }
        $data = array();
        $activated = getActiveModule();
        if (isset($_POST['submit-uninstall'])) {
            $module_path = __MODULES_PATH . '/' . $act_id;
            if (is_dir(($module_path))) {
                $set = $obj->settings->get($act_id);
                if (method_exists($set, 'uninstaller')) {
                    if (!$set->uninstaller()) {
                        $uninstaller = false;
                    }
                } else $uninstaller = true;
                $old_perm = fileperms(__MODULES_PATH);
                $perm_read = 0755;
                changeMod(__MODULES_PATH, $perm_read);
                changeMod($module_path, $perm_read);
                /**
                 * remove locale file
                 */
                rrmdir($module_path);
                changeMod(__MODULES_PATH, $old_perm);
                /**
                 * Remove module from list activated
                 */
                if (isset($activated[$act_id])) {
                    unset($activated[$act_id]);
                    $model->uninstall_module($act_id);
                }
                if (!empty($activated) && is_array($activated)) {
                    file_put_contents($cache_file, serialize($activated));
                }
                if ($uninstaller) {
                    ZenView::set_success(1, ZPUBLIC, HOME . '/admin/general/modules');
                } else {
                    ZenView::set_notice('Đã xóa module. Tuy nhiên không thể hoàn thành hủy cài đặt', HOME . '/admin/general/modules');
                }
            }
        }
        ZenView::set_title('Gỡ bỏ ' . $act_id);
        ZenView::set_tip('Bạn chắc chắn muốn gỡ bỏ module này, các tính năng của module sẽ ngừng hoạt động?');
        $obj->view->data = $data;
        $obj->view->show('admin/general/modules/uninstall');
        break;
    case 'checkUpdate':
        $modules = scan_modules();
        if (empty($act_id) || !isset($modules[$act_id])) {
            ZenView::set_error('Không tồn tại module này', ZPUBLIC, HOME . '/admin/general/modules');
            exit;
        }
        $data = array();
        $moduleData = $modules[$act_id];
        $result = $model->addon_check_update($act_id, 'module', $moduleData['version']);
        if ($result->status === 3) {
            ZenView::set_error('Không tồn tại module này trên ZenCMS Add-ons', ZPUBLIC, HOME . '/admin/general/modules');
        } elseif ($result->status === 2) {
            ZenView::set_error('Vui lòng kiểm tra lại cài đặt đồng bộ hoặc code', ZPUBLIC, HOME . '/admin/general/modules');
        } elseif ($result->status === 1) {
            ZenView::set_success('Bạn đang sử dụng phiên bản mới nhất của module này', ZPUBLIC, HOME . '/admin/general/modules');
        } else {
            ZenView::set_success('Có một phiên bản cần cập nhật');
            $data['old_version'] = $moduleData;
            $data['new_version'] = $result->data;
            if (isset($_POST['submit-update'])) {
                if (!$data['new_version']->amount || $data['new_version']->paid) {
                    redirect(genUrlAppFollow('browseAddOns') . '/install/' . $data['new_version']->type . '/' . $data['new_version']->pid);
                } else {
                    redirect(genUrlAppFollow('browseAddOns') . '/purchase/' . $data['new_version']->type . '/' . $data['new_version']->pid);
                }
            } elseif (isset($_POST['cancel'])) {
                redirect(HOME . '/admin/general/modules');
            }
        }
        ZenView::set_title($act_id . ' | Kiểm tra cập nhật');
        $obj->view->data = $data;
        $obj->view->show('admin/general/modules/checkUpdate');
        break;
}
