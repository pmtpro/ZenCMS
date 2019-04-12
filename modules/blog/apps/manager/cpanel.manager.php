<?php
/**
 * name = Quản lí nội dung
 * icon = blog_manager_cpanel
 * position = 1
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
 * get blog data
 */
$model = $obj->model->get('blog');

/**
 * get hook
 */
$obj->hook->get('blog');

/**
 * load user data
 */
$user = $obj->user;

/**
 * load library
 */
$seo = load_library('seo');
$security = load_library('security');
$pagination = load_library('pagination');

/**
 * set page title
 */
$page_title = 'Quản lí nội dung';
$data['page_title'] = $page_title;
$tree[] = url(_HOME . '/blog/manager', 'blog manager');
$tree[] = url(_HOME . '/blog/manager/cpanel', $page_title);
$data['display_tree'] = display_tree_modulescp($tree);

$cats = array();
$act = '';
$ActID = 0;
$ActID1 = 0;
$ActID2 = 0;
$DisplayContent = array();

$ico_add = icon('manager_add');
$ico_edit = icon('manager_edit');
$ico_up = '&#8593;';
$ico_down = '&#8595;';
$ico_top = '&#8657;';
$ico_bottom = '&#8659;';
$ico_delete = icon('manager_delete');

if (isset($app[1])) {

    $sid = (int)$security->removeSQLI($app[1]);

} else {

    $sid = 0;
}

if (isset($app[2])) {
    $act = $security->cleanXSS($app[2]);
}
if (isset($app[3])) {
    $ActID = (int)$security->removeSQLI($app[3]);
}
if (isset($app[3])) {
    $ActID1 = (int)$security->removeSQLI($app[3]);
}
if (isset($app[4])) {
    $ActID2 = (int)$security->removeSQLI($app[4]);
}

/**
 * check blog is exists
 * if not exist, redriect to cpanel page
 */
if (!$model->blog_exists($sid)) {

    redirect(_HOME . '/blog/manager/cpanel');
}

switch ($act) {

    case 'add':
        if (!$model->blog_exists($ActID)) {

            redirect(_HOME . '/blog/manager/cpanel/' . $sid);
        }

        if (isset($_POST['SubAdd'])) {

            if ($security->check_token('token_add_stote') == FALSE) {
                break;
            }

            $name = $_POST['name'];
            $title = $_POST['title'];
            $url = $seo->url($name);
            $keyword = $_POST['keyword'];
            $des = $_POST['des'];

            /**
             * valid_name hook *
             */
            $name = $obj->hook->loader('valid_name', $name);

            if ($name == false) {

                $data['errors'][] = 'Tiêu đề bài viết quá ngắn';

            } else {

                if ($model->blog_name_exists($name)) {

                    $data['errors'][] = 'Đã tồn tài bài viết cùng tên!';
                }
            }

            /**
             * valid_title hook *
             */
            $title = $obj->hook->loader('valid_title', $title);
            /**
             * valid_keyword hook *
             */
            $keyword = $obj->hook->loader('valid_keyword', $keyword);
            /**
             * valid_des hook *
             */
            $des = $obj->hook->loader('valid_des', $des);

            if (empty($data['errors'])) {

                $blog['uid'] = $user['id'];
                $blog['parent'] = $ActID2;
                $blog['name'] = h($name);
                $blog['title'] = h($title);
                $blog['keyword'] = h($keyword);
                $blog['des'] = h($des);
                $blog['type'] = 'folder';

                if ($ActID != 0) {

                    $get_weight = $model->get_blog_data($ActID, '`weight`');

                } else {

                    $get_weight = 0;
                }
                $blog['weight'] = $get_weight['weight'] + 1;

                /**
                 * insert blog
                 */
                $ok = $model->insert_blog($blog);

                if (!$ok) {

                    $data['errors'][] = 'Lỗi dữ liệu! Vui lòng thử lại';

                } else {

                    $data['success'] = 'Thành công!';
                }
            }
        }

        if (!isset($data['success'])) {

            /**
             * get token
             */
            $token = $security->get_token('token_add_stote');

            $Display = '<div class="div_manager border_blue"><form method="POST">';
            $Display .= '<label for="name">Tên</label><br/><input type="text" id="name" name="name" value=""/><br />';
            $Display .= '<div class="tip">Để trống tiêu đề nếu bạn muốn lấy tiêu đề trùng tên</div>';
            $Display .= '<label for="title">Tiêu đề</label><br/><input type="text" id="title" name="title" value=""/><br/>';
            $Display .= '<label for="keyword">Keyword</label><br/><textarea name="keyword"></textarea><br/>';
            $Display .= '<label for="des">Description (Mô tả)</label><br/><textarea id="des" name="des"></textarea><br/>';
            $Display .= '<input type="hidden" name="token_add_stote" value="' . $token . '" />';
            $Display .= '<input type="submit" name="SubAdd" value="Tạo mới" class="button BgRed"/>';
            $Display .= '<a href="' . _HOME . '/blog/manager/cpanel/' . $sid . '" class="button BgBlue">Hủy</a>';
            $Display .= '</form></div>';

        } else {

            $Display = '';
        }

        $DisplayContent[$ActID] = $Display;

        break;

    case 'edit':

        /**
         * check blog exist
         */
        if (!$model->blog_exists($ActID)) {

            redirect(_HOME . '/blog/manager/cpanel/' . $sid);
        }

        if (isset($_POST['SubEdit'])) {

            if ($security->check_token('token_edit_stote') == FALSE) {
                break;
            }

            $name = $_POST['name'];
            $title = $_POST['title'];
            $url = $seo->url($name);
            $keyword = $_POST['keyword'];
            $des = $_POST['des'];

            /**
             * valid_name hook *
             */
            $name = $obj->hook->loader('valid_name', $name);

            if ($name == false) {

                $data['errors'][] = 'Tiêu đề bài viết quá ngắn';

            } else {

                if ($model->blog_name_exists($name, $ActID)) {

                    $data['errors'][] = 'Đã tồn tài bài viết cùng tên!';
                }
            }
            /**
             * valid_title hook *
             */
            $title = $obj->hook->loader('valid_title', $title);
            /**
             * valid_keyword hook *
             */
            $keyword = $obj->hook->loader('valid_keyword', $keyword);
            /**
             * valid_des hook
             */
            $des = $obj->hook->loader('valid_des', $des);

            if (empty($data['errors'])) {

                if (empty($title)) {
                    $title = $name;
                }
                $blog['name'] = h($name);
                $blog['url'] = h($url);
                $blog['title'] = h($title);
                $blog['keyword'] = h($keyword);
                $blog['des'] = h($des);

                /**
                 * update blog
                 */
                $ok = $model->update_blog($blog, $ActID);

                if (!$ok) {

                    $data['errors'][] = 'Lỗi dữ liệu! Vui lòng thử lại';

                } else {

                    $data['success'] = 'Thành công!';
                }
            }
        }
        if (!isset($data['success'])) {

            $blog_data = $model->get_blog_data($ActID);

            $token = $security->get_token('token_edit_stote');

            $Display = '<div class="div_manager border_blue"><form method="POST">';
            $Display .= '<label for="name">Tên</label><br/><input type="text" id="name" name="name" value="' . $blog_data['name'] . '"/><br/>';
            $Display .= '<div class="tip">Để trống tiêu đề nếu bạn muốn lấy tiêu đề trùng tên</div>';
            $Display .= '<label for="title">Tiêu đề</label><br/><input type="text" id="title" name="title" value="' . $blog_data['title'] . '"/><br/>';
            $Display .= '<label for="keyword">Keyword</label><br/><textarea name="keyword">' . $blog_data['keyword'] . '</textarea><br/>';
            $Display .= '<label for="des">Description (Mô tả)</label><br/><textarea id="des" name="des">' . $blog_data['des'] . '</textarea><br/>';
            $Display .= '<input type="hidden" name="token_edit_stote" value="' . $token . '" />';
            $Display .= '<input type="submit" name="SubEdit" value="Lưu thay đổi" class="button BgRed"/>';
            $Display .= '<a href="' . _HOME . '/blog/manager/cpanel/' . $sid . '" class="button BgBlue">Hủy</a>';
            $Display .= '</form></div>';

        } else {
            $Display = '';
        }

        $DisplayContent[$ActID] = $Display;
        break;

    case 'MoveUp':

        if (!empty($ActID1)) {

            $sdata1 = $model->get_blog_data($ActID1, 'weight, parent, type');

            $w1 = $sdata1['weight'];

            $getlist = $model->get_list_blog($sid, $sdata1['type'], array('weight' => 'ASC', 'time' => 'DESC'));

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

        redirect(_HOME . '/blog/manager/cpanel/' . $sid);

        break;

    case 'MoveDown':

        if (!empty($ActID1)) {

            $sdata1 = $model->get_blog_data($ActID1, 'weight, parent, type');

            $w1 = $sdata1['weight'];

            $getlist = $model->get_list_blog($sid, $sdata1['type'], array('weight' => 'ASC', 'time' => 'DESC'));

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

        redirect(_HOME . '/blog/manager/cpanel/' . $sid);

        break;

    case 'MoveTop':

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

       redirect(_HOME . '/blog/manager/cpanel/' . $sid);
        break;

    case 'MoveBottom':

        if ($ActID) {

            $top = $model->gets(array('weight', 'id'), '', array('weight' => 'DESC', 'time' => 'DESC'), 1);

            if ($top[0]['id'] != $ActID) {

                $TID = $top[0]['weight'];
                $TID = $TID + 1;
                $model->update_blog(array('weight' => $TID), $ActID);
            }
        }
        redirect(_HOME . '/blog/manager/cpanel/' . $sid);
        break;

    case 'delete':

        if (isset($_POST['sub_delete'])) {

            if (!$security->check_token('token_delete_item')) {
                redirect(_HOME . '/blog/manager/cpanel/' . $sid);
            }

            if (!$model->remove_to_recycle_bin($ActID)) {
                $data['errors'][] = 'Không thể chuyển hết vào thùng rác!';
            } else {
                redirect(_HOME . '/blog/manager/cpanel/' . $sid);
            }
        }
        $token = $security->get_token('token_delete_item');

        $DisplayContent[$ActID] = '<div class="div_manager border_red">
            Bạn có chắc chắn chuyển mục này vào thùng rác?<br/>
            <form method="post">
            <input type="hidden" name="token_delete_item" value="' . $token . '"/>
            <input type="submit" name="sub_delete" value="Chuyển vào thùng rác" class="button BgRed"/> 
            <a href="' . _HOME . '/blog/manager/cpanel/' . $sid . '" class="button BgBlue">Hủy</a>
            </form>
            </div>';
        break;
}

$limit = 10;
/**
 * num_folders_in_cpanel hook *
 */
$limit = $obj->hook->loader('num_folders_in_cpanel', $limit);

$pagination->setLimit($limit);
$pagination->SetGetPage('pageFolder');
$start = $pagination->getStart();
$sql_limit = $start . ',' . $limit;

$cats = $model->get_list_blog($sid, 'folder', array('weight' => 'ASC', 'time' => 'DESC'), $sql_limit);

$total = $model->total_result;
$pagination->setTotal($total);
$data['folders_pagination'] = $pagination->navi_page('?pageFolder={pageFolder}');

foreach ($cats as $kid => $cat) {

    $cats[$kid]['navi']['add'] = url(_HOME . '/blog/manager/cpanel/' . $sid . '/add/' . $kid . '/' . $cats[$kid]['parent'], $ico_add, 'title="Thêm mục"');
    $cats[$kid]['navi']['edit'] = url(_HOME . '/blog/manager/cpanel/' . $sid . '/edit/' . $kid, $ico_edit, 'title="Chỉnh sửa"');
    $cats[$kid]['navi']['MoveUp'] = url(_HOME . '/blog/manager/cpanel/' . $sid . '/MoveUp/' . $kid, $ico_up, 'title="Chuyển lên"');
    $cats[$kid]['navi']['MoveDown'] = url(_HOME . '/blog/manager/cpanel/' . $sid . '/MoveDown/' . $kid, $ico_down, 'title="Chuyển xuống"');
    $cats[$kid]['navi']['MoveTop'] = url(_HOME . '/blog/manager/cpanel/' . $sid . '/MoveTop/' . $kid, $ico_top, 'title="Chuyển lên trên cùng"');
    $cats[$kid]['navi']['MoveBottom'] = url(_HOME . '/blog/manager/cpanel/' . $sid . '/MoveBottom/' . $kid, $ico_bottom, 'title="Chuyển xuống dưới cùng"');
    $cats[$kid]['navi']['delete'] = url(_HOME . '/blog/manager/cpanel/' . $sid . '/delete/' . $kid, $ico_delete, 'title="Chuyển vào thùng rác"');
}


$limit = 10;
/**
 * num_posts_in_cpanelhook *
 */
$limit = $obj->hook->loader('num_posts_in_cpanel', $limit);

$pagination->setLimit($limit);
$pagination->SetGetPage('page');
$start = $pagination->getStart();
$sql_limit = $start . ',' . $limit;

$posts = $model->get_list_blog($sid, 'post', array('weight' => 'ASC', 'time' => 'DESC'), $sql_limit);

$total = $model->total_result;
$pagination->setTotal($total);
$data['posts_pagination'] = $pagination->navi_page();

/* @var $kid type */
foreach ($posts as $kid => $post) {
    $posts[$kid]['navi']['edit'] = url(_HOME . '/blog/manager/editpost/' . $kid . '/step3', $ico_edit, 'title="Chỉnh sửa" target="_blank"');
    $posts[$kid]['navi']['MoveUp'] = url(_HOME . '/blog/manager/cpanel/' . $sid . '/MoveUp/' . $kid, $ico_up, 'title="Chuyển lên"');
    $posts[$kid]['navi']['MoveDown'] = url(_HOME . '/blog/manager/cpanel/' . $sid . '/MoveDown/' . $kid, $ico_down, 'title="Chuyển xuống"');
    $posts[$kid]['navi']['MoveTop'] = url(_HOME . '/blog/manager/cpanel/' . $sid . '/MoveTop/' . $kid, $ico_top, 'title="Chuyển lên trên cùng"');
    $posts[$kid]['navi']['MoveBottom'] = url(_HOME . '/blog/manager/cpanel/' . $sid . '/MoveBottom/' . $kid, $ico_bottom, 'title="Chuyển xuống dưới cùng"');
    $posts[$kid]['navi']['delete'] = url(_HOME . '/blog/manager/cpanel/' . $sid . '/delete/' . $kid, $ico_delete, 'title="Chuyển vào thùng rác"');
}

$data['cats'] = $cats;
$data['posts'] = $posts;

$data['sid'] = $sid;
$data['DisplayContent'] = $DisplayContent;

$names = $model->get_blog_data($sid, array('name', 'url', 'title'));

if (isset($names['name'])) {

    $data['name_blog'] = $names['name'];
    $data['tip'] = 'Bạn đang trong thư mục <b><a href="' . $names['full_url'] . '" title="' . $names['title'] . '" target="_blank">' . $data['name_blog'] . '</a></b><br/>Tất cả những sắp xếp ở đây chỉ là sắp xếp theo CSDL';
} else {

    $data['name_blog'] = 'Trang chủ';

    $data['tip'] = 'Bạn đang trong thư mục <b>' . $data['name_blog'] . '</b><br/>Tất cả những sắp xếp ở đây chỉ là sắp xếp theo CSDL';
}

$blog = $model->get_blog_data($sid, 'parent');

if (!isset($blog['parent'])) {

    $blog['parent'] = 0;
}

$data['manager_navi'][] = '<a href="' . _HOME . '/blog/manager/cpanel/' . $blog['parent'] . '" title="Trở lại mục trước" class="button BgRed">Trở lại</a>';

if ($sid != 0) {

    $data['manager_navi'][] = '<a href="' . _HOME . '/blog/manager/newpost/' . $sid . '" title="Viết bài mới" class="button BgBlue">Viết bài</a>';
}
$obj->view->data = $data;
$obj->view->show('blog/manager/' . $app[0]);
