<?php
/**
 * name = Thùng rác
 * icon = blog_manager_recycle_bin
 * position = 160
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
 * get blog model
 */
$model = $obj->model->get('blog');

/**
 * load user data
 */
$user = $obj->user;

/**
 * get hook
 */
$obj->hook->get('blog');

/**
 * load helpers
 */
load_helper('gadget');
load_helper('blog_access_app');

/**
 * load library
 */
$seo = load_library('seo');
$security = load_library('security');
$p = load_library('pagination');

/**
 * check access
 */
if (is_allow_access_blog_app(__FILE__) == false) {

    show_error(403);
}

$sid = 0;
$act = '';

if (isset($app[1])) {

    $sid = $security->removeSQLI($app[1]);
    $sid = (int)$sid;
}

if (isset($app[2])) {
    $step = $security->cleanXSS($app[2]);
}

if (isset($_GET['delete'])) {

    if (!$obj->hook->loader('delete', $_GET['delete'])) {

        $data['notices'] = 'Không thành công!';
    } else {
        $data['success'] = 'Thành công';
    }

}

if (isset($_GET['reblog'])) {

    $data_reblog = $model->get_blog_data($_GET['reblog'], 'parent');

    if (!$model->blog_exists($data_reblog['parent'])) {

        $data['errors'][] = 'Bài viết này không thể khôi phục do thư mục chứa nó đã bị xóa';

    } else {

        if (!$model->restore($_GET['reblog'])) {

            $data['notices'] = 'Không thành công!';
        } else {

            $blog = $model->get_blog_data($_GET['reblog']);

            $data['success'] = 'Thành công <u>' . url($blog['full_url'], 'Đi đến bài viết', 'target="_blank"') . '</u>';
        }
    }
}

if (isset($_GET['move'])) {

    $data_reblog = $model->get_blog_data($_GET['move']);

    if (empty($data_reblog) || $data_reblog['recycle_bin'] == 0) {

        $data['notices'][] = 'Bài này không tồn tại hoặc không còn nằm trong thùng rác';

    } else {

        if (isset($_POST['sub_move'])) {

            $data_moveto = $model->get_blog_data($_POST['to']);

            if (empty($data_moveto)) {

                $data['notices'][] = 'Không tồn tại mục này';

            } else {

                $update['parent'] = $_POST['to'];

                $update['recycle_bin'] = 0;

                if (!$model->update_blog($update, $_GET['move'])) {

                    $data['notices'][] = 'Lỗi dữ liệu';

                } else {

                    redirect(_HOME . '/blog/manager/recycleBin');
                }
            }
        }


        $data['blog'] = $data_reblog;

        $data['tree_folder'] = $model->get_tree_folder();
        /**
         * set page title
         */
        $page_title = 'Di chuyển';
        $tree[] = url(_HOME . '/blog/manager', 'blog manager');
        $tree[] = url(_HOME . '/blog/manager/recycleBin', 'Thùng rác');
        $data['display_tree'] = display_tree_modulescp($tree);
        $data['page_title'] = $page_title;
        $obj->view->data = $data;
        $obj->view->show('blog/manager/recycleBin/move');
        return;
    }
}


/**
 */
$model->only_filter_recycle_bin();

/**
 * start pagination
 */
$limit = 10;
/**
 * num_post_display_recycle_bin hook *
 */
$limit = $obj->hook->loader('num_post_display_recycle_bin', $limit);

$p->setLimit($limit);
$p->SetGetPage('page');
$start = $p->getStart();
$sql_limit = $start . ',' . $limit;

$data['posts'] = $model->gets('*', "where type = 'post'", array('time' => 'DESC'), $sql_limit);

$total = $model->total_result;
$p->setTotal($total);
$data['posts_pagination'] = $p->navi_page();

/**
 * set manager bar for blog
 */
foreach ($data['posts'] as $key => $post) {

    $data['posts'][$key]['manager_bar'] = $obj->hook->loader('recycleBin_manager_bar', $post['id']);
}

/**
 * make sure all post is deleted
 */
if (!$total) {

    $limit = 10;
    /**
     * num_post_display_recycle_bin hook *
     */
    $limit = $obj->hook->loader('num_folder_display_recycle_bin', $limit);

    $p->setLimit($limit);
    $p->SetGetPage('fpage');
    $start = $p->getStart();
    $sql_limit = $start . ',' . $limit;

    $data['cats'] = $model->get_all_folder_recyclebin($sql_limit);

    $total = $model->total_result;
    $p->setTotal($total);
    $data['folders_pagination'] = $p->navi_page('?fpage={fpage}');

    /**
     * set manager bar for blog
     */
    foreach ($data['cats'] as $key => $cat) {

        $data['cats'][$key]['manager_bar'] = $obj->hook->loader('recycleBin_manager_bar', $cat['id']);
    }

    $data['count_cats'] = 0;

} else {

    $data['cats'] = $model->get_all_folder_recyclebin($sql_limit);

    $total = $model->total_result;

    $data['count_cats'] = $total;

    $data['cats'] = array();
}

/**
 * set page title
 */
$page_title = 'Thùng rác';
$tree[] = url(_HOME . '/blog/manager', 'blog manager');
$tree[] = url(_HOME . '/blog/manager/recycleBin', $page_title);
$data['display_tree'] = display_tree_modulescp($tree);
$data['page_title'] = $page_title;
$obj->view->data = $data;
$obj->view->show('blog/manager/recycleBin/index');
