<?php
/**
 * name = Quản lí module
 * icon = icon-sitemap
 * position = 40
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
    'icon' => 'icon-sitemap'
);
$page_menu[] = array(
    'full_url' => HOME . '/admin/general/modules/upload',
    'name' => 'Tải lên module',
    'title' => 'Tải lên module',
    'icon' => 'icon-upload-alt'
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
        $list = scan_modules();
        $data['modules'] = array();
        $activatedList = get_list_modules();
        $data['module_activated'] = array_keys($activatedList);
        foreach ($list as $mod => $info) {
            if (in_array($info['url'], $data['module_activated'])) {
                $info['activated'] = true;
                $info['actions'][] = ZenView::gen_menu(array(
                    'full_url' => HOME . '/admin/general/modules?module=' . $info['url'] . '&action=deactivate',
                    'actID' => 'deactivate',
                    'name' => 'Hủy kích hoạt',
                    'icon' => 'icon-check-empty',
                ));
            } else {
                $info['activated'] = false;
                $info['actions'][] = ZenView::gen_menu(array(
                    'full_url' => HOME . '/admin/general/modules?module=' . $info['url'] . '&action=activate',
                    'actID' => 'activate',
                    'name' => 'Kích hoạt',
                    'icon' => 'icon-check',
                ));
            }

            if (!empty($info['option']) && is_array($info['option'])) {
                foreach($info['option'] as $url => $optionData) {
                    $info['actions'][] = ZenView::gen_menu(array(
                        'full_url' => $optionData['full_url'],
                        'actID' => 'option-' . $url,
                        'name' => $optionData['name'],
                        'title' => $optionData['title'],
                        'icon' => 'icon-cogs',
                    ));
                }
            }

            $info['readme'] = '';
            if ($info['readme_file']) {
                $info['readme'] = file_get_contents($info['readme_file']);
            }
            if ($info['readme']) {
                $info['actions'][] = ZenView::gen_menu(array(
                    'full_url' => HOME . '/admin/general/modules/readme/' . $info['url'],
                    'actID' => 'readme',
                    'name' => 'Read me',
                    'icon' => 'icon-legal',
                ));
            }

            $info['actions'][] = ZenView::gen_menu(array(
                'full_url' => HOME . '/admin/general/modules/info/' . $info['url'],
                'actID' => 'info',
                'name' => 'Xem thông tin',
                'icon' => 'icon-info-sign',
            ));
            $info['actions'][] = ZenView::gen_menu(array(
                'full_url' => HOME . '/admin/general/modules/update/' . $info['url'],
                'actID' => 'update',
                'name' => 'Cập nhật',
                'icon' => 'icon-arrow-up',
            ));
            $info['actions'][] = ZenView::gen_menu(array(
                'full_url' => HOME . '/admin/general/modules/uninstall/' . $info['url'],
                'actID' => 'uninstall',
                'name' => 'Gỡ bỏ',
                'icon' => 'icon-remove',
            ));

            $data['modules'][$mod] = $info;
        }

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
                foreach($list_protected as $modProtected) {
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
        $data['module_activated'] = get_list_modules();
        $data['menus'][HOME . '/admin/general/modules/upload'] = 'Tải lên';
        ZenView::set_title('Quản lí modules');
        $obj->view->data = $data;
        $obj->view->show('admin/general/modules/index');
        break;
    case 'readme':
        $module_list = scan_modules();
        if (empty($act_id) || !in_array($_REQUEST['module'], $data['available_module'])) {
            ZenView::set_error('Không tồn module này!', ZPUBLIC, HOME . '/admin/general/modules');
            exit;
        }
        break;
    case 'log':
        $modules = scan_modules();
        if ($_GET['_location'] != 'tmp') {
            redirect(HOME . '/admin/general/modules');
            exit;
        }
        $mod = '';
        if (isset($_GET['_m'])) {
            $mod = __TMP_DIR . '/' . hexToStr($_GET['_m']);
        }
        $file = $zip->PclZip($mod);
        $list = $zip->listContent();
        if (empty($list)) {
            @unlink($mod);
            ZenView::set_error('Không thể đọc module này', ZPUBLIC, HOME . '/admin/general/modules');
            exit;
        }
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
        if (empty($result)) {
            rrmdir($tmpdir);
            @unlink($mod);
            ZenView::set_error('Không thể giải nén module này', ZPUBLIC, HOME . '/admin/general/modules');
            exit;
        }
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
        if (in_array($name, array_keys($modules))) {
            $o_mod = __MODULES_PATH . '/' . $name . '/' . $name . '.info';
            if (file_exists($o_mod)) {
                $data['is_exists'] = true;
                $o_mod_info = $parse->ini_file($o_mod);
                $o_mod_info['url'] = $name;
                if ($info['version'] > $o_mod_info['version']) {
                    $data['updatable'] = true;
                    ZenView::set_tip('Đây là <b>phiên bản mới</b> của một module đã có trong hệ thống. Vui lòng kiểm tra lại để cập nhật!', 'module-updatable');
                } elseif ($info['version'] < $o_mod_info['version']) {
                    ZenView::set_tip('Đây là <b>phiên bản cũ</b> của một module đã có trong hệ thống!', 'module-updatable');
                } else {
                    ZenView::set_tip('Đã tồn tại module này. Hãy kiểm tra lại thông tin trước khi cập nhật');
                }
            }
        }
        /**
         * remove tmp directory
         */
        rrmdir($tmpdir);
        if (isset($_POST['submit-update'])) {
            $old_perm = fileperms(__MODULES_PATH);
            $perm_read = 0755;
            changeMod(__MODULES_PATH, $perm_read);
            if ($zip->extract(PCLZIP_OPT_PATH, __MODULES_PATH)) {
                @unlink($mod);
                changeMod(__MODULES_PATH, $old_perm);
                ZenView::set_success('Đã cập nhật thành công module', ZPUBLIC, HOME . '/admin/general/modules');
            } else {
                changeMod(__MODULES_PATH, $old_perm);
                ZenView::set_notice('Không thể cập nhật module này');
            }
        }
        if (isset($_POST['submit-delete'])) {
            @unlink($mod);
            redirect(HOME . '/admin/general/modules');
        }
        $data['mod'] = $info;
        $data['o_mod'] = $o_mod_info;
        ZenView::set_title('Thông tin module');
        ZenView::set_breadcrumb(url(HOME . '/admin/general/modules/upload', 'Tải lên module'));
        $obj->view->data = $data;
        $obj->view->show('admin/general/modules/log');
        return;
        break;
    case 'upload':
        $accept_format = array('rar', 'zip');
        $data['accept_format'] = implode(', ', $accept_format);
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
                $upload->file_overwrite = true;
                $upload->allowed = $accept_format;
                $uploadPath = __TMP_DIR;
                $upload->process($uploadPath);

                if ($upload->processed) {
                    /**
                     * get data up
                     */
                    $dataUp = $upload->data();
                    $modules = scan_modules();
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
                    $module_name = rtrim($tempName, '/');
                    /**
                     * List file to check
                     */
                    $check = array(
                        $tempName,
                        $tempName . $module_name . '.info',
                        $tempName . $module_name . 'Controller.php',
                        $tempName . $module_name . 'Settings.php'
                    );
                    if (empty($list)) {
                        ZenView::set_error('Không thể đọc file này: ' . $zip->error_string);
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
                                ZenView::set_notice('Module này không đúng định dạng');
                                $fail = TRUE;
                                unlink($dataUp['full_path']);
                                break;
                            }
                        }
                        if ($fail == false) {
                            if (in_array($module_name, array_keys($modules))) {
                                redirect(HOME . '/admin/general/modules/log?_location=tmp&_m=' . strToHex($dataUp['file_name']));
                                return;
                            } else {
                                $old_perm = fileperms(__MODULES_PATH);
                                $perm_read = 0755;
                                changeMod(__MODULES_PATH, $perm_read);
                                if ($zip->extract(PCLZIP_OPT_PATH, __MODULES_PATH)) {
                                    unlink($dataUp['full_path']);
                                    changeMod(__MODULES_PATH, $old_perm);
                                    ZenView::set_success('Cài đặt module thành công, vui lòng kích hoạt module trước khi sử dụng', ZPUBLIC, HOME . '/admin/general/modules');
                                } else {
                                    changeMod(__MODULES_PATH, $old_perm);
                                    ZenView::set_notice('Không thể giải nén file này');
                                    unlink($dataUp['full_path']);
                                }
                            }
                        }
                    }
                } else {
                    ZenView::set_error($upload->error);
                }
            }
        }
        ZenView::set_title('Tải lên module');
        ZenView::set_tip('Hỗ trợ định dạng ' . $data['accept_format'], 'module-accept-format');
        ZenView::set_breadcrumb(url(HOME . '/admin/general/modules/upload', 'Tải lên module'));
        $obj->view->data = $data;
        $obj->view->show('admin/general/modules/upload');
        break;
    case 'uninstall':
        $modules = scan_modules();
        $list_protected = sysConfig('modules_protected');
        if (empty($act_id) || !in_array($act_id, array_keys($modules))) {
            ZenView::set_error('Không tồn module này!', ZPUBLIC, HOME . '/admin/general/modules');
            exit;
        }
        if (isset($_POST['submit-cancel'])) {
            redirect( HOME . '/admin/general/modules');
            exit;
        }
        $activated = get_list_modules();
        if (isset($_POST['submit-uninstall'])) {
            $module_path = __MODULES_PATH . '/' . $act_id;
            if (is_dir(($module_path))) {
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
                ZenView::set_success(1, ZPUBLIC, HOME . '/admin/general/modules');
            }
        }
        ZenView::set_title('Gỡ bỏ ' . $act_id);
        ZenView::set_tip('Bạn chắc chắn muốn gỡ bỏ module này, các tính năng của module sẽ ngừng hoạt động?');
        $obj->view->data = $data;
        $obj->view->show('admin/general/modules/uninstall');
        break;
}
