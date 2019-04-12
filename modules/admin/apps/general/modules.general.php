<?php
/**
 * name = Quản lí modules
 * icon = admin_general_modules
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

$cache_file = __MODULES_PATH . '/modules.dat';

$act = '';
$act_id = '';

if (isset($app[1])) {

    $act = $security->cleanXSS($app[1]);
}

if (isset($app[2])) {

    $act_id = $security->cleanXSS($app[2]);
}

switch ($act) {

    case 'readinfo':

        $modules = scan_modules();

        if ($_GET['_location'] == 'tmp') {

            $mod = '';

            if (isset($_GET['_m'])) {

                $mod = __TMP_DIR . '/' . hexToStr($_GET['_m']);
            }

            $file = $zip->PclZip($mod);

            $list = $zip->listContent();

            if (empty($list)) {

                @unlink($mod);
                redirect(_HOME . '/admin/general/modules');

            } else {

                $tmpdir = tempdir();

                $name = trim($list[0]['filename'], '/');

                $result = $zip->extract(
                    PCLZIP_OPT_PATH, $tmpdir,
                    PCLZIP_OPT_BY_NAME, $name . '/' . $name . '.info'
                );

                if (empty ($result)) {

                    rrmdir($tmpdir);
                    @unlink($mod);
                    redirect(_HOME . '/admin/general/modules');

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
                    if (empty($info['id'])) {

                        $info['id'] = 'NO ID';
                    }

                    $info['url'] = $name;

                    $data['update'] = false;

                    $data['is_exists'] = false;

                    if (in_array($name, array_keys($modules))) {

                        $o_mod = __MODULES_PATH . '/' . $name . '/' . $name . '.info';

                        if (file_exists($o_mod)) {

                            $data['is_exists'] = true;

                            $o_mod_info = $parse->ini_file($o_mod);
                            $o_mod_info['url'] = $name;

                            if ($info['version'] > $o_mod_info['version']) {

                                $data['update'] = true;
                            }
                        }
                    }

                    rrmdir($tmpdir);

                    if (isset($_POST['sub_update'])) {

                        $old_perm = fileperms(__MODULES_PATH);

                        $perm_read = 0755;

                        changemod(__MODULES_PATH, $perm_read);

                        if ($zip->extract(PCLZIP_OPT_PATH, __MODULES_PATH)) {

                            @unlink($mod);
                            changemod(__MODULES_PATH, $old_perm);
                            redirect(_HOME . '/admin/general/modules');

                        } else {
                            changemod(__MODULES_PATH, $old_perm);
                            $data['notices'][] = 'Không thể cập nhật module này';
                        }
                    }

                    if (isset($_POST['sub_delete'])) {

                        @unlink($mod);
                        redirect(_HOME . '/admin/general/modules');
                    }

                    $data['mod'] = $info;
                    $data['o_mod'] = $o_mod_info;

                    $data['page_title'] = 'Thông tin module';
                    $data['menus'][_HOME . '/admin/general/modules'] = 'Quản lí module';

                    $tree[] = url(_HOME . '/admin', 'Admin CP');
                    $tree[] = url(_HOME . '/admin/general', 'Tổng quan');
                    $tree[] = url(_HOME . '/admin/general/modules', 'Quản lí module');
                    $data['display_tree'] = display_tree($tree);
                    $obj->view->data = $data;
                    $obj->view->show('admin/general/modules/readinfo');
                    return;
                }
            }

        } else {

            redirect(_HOME . '/admin/general/modules');
        }

        break;

    case 'upload':

        /**
         * load upload library
         */
        $upload = load_library('upload');

        $accept = array('zip', 'rar');

        $data['accept_format'] = implode(', ', $accept);

        if (isset($_POST['sub_upload'])) {

            /**
             * set upload path
             */
            $config['upload_path'] = __TMP_DIR;
            $config['allowed_types'] = $accept;
            $config['overwrite'] = TRUE;
            $config['seo_name'] = FALSE;
            $upload->initialize($config);

            if ($upload->do_upload('module')) {

                /**
                 * get data up
                 */
                $dataup = $upload->data();

                $modules = scan_modules();

                $zipname = preg_replace('/' . $dataup['file_ext'] . '$/is', '', $dataup['file_name']);

                $file = $zip->PclZip($dataup['full_path']);

                $list = $zip->listContent();

                $tempname = $list[0]['filename'];

                $module_name = rtrim($tempname, '/');

                $check = array($tempname, $tempname . $module_name . '.info', $tempname . $module_name . 'Controller.php', $tempname . $module_name . 'Settings.php');

                if (empty($list)) {

                    $data['errors'][] = 'Không thể đọc file này<br/>' . $zip->error_string;

                    @unlink($dataup['full_path']);

                } else {

                    $failer = FALSE;

                    foreach ($check as $checkfile) {

                        $finded = false;

                        foreach ($list as $zipfile) {

                            if ($checkfile == $zipfile['filename']) {

                                $finded = true;

                                break;
                            }
                        }

                        if ($finded == false) {

                            $data['notices'][] = 'Module này không đúng định dạng';

                            $failer = TRUE;

                            @unlink($dataup['full_path']);
                            break;
                        }
                    }

                    if ($failer == false) {

                        if (in_array($module_name, array_keys($modules))) {

                            redirect(_HOME . '/admin/general/modules/readinfo?_location=tmp&_m=' . strToHex($dataup['file_name']));

                            return;

                        } else {

                            $old_perm = fileperms(__MODULES_PATH);

                            $perm_read = 0755;

                            changemod(__MODULES_PATH, $perm_read);

                            if ($zip->extract(PCLZIP_OPT_PATH, __MODULES_PATH)) {

                                @unlink($dataup['full_path']);
                                changemod(__MODULES_PATH, $old_perm);
                                redirect(_HOME . '/admin/general/modules');

                            } else {

                                changemod(__MODULES_PATH, $old_perm);

                                $data['notices'][] = 'Không thể giải nén file này';

                                @unlink($dataup['full_path']);
                            }
                        }
                    }
                }

            } else {

                $data['errors'] = $upload->error;
            }
        }

        $data['menus'][_HOME . '/admin/general/modules'] = 'Trở lại';

        $data['page_title'] = 'Tải lên module';
        $tree[] = url(_HOME . '/admin', 'Admin CP');
        $tree[] = url(_HOME . '/admin/general', 'Tổng quan');
        $tree[] = url(_HOME . '/admin/general/modules', 'Quản lí module');
        $data['display_tree'] = display_tree($tree);

        $obj->view->data = $data;
        $obj->view->show('admin/general/modules/upload');
        break;

    case 'uninstall':

        $modules = scan_modules();

        $list_protected = sys_config('modules_protected');

        if (empty($act_id) || !in_array($act_id, array_keys($modules))) {

            redirect(_HOME . '/admin/general/modules');
            exit;
        }
        $actived = get_list_modules();

        if (isset($_POST['sub_uninstall'])) {

            $module_path = __MODULES_PATH . '/' . $act_id;

            if (is_dir(($module_path))) {

                $old_perm = fileperms(__MODULES_PATH);

                $perm_read = 0755;

                changemod(__MODULES_PATH, $perm_read);

                changemod($module_path, $perm_read);

                rrmdir($module_path);

                changemod(__MODULES_PATH, $old_perm);

                foreach ($actived[APP] as $app) {

                    if ($app == $act_id) {

                        unset($actived[APP][$act_id]);
                        break;
                    }
                }
                foreach ($actived[BACKGROUND] as $bg) {

                    if ($bg == $act_id) {

                        unset($actived[BACKGROUND][$act_id]);
                        break;
                    }
                }

                if (!empty($actived) && is_array($actived)) {

                    file_put_contents($cache_file, serialize($actived));
                }

                redirect(_HOME . '/admin/general/modules');
            }
        }

        $data['menus'][_HOME . '/admin/general/modules'] = 'Trở lại';

        $data['page_title'] = 'Uninstall ' . $act_id;
        $tree[] = url(_HOME . '/admin', 'Admin CP');
        $tree[] = url(_HOME . '/admin/general', 'Tổng quan');
        $tree[] = url(_HOME . '/admin/general/modules', 'Quản lí module');
        $data['display_tree'] = display_tree($tree);

        $obj->view->data = $data;
        $obj->view->show('admin/general/modules/uninstall');
        break;

    default:

        $list_protected = sys_config('modules_protected');

        $protected = array_merge($list_protected[APP], $list_protected[BACKGROUND]);

        if (isset($_POST['sub'])) {

            if (empty($_POST['background'])) {

                $_POST['background'] = array();
            }

            if (empty($_POST['app'])) {

                $_POST['app'] = array();
            }
            if (!in_array('_background', $_POST['background'])) {

                $_POST['background'][] = '_background';
            }
            if (!in_array('admin', $_POST['app'])) {

                $_POST['app'][] = 'admin';
            }

            $out[BACKGROUND] = $_POST['background'];
            $out[APP] = $_POST['app'];
            $cache = $out;

            if (file_exists($cache_file)) {

                changemod($cache_file, 0644);
            }

            if (file_put_contents($cache_file, serialize($cache))) {

                $data['success'] = wait_redirect(_HOME . '/admin/general/modules', 'Thành công<br/>Đã kích hoạt <b>' . count($out[BACKGROUND]) . '</b> module chạy nền và <b>' . count($out[APP]) . '</b> module ứng dụng<br/>
        Bạn sẽ được load lại trang trong vòng {s} giây nữa', 3);

            } else {
                $data['errors'][] = 'Không thế ghi file ' . $cache_file;
            }
        }

        $data['module_actived'] = get_list_modules();

        $list = scan_modules();

        $data['modules'] = array();

        foreach ($list as $mod => $info) {

            $path = $info['full_path'];

            $info_file = $path . '/' . $mod . '.info';
            $readme_file = $path . '/readme.txt';
            $setting_name = $mod . 'Settings';
            $setting_file = $path . '/' . $setting_name . '.php';

            if ($mod != 'admin') {

                if (!class_exists($setting_name)) {

                    include_once $setting_file;
                }
            }

            if (class_exists($setting_name)) {

                if (file_exists($info_file)) {

                    if ($mod != 'admin') {

                        $set = new $setting_name();

                    } else {

                        $set = new adminSettings();
                    }

                    if (empty($set->setting['type'])) {

                        $set->setting['type'] = APP;
                    }

                    if ($set->setting['type'] == BACKGROUND) {

                        $type = BACKGROUND;

                    } elseif (empty($set->setting['type']) || $set->setting['type'] == APP || is_null($set->setting['type'])) {

                        $type = APP;
                    }

                    $info = $parse->ini_file($info_file);

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
                    if (empty($info['id'])) {

                        $info['id'] = 'NO ID';
                    }

                    if (isset($set->setting['extends']) && !is_null($set->setting['extends']) && !empty($set->setting['extends']) && is_array($set->setting['extends'])) {

                        $option_list = array();

                        $i = 0;

                        foreach ($set->setting['extends'] as $app => $extends) {

                            $i++;

                            if (isset($extends['router']) && isset($extends['name'])) {

                                $name = $extends['name'];
                            } else {

                                $name = 'Tùy chọn ' . $i;
                            }

                            $option_list[] = url(_HOME . '/' . $mod . '/' . $app, $name, 'target="_blank" style="color: red; text-decoration: underline;"');
                        }

                        $info['option'] = implode(', ', $option_list);

                    } else {

                        $info['option'] = '';
                    }

                    if (in_array($mod, $protected)) {

                        $info['protected'] = true;
                    } else {
                        $info['protected'] = false;
                    }

                    if (file_exists($readme_file)) {

                        $info['readme'] = _HOME . '/modules/' . $mod . '/readme.txt';
                    } else {

                        $info['readme'] = '';
                    }

                    $data['modules'][$type][$mod] = $info;

                }

            } else {

                unset($list[$mod]);
            }
        }

        $data['menus'][_HOME . '/admin/general/modules/upload'] = 'Tải lên';

        $data['page_title'] = 'Quản lí modules';
        $tree[] = url(_HOME . '/admin', 'Admin CP');
        $tree[] = url(_HOME . '/admin/general', 'Tổng quan');
        $tree[] = url(_HOME . '/admin/general/modules', $data['page_title']);
        $data['display_tree'] = display_tree($tree);

        $obj->view->data = $data;
        $obj->view->show('admin/general/modules/index');

        break;
}