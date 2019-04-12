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

Class browseAddOnsController Extends ZenController
{
    public function appendMenuControls() {
        run_hook('admin', 'menu_modules_controls', function($menu) {
            $menu[] = array(
                'name' => 'Tìm kiếm Module mới',
                'full_url' => genUrlAppFollow('browseAddOns/module'),
                'icon' => 'fa fa-search'
            );
            return $menu;
        });
        run_hook('admin', 'menu_templates_controls', function($menu) {
            $menu[] = array(
                'name' => 'Tìm kiếm Template mới',
                'full_url' => genUrlAppFollow('browseAddOns/template'),
                'icon' => 'fa fa-search'
            );
            return $menu;
        });
        run_hook('admin', 'module_actions', function($actions, $info) {
            $actions[] = ZenView::gen_menu(array(
                'full_url' => genUrlAppFollow('browseAddOns') . '/checkUpdate/module/' . $info['package'],
                'actID' => 'checkUpdate',
                'name' => 'Kiểm tra cập nhật',
                'icon' => 'fa fa-level-up',
            ));
            return $actions;
        }, 1);
        run_hook('admin', 'template_actions', function($actions, $info) {
            $actions[] = ZenView::gen_menu(array(
                'full_url' => genUrlAppFollow('browseAddOns') . '/checkUpdate/template/' . $info['package'],
                'actID' => 'checkUpdate',
                'name' => 'Kiểm tra cập nhật',
                'icon' => 'fa fa-level-up',
            ));
            return $actions;
        }, 1);
    }

    public function update_notice() {
        ZenView::add_js(_URL_MODULES . '/browseAddOns/js/check_update.js?v=1', 'foot');
        /*
        $model = $this->model->get('browseAddOns');
        $list_update = $model->browseUpdate();
        run_hook('admin', 'note_total_notice', function($data) use ($list_update) {
            $number_update = count($list_update);
            return $data + $number_update;
        });
        run_hook('admin', 'note_total_notice_update', function($n) use ($list_update) {
            $number_update = count($list_update);
            return $n+ $number_update;
        });
        run_hook('admin', 'note_nav_tabs_item', function($data) use ($list_update) {
            $out = '';
            foreach($list_update as $package) {
                $out .= '<li>
                    <div class="col1">
                        <div class="cont">
                            <div class="cont-col1">
                                <div class="label label-sm label-danger">
                                    <i class="fa fa-level-up"></i>
                                </div>
                            </div>
                            <div class="cont-col2">
                                <div class="desc">
                                    <b>' . $package['name'] . '</b> có bản cập nhật.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col2">
                        <a href="' . genUrlAppFollow('browseAddOns') . '/checkUpdate/module/' . $package['package'] . '" class="btn btn-success btn-sm">Cập nhật</a>
                    </div>
                </li>';
            }
            return $data . $out;
        });
        */
    }

    public function ajax_check_update() {
        $model = $this->model->get('browseAddOns');
        $list_update = $model->browseUpdate();
        ZenView::ajax_response($list_update);
    }

    public function index() {
        $this->browse_api();
    }
    public function module($arg = array()) {
        $this->browse_api('module', $arg);
    }
    public function template($arg = array()) {
        $this->browse_api('template', $arg);
    }

    public function install($arg = array()) {
        /**
         * load security library
         */
        $security = load_library('security');
        /**
         * get browseAddOns model
         */
        $model = $this->model->get('browseAddOns');
        $tokenLogin = $model->authorized_token_api();
        $adminModel = $this->model->get('admin');

        if (isset($arg[0]) && isset($arg[1]) && in_array($arg[0], array('module', 'template'))) {
            $type = $arg[0];
            $package_name = $security->cleanXSS($arg[1]);
            if (isset($arg[2]) && is_numeric($arg[2])) {
                $version_id = $arg[2];
            }
            $data['package'] = $model->browse_api_package($package_name, $type);
            /**
             * check if is free package
             */
            if (empty($data['package']->amount) || (!empty($data['package']->amount) && $data['package']->paid)) {
                $back_url = genUrlAppFollow('browseAddOns') . '/' . $data['package']->type . '/' . $data['package']->pid;

                if (empty($data['package']->versions)) {
                    ZenView::set_notice('Không có phiên bản nào', ZPUBLIC, $back_url);
                    exit;
                }
                if (!isset($version_id)) {
                    $versionData = $data['package']->versions[0];
                } else {
                    foreach ($data['package']->versions as $ver) {
                        if ($ver->id == $version_id) {
                            $versionData = $ver;
                            break;
                        }
                    }
                }
                if (empty($versionData)) {
                    ZenView::set_notice('Không tìm thấy phiên bản phù hợp', ZPUBLIC, $back_url);
                    exit;
                }

                $api_download = $versionData->full_link_down . '?token=' . urlencode($tokenLogin);

                /**
                 * init library
                 */
                $upload = load_library('upload', array('init_data' => $api_download));

                if ($upload->uploaded) {
                    /**
                     * config upload
                     */
                    $upload->file_overwrite = true;
                    $upload->allowed = array('zip');
                    $uploadPath = __TMP_DIR;
                    /**
                     * auto make directory by month-year
                     */
                    $subDir = autoMkSubDir($uploadPath);
                    $upload->process($uploadPath . '/' . $subDir);
                    /**
                     * upload icon
                     */
                    if ($upload->processed) {
                        $dataUp = $upload->data();
                        /**
                         * install module
                         */
                        redirect(HOME . '/admin/general/modules/install?_location=tmp&m=' . base64_encode($dataUp['file_name']) . '&back=' . urlencode(genUrlAppFollow('browseAddOns/module/' . $package_name)));
                    } else ZenView::set_error($upload->error);
                } else ZenView::set_error($upload->error);
            } else {
                ZenView::set_error('Xin lỗi, bạn phải thanh toán mới có thể thực hiện được thao tác này');
            }
            $this->browse_api($data['package']->type, array(0=>$arg[1]));
        }
    }

    public function purchase($arg = array()) {
        /**
         * load security library
         */
        $security = load_library('security');
        /**
         * get browseAddOns model
         */
        $model = $this->model->get('browseAddOns');

        if (isset($arg[0]) && isset($arg[1]) && in_array($arg[0], array('module', 'template'))) {
            $type = $arg[0];
            $package_name = $security->cleanXSS($arg[1]);
            $data['package'] = $model->browse_api_package($package_name, $type);
            redirect($data['package']->full_link_purchase . '?back=' . urlencode(genUrlAppFollow('browseAddOns/module/' . $package_name)));
        }
    }

    public function checkUpdate($arg = array()) {
        $type = isset($arg[0]) ? $arg[0] : '';
        $package_name = isset($arg[1]) ? $arg[1] : '';
        if (!in_array($type, array('module', 'template'))) {
            ZenView::set_error('Không tồn tại add-on này', ZPUBLIC, HOME . '/admin/general/modules');
            exit;
        }
        /**
         * load fhandle helper
         */
        load_helper('fhandle');
        if ($type == 'module') {
            $list_package = scan_modules();
        } else {
            $list_package = scan_templates();
        }
        if (empty($package_name) || !isset($list_package[$package_name])) {
            ZenView::set_error('Không tồn tại ' . $type . ' này', ZPUBLIC, HOME . '/admin/general/modules');
            exit;
        }
        /**
         * get browseAddOns model
         */
        $model = $this->model->get('browseAddOns');
        $data = array();
        $packageData = $list_package[$package_name];
        $result = $model->checkUpdate($package_name, $type, $packageData['version']);
        if ($result->status === 3) {
            ZenView::set_error('Không tồn tại ' . $type . ' này trên ZenCMS Add-ons', ZPUBLIC, HOME . '/admin/general/' . $type . 's');
        } elseif ($result->status === 2) {
            ZenView::set_error('Vui lòng kiểm tra lại cài đặt đồng bộ hoặc code', ZPUBLIC, HOME . '/admin/general/' . $type . 's');
        } elseif ($result->status === -1) {
            ZenView::set_success('Bạn đang sử dụng phiên bản mới nhất của ' . $type . ' này', ZPUBLIC, HOME . '/admin/general/' . $type . 's');
        } elseif ($result->status === 0) {
            ZenView::set_success('Có một phiên bản cần cập nhật');
            $data['old_version'] = $packageData;
            $data['new_version'] = $result->data;
            if (isset($_POST['submit-update'])) {
                if (!$data['new_version']->amount || $data['new_version']->paid) {
                    redirect(genUrlAppFollow('browseAddOns') . '/install/' . $data['new_version']->type . '/' . $data['new_version']->pid);
                } else {
                    redirect(genUrlAppFollow('browseAddOns') . '/purchase/' . $data['new_version']->type . '/' . $data['new_version']->pid);
                }
            } elseif (isset($_POST['cancel'])) {
                redirect(HOME . '/admin/general/' . $type . 's');
            }
        } else {
            ZenView::set_error($result->msg, ZPUBLIC, HOME . '/admin/general/' . $type . 's');
        }
        ZenView::set_title($package_name . ' | Kiểm tra cập nhật ' . $type);
        $this->view->data = $data;
        $this->view->show('browseAddOns/checkUpdate');
    }

    public function browse_api($type = null, $arg = array()) {
        /**
         * set page title
         */
        ZenView::set_title('Tìm kiếm ' . (empty($type)? 'add-ons': $type));
        /**
         * set base url
         */
        $data['base_url'] = genUrlAppFollow('browseAddOns');
        /**
         * load time helper
         */
        load_helper('time');
        /**
         * load security library
         */
        $security = load_library('security');
        /**
         * get browseAddOns model
         */
        $model = $this->model->get('browseAddOns');
        /**
         * check if load package
         */
        if (isset($arg[0]) && $type) {
            $package = $security->cleanXSS($arg[0]);
            $data['package'] = $model->browse_api_package($package, $type, 'list-package');
            if ($data['package']) {
                ZenView::set_title($data['package']->name . ' | Tìm kiếm ' . $type);
            }
        }
        $data['type'] = $type;
        $data['packages'] = $model->browse_api_list_package($type, 'new', 10, 'list-package', 'list-package', 'page');
        $data['top_packages'] = $model->browse_api_list_package($type, 'top', 10, 'list-package');
        ZenView::set_breadcrumb(url($data['base_url'], 'Tìm kiếm ' . (empty($type)? 'add-ons': $type)));
        ZenView::set_tip('Chú ý, Danh sách ' . (empty($type)? 'add-ons': $type) . ' được load từ website <a href="http://zencms.vn">ZenCMS.VN</a>');
        $this->view->data = $data;
        $this->view->show('browseAddOns/index');
    }
}
