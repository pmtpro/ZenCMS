<?php
/**
 * name = Quản lí file
 * icon = admin_tools_file_manager
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
 * load admin hook
 */
$obj->hook->get('admin');

/**
 * load helper
 */
load_helper('fhandle');
load_helper('gadget');
load_helper('time');

/**
 * load library
 */
$zip = load_library('pclzip');
$upload = load_library('upload');
$security = load_library('security');
$validation = load_library('validation');

/**
 * get page title
 */
$data['page_title'] = 'Quản lí file';
$tree[] = url(_HOME . '/admin', 'Admin CP');
$tree[] = url(_HOME . '/admin/tools', 'Tools');
$data['display_tree'] = display_tree($tree);

if (!empty($_GET['file']) && $_GET['file'] != '/') {

    $_file = '/' . trim($_GET['file'], '/');

} else {

    $_file = '';
}

$_file = urldecode($_file);

$base = __SITE_PATH;

$full_path = $base . $_file;

if ($_file) {

    $data['file'] = $_file;
} else {

    $data['file'] = '/';
}
if (file_exists($full_path)) {

    $strip = end(explode('/', $full_path));

    $path_levelup = preg_replace('/\/' . $strip . '$/is', '', $_file);

    $data['scans_levelup'] = glob($path_levelup . '/*');


    $data['path'] = $_file;

    $data['path_levelup'] = urlencode($path_levelup);

    $public_cpanel[_HOME . '/admin/tools/fileManager'] = 'Gốc';
    $public_cpanel[_HOME . '/admin/tools/fileManager/edit?file=' . $data['path_levelup']] = 'Lên';
    $data['public_cpanel'] = $public_cpanel;

    $cur_info = array('path' => urlencode($data['path']));

    $data['fileinfo'] = array_merge(fileinfo($full_path), $cur_info);

    $data['fileinfo']['time'] = get_date($data['fileinfo']['mtime']);

    if (is_file($full_path)) {

        /**
         * load helper
         */
        load_helper('gadget');

        /**
         * These files can be edited
         */
        $allow_edit = array('html', 'xml', 'xhtml', 'html5', 'css', 'js', 'txt', 'php', 'info', 'log');

        /**
         * extensions_allow_edit hook *
         */
        $allow_edit = $obj->hook->loader('fileManager_extensions_allow_edit', $allow_edit);

        $images = array('jpg', 'jpeg', 'png', 'gif', 'bmp');

        /**
         * extensions_allow_edit hook *
         */
        $images = $obj->hook->loader('fileManager_extensions_images', $images);

        $data['fileinfo']['content'] = fileinfo($full_path, ONLY_CONTENT);

        if (in_array($data['fileinfo']['extension'], $images)) {

            $data['fileinfo']['is_image'] = true;
        } else {

            $data['fileinfo']['is_image'] = false;
        }

        if (!in_array($data['fileinfo']['extension'], $allow_edit)) {

            $data['fileinfo']['full_url'] = _HOME . $_file;
            $obj->view->data = $data;
            $obj->view->show('admin/tools/fileManager/review');
            return;

        } else {

            $file_protected = array('/systems', '/index.php', '/modules/admin', '/modules/_background', '/modules/modules.dat');

            $file_free_edit = array('/systems/includes/config/ZenDB.php');


            if (isset($_POST['sub_save'])) {

                $not_allow_edit = false;

                if (!in_array($_file, $file_free_edit)) {

                    foreach ($file_protected as $fprotected) {

                        $fprotected = str_replace(array('/', '.'), array('\/', '\.'), $fprotected);

                        if (preg_match('/^' . $fprotected . '/', $_file)) {

                            $data['notices'][] = 'Bạn không được chỉnh sửa file này';

                            $not_allow_edit = true;
                            break;
                        }
                    }
                }

                if ($not_allow_edit == false) {

                    if ($security->check_token('token_save_code')) {

                        $content = $_POST['content'];

                        if (file_put_contents($full_path, $content)) {

                            $data['success'] = 'Lưu thành công';

                            $data['fileinfo']['content'] = fileinfo($full_path, ONLY_CONTENT);

                        } else {

                            $data['notices'][] = 'Không thể ghi file';
                        }
                    }
                }
            }

            $data['token_save_code'] = $security->get_token('token_save_code');
            $data['page_more'] = gadget_editarea($data['fileinfo']['extension']);
            $obj->view->data = $data;
            $obj->view->show('admin/tools/fileManager/file');
            return;
        }

    } else {

        $inor = array('.', '..');

        $scans = scandir($full_path);

        $data['scans'] = array();

        $arr_file = array();
        $arr_dir = array();

        foreach ($scans as $scan) {

            if (!in_array($scan, $inor)) {

                $path_scan = $full_path . '/' . $scan;

                $moreinfo = fileinfo($path_scan);

                $path = $_file . '/' . $moreinfo['name'];

                $short_path_info = array('path' => urlencode($path));

                $moreinfo = array_merge($moreinfo, $short_path_info);

                $moreinfo['time'] = get_date($moreinfo['mtime']);
                $moreinfo['input_name'] = base64_encode($moreinfo['name']);

                if (is_file($path_scan)) {

                    $arr_file[] = $moreinfo;

                } else {

                    $arr_dir[] = $moreinfo;
                }
            }
        }

        $data['scans'] = array_merge($arr_dir, $arr_file);

        if (isset($_GET['do'])) {

            $_do = $_GET['do'];
        } else {

            $_do = '';
        }

        $cp[_HOME . '/admin/tools/fileManager?file=' . $_file . '&do=newdir'] = 'Mục mới';
        $cp[_HOME . '/admin/tools/fileManager?file=' . $_file . '&do=newfile'] = 'File mới';
        $cp[_HOME . '/admin/tools/fileManager?file=' . $_file . '&do=upload'] = 'Upload';
        $data['dir_manager_bar'] = $cp;

        $_file_manager_bar['sub_rename'] = 'Đổi tên';
        $_file_manager_bar['sub_chmod'] = 'Chmod';
        $_file_manager_bar['sub_unzip'] = 'Unzip';
        $_file_manager_bar['sub_zip'] = 'Zip';
        $_file_manager_bar['sub_delete'] = 'Xóa';
        $data['file_manager_bar'] = $_file_manager_bar;


        switch ($_do) {

            case 'newdir':

                if (isset($_POST['sub_new'])) {

                    if ($security->check_token('token_new') && $_POST['type'] == 'dir') {

                        $nameDir = $_POST['name'];

                        if (!$validation->isValid('nameDir', $nameDir)) {

                            $data['notices'][] = 'Tên thư mục chứa kí tự không cho phép';
                        } else {

                            $newDir = $full_path . '/' . $nameDir;

                            $old_perm = fileperms($full_path);

                            $perm_read = 0755;

                            changemod($full_path, $perm_read);

                            $action = mkdir($newDir);

                            changemod($full_path, $old_perm);

                            if ($action) {

                                redirect(_HOME . '/admin/tools/fileManager?file=' . $_file);

                            } else {

                                $data['errors'][] = 'Không thể tạo thư mục';
                            }
                        }
                    }
                }

                $data['type'] = 'dir';
                $data['token_new'] = $security->get_token('token_new');
                $obj->view->data = $data;
                $obj->view->show('admin/tools/fileManager/new');
                return;

                break;

            case 'newfile':

                if (isset($_POST['sub_new'])) {

                    if ($security->check_token('token_new') && $_POST['type'] == 'file') {

                        $nameFile = $_POST['name'];

                        if (!$validation->isValid('nameFile', $nameFile)) {

                            $data['notices'][] = 'Tên file không hợp lệ';
                        } else {

                            $newPath = $full_path . '/' . $nameFile;

                            $ourFileHandle = fopen($newPath, 'w');

                            fclose($ourFileHandle);

                            if ($ourFileHandle) {

                                redirect(_HOME . '/admin/tools/fileManager?file=' . $_file);

                            } else {

                                $data['errors'][] = 'Không thể tạo file này';
                            }
                        }
                    }
                }

                $data['type'] = 'file';
                $data['token_new'] = $security->get_token('token_new');
                $obj->view->data = $data;
                $obj->view->show('admin/tools/fileManager/new');
                return;
                break;

            case 'upload':

                if (isset($_POST['sub_upload'])) {

                    if ($security->check_token('token_upload')) {

                        /**
                         * set upload path
                         */
                        $upload->upload_path = $full_path;

                        if ($upload->do_upload('file')) {

                            redirect(_HOME . '/admin/tools/fileManager?file=' . $_file);

                        } else {

                            $data['errors'] = $upload->error;
                        }
                    }
                }

                $data['page_title'] = 'Upload';
                $data['token_upload'] = $security->get_token('token_upload');
                $obj->view->data = $data;
                $obj->view->show('admin/tools/fileManager/upload');
                return;
                break;

            default:

                if (isset($_GET['selected'])) {

                    $data['info'] = array();

                    if (!is_array($_GET['selected'])) {

                        $_GET['selected'] = array($_GET['selected']);
                    }

                    foreach ($_GET['selected'] as $k => $item) {

                        $item_path = $full_path . '/' . base64_decode($item);

                        if (!file_exists($item_path)) {

                            unset($_GET['selected'][$k]);

                        } else {

                            $data['info'][$item] = fileinfo($item_path);
                        }
                    }

                    if (empty($_GET['selected'])) {

                        redirect(_HOME . '/admin/tools/fileManager?file=' . $_file);
                    }

                    /**
                     * rename file & directoty
                     */
                    if (isset($_GET['sub_rename'])) {

                        if (isset($_POST['sub_do_rename'])) {

                            $failure = array();

                            foreach ($_GET['selected'] as $k => $item) {

                                $item_path = $full_path . '/' . base64_decode($item);

                                if (!empty($_POST[$item])) {

                                    $name = $_POST[$item];

                                    $name = trim($name);
                                    $name = trim($name, '/');

                                    if (is_file($item_path)) {

                                        $check = $validation->isValid('nameFile', $name);

                                    } else {
                                        $check = $validation->isValid('nameDir', $name);
                                    }
                                    if (!$check) {

                                        $failure[$item] = 'Tên chứa kí tự không hợp lệ';

                                    } else {

                                        $new_path = $full_path . '/' . $name;

                                        if (file_exists($new_path)) {

                                            $failure[$item] = 'File này đã tồn tại';

                                        } else {


                                            if (!rename($item_path, $new_path)) {

                                                $failure[$item] = 'Không thể đổi tên file này';

                                            } else {

                                                unset($_GET['selected'][$k]);
                                            }
                                        }
                                    }
                                }
                            }

                            if (empty($failure)) {

                                redirect(_HOME . '/admin/tools/fileManager?file=' . $_file);

                            } else {

                                $data['failure'] = $failure;
                            }
                        }

                        $data['public_cpanel'][_HOME . '/admin/tools/fileManager?file=' . $_file] = 'Trở lại';
                        $data['selected'] = h($_GET['selected']);
                        $data['token_rename'] = $security->get_token('token_rename');
                        $obj->view->data = $data;
                        $obj->view->show('admin/tools/fileManager/rename');
                        return;

                    } elseif (isset($_GET['sub_chmod'])) {


                        if (isset($_POST['sub_do_chmod'])) {

                            $u = (int)$_POST['u'];
                            $g = (int)$_POST['g'];
                            $w = (int)$_POST['w'];

                            if (!is_numeric($u) || !is_numeric($g) || !is_numeric($w) || $u > 7 || $g > 7 || $w > 7) {

                                $data['notices'][] = 'Không thể chmod về giá trị này';

                            } else {

                                $chmod_num = "0" . "$u" . "$g" . "$w";

                                $chmod_num = octdec($chmod_num);

                                $failure = array();

                                foreach ($_GET['selected'] as $k => $item) {

                                    $item_path = $full_path . '/' . base64_decode($item);

                                    if (file_exists($item_path)) {

                                        if (!changemod($item_path, $chmod_num)) {

                                            $failure[$item] = 'Không thể chmod file này<br/>' . get_global_msg('changemod');

                                        } else {

                                            unset($_GET['selected'][$k]);
                                        }

                                    } else {

                                        unset($_GET['selected'][$k]);
                                    }
                                }
                            }

                            if (empty($failure)) {

                                redirect(_HOME . '/admin/tools/fileManager?file=' . $_file);

                            } else {

                                $data['failure'] = $failure;
                            }
                        }

                        $data['page_more'] = gadget_loadjs('gadget/chmodfunc.js');

                        $data['public_cpanel'][_HOME . '/admin/tools/fileManager?file=' . $_file] = 'Trở lại';

                        $data['selected'] = h($_GET['selected']);

                        $data['token_chmod'] = $security->get_token('token_chmod');
                        $obj->view->data = $data;
                        $obj->view->show('admin/tools/fileManager/chmod');
                        return;

                    } elseif (isset($_GET['sub_delete'])) {

                        if (isset($_POST['sub_do_delete'])) {

                            if ($security->check_token('token_delete')) {

                                foreach ($_GET['selected'] as $k => $item) {

                                    $item_path = $full_path . '/' . base64_decode($item);

                                    if (file_exists($item_path)) {

                                        if (is_dir($item_path)) {

                                            rrmdir($item_path);

                                            unset($_GET['selected'][$k]);

                                        } else {

                                            if (!@unlink($item_path)) {

                                                $failure[$item] = 'Không thể xóa file này';

                                            } else {

                                                unset($_GET['selected'][$k]);
                                            }
                                        }

                                    } else {

                                        unset($_GET['selected'][$k]);
                                    }
                                }

                                if (empty($failure)) {

                                    redirect(_HOME . '/admin/tools/fileManager?file=' . $_file);

                                } else {

                                    $data['failure'] = $failure;
                                }
                            }
                        }

                        $data['public_cpanel'][_HOME . '/admin/tools/fileManager?file=' . $_file] = 'Trở lại';

                        $data['selected'] = h($_GET['selected']);

                        $data['token_delete'] = $security->get_token('token_delete');
                        $obj->view->data = $data;
                        $obj->view->show('admin/tools/fileManager/delete');
                        return;

                    } elseif (isset($_GET['sub_zip'])) {

                        $name = base64_decode($_GET['selected'][0]);

                        $name = $name . '.zip';

                        $data['public_cpanel'][_HOME . '/admin/tools/fileManager?file=' . $_file] = 'Trở lại';

                        $data['selected'] = h($_GET['selected']);

                        $data['zip_path'] = $_file . '/' . $name;

                        if (isset($_POST['sub_do_zip'])) {

                            if ($security->check_token('token_zip')) {

                                $zip_path = '/' . trim($_POST['zip_path'], '/');

                                $zip_full_path = __SITE_PATH . $zip_path;

                                $list = array();

                                foreach ($_GET['selected'] as $k => $item) {

                                    $zip_item_path = $full_path . '/' . base64_decode($item);

                                    $list[] = $zip_item_path;

                                    $arr = explode('/', $zip_item_path);

                                    if (empty($filter)) {

                                        $filter = $arr;
                                    }

                                    $filter = array_intersect($filter, $arr);
                                }

                                $remove = implode('/', $filter);

                                $zip->PclZip($zip_full_path);

                                $v_list = $zip->create($list,
                                    PCLZIP_OPT_REMOVE_PATH, $remove);

                                if ($v_list == 0) {

                                    $data['errors'][] = $zip->errorInfo(true);

                                } else {

                                    $data['list'] = $v_list;
                                    $obj->view->data = $data;
                                    $obj->view->show('admin/tools/fileManager/zip_complete');
                                    return;
                                }
                            }
                        }

                        $data['token_zip'] = $security->get_token('token_zip');
                        $obj->view->data = $data;
                        $obj->view->show('admin/tools/fileManager/zip');
                        return;

                    } elseif (isset($_GET['sub_unzip'])) {

                        $_GET['selected'] = $_GET['selected'][0];

                        $data['page_title'] = 'Unzip';

                        $data['public_cpanel'][_HOME . '/admin/tools/fileManager?file=' . $_file] = 'Trở lại';

                        $data['selected'] = h($_GET['selected']);

                        if (isset($_POST['sub_do_unzip'])) {

                            if ($security->check_token('token_unzip')) {

                                $item = $_GET['selected'];

                                $item_path = $full_path . '/' . base64_decode($item);

                                $extract_dir = __SITE_PATH . $_POST['extract_path'];

                                if (!file_exists($item_path)) {

                                    redirect(_HOME . '/admin/tools/fileManager?file=' . $_file);

                                } else {

                                    if (!is_dir($extract_dir)) {

                                        $data['errors'][] = 'Không tồn tại thư mục này';

                                    } else {

                                        $zip->PclZip($item_path);

                                        $list = $zip->extract(PCLZIP_OPT_PATH, $extract_dir);

                                        if ($list) {

                                            $data['extract_list'] = $list;

                                            $obj->view->data = $data;
                                            $obj->view->show('admin/tools/fileManager/unzip_complete');
                                            return;

                                        } else {

                                            $data['notices'][] = 'Không thể giải nén file này';
                                        }
                                    }
                                }
                            }
                        }

                        $data['token_unzip'] = $security->get_token('token_unzip');
                        $obj->view->data = $data;
                        $obj->view->show('admin/tools/fileManager/unzip');
                        return;
                    }
                }
                break;
        }


        $obj->view->data = $data;
        $obj->view->show('admin/tools/fileManager/directory');
        return;
    }
}

$data['page_title'] = 'Không tồn tại file này';
$data['errors'][] = $data['page_title'];
$obj->view->data = $data;
$obj->view->show('admin/tools/fileManager/not_found');