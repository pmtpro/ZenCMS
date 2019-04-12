<?php
/**
 * name = Quản lí nội dung
 * icon = fa fa-folder-open-o
 * position = 1
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
if (!defined('__ZEN_KEY_ACCESS'))
    exit('No direct script access allowed');

$model = $obj->model->get('blog'); //get blog model
$model->set_filter('status', array(0, 1, 2));
$accModel = $obj->model->get('account');
$hook = $obj->hook->get('blog'); //get blog hook
$user = $obj->user; //load user data

/**
 * load library
 */
$seo = load_library('seo');
$security = load_library('security');
$pagination = load_library('pagination');

/**
 * set base url
 */
$baseUrl = HOME . '/admin/general/modulescp?appFollow=blog/manager';
$data['base_url'] = $baseUrl;

/**
 * set page title
 */
$page_title = 'Quản lí nội dung';
ZenView::set_title($page_title);
/**
 * add js file
 */
ZenView::add_js(_URL_MODULES . '/blog/js/manager/cpanel.manager.js');
/**
 * add breadcrumb
 */
ZenView::set_breadcrumb(url($baseUrl, 'Blog manager'));

$cats = array();
$act = '';
$ActID = 0;
$ActID1 = 0;
$ActID2 = 0;
$DisplayContent = array();

$icon['view'] = 'fa fa-eye';
$icon['add'] = 'fa fa-plus';
$icon['edit'] = 'fa fa-pencil';
$icon['moveUp'] = 'fa fa-angle-up';
$icon['moveDown'] = 'fa fa-angle-down';
$icon['moveTop'] = 'fa fa-angle-double-up';
$icon['moveBottom'] = 'fa fa-angle-double-down';
$icon['removeToTrash'] = 'fa fa-trash-o';
$icon['restoreToDraft'] = 'fa fa-undo';
$icon['delete'] = 'fa fa-times';

$blogID = 0;
if (isset($app[1]))
    $blogID = (int)$security->removeSQLI($app[1]);
if (isset($app[2]))
    $act = $security->cleanXSS($app[2]);
if (isset($app[3]))
    $ActID = (int)$security->removeSQLI($app[3]);
if (isset($app[3]))
    $ActID1 = (int)$security->removeSQLI($app[3]);
if (isset($app[4]))
    $ActID2 = (int)$security->removeSQLI($app[4]);

/**
 * check blog is exists
 * if not exist, redriect to cpanel page
 */
if (!$model->blog_exists($blogID)) {
    ZenView::set_error('Không tồn tại mục này!', ZPUBLIC, $baseUrl . '/cpanel');
    exit;
}

/**
 * set url go back
 */
$goBackUrl = $baseUrl . '/cpanel/' . $blogID;

/**
 * get current blog data
 */
$blog = $model->get_blog_data($blogID);
$blogParent = $blog['parent'];

/**
 * do filter
 */
$filterListID = $blogID;
$arr_stt = array(0, 1, 2);
$filter_post_value = $arr_stt;
$filter_cat_value = $arr_stt;
$filter_cat_pos = $blogID;
$filter_post_pos = $blogID;
$param_post_stt = '';
$param_cat_stt = '';

$_get_filter_for = ZenInput::get('filter_for');
$_get_filter_status = (int) ZenInput::get('filter_status');
$_get_pos = ZenInput::get('pos');

if ($_get_filter_for && $_get_filter_status !== '' && in_array($_get_filter_status, $arr_stt)) {
    if ($_get_filter_for == 'post') {
        $filter_post_value = $_get_filter_status;
        $param_post_stt = '&filter_status=' . $_get_filter_status . '&filter_for=' . $_get_filter_for . ($_get_pos ? '&pos=' . $_get_pos : '');
        if ($_get_pos == 'all') {
            $filter_post_pos = null;
        }
    } elseif ($_get_filter_for == 'cat') {
        $filter_cat_value = $_get_filter_status;
        $param_cat_stt = '&filter_status=' . $_get_filter_status . '&filter_for=' . $_get_filter_for . ($_get_pos ? '&pos=' . $_get_pos : '');
        if ($_get_pos == 'all') {
            $filter_cat_pos = null;
        }
    }
}

switch ($act) {
    case 'moveUp':
        /**
         * $ActID1 is $ActID
         */
        if (!empty($ActID1)) {
            /**
             * before_move_up hook*
             */
            $ActID1 = $hook->loader('before_move_up', $ActID1);

            $sdata1 = $model->get_blog_data($ActID1, 'weight, parent, type');
            $w1 = $sdata1['weight'];
            $getlist = $model->get_list_blog($blogID, array(
                'type' => $sdata1['type'],
                'order' => array('weight' => 'ASC', 'time' => 'DESC'),
                'both_child' => false));
            foreach ($getlist as $s) {
                if (isset($save)) {
                    if ($s['id'] == $ActID1) {
                        $sdata2 = $save;
                        break;
                    }
                }
                $save = $s;
            }
            $w2 = $sdata2['weight'];
            $ActID2 = $sdata2['id'];
            $update_more = false;
            if ($w1 == $w2) {
                $w2 = $w2 - 1;
                $update_more = true;
            }
            if ($update_more) {
                foreach ($getlist as $s) {
                    if (isset($start) && $start == true) {
                        if ($s['id'] != $ActID2) {
                            $model->update_blog(array('weight' => '{`weight` + 1}'), $s['id']);
                        }
                    } else {
                        $model->update_blog(array('weight' => '{`weight` - 1}'), $s['id']);
                    }
                    if ($s['id'] == $ActID1) {
                        $start = true;
                    }
                }
            }
            $model->update_blog(array('weight' => "$w2"), $ActID1);
            $model->update_blog(array('weight' => "$w1"), $ActID2);
            /**
             * after_move_up hook*
             */
            $ActID1 = $hook->loader('after_move_up', $ActID1);
        }
        redirect($baseUrl . '/cpanel/' . $blogID);
        break;

    case 'moveDown':
        /**
         * $ActID1 is $ActID
         */
        if (!empty($ActID1)) {
            /**
             * before_move_down hook*
             */
            $ActID1 = $hook->loader('before_move_down', $ActID1);

            $sdata1 = $model->get_blog_data($ActID1, 'weight, parent, type');
            $w1 = $sdata1['weight'];
            $getlist = $model->get_list_blog($blogID, array(
                'type' => $sdata1['type'],
                'order' => array('weight' => 'ASC', 'time' => 'DESC'),
                'both_child' => false));
            foreach ($getlist as $s) {
                if (isset($a) && $a == true) {
                    $sdata2 = $s;
                    break;
                }
                if ($s['id'] == $ActID1) {
                    $a = true;
                }
            }
            $ActID2 = $ActID1;
            $w2 = $w1;
            $ActID1 = $sdata2['id'];
            $w1 = $sdata2['weight'];
            $update_more = false;
            if ($w1 == $w2) {
                $w2 = $w2 - 1;
                $update_more = true;
            }
            if ($update_more) {
                foreach ($getlist as $s) {
                    if (isset($start) && $start == true) {
                        if ($s['id'] != $ActID2) {
                            $model->update_blog(array('weight' => '{`weight` + 1}'), $s['id']);
                        }
                    } else {
                        $model->update_blog(array('weight' => '{`weight` - 1}'), $s['id']);

                    }
                    if ($s['id'] == $ActID1) {
                        $start = true;
                    }
                }
            }
            $model->update_blog(array('weight' => "$w2"), $ActID1);
            $model->update_blog(array('weight' => "$w1"), $ActID2);
            /**
             * after_move_down hook*
             */
            $ActID1 = $hook->loader('after_move_down', $ActID1);
        }
        redirect($baseUrl . '/cpanel/' . $blogID);
        break;

    case 'moveTop':
        if ($ActID) {
            /**
             * before_move_to_top hook*
             */
            $ActID = $hook->loader('before_move_to_top', $ActID);

            $top = $model->gets('weight', '', array('weight' => 'ASC', 'time' => 'DESC'), 1);
            $TID = $top[0]['weight'];
            if ($TID > 1) {
                $TID = $TID - 1;
                $model->update_blog(array('weight' => $TID), $ActID);
            } else {
                $model->update_blog(array('weight' => $TID), $ActID);
                $model->update_blog(array('weight' => '{`weight`+1}'), "`weight` >= '$TID' and `id` != '$ActID'");
            }
            /**
             * after_move_to_top hook*
             */
            $ActID = $hook->loader('after_move_to_top', $ActID);
        }
        redirect($baseUrl . '/cpanel/' . $blogID);
        break;

    case 'moveBottom':
        if ($ActID) {
            /**
             * before_move_to_bottom hook*
             */
            $ActID = $hook->loader('before_move_to_bottom', $ActID);
            $top = $model->gets(array('weight', 'id'), '', array('weight' => 'DESC', 'time' =>
                'DESC'), 1);
            if ($top[0]['id'] != $ActID) {
                $TID = $top[0]['weight'];
                $TID = $TID + 1;
                $model->update_blog(array('weight' => $TID), $ActID);
                /**
                 * after_move_to_bottom hook*
                 */
                $ActID = $hook->loader('after_move_to_bottom', $ActID);
            }
        }
        redirect($baseUrl . '/cpanel/' . $blogID);
        break;

    case 'moveToTrash':
        if (isset($_REQUEST['submit-trash'])) {
            /**
             * before_remove_to_trash hook*
             */
            $ActID = $hook->loader('before_remove_to_trash', $ActID);

            if (!$model->remove_to_trash($ActID)) {
                ZenView::set_error('Không thể chuyển vào thùng rác!', ZPUBLIC, $baseUrl . '/cpanel/' . $blogID . (!empty($param_cat_stt) ? $param_cat_stt : (!empty($param_post_stt) ? $param_post_stt : '')));
            } else {
                /**
                 * after_remove_to_trash hook*
                 */
                $ActID = $hook->loader('after_remove_to_trash', $ActID);
                ZenView::set_success('Đã chuyển vào thùng rác!', ZPUBLIC, $baseUrl . '/cpanel/' . $blogID . (!empty($param_cat_stt) ? $param_cat_stt : (!empty($param_post_stt) ? $param_post_stt : '')));
            }
        } else
            redirect($baseUrl . '/cpanel/' . $blogID . (!empty($param_cat_stt) ? $param_cat_stt : (!empty($param_post_stt) ? $param_post_stt : '')));
        break;

    case 'restoreToDraft':
        if (isset($_REQUEST['submit-draft'])) {
            /**
             * before_remove_to_draft hook*
             */
            $ActID = $hook->loader('before_remove_to_draft', $ActID);

            if (!$model->remove_to_draft($ActID)) {
                ZenView::set_error('Không thể chuyển sang trạng thái nháp!', ZPUBLIC, $baseUrl . '/cpanel/' . $blogID . (!empty($param_cat_stt) ? $param_cat_stt : (!empty($param_post_stt) ? $param_post_stt : '')));
            } else {
                /**
                 * after_remove_to_draft hook*
                 */
                $ActID = $hook->loader('after_remove_to_draft', $ActID);
                ZenView::set_success('Khôi phục thành công!', ZPUBLIC, $baseUrl . '/cpanel/' . $blogID . (!empty($param_cat_stt) ? $param_cat_stt : (!empty($param_post_stt) ? $param_post_stt : '')));
            }
        } else
            redirect($baseUrl . '/cpanel/' . $blogID . (!empty($param_cat_stt) ? $param_cat_stt : (!empty($param_post_stt) ? $param_post_stt : '')));
        break;

    case 'delete':
        /**
         * before_delete hook*
         */
        $ActID = $hook->loader('before_delete', $ActID);
        if ($model->delete($ActID) == true) {
            ZenView::set_success('Đã xóa khỏi hệ thống!', ZPUBLIC, $baseUrl . '/cpanel/' . $blogID . (!empty($param_cat_stt) ? $param_cat_stt : (!empty($param_post_stt) ? $param_post_stt : '')));
        } else {
            ZenView::set_success('Không thể xóa. vui lòng thử lại!', ZPUBLIC, $baseUrl . '/cpanel/' . $blogID . (!empty($param_cat_stt) ? $param_cat_stt : (!empty($param_post_stt) ? $param_post_stt : '')));
        }
        break;
}

$limit = 10;
/**
 * num_cat_in_cpanel hook *
 */
$limit = $obj->hook->loader('num_cat_in_cpanel', $limit);

$model->set_filter('status', $filter_cat_value);
$pagination->setLimit($limit);
$pagination->SetGetPage('pageFolder');
$start = $pagination->getStart();
$sql_limit = $start . ',' . $limit;
$cats = $model->get_list_blog($filter_cat_pos, array(
        'get' => 'name, title, url, time, status, parent, uid',
        'type' => 'folder',
        'order' => array('weight' => 'ASC', 'time_update' => 'DESC', 'time' => 'DESC'),
        'limit' => $sql_limit,
        'both_child' => false
    )
);

$total = $model->total_result;
$pagination->setTotal($total);
ZenView::set_paging($pagination->navi_page('pageFolder='), 'cat');
$data['stats']['count']['cat'] = $total;
if (empty($cats)) {
    ZenView::set_notice('Hiện tại chưa có thư mục nào ở đây!', 'cat');
} else {

    $cats = array_map(function ($cat) use ($model, $accModel, $blogID, $baseUrl, $param_cat_stt, $param_post_stt, $icon) {
        $parentData = $model->get_blog_data($cat['parent'], 'id, url, name, title');
        if (empty($parentData)) {
            $cat['cat'] = array();
        } else {
            $cat['cat'] = $parentData;
            $cat['cat']['full_real_url'] = $cat['cat']['full_url'];
            $cat['cat']['full_url'] = $baseUrl . '/cpanel/' . $cat['cat']['id'] . $param_cat_stt;
        }
        $userData = $accModel->get_user_data($cat['uid'], 'username, nickname');
        if (!empty($userData)) {
            $cat['user'] = $userData;
            $cat['user']['full_url'] = HOME . '/admin/members/user/' . $userData['username'];
            $cat['user']['full_real_url'] = HOME . '/account/wall/' . $userData['username'];
        }
        $cat['full_real_url'] = $cat['full_url'];
        $cat['full_url'] = $baseUrl . '/cpanel/' . $cat['id'] . $param_cat_stt;
        if ($cat['status'] == 0) {
            $cat['status_detail'] = array(
                'id' => 0,
                'name' => 'Đã đăng',
                'show' => '<span class="badge badge-success">Đã đăng</span>',
                'full_url' => $baseUrl . '/cpanel/' . $blogID . '&filter_status=0&filter_for=cat');
        } elseif ($cat['status'] == 1) {
            $cat['status_detail'] = array(
                'id' => 1,
                'name' => 'Nháp',
                'show' => '<span class="badge badge-warning">Nháp</span>',
                'full_url' => $baseUrl . '/cpanel/' . $blogID . '&filter_status=1&filter_for=cat');
        } elseif ($cat['status'] == 2) {
            $cat['status_detail'] = array(
                'id' => 2,
                'name' => 'Đã xóa',
                'show' => '<span class="badge badge-danger">Đã xóa</span>',
                'full_url' => $baseUrl . '/cpanel/' . $blogID . '&filter_status=2&filter_for=cat');
        }
        $cat['actions']['edit'] = ZenView::gen_menu(array(
            'full_url' => $baseUrl . '/editor&id=' . $cat['id'],
            'name' => 'Chỉnh sửa',
            'title' => 'Chỉnh sửa thư mục này',
            'icon' => $icon['edit']));
        $cat['actions']['view'] = array(
            'full_url' => $cat['full_real_url'],
            'name' => 'Xem thư mục',
            'title' => 'Xem thư mục này',
            'icon' => $icon['view'],
            'attr' => 'target="_blank"');
        $cat['actions']['moveUp'] = ZenView::gen_menu(array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '/moveUp/' . $cat['id'],
            'name' => 'Chuyển lên',
            'title' => 'Chuyển thư mục này lên trên',
            'icon' => $icon['moveUp'],
            'divider' => true
        ));
        $cat['actions']['moveDown'] = ZenView::gen_menu(array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '/moveDown/' . $cat['id'],
            'name' => 'Chuyển xuống',
            'title' => 'Chuyển thư mục này xuống dưới',
            'icon' => $icon['moveDown']));
        $cat['actions']['moveTop'] = ZenView::gen_menu(array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '/moveTop/' . $cat['id'],
            'name' => 'Chuyển lên trên cùng',
            'title' => 'Chuyển thư mục này lên trên cùng',
            'icon' => $icon['moveTop']));
        $cat['actions']['moveBottom'] = ZenView::gen_menu(array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '/moveBottom/' . $cat['id'],
            'name' => 'Chuyển xuống dưới cùng',
            'title' => 'Chuyển thư mục này xuống dưới cùng',
            'icon' => $icon['moveBottom']));
        if ($cat['status'] != 2) {
            $cat['actions']['moveToTrash'] = ZenView::gen_menu(array(
                'full_url' => $baseUrl . '/cpanel/' . $blogID . '/moveToTrash/' . $cat['id'] . '&submit-trash' . (!empty($param_cat_stt) ? $param_cat_stt : (!empty($param_post_stt) ? $param_post_stt : '')),
                'name' => 'Chuyển vào thùng rác',
                'title' => 'Chuyển thư mục này vào thùng rác',
                'icon' => $icon['removeToTrash'],
                'attr' => cfm('Bạn có muốn chuyển bài này vào thùng rác, tất cả các bài viết con trong thư mục sẽ chuyển hết vào thùng rác?'),
                'divider' => true,
            ));
        } else {
            $cat['actions']['restoreToDraft'] = ZenView::gen_menu(array(
                'full_url' => $baseUrl . '/cpanel/' . $blogID . '/restoreToDraft/' . $cat['id'] . '&submit-draft' . (!empty($param_cat_stt) ? $param_cat_stt : (!empty($param_post_stt) ? $param_post_stt : '')),
                'name' => 'Bỏ khỏi thùng rác',
                'icon' => $icon['restoreToDraft'],
                'attr' => cfm('Bạn có chắc chắn muốn khôi phục bài viết này về trạng thái nháp?'),
                'divider' => true,
            ));
            $cat['actions']['delete'] = ZenView::gen_menu(array(
                'full_url' => $baseUrl . '/cpanel/' . $blogID . '/delete/' . $cat['id'] . '&submit-delete' . (!empty($param_cat_stt) ? $param_cat_stt : (!empty($param_post_stt) ? $param_post_stt : '')),
                'name' => 'Xóa khỏi hệ thống',
                'icon' => $icon['delete'],
                'attr' => cfm('Bạn có chắc chắn muốn xóa thư mục này? Chú ý, thư mục chỉ có thể xóa khi không có bài viết')
            ));
        }
        $cat['actions'] = ZenView::gen_menu($cat['actions']);
        return $cat;
    }, $cats);
}

$limit = 10;
/**
 * num_post_in_cpanel hook *
 */
$limit = $obj->hook->loader('num_post_in_cpanel', $limit);

$model->set_filter('status', $filter_post_value);
$pagination->setLimit($limit);
$pagination->SetGetPage('pagePost');
$start = $pagination->getStart();
$sql_limit = $start . ',' . $limit;
$posts = $model->get_list_blog($filter_post_pos, array(
    'type' => 'post',
    'order' => array('weight' => 'ASC', 'time' => 'DESC'),
    'limit' => $sql_limit,
    'both_child' => false)
);
$total = $model->total_result;
$pagination->setTotal($total);
ZenView::set_paging($pagination->navi_page('pagePost='), 'post');
$data['stats']['count']['post'] = $total;
if (empty($posts)) {
    ZenView::set_notice('Hiện tại chưa có bài viết nào trong chuyên mục này!',
        'post');
} else {
    /**
     * foreach ($posts as $kid => $post) {
     * $parentData = $model->get_blog_data($post['parent'], 'id, url, name, title');
     * if (empty($parentData)) {
     * $posts[$kid]['cat'] = array();
     * } else {
     * $posts[$kid]['cat'] = $parentData;
     * $posts[$kid]['cat']['full_real_url'] = $posts[$kid]['cat']['full_url'];
     * $posts[$kid]['cat']['full_url'] = $baseUrl . '/cpanel/' . $posts[$kid]['cat']['id'] .
     * $param_post_stt;
     * }
     * $userData = $accModel->get_user_data($post['uid'], 'username, nickname');
     * if (!empty($userData)) {
     * $posts[$kid]['user'] = $userData;
     * $posts[$kid]['user']['full_url'] = HOME . '/admin/members/user/' . $userData['username'];
     * $posts[$kid]['user']['full_real_url'] = HOME . '/account/wall/' . $userData['username'];
     * }
     * $posts[$kid]['full_real_url'] = $posts[$kid]['full_url'];
     * $posts[$kid]['full_url'] = $baseUrl . '/editor&id=' . $kid;
     * if ($post['status'] == 0) {
     * $posts[$kid]['status_detail'] = array(
     * 'id' => 0,
     * 'name' => 'Đã đăng',
     * 'show' => '<span class="badge badge-success">Đã đăng</span>',
     * 'full_url' => $baseUrl . '/cpanel/' . $blogID .
     * '&filter_status=0&filter_for=post');
     * } elseif ($post['status'] == 1) {
     * $posts[$kid]['status_detail'] = array(
     * 'id' => 1,
     * 'name' => 'Nháp',
     * 'show' => '<span class="badge badge-warning">Nháp</span>',
     * 'full_url' => $baseUrl . '/cpanel/' . $blogID .
     * '&filter_status=1&filter_for=post');
     * } elseif ($post['status'] == 2) {
     * $posts[$kid]['status_detail'] = array(
     * 'id' => 2,
     * 'name' => 'Đã xóa',
     * 'show' => '<span class="badge badge-danger">Đã xóa</span>',
     * 'full_url' => $baseUrl . '/cpanel/' . $blogID .
     * '&filter_status=2&filter_for=post');
     * }
     * $posts[$kid]['actions']['view'] = array(
     * 'full_url' => $posts[$kid]['full_real_url'] . '?_review_',
     * 'name' => 'Xem bài viết',
     * 'title' => 'Xem bài viết này',
     * 'icon' => $icon['view'],
     * 'attr' => 'target="_blank"');
     * $posts[$kid]['actions']['moveUp'] = array(
     * 'full_url' => $baseUrl . '/cpanel/' . $blogID . '/moveUp/' . $kid,
     * 'name' => 'Chuyển lên',
     * 'title' => 'Chuyển bài viết này lên trên',
     * 'icon' => $icon['moveUp'],
     * );
     * $posts[$kid]['actions']['moveDown'] = array(
     * 'full_url' => $baseUrl . '/cpanel/' . $blogID . '/moveDown/' . $kid,
     * 'name' => 'Chuyển xuống',
     * 'title' => 'Chuyển bài viết này xuống dưới',
     * 'icon' => $icon['moveDown']);
     * $posts[$kid]['actions']['moveTop'] = array(
     * 'full_url' => $baseUrl . '/cpanel/' . $blogID . '/moveTop/' . $kid,
     * 'name' => 'Chuyển lên trên cùng',
     * 'title' => 'Chuyển bài viết này lên trên cùng',
     * 'icon' => $icon['moveTop']);
     * $posts[$kid]['actions']['moveBottom'] = array(
     * 'full_url' => $baseUrl . '/cpanel/' . $blogID . '/moveBottom/' . $kid,
     * 'name' => 'Chuyển xuống dưới cùng',
     * 'title' => 'Chuyển bài viết này xuống dưới cùng',
     * 'icon' => $icon['moveBottom']);
     * $posts[$kid]['actions']['delete'] = array(
     * 'full_url' => $baseUrl . '/cpanel/' . $blogID . '/moveToTrash/' . $kid .
     * '&submit-trash' . (!empty($param_cat_stt) ? $param_cat_stt : (!empty($param_post_stt) ?
     * $param_post_stt : '')),
     * 'name' => 'Chuyển vào thùng rác',
     * 'title' => 'Chuyển bài viết này vào thùng rác',
     * 'icon' => $icon['removeToTrash'],
     * 'divider' => true,
     * 'attr' => cfm('Bạn có muốn chuyển bài này vào thùng rác?'));
     * }
     */
    $posts = array_map(function ($post) use ($model, $accModel, $blogID, $baseUrl, $param_cat_stt, $param_post_stt, $icon) {
        $parentData = $model->get_blog_data($post['parent'], 'id, url, name, title');
        if (empty($parentData)) {
            $post['cat'] = array();
        } else {
            $post['cat'] = $parentData;
            $post['cat']['full_real_url'] = $post['cat']['full_url'];
            $post['cat']['full_url'] = $baseUrl . '/cpanel/' . $post['cat']['id'] . $param_post_stt;
        }
        $userData = $accModel->get_user_data($post['uid'], 'username, nickname');
        if (!empty($userData)) {
            $post['user'] = $userData;
            $post['user']['full_url'] = HOME . '/admin/members/user/' . $userData['username'];
            $post['user']['full_real_url'] = HOME . '/account/wall/' . $userData['username'];
        }
        $post['full_real_url'] = $post['full_url'];
        $post['full_url'] = $baseUrl . '/editor&id=' . $post['id'];
        if ($post['status'] == 0) {
            $post['status_detail'] = array(
                'id' => 0,
                'name' => 'Đã đăng',
                'show' => '<span class="badge badge-success">Đã đăng</span>',
                'full_url' => $baseUrl . '/cpanel/' . $blogID .
                    '&filter_status=0&filter_for=post');
        } elseif ($post['status'] == 1) {
            $post['status_detail'] = array(
                'id' => 1,
                'name' => 'Nháp',
                'show' => '<span class="badge badge-warning">Nháp</span>',
                'full_url' => $baseUrl . '/cpanel/' . $blogID .
                    '&filter_status=1&filter_for=post');
        } elseif ($post['status'] == 2) {
            $post['status_detail'] = array(
                'id' => 2,
                'name' => 'Đã xóa',
                'show' => '<span class="badge badge-danger">Đã xóa</span>',
                'full_url' => $baseUrl . '/cpanel/' . $blogID .
                    '&filter_status=2&filter_for=post');
        }
        $post['actions']['view'] = array(
            'full_url' => $post['full_real_url'] . '?_review_',
            'name' => 'Xem bài viết',
            'title' => 'Xem bài viết này',
            'icon' => $icon['view'],
            'attr' => 'target="_blank"');
        $post['actions']['moveUp'] = array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '/moveUp/' . $post['id'],
            'name' => 'Chuyển lên',
            'title' => 'Chuyển bài viết này lên trên',
            'icon' => $icon['moveUp'],
            'divider' => true
        );
        $post['actions']['moveDown'] = array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '/moveDown/' . $post['id'],
            'name' => 'Chuyển xuống',
            'title' => 'Chuyển bài viết này xuống dưới',
            'icon' => $icon['moveDown']);
        $post['actions']['moveTop'] = array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '/moveTop/' . $post['id'],
            'name' => 'Chuyển lên trên cùng',
            'title' => 'Chuyển bài viết này lên trên cùng',
            'icon' => $icon['moveTop']);
        $post['actions']['moveBottom'] = array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '/moveBottom/' . $post['id'],
            'name' => 'Chuyển xuống dưới cùng',
            'title' => 'Chuyển bài viết này xuống dưới cùng',
            'icon' => $icon['moveBottom']);
        if ($post['status'] != 2) {
            $post['actions']['moveToTrash'] = array(
                'full_url' => $baseUrl . '/cpanel/' . $blogID . '/moveToTrash/' . $post['id'] . '&submit-trash' . (!empty($param_cat_stt) ? $param_cat_stt : (!empty($param_post_stt) ? $param_post_stt : '')),
                'name' => 'Chuyển vào thùng rác',
                'title' => 'Chuyển bài viết này vào thùng rác',
                'icon' => $icon['removeToTrash'],
                'divider' => true,
                'attr' => cfm('Bạn có muốn chuyển bài này vào thùng rác?')
            );
        } else {
            $post['actions']['restoreToDraft'] = array(
                'full_url' => $baseUrl . '/cpanel/' . $blogID . '/restoreToDraft/' . $post['id'] . '&submit-draft' . (!empty($param_cat_stt) ? $param_cat_stt : (!empty($param_post_stt) ? $param_post_stt : '')),
                'name' => 'Bỏ khỏi thùng rác',
                'icon' => $icon['restoreToDraft'],
                'divider' => true,
                'attr' => cfm('Bạn có chắc chắn muốn khôi phục bài viết này về trạng thái nháp?')
            );
            $post['actions']['delete'] = array(
                'full_url' => $baseUrl . '/cpanel/' . $blogID . '/delete/' . $post['id'] . '&submit-delete' . (!empty($param_cat_stt) ? $param_cat_stt : (!empty($param_post_stt) ? $param_post_stt : '')),
                'name' => 'Xóa khỏi hệ thống',
                'icon' => $icon['delete'],
                'attr' => cfm('Bạn có chắc chắn muốn xóa toàn bộ bài viết và những gì liên quan đến bài viết như bình luận hay những đính kèm của bài viết?')
            );
        }
        return $post;
    }, $posts);
}

$data['cats'] = $cats;
$data['posts'] = $posts;
$data['blogID'] = $blogID;

if (empty($blog['name'])) {
    $blog['name'] = 'Trang chủ';
    $blog['title'] = dbConfig('title');
    $blog['full_url'] = HOME . '/blog';
}

ZenView::set_menu(array('pos' => 'filter-post', 'menu' => array(
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID,
            'name' => 'Không lọc',
            'icon' => 'fa fa-circle-thin',
            'active' => (!$_get_filter_status && !$_get_pos && !$_get_filter_for) ? true : false
        ),
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '&filter_status=0&filter_for=post',
            'name' => 'Đã đăng',
            'icon' => 'fa fa-check',
            'active' => ($_get_filter_status == 0 && !$_get_pos && $_get_filter_for == 'post') ? true : false,
            'divider' => true
        ),
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '&filter_status=1&filter_for=post',
            'name' => 'Nháp',
            'icon' => 'fa fa-hdd-o',
            'active' => ($_get_filter_status == 1 && !$_get_pos && $_get_filter_for == 'post') ? true : false
        ),
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '&filter_status=2&filter_for=post',
            'name' => 'Thùng rác',
            'icon' => 'fa fa-trash-o',
            'active' => ($_get_filter_status == 2 && !$_get_pos && $_get_filter_for == 'post') ? true : false
        ),
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '&filter_status=0&filter_for=post&pos=all',
            'name' => 'Đã đăng (tất cả)',
            'icon' => 'fa fa-check',
            'active' => ($_get_filter_status == 0 && $_get_pos == 'all' && $_get_filter_for == 'post') ? true : false,
            'divider' => true
        ),
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID .
                '&filter_status=1&filter_for=post&pos=all',
            'name' => 'Nháp (tất cả)',
            'icon' => 'fa fa-hdd-o',
            'active' => ($_get_filter_status == 1 && $_get_pos == 'all' && $_get_filter_for == 'post') ? true : false
        ),
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID .
                '&filter_status=2&filter_for=post&pos=all',
            'name' => 'Thùng rác (tất cả)',
            'icon' => 'fa fa-trash-o',
            'active' => ($_get_filter_status == 2 && $_get_pos == 'all' && $_get_filter_for == 'post') ? true : false
        )
    )
    )
);

ZenView::set_menu(array('pos' => 'filter-cat', 'menu' => array(
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID,
            'name' => 'Không lọc',
            'icon' => 'fa fa-circle-thin',
            'active' => (!$_get_filter_status && !$_get_pos && !$_get_filter_for) ? true : false
        ),
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID .
                '&filter_status=0&filter_for=cat',
            'name' => 'Đã đăng',
            'icon' => 'fa fa-check',
            'active' => ($_get_filter_status == 0 && !$_get_pos && $_get_filter_for == 'cat') ? true : false,
            'divider' => true
        ),
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID .
                '&filter_status=1&filter_for=cat',
            'name' => 'Nháp',
            'icon' => 'fa fa-hdd-o',
            'active' => ($_get_filter_status == 1 && !$_get_pos && $_get_filter_for == 'cat') ? true : false
        ),
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID .
                '&filter_status=2&filter_for=cat',
            'name' => 'Thùng rác',
            'icon' => 'fa fa-trash-o',
            'active' => ($_get_filter_status == 2 && !$_get_pos && $_get_filter_for == 'cat') ? true : false
        ),
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID .
                '&filter_status=0&filter_for=cat&pos=all',
            'name' => 'Đã đăng (tất cả)',
            'icon' => 'fa fa-check',
            'active' => ($_get_filter_status == 0 && $_get_pos == 'all' && $_get_filter_for == 'cat') ? true : false,
            'divider' => true
        ),
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID .
                '&filter_status=1&filter_for=cat&pos=all',
            'name' => 'Nháp (tất cả)',
            'icon' => 'fa fa-hdd-o',
            'active' => ($_get_filter_status == 1 && $_get_pos == 'all' && $_get_filter_for == 'cat') ? true : false
        ),
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '&filter_status=2&filter_for=cat&pos=all',
            'name' => 'Thùng rác (tất cả)',
            'icon' => 'fa fa-trash-o',
            'active' => ($_get_filter_status == 2 && $_get_pos == 'all' && $_get_filter_for == 'cat') ? true : false
        ))
    )
);

ZenView::set_menu(array('pos' => 'section-action', 'menu' => array(array(
        'full_url' => $baseUrl . '/cpanel/',
        'name' => 'Trang chủ',
        'icon' => 'fa fa-home'), array(
        'full_url' => $baseUrl . '/cpanel/' . $blog['parent'],
        'name' => 'Về mục trước',
        'icon' => 'fa fa-arrow-left')
    )
    )
);
$data['blog'] = $blog;
$obj->view->data = $data;
$obj->view->show('blog/manager/cpanel');
