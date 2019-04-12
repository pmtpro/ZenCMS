<?php
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

Class updateController Extends ZenController
{

    function index($request_data = array())
    {
        $model = $this->model->get('update');

        $data['page_title'] = 'Update code';

        $data['steps'][1]['name'] = 'Thay đổi cấu trúc cột perm trong bảng users thành varchar';
        $data['steps'][1]['process'] = FALSE;

        $data['steps'][2]['name'] = 'Thay đổi cột perm trong bảng users';
        $data['steps'][2]['process'] = FALSE;

        $data['steps'][3]['name'] = 'Kiểm tra lại cột title trong bảng store';
        $data['steps'][3]['process'] = FALSE;

        $data['steps'][4]['name'] = 'Thay đổi cấu trúc recycle bin';
        $data['steps'][4]['process'] = FALSE;

        $data['steps'][5]['name'] = 'Đổi tên cột `ref` trong table store thành `rel`';
        $data['steps'][5]['process'] = FALSE;

        $data['steps'][6]['name'] = 'Đổi tên cột `zen_confirm` trong table users thành `ss_zen_login`';
        $data['steps'][6]['process'] = FALSE;

        $data['steps'][7]['name'] = 'Đổi tên table zen_cms_links thành zen_cms_stores_links';
        $data['steps'][7]['process'] = FALSE;

        $data['steps'][8]['name'] = 'Đổi tên table zen_cms_files thành zen_cms_stores_files';
        $data['steps'][8]['process'] = FALSE;

        $data['steps'][9]['name'] = 'Thêm cột `uid` vào bảng `zen_cms_stores_links`';
        $data['steps'][9]['process'] = FALSE;

        $data['steps'][10]['name'] = 'Thêm cột `uid`, `status`, `type` vào bảng `zen_cms_stores_files`';
        $data['steps'][10]['process'] = FALSE;

        $data['steps'][11]['name'] = 'Update user for all old files and old links';
        $data['steps'][11]['process'] = FALSE;

        $data['steps'][12]['name'] = 'Đổi tên cột `code_confirm` trong table users thành `security_code`';
        $data['steps'][12]['process'] = FALSE;

        $data['steps'][13]['name'] = 'Thêm cột `smiles` vào table users';
        $data['steps'][13]['process'] = FALSE;

        $data['steps'][14]['name'] = 'Đổi tên `zen_cms_images` thành `zen_cms_stores_images`';
        $data['steps'][14]['process'] = FALSE;

        $data['steps'][15]['name'] = 'Thêm cột `uid` vào table `zen_cms_images`';
        $data['steps'][15]['process'] = FALSE;

        $data['steps'][16]['name'] = 'Thêm row `templates` vào `zen_cms_config`';
        $data['steps'][16]['process'] = FALSE;

        $data['steps'][17]['name'] = 'Thêm cột `ip` vào `zen_cms_likes`';
        $data['steps'][17]['process'] = FALSE;

        $data['steps'][18]['name'] = 'Thêm cột `ip` vào `zen_cms_dislikes`';
        $data['steps'][18]['process'] = FALSE;

        $data['steps'][19]['name'] = 'Đổi tên cột `zen_cms_comments` thành `zen_cms_stores_comments`';
        $data['steps'][19]['process'] = FALSE;

        $data['steps'][20]['name'] = 'Thêm cột `wgid` vào `zen_cms_widgets`';
        $data['steps'][20]['process'] = FALSE;

        $data['steps'][21]['name'] = 'Đổi tên table `zen_cms_hot_link` thành `zen_cms_link_list`';
        $data['steps'][21]['process'] = FALSE;

        $data['steps'][22]['name'] = 'Thêm cột `time` vào table`zen_cms_link_list`';
        $data['steps'][22]['process'] = FALSE;

        $data['steps'][23]['name'] = 'Thêm cột `style` vào table`zen_cms_link_list`';
        $data['steps'][23]['process'] = FALSE;

        $data['steps'][24]['name'] = 'Thêm cột `tags` vào table`zen_cms_link_list`';
        $data['steps'][24]['process'] = FALSE;

        $data['steps'][25]['name'] = 'Thêm cột `type` vào table`zen_cms_tags`';
        $data['steps'][25]['process'] = FALSE;

        $data['steps'][26]['name'] = 'Đổi tên `zen_cms_stores` thành `zen_cms_blogs`';
        $data['steps'][26]['process'] = FALSE;

        $data['steps'][27]['name'] = 'Đổi tên `zen_cms_stores_comments` thành `zen_cms_blogs_comments`';
        $data['steps'][27]['process'] = FALSE;

        $data['steps'][28]['name'] = 'Đổi tên `zen_cms_stores_files` thành `zen_cms_blogs_files`';
        $data['steps'][28]['process'] = FALSE;

        $data['steps'][29]['name'] = 'Đổi tên `zen_cms_stores_images` thành `zen_cms_blogs_images`';
        $data['steps'][29]['process'] = FALSE;

        $data['steps'][30]['name'] = 'Đổi tên `zen_cms_stores_links` thành `zen_cms_blogs_links`';
        $data['steps'][30]['process'] = FALSE;

        $data['steps'][31]['name'] = 'Đổi tên `zen_cms_stores_settings` thành `zen_cms_blogs_settings`';
        $data['steps'][31]['process'] = FALSE;



        if (isset($_POST['sub'])) {
            if ($model->alter_table_users()) {
                $data['steps'][1]['process'] = TRUE;
            }
            if ($model->change_user_perm()) {
                $data['steps'][2]['process'] = TRUE;
            }
            if ($model->check_store_title()) {
                $data['steps'][3]['process'] = TRUE;
            }
            if ($model->change_recycle_bin()) {
                $data['steps'][4]['process'] = TRUE;
            }
            if ($model->change_ref_to_rel()) {
                $data['steps'][5]['process'] = TRUE;
            }
            if ($model->change_zen_confirm_to_ss_zen_token()) {
                $data['steps'][6]['process'] = TRUE;
            }
            if ($model->rename_table_links()) {
                $data['steps'][7]['process'] = TRUE;
            }
            if ($model->rename_table_files()) {
                $data['steps'][8]['process'] = TRUE;
            }
            if ($model->add_colum_uid_links()) {
                $data['steps'][9]['process'] = TRUE;
            }
            if ($model->add_colum_uid_files()) {
                $data['steps'][10]['process'] = TRUE;
            }
            if ($model->update_uid_links_files()) {
                $data['steps'][11]['process'] = TRUE;
            }
            if ($model->change_code_confirm_to_security_code()) {
                $data['steps'][12]['process'] = TRUE;
            }
            if ($model->add_colum_smiles_to_users()) {
                $data['steps'][13]['process'] = TRUE;
            }
            if ($model->rename_table_images()) {
                $data['steps'][14]['process'] = TRUE;
            }
            if ($model->add_colum_uid_to_stores_images()) {
                $data['steps'][15]['process'] = TRUE;
            }
            if ($model->add_row_templates_to_config()) {
                $data['steps'][16]['process'] = TRUE;
            }
            if ($model->add_colum_ip_likes()) {
                $data['steps'][17]['process'] = TRUE;
            }
            if ($model->add_colum_ip_dislikes()) {
                $data['steps'][18]['process'] = TRUE;
            }
            if ($model->rename_comments_to_stores_comments()) {
                $data['steps'][19]['process'] = TRUE;
            }
            if ($model->add_colum_wgid_to_widgets()) {
                $data['steps'][20]['process'] = TRUE;
            }
            if ($model->rename_table_hot_link()) {
                $data['steps'][21]['process'] = TRUE;
            }
            if ($model->add_time_to_link_list()) {
                $data['steps'][22]['process'] = TRUE;
            }
            if ($model->add_style_to_link_list()) {
                $data['steps'][23]['process'] = TRUE;
            }
            if ($model->add_tags_to_link_list()) {
                $data['steps'][24]['process'] = TRUE;
            }
            if ($model->add_type_to_tags()) {
                $data['steps'][25]['process'] = TRUE;
            }
            if ($model->rename_table('zen_cms_stores', 'zen_cms_blogs')) {
                $data['steps'][26]['process'] = TRUE;
            }
            if ($model->rename_table('zen_cms_stores_comments', 'zen_cms_blogs_comments')) {
                $data['steps'][27]['process'] = TRUE;
            }
            if ($model->rename_table('zen_cms_stores_files', 'zen_cms_blogs_files')) {
                $data['steps'][28]['process'] = TRUE;
            }
            if ($model->rename_table('zen_cms_stores_images', 'zen_cms_blogs_images')) {
                $data['steps'][29]['process'] = TRUE;
            }
            if ($model->rename_table('zen_cms_stores_links', 'zen_cms_blogs_links')) {
                $data['steps'][30]['process'] = TRUE;
            }
            if ($model->rename_table('zen_cms_stores_settings', 'zen_cms_blogs_settings')) {
                $data['steps'][31]['process'] = TRUE;
            }
        }

        $this->view->data = $data;
        $this->view->show('update');
    }

}

?>