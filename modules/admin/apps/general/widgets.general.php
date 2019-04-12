<?php
/**
 * name = Quản lí widget
 * icon = admin_general_widgets
 * position = 80
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
 * load admin model
 */
$model = $obj->model->get('admin');

/**
 * load hook
 */
$obj->hook->get('admin');

/**
 * load library
 */
$p = load_library('pagination');
$security = load_library('security');

$data['page_title'] = 'Quản lí widgets';
$tree[] = url(_HOME . '/admin', 'Admin CP');
$tree[] = url(_HOME . '/admin/general', 'Tổng quan');
$tree[] = url(_HOME . '/admin/general/widgets', $data['page_title']);

$wg = '';
$act = '';
$wid = 0;

if (isset($app[1])) {

    $wg = $security->cleanXSS($app[1]);
}
if (isset($app[2])) {

    $act = $security->cleanXSS($app[2]);
}
if (isset($app[3])) {

    $wid = $security->removeSQLI($app[3]);
}

$list_widget_groups = array_keys($GLOBALS['widgets']);

$data['list_widget_groups'] = $list_widget_groups;

$data['widgets'] = array();
$data['wg'] = '';

if (empty($wg)) {

    $data['display_tree'] = display_tree($tree);
    $obj->view->data = $data;
    $obj->view->show('admin/general/widgets/index');

} else {

    $wg = urldecode($wg);

    if (!in_array($wg, $list_widget_groups)) {

        redirect(_HOME . '/admin/general/widgets');
    }

    $data['wg'] = $wg;

    switch ($act) {

        default:

            if (isset($_POST['sub_order'])) {

                foreach ($_POST['weight'] as $num => $value) {

                    $num = (int) $num;

                    $update['weight'] = $value;

                    $model->update_widget($num, $update);

                    $update = array();
                }
            }

            $widgets = $model->get_widget_group($wg);
            $data['widgets'] = $widgets;
            $data['display_tree'] = display_tree($tree);
            $obj->view->data = $data;
            $obj->view->show('admin/general/widgets/widgetGroup');
            break;

        case 'new':

            if (isset($_POST['sub_new'])) {

                $ins['title'] = h($_POST['title']);
                $ins['content'] = h($_POST['content']);
                $ins['wg'] = $wg;

                if ($model->insert_widget($ins)) {

                    redirect(_HOME . '/admin/general/widgets/' . urlencode($wg));

                } else {

                    $data['errors'][] = 'Lỗi dữ liệu';
                }
            }

            if (isset($_GET['add'])) {

                $addid = $security->removeSQLI($_GET['add']);

                $update['wg'] = $wg;

                if ($model->update_widget($addid, $update)) {

                    redirect(_HOME . '/admin/general/widgets/' . urlencode($wg));

                } else {

                    $data['errors'][] = 'Lỗi dữ liệu';
                }

            }

            $data['widgets'] = array();

            $widgets = $model->get_widget_group('');

            $total = count($widgets);

            $limit = 10;
            /**
             *
             */
            $obj->hook->loader('num_widget_display_in_cp', $limit);

            $p->setLimit($limit);
            $p->SetGetPage('page');
            $start = $p->getStart();

            for ($i = $start; $i < $start + $limit; $i++) {

                if (isset($widgets[$i])) {

                    $data['widgets'][] = $widgets[$i];
                }
            }

            $p->setTotal($total);
            $data['widgets_pagination'] = $p->navi_page();

            $tree[] = url(_HOME . '/admin/general/widgets/' . urlencode($wg), $wg);
            $data['display_tree'] = display_tree($tree);
            $data['page_title'] = 'Thêm widget vào vị trí ' . $wg;
            $obj->view->data = $data;
            $obj->view->show('admin/general/widgets/new');
            break;

        case 'edit':

            $wd = $model->get_widget_data($wid);

            if (empty($wd)) {

                redirect(_HOME . '/admin/general/widgets/' . urlencode($wg));
            }

            if (isset($_POST['sub_edit'])) {

                if (empty($_POST['content'])) {

                    $data['notices'][] = 'Chưa có nội dung widget';

                } else {

                    $update['title'] = h($_POST['title']);
                    $update['content'] = h($_POST['content']);

                    if ($model->update_widget($wid, $update)) {

                        $data['success'] = 'Thành công';

                        $wd = $model->get_widget_data($wid);

                    } else {
                        $data['errors'][] = 'Lỗi dữ liệu';
                    }
                }
            }

            $tree[] = url(_HOME . '/admin/general/widgets/' . urlencode($wg), $wg);
            $data['display_tree'] = display_tree($tree);
            $data['page_title'] = 'Sửa widget';
            $data['widget_data'] = $wd;
            $obj->view->data = $data;
            $obj->view->show('admin/general/widgets/edit');
            break;

        case 'unbound':

            $wd = $model->get_widget_data($wid);

            if (empty($wd)) {

                redirect(_HOME . '/admin/general/widgets/' . urlencode($wg));
            }

            $update['wg'] = '';

            $model->update_widget($wid, $update);

            redirect(_HOME . '/admin/general/widgets/' . urlencode($wg));

            $tree[] = url(_HOME . '/admin/general/widgets/' . urlencode($wg), $wg);
            $data['display_tree'] = display_tree($tree);
            $data['page_title'] = 'Bỏ widget';
            $data['widget_data'] = $wd;
            $obj->view->data = $data;
            $obj->view->show('admin/general/widgets/unbound');

            break;

        case 'review':

            $wd = $model->get_widget_data($wid);

            if (empty($wd)) {

                redirect(_HOME . '/admin/general/widgets/' . urlencode($wg));
            }

            $tree[] = url(_HOME . '/admin/general/widgets/' . urlencode($wg), $wg);
            $data['display_tree'] = display_tree($tree);
            $data['page_title'] = 'Xem trước';
            $data['widget_data'] = $wd;
            $obj->view->data = $data;
            $obj->view->show('admin/general/widgets/review');

            break;

        case 'delete':

            $wd = $model->get_widget_data($wid);

            if (empty($wd)) {

                redirect(_HOME . '/admin/general/widgets/' . urlencode($wg));
            }

            if (isset ($_POST['sub_delete'])) {

                if (!$model->delete_widget($wid) ) {

                    $data['errors'][] = 'Lỗi dự liệu';
                } else {
                    redirect(_HOME . '/admin/general/widgets/' . urlencode($wg));
                }
            }

            $tree[] = url(_HOME . '/admin/general/widgets/' . urlencode($wg), $wg);
            $data['display_tree'] = display_tree($tree);
            $data['page_title'] = 'Bỏ widget';
            $data['widget_data'] = $wd;
            $obj->view->data = $data;
            $obj->view->show('admin/general/widgets/delete');

            break;
    }
}
?>