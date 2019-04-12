<?php
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

class blogController extends ZenController
{

    private $request_data;
    private $blog = array();
    private $libPerm;
    private $blogModel;
    private $blogHook;
    private $id;

    public function add_admin_menu() {
        ZenView::add_menu('stick-actions', array(
            'full_url' => HOME . '/admin/general/modulescp?appFollow=blog/manager/editor&act=new',
            'name' => 'Viết bài',
            'icon' => 'icon-pencil'
        ));
        ZenView::add_menu('stick-actions', array(
            'full_url' => HOME . '/admin/general/modulescp?appFollow=blog/manager',
            'name' => 'Quản lí blog',
            'icon' => 'icon-folder-open-alt'
        ));
    }
    public function add_main_menu() {
        $model = $this->model->get('blog');
        $model->set_filter('status', array(0));
        $list_main_folder = $model->get_list_blog(0, array(
            'get' => 'id, url, name, title, icon',
            'type' => 'folder',
            'order' => array('weight' => 'ASC', 'time' => 'DESC'),
            'both_child' => false
        ));
        ZenView::set_menu(array(
            'pos' => 'main',
            'menu' => $list_main_folder
        ));
    }

    public function ckeditor_action_upload_image_api() {
        /**
         * load library
         */
        $security = load_library('security');
        /**
         * get blog model
         */
        $model = $this->model->get('blog');
        $model->set_filter('status', array(0,1,2));
        /**
         * get user data
         */
        $user = $this->user;
        if (isset($_GET['blogID'])) {
            $blogID = (int) $security->removeSQLI($_GET['blogID']);
        }
        if (empty($blogID) || !$model->blog_exists($blogID)) {
            return;
        }
        /**
         * Get blog data
         */
        $blogData = $model->get_blog_data($blogID, 'name, url');

        run_hook('api', 'ckeditor_init_upload', function($upload) use ($blogData){
            $upload->file_new_name_body = !empty($blogData['url']) ? $blogData['url'] : (!empty($blogData['name'])?$blogData['name']:null);
            return $upload;
        });

        run_hook('api', 'ckeditor_data_after_upload', function($data) use ($blogID, $model, $user) {
            $insertImage['uid'] = $user['id'];
            $insertImage['sid'] = $blogID;
            $insertImage['url'] = $data['url'];
            $insertImage['type'] = $data['file_name_ext'];
            $insertImage['time'] = time();
            $model->insert_image($insertImage);
            return $data;
        });
    }

    private function load_not_found() {
        ZenView::set_title('Bài đăng không tồn tại!');
        ZenView::set_error('Bài đăng này không tôn tại hoặc đã bị xóa bời người quản lí!');
        $this->view->show('blog/error');
    }

    private function load_home() {
        $model = $this->blogModel;
        $hook = $this->hook->get('blog');
        $cats = $model->get_list_blog(0, array('get' => 'url, name, title, time, view, icon'));
        /**
         * num_display_sub_cat_in_index hook*
         */
        $limit_sub_cat = $hook->loader('num_display_sub_cat_in_index', 0);
        /**
         * order_display_sub_cat_in_index hook*
         */
        $order_sub_cat = $hook->loader('order_display_sub_cat_in_index', array('weight' => 'ASC', 'time' => 'DESC'));
        /**
         * order_display_sub_cat_in_index hook*
         */
        $filter_sub_cat = $hook->loader('filter_display_sub_cat_in_index', NULL);
        foreach ($cats as $kid => $cat) {
            $cats[$kid]['sub_cat'] = $model->get_list_blog($kid, array(
                    'type' => $filter_sub_cat,
                    'order' => $order_sub_cat,
                    'limit' => $limit_sub_cat,
                    'both_child' => false
                )
            );
        }

        /**
         * num_post_top_new tempConfig*
         */
        $num_new  = tplConfig('num_post_top_new');
        $data['list']['new'] = $model->get_list_blog(null, array(
                'get' => $hook->loader('index_top_new_get', 'id, parent, uid, url, name, des, title, time, view, icon'),//index_top_new_get hook*
                'type' => 'post',
                'order' => $hook->loader('index_top_new_order', array('time' => 'DESC')),//index_top_new_order hook*
                'limit' => $hook->loader('index_top_new_limit', $num_new ? $num_new : 10),//index_top_new_limit hook*
                'both_child' => false
            )
        );

        /**
         * num_post_top_hot tempConfig*
         */
        $num_hot = tplConfig('num_post_top_hot');
        $data['list']['hot'] = $model->get_list_blog(null, array(
                'get' => $hook->loader('index_top_hot_get', 'id, parent, uid, url, name, title, des, time, view, icon'),//index_top_hot_get hook*
                'type' => 'post',
                'order' => $hook->loader('index_top_hot_order', array('view' => 'DESC')),//index_top_hot_order hook*
                'limit' => $hook->loader('index_top_hot_limit', $num_hot ? $num_hot : 10),//index_top_hot_limit hook*
                'both_child' => false
            )
        );

        /**
         * num_post_top_rand tempConfig*
         */
        $num_rand = tplConfig('num_post_top_rand');
        $data['list']['rand'] = $model->get_list_blog(null, array(
                'get' => $hook->loader('index_top_rand_get', 'id, parent, uid, url, name, title, des, time, view, icon'),//index_top_rand_get hook*
                'type' => 'post',
                'order' => $hook->loader('index_top_rand_order', array('RAND()' => '')),//index_top_rand_order hook*
                'limit' => $hook->loader('index_top_rand_limit', $num_rand ? $num_rand : 10),//index_top_rand_limit hook*
                'both_child' => false
            )
        );

        /**
         * lists_in_index hook *
         */
        $data['list'] = $hook->loader('lists_in_index', $data['list']);

        ZenView::set_title(dbConfig('title'));
        ZenView::set_keyword(dbConfig('keyword'));
        ZenView::set_desc(dbConfig('des'));
        $data['cats'] = $cats;
        $this->view->data = $data;
        $this->view->show('blog');
    }

    private function load_folder() {
        /**
         * load helpers
         */
        load_helper('gadget');
        /**
         * load library
         */
        $paging = load_library('pagination');

        /**
         * num_post_in_folder tempConfig*
         */
        $num_post = tplConfig('num_post_in_folder');
        /**
         * number_post_display_in_folder hook *
         */
        $limit = $this->blogHook->loader('number_post_display_in_folder', $num_post ? $num_post : 10);
        $paging->setLimit($limit);
        $paging->SetGetPage('page');
        $start = $paging->getStart();
        $sql_limit = $start.','.$limit;
        $data['list']['posts'] = $this->blogModel->get_list_blog($this->id, array(
                'get' => 'id, parent, uid, url, name, title, des, time, view, icon',
                'type' => 'post',
                'order'=> array('weight' => 'ASC', 'time' => 'DESC'),
                'limit' => $sql_limit,
                'both_child' => true
            )
        );
        $total = $this->blogModel->total_result;
        $paging->setTotal($total);
        ZenView::set_paging($paging->navi_page(), 'post');

        /**
         * num_folder_in_folder tempConfig*
         */
        $num_folder = tplConfig('num_folder_in_folder');
        /**
         * number_folder_display_in_folder hook *
         */
        $limit = $this->blogHook->loader('number_folder_display_in_folder', $num_folder ? $num_folder : 0);
        if ($limit) {
            $paging->setLimit($limit);
            $paging->SetGetPage('fpage');
            $start = $paging->getStart();
            $sql_limit = $start.','.$limit;
        }
        $data['list']['folders'] = $this->blogModel->get_list_blog($this->id, array(
                'get' => 'id, parent, uid, url, name, title, des, time, view, icon',
                'type' => 'folder',
                'order' => array('weight' => 'ASC', 'time' => 'DESC'),
                'limit' => $limit ? $sql_limit : 0
            )
        );
        if ($limit) {
            $total = $this->blogModel->total_result;
            $paging->setTotal($total);
            ZenView::set_paging($paging->navi_page(), 'folder');
        }

        if (!empty($data['list']['folders'])) {
            /**
             * set app menu
             */
            ZenView::set_menu(array(
                'pos' => 'app',
                'name' => 'Chuyên mục con',
                'menu' => $data['list']['folders']
            ));
        }

        /**
         * num_rand_post_in_folder tempConfig*
         */
        $num_rand_post = tplConfig('num_rand_post_in_folder');
        /**
         * number_rand_post_display_in_folder hook *
         */
        $limit_rand = $this->blogHook->loader('number_rand_post_display_in_folder', $num_rand_post);
        if ($limit_rand) {
            $data['list']['rand_posts'] = $this->blogModel->get_list_blog(null, array(
                    'get' => 'id, parent, uid, url, name, title, time, view, icon',
                    'type' => 'post',
                    'order' => array('RAND()' => ''),
                    'limit' => $limit_rand
                )
            );
        } else $data['list']['rand_posts'] = array();

        /**
         * list_in_folder hook*
         */
        $data['list'] = $this->blogHook->loader('list_in_folder', $data['list'], array('var' => array('blog' => $this->blog)));

        /**
         * load manager bar
         */
        if ($this->libPerm->is_manager()) {
            $manager_menu = array(
                array(
                    'name' => 'Đến trang quản lí',
                    'icon' => 'glyphicon glyphicon-cog',
                    'full_url' => HOME . '/admin/general/modulescp?appFollow=blog/manager/cpanel/' . $this->id
                )
            );
            ZenView::set_menu(
                array(
                    'pos' => 'manager',
                    'name' => 'Quản lí',
                    'menu' => $manager_menu
                )
            );
        }

        $data['blog'] = $this->blog;
        $page_title = $this->blog['title'] ? $this->blog['title'] : $this->blog['name'];
        $page_des = $this->blog['des'];
        if (!empty($_GET['page'])) {
            $get_page = (int) $_GET['page'];
            $page_title = 'Trang ' . $get_page . ' - ' . $page_title;
            $page_des = 'Trang ' . $get_page . ', ' . $page_des;
        }
        /**
         * set page meta
         */
        ZenView::set_title($page_title);
        ZenView::set_keyword($this->blog['keyword']);
        ZenView::set_desc($page_des);
        ZenView::set_url($this->blog['full_url']);
        ZenView::set_image($this->blog['full_icon']);
        ZenView::set_breadcrumb($this->blog_path());
        $this->view->data = $data;
        $this->view->show('blog/folder');
    }

    private function load_post() {
        /**
         * get user data
         */
        $accModel = $this->model->get('account');
        $this->blog['user'] = $accModel->get_user_data($this->blog['uid'], 'username, nickname, perm');

        /**
         * Load gadget helper
         */
        load_helper('gadget');

        /**
         * number_other_folder_display_in_post hook *
         */
        $limit_other = $this->blogHook->loader('number_other_folder_display_in_post', 5);
        $data['list']['other_folders'] = $this->blogModel->get_list_blog(null, array(
                'get' => 'url, name, title, time, view, icon',
                'type' => 'folder',
                'order' => array('time' => 'DESC'),
                'limit' => $limit_other,
                'both_child' => false
            )
        );

        /**
         * num_same_post_in_post tempConfig*
         */
        $num_same_post = tplConfig('num_same_post_in_post');
        /**
         * number_same_post_display_in_post hook *
         */
        $limit_same = $this->blogHook->loader('number_same_post_display_in_post', $num_same_post);
        if ($limit_same) {
            $data['list']['same_posts'] = $this->blogModel->get_list_blog($data['parent'], array(
                    'get' => 'url, name, title, time, view, icon',
                    'type' => 'post',
                    'order' => array('time' => 'DESC'),
                    'limit' => $limit_same,
                    'both_child' => true
                )
            );
        } else $data['list']['same_posts'] = array();

        /**
         * num_rand_post_in_post tempConfig*
         */
        $num_rand_post = tplConfig('num_rand_post_in_post');
        /**
         * number_rand_post_display_in_post hook *
         */
        $limit_rand = $this->blogHook->loader('number_rand_post_display_in_post', $num_rand_post);
        if ($limit_rand) {
            $data['list']['rand_posts'] = $this->blogModel->get_list_blog(null, array(
                    'get' => 'id, parent, uid, url, name, title, time, view, icon',
                    'type' => 'post',
                    'order' => array('RAND()' => ''),
                    'limit' => $limit_rand
                )
            );
        } else $data['list']['rand_posts'] = array();

        /**
         * lists_in_post hook *
         */
        $data['list'] = $this->blogHook->loader('list_in_post', $data['list'], array('var' => array('blog' => $this->blog)));

        /**
         * load manager bar *
         */
        if ($this->libPerm->is_manager()) {
            $manager_menu = array(
                array(
                    'name' => 'Chỉnh sửa bài viết',
                    'icon' => 'glyphicon glyphicon-cog',
                    'full_url' => HOME . '/admin/general/modulescp?appFollow=blog/manager/editor&id=' . $this->id
                )
            );
            ZenView::set_menu(
                array(
                    'pos' => 'manager',
                    'name' => 'Quản lí',
                    'menu' => $manager_menu
                )
            );
        }

        /**
         * get tag blog
         */
        $this->get_tags();
        /**
         * get attach of blog: link, file
         */
        $this->attachments();

        /**
         * set page meta
         */
        ZenView::set_title($this->blog['title'] ? $this->blog['title'] : $this->blog['name']);
        ZenView::set_keyword($this->blog['keyword']);
        ZenView::set_desc($this->blog['des']);
        ZenView::set_url($this->blog['full_url']);
        ZenView::set_image($this->blog['full_icon']);
        ZenView::set_breadcrumb($this->blog_path());
        $data['blog'] = $this->blog;
        $this->view->data = $data;

        if (modConfig('allow_post_comment', 'blog')) {
            /**
             * init ckeditor
             */
            gadget_ckeditor('bbcode_mini');
            /**
             * Load comment
             */
            $this->comment();
        }
        $this->view->show('blog/post');
    }

    private function receive_blog() {
        /**
         * load library
         */
        $security = load_library('security');
        /**
         * remove SQLi, receive blog id
         */
        if (isset($this->request_data[0])) {
            $this->id = (int)$security->removeSQLI($this->request_data[0]);
        } elseif (isset($_GET['id'])) {
            $this->id = (int)$security->removeSQLI($_GET['id']);
        } else $this->id = 0;
    }

    private function format_content() {
        $bbCode = load_library('bbcode');
        /**
         * load content
         */
        if (isset($this->blog['type_data']) && isset($this->blog['content'])) {
            /**
             * out_content hook *
             */
            $this->blog['content'] = $this->blogHook->loader('out_content', $this->blog['content']);
            if ($this->blog['type_data'] == 'bbcode') {

                $this->blog['content'] = $bbCode->parse($this->blog['content']);
                /**
                 * out_bbcode_content hook *
                 */
                $this->blog['content'] = $this->blogHook->loader('out_bbcode_content', $this->blog['content']);
            } else {
                /**
                 * out_html_content hook *
                 */
                $this->blog['content'] = $this->blogHook->loader('out_html_content', $this->blog['content']);
            }
            $this->blog['content'] = parse_smile($this->blog['content']);
        }
    }

    /**
     * Get blog tag
     * @return mixed
     */
    private function get_tags() {
        $this->blog['tags'] = $this->blogModel->get_tags_blog($this->id);
    }

    private function comment() {
        /**
         * load library
         */
        $security = load_library('security');
        $paging = load_library('pagination');
        load_helper('gadget');
        /**
         * config_ckeditor_comment hook*
         */
        $ck_set = $this->blogHook->loader('config_ckeditor_comment', array('type' => 'mini-bbcode'));
        /**
         * load gadget for message form
         */
        gadget_ckeditor('comment-msg', $ck_set);

        /**
         * start post comments
         */
        if (isset($_POST['submit-comment'])) {
            /**
             * check request token
             */
            if ($security->check_token('token_comment')) {
                /**
                 * check captcha code
                 */
                if (!$security->check_token('captcha_code') and empty($this->user['id'])) {
                    ZenView::set_error('Mã xác nhận không chính xác', 'comment');
                } else {
                    $continuous = true;
                    if (isset($this->user['id'])) {
                        $ins_cmt['uid'] = $this->user['id'];
                    } else $ins_cmt['uid'] = 0;
                    if (!$ins_cmt['uid']) {
                        if (empty($_POST['name'])) {
                            ZenView::set_error('Bạn chưa nhập tên mình', 'comment');
                            $continuous = false;
                        } else {
                            /**
                             * valid_data_comment_name hook*
                             */
                            $cmt_name = $this->blogHook->loader('valid_data_comment_name', $_POST['name']);
                            if (ZenView::is_success('comment')) {
                                $ins_cmt['name'] = h($cmt_name);
                            } else $continuous = false;
                        }
                    } else $ins_cmt['name'] = $this->user['username'];

                    if (empty($_POST['msg'])) {
                        ZenView::set_notice('Nội dung comment không được để trống', 'comment');
                        $continuous = false;
                    } else {
                        /**
                         * valid_data_comment_msg hook*
                         */
                        $cmt_msg = $this->blogHook->loader('valid_data_comment_msg', $_POST['msg']);
                        if (ZenView::is_success('comment')) {
                            if (!empty($cmt_msg)) $ins_cmt['msg'] = h($cmt_msg);
                            else $continuous = false;
                        } else $continuous = false;
                    }

                    if ($continuous == true) {
                        $ins_cmt['sid'] = $this->id;
                        $ins_cmt['ip'] = client_ip();
                        if (!$this->blogModel->insert_comment($ins_cmt)) {
                            ZenView::set_error('Không thể đăng bình luận. Vui lòng thử lại', 'comment');
                        }
                    }
                }
            }
        }

        /**
         * This action only for manager
         */
        if (is(ROLE_MANAGER)) {
            $blogData = $this->blog;
            /**
             * add controls comment
             */
            run_hook('blog', 'post_comment_private_control', function($data, $stream) use ($blogData) {
                return $data . '<a href="' . $blogData['full_url'] . '?deleteCmt=' . $stream['cmt']['id'] . (isset($_GET['_review_']) ? '&_review_':'') . '" ' .cfm('Bạn có chắc chắn muốn xóa comment này?'). '>Xóa</a>';
            });
            if (!empty($_GET['deleteCmt'])) {
                $cmtID = (int) $security->removeSQLI($_GET['deleteCmt']);
                $cmtData = $this->blogModel->get_comment_data($cmtID, 'uid');
                if ($this->libPerm->is_lower_levels_of($cmtData['uid'])) {
                    ZenView::set_error('Bạn không thể xóa comment của cấp trên', 'comment');
                } else {
                    if ($this->blogModel->delete_comment($cmtID)) {
                        ZenView::set_success(1, 'comment', $this->blog['full_url'] . (isset($_GET['_review_']) ? '?_review_' : ''));
                    } else ZenView::set_error('Đã xảy ra lỗi. Vui lòng thử lại', 'comment');
                }
            }
        }

        /**
         * number_comment_display hook *
         */
        $limit = $this->blogHook->loader('number_comment_display', 5);
        $paging->setLimit($limit);
        $paging->SetGetPage('cmtPage');
        $start = $paging->getStart();
        $sql_limit = $start.','.$limit;
        /**
         * get list comment
         */
        $this->view->data['blog']['comments'] = $this->blogModel->get_comments($this->id, $sql_limit);
        if (empty($this->view->data['blog']['comments'])) {
            ZenView::set_notice('Chưa có thảo luận nào. Hãy là người đầu tiên', 'comments-list');
        }
        $paging->setTotal($this->blogModel->total_result);
        ZenView::set_paging($paging->navi_page('?cmtPage={cmtPage}#comments'), 'comment');
        /**
         * get token comment
         */
        $this->view->data['token_comment'] = $security->get_token('token_comment');
        $captcha_security_key = $security->get_token('captcha_security_key', 4);
        /**
         * captcha security image
         */
        $this->view->data['captcha_src'] = HOME . '/captcha/image/image_' . $captcha_security_key . '.jpg';
    }

    private function like() {
        /**
         * check like action
         */
        if (isset($_GET['_t_'])) {
            $security = load_library('security');
            if ($security->check_token('_t_', 'GET')) {
                if (isset ($this->user['id']) && !empty($this->user['id'])) {
                    $lData['fromid'] = $this->user['id'];
                } else $lData['ip'] = client_ip();
                $lData['toid'] = $this->id;
                if (isset($_GET['_like_'])) {
                    $this->blogModel->do_like($lData);
                } else {
                    if (isset($_GET['_dislike_'])) {
                        $this->blogModel->do_dislike($lData);
                    }
                }
                redirect($this->blog['full_url']);
            }
        }

        /**
         * like & dislike
         */
        $this->blogHook['like'] = $this->blogModel->get_like($this->id);
        $this->blog['dislike'] = $this->blogModel->get_dislike($this->id);

        /**
         * number_like_display hook*
         */
        $this->blog['like'] = $this->blogHook->loader('number_like_display', $this->blog['like']);

        /**
         * number_dislike_display hook*
         */
        $this->blog['dislike'] = $this->blogHook->loader('number_dislike_display', $this->blog['dislikes']);

        $this->blog['is_liked'] = $this->blogModel->is_liked($this->id);
        $this->blog['is_disliked'] = $this->blogModel->is_disliked($this->id);

        /**
         * get like token
         */
        $token_like = $security->get_token('_t_');

        /**
         * set link like & dislike
         */
        $link_like = $this->blog['full_url'] . '?_like_&_t_=' . $token_like;
        $link_dislike = $this->blog['full_url'] . '?_dislike_&_t_=' . $token_like;

        /**
         * hook link like, dislike
         */
        $this->blog['link_like'] = $this->hook->loader('link_like', $link_like);
        $this->blog['link_dislike'] = $this->hook->loader('link_dislike', $link_dislike);
    }

    private function attachments() {
        $this->blog['attachments'] = array();
        /**
         * get link and file download
         */
        $link = $this->blogModel->get_links($this->id);
        $file = $this->blogModel->get_files($this->id);

        /**
         * links_download hook & files_download hook*
         */
        $link_list = $this->blogHook->loader('link_download', $link);
        $file_list = $this->blogHook->loader('file_download', $file);
        if (!empty($link_list)) {
            $this->blog['attachments']['link'] = $link_list;
        }
        if (!empty($file_list)) {
            $this->blog['attachments']['file'] = $file_list;
        }
    }

    public function index($request_data = array()) {
        $this->request_data = $request_data;
        /**
         * load library
         */
        $this->libPerm = load_library('permission');
        $this->libPerm->set_user($this->user);
        /**
         * load blog hook
         */
        $this->blogHook = $this->hook->get('blog');
        /**
         * get blog model
         */
        $this->blogModel = $this->model->get('blog');
        if (isset($_GET['_review_']) && is(ROLE_MANAGER)) {
            $this->blogModel->set_filter('status', array(0,1,2));
        } else $this->blogModel->set_filter('status', array(0));

        /**
         * receive blog via blog id
         */
        $this->receive_blog();
        /**
         * if id = 0: is home
         */
        if ($this->id == 0) {
            $this->load_home();
        } else {
            /**
             * Check blog is exists *
             */
            if ($this->blogModel->blog_exists($this->id) == false) {
                $this->load_not_found();
            } else {
                /**
                 * If is a blog
                 * Get blog data
                 */
                $this->blog = $this->blogModel->get_blog_data($this->id);
                /**
                 * update view
                 */
                $this->blogModel->update_view($this->id);

                if (!empty($this->blog['content'])) {
                    /**
                     * format content before view
                     */
                    $this->format_content();
                }

                if ($this->blog['type'] == 'folder') {
                    $this->load_folder();
                } elseif ($this->blog['type'] == 'post') {
                    $this->load_post();
                } else $this->load_not_found();
            }
        }
    }

    public function download($arr = array()) {
        /**
         * Load library
         */
        $security = load_library('security');
        $device = load_library('DDetect');
        /**
         * Get blog model
         */
        $model = $this->model->get('blog');
        $model->set_filter('status', array(0));
        $hook = $this->hook->get('blog');

        if (isset($arr[0])) {
            $id = (int) $security->removeSQLI($arr[0]);
        }
        if (empty($id)) {
            redirect (HOME);
            return;
        }

        /**
         * Check blog is exists *
         */
        if ($model->blog_exists($id) == false) {
            ZenView::set_tip('Bài đăng không tồn tại');
            ZenView::set_notice('Bài đăng này không tôn tại hoặc đã bị xóa bời người quản lí!');
            $this->view->data = array();
            $this->view->show('blog/error');
            return false;
        }

        /**
         * If is a blog
         */
        $this->blog = $model->get_blog_data($id); // load data of blog

        /**
         * get link and file download
         */
        $this->attachments();

        $data['device']['is_mobile'] = false;

        /**
         * Check device is mobile
         */
        if ($device->isMobile()) {
            $data['device']['is_mobile'] = true;
            if ($device->isAndroidOS()) {
                $data['device']['platform'] = 'Android';
            } elseif ($device->isiOS()) {
                $data['device']['platform'] = 'iOS';
            } elseif ($device->isJavaOS()) {
                $data['device']['platform'] = 'Java';
            } elseif ($device->isSymbianOS()) {
                $data['device']['platform'] = 'Symbian (Java)';
            } elseif ($device->isWindowsPhoneOS()) {
                $data['device']['platform'] = 'WindowsPhone';
            } else {
                $data['device']['platform'] = false;
            }
        }

        $keyword_platform['android'] = 'android|apk';
        $keyword_platform['ios'] = 'ios|iphone|ipad|ipa';
        $keyword_platform['java'] = 'java|jar|jad';
        $keyword_platform['windowsphone'] = 'windowsphone';

        if (!empty($this->blog['attachments']['links'])) {
            foreach ($this->blog['attachments']['links'] as $link) {
                if ($data['device']['platform']) {
                    if (preg_match('/(' . $keyword_platform[strtolower($data['device']['platform'])] . ')/is', $link['name'])) {
                        ZenView::set_success(wait_redirect($link['link'], 'Hệ thống đang tự động chọn file cho máy bạn vui lòng chờ %s giây nữa', 2));
                    }
                }
            }
        }

        foreach ($this->blog['attachments']['files'] as $file) {
            if ($data['device']['platform']) {
                if (preg_match('/(' . $keyword_platform[strtolower($data['device']['platform'])] . ')/is', $file['name'])) {
                    ZenView::set_success(wait_redirect($file['link'], 'Hệ thống đang tự động chọn file cho máy bạn vui lòng chờ %s giây nữa', 2));
                }
            }
        }

        $data['blog'] = $this->blog;
        $this->view->data = $data;
        $this->view->show('blog/download');
    }

    public function manager($app = array('index')) {
        ZenView::set_menu(array(
            'pos' => 'page_menu',
            'menu' => get_apps('blog/apps/manager', 'blog/manager')
        ));
        load_apps('blog/apps/manager', $app);
    }

    public function api($app = array('index'), $data = array()) {
        load_apps('blog/apps/api', $app, $data);
    }

    public function editor_action_api() {
        run_hook('api', 'data_after_upload', function($data) {
            $data['base_path'] = $data['base_path'] . '?v=3';
            return $data;
        });
        run_hook('api', 'data_after_upload', function($data) {
            $data['base_path'] = $data['base_path'] . '&id=' . $_GET['id'] . '&a=' . $_GET['urlUpload'];
            return $data;
        });
    }

    private function blog_path($id = null) {
        static $tree = array();
        static $i;
        $i++;
        if ($id === null) {
            $id = $this->id;
        }
        $blog = $this->blogModel->get_blog_data($id);
        if (!empty($blog)) {
            if ($i != 1) {
                $tree[] = url($blog['full_url'], $blog['name'], 'title="' . $blog['title'] . '"');
            }
            $parent = $blog['parent'];
            $this->blog_path($parent);
        } else {
            $tree = array_reverse ($tree);
        }
        return $tree;
    }
}
