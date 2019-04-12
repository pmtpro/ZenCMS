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
if (!defined('__ZEN_KEY_ACCESS'))
    exit('No direct script access allowed');

class blogController extends ZenController
{

    private $request_data;
    private $blog = array();
    private $libPerm;
    private $blogModel;
    private $blogHook;
    private $id;

    public function add_admin_menu()
    {
        ZenView::add_menu('stick-actions', array(
            'full_url' => HOME .
                '/admin/general/modulescp?appFollow=blog/manager/editor&act=new',
            'name' => 'Viết bài',
            'icon' => 'fa fa-pencil'));
        ZenView::add_menu('stick-actions', array(
            'full_url' => HOME . '/admin/general/modulescp?appFollow=blog/manager/cpanel',
            'name' => 'Quản lí blog',
            'icon' => 'fa fa-folder-open'));
    }

    public function add_main_menu()
    {
        $model = $this->model->get('blog');
        $model->set_filter('status', array(0));
        $list_main_folder = $model->get_list_blog(0, array(
            'get' => 'id, url, name, title, icon',
            'type' => 'folder',
            'order' => array('weight' => 'ASC', 'time' => 'DESC'),
            'both_child' => false));
        ZenView::set_menu(array('pos' => 'main', 'menu' => $list_main_folder));
    }

    public function ckeditor_action_upload_image_api()
    {
        /**
         * load library
         */
        $security = load_library('security');
        /**
         * get blog model
         */
        $model = $this->model->get('blog');
        $model->set_filter('status', array(0, 1, 2));
        /**
         * get user data
         */
        $user = $this->user;
        $_get_blogID = ZenInput::get('blogID');
        $blogID = $_get_blogID ? (int)$security->removeSQLI($_get_blogID) : 0;
        if (!$blogID || !$model->blog_exists($blogID)) {
            return;
        }
        /**
         * Get blog data
         */
        $blogData = $model->get_blog_data($blogID, 'name, url');

        run_hook('api', 'ckeditor_init_upload', function ($upload)use ($blogData) {
                $upload->file_new_name_body = !empty($blogData['url']) ? $blogData['url'] : (!empty($blogData['name']) ? $blogData['name'] : null);
                return $upload;
            }
        );

        run_hook('api', 'ckeditor_data_after_upload', function ($data)use ($blogID, $model, $user) {
                $insertImage['uid'] = $user['id'];
                $insertImage['sid'] = $blogID;
                $insertImage['url'] = $data['url'];
                $insertImage['type'] = $data['file_name_ext'];
                $insertImage['time'] = time();
                $model->insert_image($insertImage);
                return $data;
            }
        );
    }

    public function index($request_data = array())
    {
        load_helper('time');

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
        if (ZenInput::get('_review_') && is(ROLE_MANAGER)) {
            $this->blogModel->set_filter('status', array(0, 1, 2));
        } else {
            $this->blogModel->set_filter('status', array(0));
        }

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
                 * blog_id hook*
                 */
                $this->id = $this->blogHook->loader('blog_id', $this->id);

                if ($this->libPerm->is_manager()) {
                    ZenView::add_admin_navbar(
                        array(
                            'name' => 'Quản lí blog',
                            'icon' => 'glyphicon glyphicon-cog',
                            'full_url' => HOME . '/admin/general/modulescp?appFollow=blog/manager'
                        )
                    );
                }

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

                $blog_title = $this->blog['title'] ? $this->blog['title'] : $this->blog['name'];
                /**
                 * blog_title hook*
                 */
                $blog_title = $this->blogHook->loader('blog_title', $blog_title, array('var' => array('blog' => $this->blog)));
                /**
                 * blog_keyword hook*
                 */
                $blog_keyword = $this->blogHook->loader('blog_keyword', $this->blog['keyword'], array('var' => array('blog' => $this->blog)));
                /**
                 * blog_desc hook*
                 */
                $blog_desc = $this->blogHook->loader('blog_desc', $this->blog['des'], array('var' => array('blog' => $this->blog)));

                /**
                 * blog_full_url hook*
                 */
                $blog_full_url = $this->blogHook->loader('blog_full_url', $this->blog['full_url'], array('var' => array('blog' => $this->blog)));

                /**
                 * blog_image hook*
                 */
                $blog_image = $this->blogHook->loader('blog_image', $this->blog['full_icon'], array('var' => array('blog' => $this->blog)));

                /**
                 * set page meta
                 */
                ZenView::set_title($blog_title);
                ZenView::set_keyword($blog_keyword);
                ZenView::set_desc($blog_desc);
                ZenView::set_url($blog_full_url);
                ZenView::set_image($blog_image);
                ZenView::set_breadcrumb($this->blog_path());

                if ($this->blog['type'] == 'folder') {
                    $this->load_folder();
                } elseif ($this->blog['type'] == 'post') {
                    $this->load_post();
                } else {
                    $this->load_not_found();
                }
            }
        }
    }

    private function load_not_found()
    {
        /**
         * blog_title hook*
         */
        $blog_title = $this->blogHook->loader('blog_title', 'Bài đăng không tồn tại!');
        /**
         * blog_keyword hook*
         */
        $blog_keyword = $this->blogHook->loader('blog_keyword', '');
        /**
         * blog_desc hook*
         */
        $blog_desc = $this->blogHook->loader('blog_desc', '');
        /**
         * blog_image hook*
         */
        $blog_image = $this->blogHook->loader('blog_image', '');

        ZenView::set_title($blog_title);
        ZenView::set_keyword($blog_keyword);
        ZenView::set_desc($blog_desc);
        ZenView::set_image($blog_image);
        ZenView::set_error('Bài đăng này không tôn tại hoặc đã bị xóa bời người quản lí!');
        $this->view->show('blog/error');
    }

    private function load_home()
    {
        /**
         * blog_title hook*
         */
        $blog_title = $this->blogHook->loader('blog_title', dbConfig('title'));
        /**
         * blog_keyword hook*
         */
        $blog_keyword = $this->blogHook->loader('blog_keyword', dbConfig('keyword'));
        /**
         * blog_desc hook*
         */
        $blog_desc = $this->blogHook->loader('blog_desc', dbConfig('des'));
        /**
         * blog_image hook*
         */
        $blog_image = $this->blogHook->loader('blog_image', dbConfig('image'));
        ZenView::set_title($blog_title);
        ZenView::set_keyword($blog_keyword);
        ZenView::set_desc($blog_desc);
        ZenView::set_image($blog_image);
        $this->view->show('blog');
    }

    private function load_folder()
    {
        /**
         * load helpers
         */
        load_helper('gadget');

        /**
         * load manager bar
         */
        if ($this->libPerm->is_manager()) {
            ZenView::add_admin_navbar(
                array(
                    'name' => 'Quản lí bài',
                    'icon' => 'glyphicon glyphicon-cog',
                    'full_url' => HOME . '/admin/general/modulescp?appFollow=blog/manager/cpanel/' . $this->id
                )
            );
            ZenView::add_admin_navbar(
                array(
                    'name' => 'Chỉnh sửa',
                    'icon' => 'glyphicon glyphicon-pencil',
                    'full_url' => HOME . '/admin/general/modulescp?appFollow=blog/manager/editor&id=' . $this->id
                )
            );
            ZenView::add_admin_navbar(
                array(
                    'name' => 'Viết bài',
                    'icon' => 'glyphicon glyphicon-plus',
                    'full_url' => HOME . '/admin/general/modulescp?appFollow=blog/manager/editor&id=' . $this->id . '&act=new'
                )
            );
        }

        $data['blog'] = $this->blog;
        $this->view->data = $data;
        $this->view->show('blog/folder');
    }

    private function load_post()
    {
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
         * load manager bar *
         */
        if ($this->libPerm->is_manager()) {
            ZenView::add_admin_navbar(
                array(
                    'name' => 'Chỉnh sửa bài viết',
                    'icon' => 'glyphicon glyphicon-pencil',
                    'full_url' => HOME . '/admin/general/modulescp?appFollow=blog/manager/editor&id=' . $this->id
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

        $this->view->data['blog'] = $this->blog;

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

    private function receive_blog()
    {
        /**
         * load library
         */
        $security = load_library('security');
        /**
         * remove SQLi, receive blog id
         */
        $_get_id = ZenInput::get('id');
        if (isset($this->request_data[0])) {
            $this->id = (int)$security->removeSQLI($this->request_data[0]);
        } elseif ($_get_id) {
            $this->id = (int)$security->removeSQLI($_get_id);
        } else
            $this->id = 0;
        /**
         * blog_id_before_valid hook*
         */
        $this->id = $this->blogHook->loader('blog_id_before_valid', $this->id);
    }

    private function format_content()
    {
        /**
         * load content
         */
        if (isset($this->blog['type_data']) && isset($this->blog['content'])) {
            /**
             * out_content hook *
             */
            $this->blog['content'] = $this->blogHook->loader('out_content', $this->blog['content']);
            if ($this->blog['type_data'] == 'bbcode') {
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
    private function get_tags()
    {
        $this->blog['tags'] = $this->blogModel->get_tags_blog($this->id);
    }

    private function comment()
    {
        /**
         * load library
         */
        $security = load_library('security');
        $paging = load_library('pagination');
        load_helper('gadget');
        /**
         * config_ckeditor_comment hook*
         */
        $ck_set = $this->blogHook->loader('config_ckeditor_comment', array('type' =>
                'mini-bbcode'));
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
                    } else
                        $ins_cmt['uid'] = 0;
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
                            } else
                                $continuous = false;
                        }
                    } else
                        $ins_cmt['name'] = $this->user['username'];

                    if (empty($_POST['msg'])) {
                        ZenView::set_notice('Nội dung comment không được để trống', 'comment');
                        $continuous = false;
                    } else {
                        /**
                         * valid_data_comment_msg hook*
                         */
                        $cmt_msg = $this->blogHook->loader('valid_data_comment_msg', $_POST['msg']);
                        if (ZenView::is_success('comment')) {
                            if (!empty($cmt_msg))
                                $ins_cmt['msg'] = h($cmt_msg);
                            else
                                $continuous = false;
                        } else
                            $continuous = false;
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
            run_hook('blog', 'post_comment_private_control', function ($data, $stream)use ($blogData)
            {
                return $data . '<a href="' . $blogData['full_url'] . '?deleteCmt=' . $stream['cmt']['id'] . (ZenInput::get('_review_') ? '&_review_' : '') . '" ' . cfm('Bạn có chắc chắn muốn xóa comment này?') . '>Xóa</a>'; }
            );
            $_get_deleteCmt = ZenInput::get('deleteCmt');
            if ($_get_deleteCmt) {
                $cmtID = (int)$security->removeSQLI($_get_deleteCmt);
                $cmtData = $this->blogModel->get_comment_data($cmtID, 'uid');
                if ($this->libPerm->is_lower_levels_of($cmtData['uid'])) {
                    ZenView::set_error('Bạn không thể xóa comment của cấp trên', 'comment');
                } else {
                    if ($this->blogModel->delete_comment($cmtID)) {
                        ZenView::set_success(1, 'comment', $this->blog['full_url'] . (ZenInput::get('_review_') ? '?_review_' : ''));
                    } else
                        ZenView::set_error('Đã xảy ra lỗi. Vui lòng thử lại', 'comment');
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
        $sql_limit = $start . ',' . $limit;
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

    private function like()
    {
        /**
         * check like action
         */
        if (ZenInput::get('_t_')) {
            $security = load_library('security');
            if ($security->check_token('_t_', 'GET')) {
                if (isset($this->user['id']) && !empty($this->user['id'])) {
                    $lData['fromid'] = $this->user['id'];
                } else
                    $lData['ip'] = client_ip();
                $lData['toid'] = $this->id;
                if (ZenInput::get('_like_')) {
                    $this->blogModel->do_like($lData);
                } else {
                    if (ZenInput::get('_dislike_')) {
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
        $this->blog['like'] = $this->blogHook->loader('number_like_display', $this->
            blog['like']);

        /**
         * number_dislike_display hook*
         */
        $this->blog['dislike'] = $this->blogHook->loader('number_dislike_display', $this->
            blog['dislikes']);

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

    private function attachments()
    {
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

    public function download($arr = array())
    {
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
            $id = (int)$security->removeSQLI($arr[0]);
        }
        if (empty($id)) {
            redirect(HOME);
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
                    if (preg_match('/(' . $keyword_platform[strtolower($data['device']['platform'])] .
                        ')/is', $link['name'])) {
                        ZenView::set_success(wait_redirect($link['link'],
                            'Hệ thống đang tự động chọn file cho máy bạn vui lòng chờ %s giây nữa', 2));
                    }
                }
            }
        }

        foreach ($this->blog['attachments']['files'] as $file) {
            if ($data['device']['platform']) {
                if (preg_match('/(' . $keyword_platform[strtolower($data['device']['platform'])] .
                    ')/is', $file['name'])) {
                    ZenView::set_success(wait_redirect($file['link'],
                        'Hệ thống đang tự động chọn file cho máy bạn vui lòng chờ %s giây nữa', 2));
                }
            }
        }

        $data['blog'] = $this->blog;
        $this->view->data = $data;
        $this->view->show('blog/download');
    }

    public function manager($app = array('index'))
    {
        ZenView::set_menu(array('pos' => 'page_menu', 'menu' => get_apps('blog/apps/manager',
                'blog/manager')));
        load_apps('blog/apps/manager', $app);
    }

    public function api($app = array('index'), $data = array())
    {
        load_apps('blog/apps/api', $app, $data);
    }

    public function editor_action_api()
    {
        run_hook('api', 'data_after_upload', function ($data) {
            $data['base_path'] = $data['base_path'] . '?v=3'; return $data; }
        );
        run_hook('api', 'data_after_upload', function ($data) {
                $data['base_path'] = $data['base_path'] . '&id=' . ZenInput::get('id') . '&a=' . ZenInput::get('urlUpload', true);
                return $data;
            }
        );
    }

    private function blog_path($id = null)
    {
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
            $tree = array_reverse($tree);
        }
        return $tree;
    }
}
