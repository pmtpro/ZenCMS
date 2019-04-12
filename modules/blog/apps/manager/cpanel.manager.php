<?php
/**
 * name = Quản lí nội dung
 * icon = icon-folder-open-alt
 * position = 1
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

$model = $obj->model->get('blog'); //get blog model
$accModel = $obj->model->get('account');
$obj->hook->get('blog'); //get blog hook
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

$ico_add = 'icon-plus-sign-alt';
$ico_edit = 'icon-pencil';
$ico_up = 'icon-angle-up';
$ico_down = 'icon-angle-down';
$ico_top = 'icon-double-angle-up';
$ico_bottom = 'icon-double-angle-down';
$ico_delete = 'icon-remove';

$blogID = 0;
if (isset($app[1])) $blogID = (int)$security->removeSQLI($app[1]);
if (isset($app[2])) $act = $security->cleanXSS($app[2]);
if (isset($app[3])) $ActID = (int)$security->removeSQLI($app[3]);
if (isset($app[3])) $ActID1 = (int)$security->removeSQLI($app[3]);
if (isset($app[4])) $ActID2 = (int)$security->removeSQLI($app[4]);

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
$arr_stt = array(0,1,2);
$filter_post_value = $arr_stt;
$filter_cat_value = $arr_stt;
$filter_cat_pos = $blogID;
$filter_post_pos = $blogID;
$param_post_stt = '';
$param_cat_stt = '';
if (isset($_GET['filter_for']) && isset($_GET['filter_status']) && in_array($_GET['filter_status'], $arr_stt)) {
    if ($_GET['filter_for'] == 'post') {
        $filter_post_value = $_GET['filter_status'];
        $param_post_stt = '&filter_status=' . $_GET['filter_status'] . '&filter_for=' . $_GET['filter_for'];
        if (isset($_GET['pos']) && $_GET['pos'] == 'all') {
            $filter_post_pos = null;
        }
    } elseif ($_GET['filter_for'] == 'cat') {
        $filter_cat_value = $_GET['filter_status'];
        $param_cat_stt = '&filter_status=' . $_GET['filter_status'] . '&filter_for=' . $_GET['filter_for'];
        if (isset($_GET['pos']) && $_GET['pos'] == 'all') {
            $filter_cat_pos = null;
        }
    }
}

switch ($act) {
    case 'moveUp':

        if (!empty($ActID1)) {
            $sdata1 = $model->get_blog_data($ActID1, 'weight, parent, type');
            $w1 = $sdata1['weight'];
            $getlist = $model->get_list_blog($blogID, array(
                    'type' => $sdata1['type'],
                    'order' => array('weight' => 'ASC', 'time' => 'DESC'),
                    'both_child' => false
                )
            );
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
        }
        redirect($baseUrl . '/cpanel/' . $blogID);
        break;

    case 'moveDown':
        if (!empty($ActID1)) {
            $sdata1 = $model->get_blog_data($ActID1, 'weight, parent, type');
            $w1 = $sdata1['weight'];
            $getlist = $model->get_list_blog($blogID, array(
                    'type' => $sdata1['type'],
                    'order' => array('weight' => 'ASC', 'time' => 'DESC'),
                    'both_child' => false
                )
            );
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
        }
        redirect($baseUrl . '/cpanel/' . $blogID);
        break;

    case 'moveTop':
        if ($ActID) {
            $top = $model->gets('weight', '', array('weight' => 'ASC', 'time' => 'DESC'), 1);
            $TID = $top[0]['weight'];
            if ($TID > 1) {
                $TID = $TID - 1;
                $model->update_blog(array('weight' => $TID), $ActID);
            } else {
                $model->update_blog(array('weight' => $TID), $ActID);
                $model->update_blog(array('weight' => '{`weight`+1}'), "`weight` >= '$TID' and `id` != '$ActID'");
            }
        }
       redirect($baseUrl . '/cpanel/' . $blogID);
       break;

    case 'moveBottom':
        if ($ActID) {
            $top = $model->gets(array('weight', 'id'), '', array('weight' => 'DESC', 'time' => 'DESC'), 1);
            if ($top[0]['id'] != $ActID) {
                $TID = $top[0]['weight'];
                $TID = $TID + 1;
                $model->update_blog(array('weight' => $TID), $ActID);
            }
        }
        redirect($baseUrl . '/cpanel/' . $blogID);
        break;

    case 'delete':
        if (isset($_REQUEST['submit-delete'])) {
            if (!$model->remove_to_recycle_bin($ActID)) {
                ZenView::set_error('Không thể chuyển vào thùng rác!', ZPUBLIC, $baseUrl . '/cpanel/' . $blogID . (!empty($param_cat_stt)?$param_cat_stt:(!empty($param_post_stt)?$param_post_stt:'')));
            } else {
                ZenView::set_success('Xóa thành công!', ZPUBLIC, $baseUrl . '/cpanel/' . $blogID . (!empty($param_cat_stt)?$param_cat_stt:(!empty($param_post_stt)?$param_post_stt:'')));
            }
        } else redirect($baseUrl . '/cpanel/' . $blogID . (!empty($param_cat_stt)?$param_cat_stt:(!empty($param_post_stt)?$param_post_stt:'')));
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
        'order' => array('weight' => 'ASC', 'time' => 'DESC'),
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
    foreach ($cats as $kid => $cat) {
        $parentData = $model->get_blog_data($cat['parent'], 'id, url, name, title');
        if (empty($parentData)) {
            $cats[$kid]['cat'] = array();
        } else {
            $cats[$kid]['cat'] = $parentData;
            $cats[$kid]['cat']['full_real_url'] = $cats[$kid]['cat']['full_url'];
            $cats[$kid]['cat']['full_url'] = $baseUrl . '/cpanel/' . $cats[$kid]['cat']['id'] . $param_cat_stt;
        }
        $userData = $accModel->get_user_data($cat['uid'], 'username, nickname');
        if (!empty($userData)) {
            $cats[$kid]['user'] = $userData;
            $cats[$kid]['user']['full_url'] = HOME . '/admin/members/user/' . $userData['username'];
            $cats[$kid]['user']['full_real_url'] = HOME . '/account/wall/' . $userData['username'];
        }
        $cats[$kid]['full_real_url'] = $cats[$kid]['full_url'];
        $cats[$kid]['full_url'] = $baseUrl . '/cpanel/' . $kid . $paramStt;
        if ($cat['status'] == 0) {
            $cats[$kid]['status_detail'] = array(
                'id' => 0,
                'name' => 'Đã đăng',
                'show' => '<i class="smaller status-public">Đã đăng</i>',
                'full_url' => $baseUrl . '/cpanel/' . $blogID . '&filter_status=0&filter_for=cat'
            );
        } elseif ($cat['status'] == 1) {
            $cats[$kid]['status_detail'] = array(
                'id' => 1,
                'name' => 'Nháp',
                'show' => '<i class="smaller status-draft">Nháp</i>',
                'full_url' => $baseUrl . '/cpanel/' . $blogID . '&filter_status=1&filter_for=cat'
            );
        } elseif ($cat['status'] == 2) {
            $cats[$kid]['status_detail'] = array(
                'id' => 2,
                'name' => 'Đã xóa',
                'show' => '<i class="smaller status-trash">Đã xóa</i>',
                'full_url' => $baseUrl . '/cpanel/' . $blogID . '&filter_status=2&filter_for=cat'
            );
        }
        $cats[$kid]['actions']['edit'] = ZenView::gen_menu(array(
            'full_url' => $baseUrl . '/editor&id=' . $kid,
            'name' => 'Chỉnh sửa',
            'title' => 'Chỉnh sửa thư mục này',
            'icon' => $ico_edit
        ));
        $cats[$kid]['actions']['view'] = array(
            'full_url' => $cats[$kid]['full_real_url'],
            'name' => 'Xem thư mục',
            'title' => 'Xem thư mục này',
            'icon' => 'icon-eye-open',
            'attr' => 'target="_blank"'
        );
        $cats[$kid]['actions']['moveUp'] = ZenView::gen_menu(array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '/moveUp/' . $kid,
            'name' => 'Chuyển lên',
            'title' => 'Chuyển thư mục này lên trên',
            'icon' => $ico_up
        ));
        $cats[$kid]['actions']['moveDown'] = ZenView::gen_menu(array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '/moveDown/' . $kid,
            'name' => 'Chuyển xuống',
            'title' => 'Chuyển thư mục này xuống dưới',
            'icon' => $ico_down
        ));
        $cats[$kid]['actions']['moveTop'] = ZenView::gen_menu(array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '/moveTop/' . $kid,
            'name' => 'Chuyển lên trên cùng',
            'title' => 'Chuyển thư mục này lên trên cùng',
            'icon' => $ico_top
        ));
        $cats[$kid]['actions']['moveBottom'] = ZenView::gen_menu(array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '/moveBottom/' . $kid,
            'name' => 'Chuyển xuống dưới cùng',
            'title' => 'Chuyển thư mục này xuống dưới cùng',
            'icon' => $ico_bottom
        ));
        $cats[$kid]['actions']['delete'] = ZenView::gen_menu(array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '/delete/' . $kid . '&submit-delete' . (!empty($param_cat_stt)?$param_cat_stt:(!empty($param_post_stt)?$param_post_stt:'')),
            'name' => 'Chuyển vào thùng rác',
            'title' => 'Chuyển thư mục này vào thùng rác',
            'icon' => $ico_delete,
            'attr' => cfm('Bạn có muốn chuyển bài này vào thùng rác, tất cả các bài viết con trong thư mục sẽ chuyển hết vào thùng rác?'),
            'divider' => true,
        ));
        $cats[$kid]['actions'] = ZenView::gen_menu($cats[$kid]['actions']);
    }
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
        'both_child' => false
    )
);
$total = $model->total_result;
$pagination->setTotal($total);
ZenView::set_paging($pagination->navi_page('pagePost='), 'post');
$data['stats']['count']['post'] = $total;
if (empty($posts)) {
    ZenView::set_notice('Hiện tại chưa có bài viết nào trong chuyên mục này!', 'post');
} else {
    /* @var $kid type */
    foreach ($posts as $kid => $post) {
        $parentData = $model->get_blog_data($post['parent'], 'id, url, name, title');
        if (empty($parentData)) {
            $posts[$kid]['cat'] = array();
        } else {
            $posts[$kid]['cat'] = $parentData;
            $posts[$kid]['cat']['full_real_url'] = $posts[$kid]['cat']['full_url'];
            $posts[$kid]['cat']['full_url'] = $baseUrl . '/cpanel/' . $posts[$kid]['cat']['id'] . $param_post_stt;
        }
        $userData = $accModel->get_user_data($post['uid'], 'username, nickname');
        if (!empty($userData)) {
            $posts[$kid]['user'] = $userData;
            $posts[$kid]['user']['full_url'] = HOME . '/admin/members/user/' . $userData['username'];
            $posts[$kid]['user']['full_real_url'] = HOME . '/account/wall/' . $userData['username'];
        }
        $posts[$kid]['full_real_url'] = $posts[$kid]['full_url'];
        $posts[$kid]['full_url'] = $baseUrl . '/editor&id=' . $kid;
        if ($post['status'] == 0) {
            $posts[$kid]['status_detail'] = array(
                'id' => 0,
                'name' => 'Đã đăng',
                'show' => '<i class="smaller status-public">Đã đăng</i>',
                'full_url' => $baseUrl . '/cpanel/' . $blogID . '&filter_status=0&filter_for=post'
            );
        } elseif ($post['status'] == 1) {
            $posts[$kid]['status_detail'] = array(
                'id' => 1,
                'name' => 'Nháp',
                'show' => '<i class="smaller status-draft">Nháp</i>',
                'full_url' => $baseUrl . '/cpanel/' . $blogID . '&filter_status=1&filter_for=post'
            );
        } elseif ($post['status'] == 2) {
            $posts[$kid]['status_detail'] = array(
                'id' => 2,
                'name' => 'Đã xóa',
                'show' => '<i class="smaller status-trash">Đã xóa</i>',
                'full_url' => $baseUrl . '/cpanel/' . $blogID . '&filter_status=2&filter_for=post'
            );
        }
        $posts[$kid]['actions']['view'] = array(
            'full_url' => $posts[$kid]['full_real_url'] . '?_review_',
            'name' => 'Xem bài viết',
            'title' => 'Xem bài viết này',
            'icon' => 'icon-eye-open',
            'attr' => 'target="_blank"'
        );
        $posts[$kid]['actions']['moveUp'] = array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '/moveUp/' . $kid,
            'name' => 'Chuyển lên',
            'title' => 'Chuyển bài viết này lên trên',
            'icon' => $ico_up,
        );
        $posts[$kid]['actions']['moveDown'] = array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '/moveDown/' . $kid,
            'name' => 'Chuyển xuống',
            'title' => 'Chuyển bài viết này xuống dưới',
            'icon' => $ico_down
        );
        $posts[$kid]['actions']['moveTop'] = array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '/moveTop/' . $kid,
            'name' => 'Chuyển lên trên cùng',
            'title' => 'Chuyển bài viết này lên trên cùng',
            'icon' => $ico_top
        );
        $posts[$kid]['actions']['moveBottom'] = array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '/moveBottom/' . $kid,
            'name' => 'Chuyển xuống dưới cùng',
            'title' => 'Chuyển bài viết này xuống dưới cùng',
            'icon' => $ico_bottom
        );
        $posts[$kid]['actions']['delete'] = array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '/delete/' . $kid . '&submit-delete' . (!empty($param_cat_stt)?$param_cat_stt:(!empty($param_post_stt)?$param_post_stt:'')),
            'name' => 'Chuyển vào thùng rác',
            'title' => 'Chuyển bài viết này vào thùng rác',
            'icon' => $ico_delete,
            'divider' => true,
            'attr' => cfm('Bạn có muốn chuyển bài này vào thùng rác?')
        );
    }
}

$data['cats'] = $cats;
$data['posts'] = $posts;
$data['blogID'] = $blogID;

if (empty($blog['name'])) {
    $blog['name'] = 'Trang chủ';
    $blog['title'] = dbConfig('title');
    $blog['full_url'] = HOME . '/blog';
}

ZenView::set_menu(array(
    'pos' => 'filter-post',
    'menu' => array(
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID ,
            'name' => 'Không lọc',
            'icon' => 'icon-check-empty',
            'active' => (!isset($_GET['filter_status']) && empty($_GET['pos']) && empty($_GET['filter_for'])) ? true : false
        ),
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '&filter_status=0&filter_for=post',
            'name' => 'Những bài đã đăng trong thư mục này',
            'icon' => 'icon-ok',
            'active' => ($_GET['filter_status']==0 && empty($_GET['pos']) && $_GET['filter_for'] == 'post') ? true : false
        ),
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '&filter_status=1&filter_for=post',
            'name' => 'Những bài nháp trong thư mục này',
            'icon' => 'icon-hdd',
            'active' => ($_GET['filter_status']==1 && empty($_GET['pos']) && $_GET['filter_for'] == 'post') ? true : false
        ),
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '&filter_status=2&filter_for=post',
            'name' => 'Những bài đã xóa trong thư mục này',
            'icon' => 'icon-trash',
            'active' => ($_GET['filter_status']==2 && empty($_GET['pos']) && $_GET['filter_for'] == 'post') ? true : false
        ),
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '&filter_status=0&filter_for=post&pos=all',
            'name' => 'Tất cả các bài đã đăng có sẵn',
            'icon' => 'icon-ok',
            'active' => ($_GET['filter_status']==0 && $_GET['pos']=='all' && $_GET['filter_for'] == 'post') ? true : false
        ),
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '&filter_status=1&filter_for=post&pos=all',
            'name' => 'Tất cả các bài nháp có sẵn',
            'icon' => 'icon-hdd',
            'active' => ($_GET['filter_status']==1 && $_GET['pos']=='all' && $_GET['filter_for'] == 'post') ? true : false
        ),
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '&filter_status=2&filter_for=post&pos=all',
            'name' => 'Tất cả các bài đã xóa',
            'icon' => 'icon-trash',
            'active' => ($_GET['filter_status']==2 && $_GET['pos']=='all' && $_GET['filter_for'] == 'post') ? true : false
        )
    )
));

ZenView::set_menu(array(
    'pos' => 'filter-cat',
    'menu' => array(
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID ,
            'name' => 'Không lọc',
            'icon' => 'icon-check-empty',
            'active' => (!isset($_GET['filter_status']) && empty($_GET['pos']) && empty($_GET['filter_for'])) ? true : false
        ),
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '&filter_status=0&filter_for=cat',
            'name' => 'Những mục đã đăng trong thư mục này',
            'icon' => 'icon-ok',
            'active' => ($_GET['filter_status']==0 && empty($_GET['pos']) && $_GET['filter_for'] == 'cat') ? true : false
        ),
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '&filter_status=1&filter_for=cat',
            'name' => 'Những mục nháp trong thư mục này',
            'icon' => 'icon-hdd',
            'active' => ($_GET['filter_status']==1 && empty($_GET['pos']) && $_GET['filter_for'] == 'cat') ? true : false
        ),
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '&filter_status=2&filter_for=cat',
            'name' => 'Những mục đã xóa trong thư mục này',
            'icon' => 'icon-trash',
            'active' => ($_GET['filter_status']==2 && empty($_GET['pos']) && $_GET['filter_for'] == 'cat') ? true : false
        ),
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '&filter_status=0&filter_for=cat&pos=all',
            'name' => 'Tất cả các mục đã đăng',
            'icon' => 'icon-ok',
            'active' => ($_GET['filter_status']==0 && $_GET['pos']=='all' && $_GET['filter_for'] == 'cat') ? true : false
        ),
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '&filter_status=1&filter_for=cat&pos=all',
            'name' => 'Tất cả các mục nháp',
            'icon' => 'icon-hdd',
            'active' => ($_GET['filter_status']==1 && $_GET['pos']=='all' && $_GET['filter_for'] == 'cat') ? true : false
        ),
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blogID . '&filter_status=2&filter_for=cat&pos=all',
            'name' => 'Tất cả các mục đã xóa',
            'icon' => 'icon-trash',
            'active' => ($_GET['filter_status']==2 && $_GET['pos']=='all' && $_GET['filter_for'] == 'cat') ? true : false
        )
    )
));

ZenView::set_menu(array(
    'pos' => 'section-action',
    'menu' => array(
        array(
            'full_url' => $baseUrl . '/cpanel/',
            'name' => 'Trang chủ',
            'icon' => 'icon-home'
        ),
        array(
            'full_url' => $baseUrl . '/cpanel/' . $blog['parent'],
            'name' => 'Về mục trước',
            'icon' => 'icon-arrow-left'
        )
    )
));
$data['blog'] = $blog;
$obj->view->data = $data;
$obj->view->show('blog/manager/cpanel');