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

Class browseAddOnsModel extends ZenModel
{
    private $http_host = 'http://zencms.vn';
    private $router_check_update = 'addons/api/check_update';
    private $cache_time = 1800;

    public function authorized_token_api() {
        global $registry;
        $model = $registry->model->get('admin');
        return $model->get_token_api();
    }
    public function browse_api_list_package($type = null, $order = 'new', $limit, $msgPos = ZPUBLIC, $pagingPos = null, $pageName = 'page', $disCache = false) {
        $token = $this->authorized_token_api();
        if (!$token) {
            ZenView::set_tip('Trước tiên bạn thiết lập tài khoản đồng bộ tại <a href="' . HOME . '/admin/general/config#account-sync-config" target="_blank">đây</a>', $msgPos);
            return false;
        }
        if (empty($pagingPos)) {
            $page_number = 1;
        } else {
            $page_number = (ZenInput::get($pageName) ? (int) ZenInput::get($pageName) : 1);
        }

        $url = $this->http_host . '/addons/api/list?type=' . $type . '&order=' . $order . '&limit=' . $limit . '&' . $pageName . '=' . $page_number . '&token=' . urlencode($token);

        /**
         * get cache
         */
        if (!$disCache) $cacheResult = ZenCaching::get($url);
        else $cacheResult = '';

        if ($cacheResult) {
            $result = $cacheResult;
            $error = '';
            $loadCache = true;
        } else {
            $api = load_library('restApi');
            $api->set_api($url);
            $api->rest();
            $result = $api->get_result();
            $error = $api->get_error();
            $loadCache = false;
        }
        if (!empty($error)) {
            ZenView::set_error($error, $msgPos);
            return false;
        } else {
            if (!$loadCache && $result) {
                /**
                 * set the new cache
                 */
                ZenCaching::set($url, $result, $this->cache_time);
            }
            $resultDeCode = json_decode($result);
            /**
             * load fhandle helper
             */
            load_helper('fhandle');
            $handle = scan_modules();
            $list_module = array_keys($handle);
            $resultDeCode->data->packages = array_map(function($i) use ($handle, $list_module) {
                if (!$i->amount) {
                    $i->full_api_down = genUrlAppFollow('browseAddOns') . '/install/' . $i->type . '/' . $i->pid;
                } else {
                    if (!$i->paid) $i->full_api_down = genUrlAppFollow('browseAddOns') . '/purchase/' . $i->type . '/' . $i->pid;
                    else $i->full_api_down = genUrlAppFollow('browseAddOns') . '/install/' . $i->type . '/' . $i->pid;
                }
                if (in_array($i->pid, $list_module)) {
                    $i->installed = true;
                    if ($i->version > $handle[$i->pid]['version']) {
                        $i->updatable = true;
                    } else {
                        $i->updatable = false;
                    }
                } else {
                    $i->installed = false;
                }
                return $i;
            }, $resultDeCode->data->packages);

            $status = $resultDeCode->status;

            if ($status == 0) {
                if (!empty($pagingPos)) {
                    /**
                     * load paging library
                     */
                    $paging = load_library('pagination');
                    $paging->setLimit($limit);
                    $paging->SetGetPage($pageName);
                    $paging->setTotal($resultDeCode->data->total_item);
                    ZenView::set_paging($paging->navi_page(), $pagingPos);
                }
                if (!$resultDeCode->data->packages) {
                    ZenView::set_notice('Không tìm thấy ' . ($type ? $type : 'add-ons') . ' nào', $msgPos);
                    return false;
                }
                return $resultDeCode->data->packages;
            } elseif ($status == 1) {
                /**
                 * got error
                 */
                ZenView::set_error($resultDeCode->msg, $msgPos);
                ZenView::set_tip('Trước tiên bạn thiết lập tài khoản đồng bộ tại <a href="' . HOME . '/admin/general/config#account-sync-config" target="_blank">đây</a>', $msgPos);
                return false;
            } else {
                if (empty($resultDeCode->msg)) {
                    $msg = 'Lỗi không xác định';
                } else $msg = $resultDeCode->msg;
                ZenView::set_error($msg, $msgPos);
                return false;
            }
        }
    }

    public function browse_api_package($package, $type, $msgPos = ZPUBLIC) {
        $token = $this->authorized_token_api();
        if (!$token) {
            ZenView::set_tip('Trước tiên bạn thiết lập tài khoản đồng bộ tại <a href="' . HOME . '/admin/general/config#account-sync-config" target="_blank">đây</a>', $msgPos);
            return false;
        }
        $url = $this->http_host . '/addons/api/get_package_info?type=' . $type . '&package=' . $package . '&token=' . urlencode($token);
        /**
         * get cache
         */
        $cacheResult = ZenCaching::get($url);
        if ($cacheResult) {
            return $cacheResult;
        }

        $api = load_library('restApi');
        $api->set_api($url);
        $api->rest();
        $result = $api->get_result();
        $error = $api->get_error();
        if (!empty($error)) {
            ZenView::set_error($error, $msgPos);
            return false;
        } else {
            $resultDeCode = json_decode($result);
            $status = $resultDeCode->status;
            if ($status == 0) {
                if (empty($resultDeCode->data->package)) {
                    ZenView::set_notice('Không tìm thấy ' . $type . ' nào', $msgPos);
                    return false;
                } else {
                    $packageData = $resultDeCode->data->package;
                    if (!$packageData->amount) {
                        $packageData->full_api_down = genUrlAppFollow('browseAddOns') . '/install/' . $packageData->type . '/' . $packageData->pid;
                    } else {
                        if (!$packageData->paid) $packageData->full_api_down = genUrlAppFollow('browseAddOns') . '/purchase/' . $packageData->type . '/' . $packageData->pid;
                        else $packageData->full_api_down = genUrlAppFollow('browseAddOns') . '/install/' . $packageData->type . '/' . $packageData->pid;
                    }
                    /**
                     * load fhandle helper
                     */
                    load_helper('fhandle');
                    $handle = scan_modules();
                    $list_module = array_keys($handle);
                    if (in_array($packageData->pid, $list_module)) {
                        $packageData->installed = true;
                        if ($packageData->version > $handle[$packageData->pid]['version']) {
                            $packageData->updatable = true;
                        } else {
                            $packageData->updatable = false;
                        }
                    } else {
                        $packageData->installed = false;
                    }
                    /**
                     * set the new cache
                     */
                    ZenCaching::set($url, $packageData, $this->cache_time);
                    return $packageData;
                }
            } elseif ($status == 1) {
                /**
                 * got error
                 */
                ZenView::set_error($resultDeCode->msg, $msgPos);
                ZenView::set_tip('Trước tiên bạn thiết lập tài khoản đồng bộ tại <a href="' . HOME . '/admin/general/config#account-sync-config" target="_blank">đây</a>', $msgPos);
                return false;
            } else {
                if (empty($resultDeCode->msg)) {
                    $msg = 'Lỗi không xác định';
                } else $msg = $resultDeCode->msg;
                ZenView::set_error($msg, $msgPos);
                return false;
            }
        }
    }

    public function browseUpdate() {
        /**
         * load fhandle helper
         */
        load_helper('fhandle');
        $listModuleHandle = scan_modules();
        $listTemplateHandle = scan_templates();
        $listHandle = array_merge($listModuleHandle, $listTemplateHandle);
        $updateList = array();
        foreach ($listHandle as $package) {
            $result = $this->checkUpdate($package['package'], $package['type'], $package['version']);
            if ($result->status === 0) {
                $updateList[] = array(
                    'name'  => $package['name'],
                    'version'  => $package['version'],
                    'author'  => $package['author'],
                    'des'  => $package['des'],
                    'package'  => $package['package'],
                    'type'  => $package['type'],
                    'protected'  => $package['protected'],
                    'activated'  => $package['activated'],
                    'full_url_icon' => $result->data->full_url_icon
                );
            }
        }
        return $updateList;
    }

    public function checkUpdate($pid, $type, $ver) {
        $url = $this->http_host . '/' . $this->router_check_update . '?package=' . $pid . '&type=' . $type . '&version=' . $ver . '&token=' . urlencode($this->authorized_token_api());
        /**
         * get cache
         */
        $cacheResult = ZenCaching::get($url);
        if ($cacheResult) {
            return $cacheResult;
        }
        $api = load_library('restApi');
        $api->set_api($url);
        $api->rest();
        $result = $api->get_result();
        $error = $api->get_error();
        if ($error) {
            return false;
        }
        $result_decoded = json_decode($result);
        /**
         * set the new cache
         */
        ZenCaching::set($url, $result_decoded, $this->cache_time);
        return $result_decoded;
    }
}