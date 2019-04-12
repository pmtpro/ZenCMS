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

class blogController extends ZenController
{

    private $blog = array();

    function index($request_data = array())
    {
        /**
         * load helpers
         */
        load_helper('time');
        load_helper('gadget');

        /**
         * load library
         */
        $security = load_library('security');
        $p = load_library('pagination');
        $bbcode = load_library('bbcode');
        $permission = load_library('permission');
        $permission->set_user($this->user);

        /**
         * get blog model
         */
        $model = $this->model->get('blog');

        if (isset($_GET['_review_recycleBin_']) && is(ROLE_MANAGER)) {

            $model->only_filter_recycle_bin();
        }

        /**
         * get hook
         */
        $this->hook->get('blog');

        /**
         * remove sqli, load id *
         */
        if (isset($request_data[0])) {

            $id = $security->removeSQLI($request_data[0]);

        } else {

            $id = 0;
        }

        $id = (int)$id;

        /**
         * Check blog is exists *
         */
        if ($model->blog_exists($id) == false) {

            $data['page_title'] = 'Error!';
            $data['notices'][] = 'Bài đăng này không tôn tại hoặc đã bị xóa bời người quản lí!';
            $this->view->data = $data;
            $this->view->show('blog/error');
            return false;
        }

        /**
         * If is a blog *
         */
        $blog_data = $model->get_blog_data($id); // load data of blog

        if (empty($blog_data['parent'])) {

            $_SESSION['ss_url_delete_and_back'] = _HOME;

        } else {

            $data_back = $model->get_blog_data($blog_data['parent'], 'url');

            if (isset($data_back['full_url'])) {

                $_SESSION['ss_url_delete_and_back'] = $data_back['full_url'];
            }

        }

        $blog_data['sid'] = $id;

        /**
         * load title blog or home *
         */
        if (isset($blog_data['title'])) {

            $data['page_title'] = $blog_data['title'];

        } else {

            if (isset($blog_data['name'])) {

                $data['page_title'] = $blog_data['name'];

            } else {

                $data['page_title'] = get_config('title');
            }
        }

        /**
         * if $id = 0 is home
         */
        if ($id == 0) {

            $cats = $model->get_list_blog(0);

            $limit_sub_cat = 0;

            /**
             * num_display_sub_cat_in_index hook *
             */
            $limit_sub_cat = $this->hook->loader('num_display_sub_cat_in_index', $limit_sub_cat);


            $order_sub_cat = array('weight' => 'ASC', 'time' => 'DESC');

            /**
             * order_display_sub_cat_in_index hook *
             */
            $order_sub_cat = $this->hook->loader('order_display_sub_cat_in_index', $order_sub_cat);

            $filter_sub_cat = NULL;

            /**
             * order_display_sub_cat_in_index hook *
             */
            $filter_sub_cat = $this->hook->loader('filter_display_sub_cat_in_index', $filter_sub_cat);

            foreach ($cats as $kid => $cat) {

                $cats[$kid]['sub_cat'] = $model->get_list_blog($kid, $filter_sub_cat, $order_sub_cat, $limit_sub_cat);
            }

            /**
             * lists_in_index hook *
             */
            $data['list'] = $this->hook->loader('lists_in_index', $blog_data, true);

            $data['page_keyword'] = get_config('keyword');
            $data['page_des'] = get_config('des');
            $data['cats'] = $cats;
            $this->view->data = $data;
            $this->view->show('blog');
            return;
        }

        /**
         * update view
         */
        $model->update_view($blog_data['sid']);

        /**
         * else is a blog *
         */
        if ($blog_data['type'] == 'folder') {
            /**
             * lists_in_folder hook *
             */
            $data['list'] = $this->hook->loader('lists_in_folder', $blog_data, true);

            /**
             * load manager bar *
             */
            if ($permission->is_manager()) {

                $blog_data['manager_bar'] = $this->hook->loader('folder_manager_bar', $id);

            } else {

                $blog_data['manager_bar'] = array();
            }

            $data['page_more'] = '';
            /**
             * set_page_more_in_folder hook *
             */
            $data['page_more'] = $this->hook->loader('set_page_more_in_folder', $data['page_more']);

        } elseif ($blog_data['type'] == 'post') {

            $data['page_more'] = '';
            /**
             * set_page_more_in_post hook *
             */
            $data['page_more'] = $this->hook->loader('set_page_more_in_post', $data['page_more']);

            $data['page_more'] .= gadget_TinymceEditer('bbcode_mini', true);

            /**
             * lists_in_post hook *
             */
            $data['list'] = $this->hook->loader('lists_in_post', $blog_data, true);

            /**
             * load manager bar *
             */
            if ($permission->is_manager()) {

                $blog_data['manager_bar'] = $this->hook->loader('post_manager_bar', $id);

            } else {

                $blog_data['manager_bar'] = array();
            }

        } else {

            $data['page_title'] = 'Error!';
            $data['notices'][] = 'Bài đăng này đã bị xóa bời người quản lí!';
            /**
             * lists_in_other hook *
             */
            $data['list'] = $this->hook->loader('lists_in_other', $blog_data, true);
            $this->view->data = $data;
            $this->view->show('blog/error');
            return false;
        }


        /**
         * load content
         */
        if (isset($blog_data['type_data']) && isset($blog_data['content'])) {
            /**
             * out_content hook *
             */
            $blog_data['content'] = $this->hook->loader('out_content', $blog_data['content']);

            if ($blog_data['type_data'] == 'bbcode') {

                $blog_data['content'] = $bbcode->parse($blog_data['content']);
                /**
                 * out_bbcode_content hook *
                 */
                $blog_data['content'] = $this->hook->loader('out_bbcode_content', $blog_data['content']);
            } else {
                /**
                 * out_html_content hook *
                 */
                $blog_data['content'] = $this->hook->loader('out_html_content', $blog_data['content']);
            }

            $blog_data['content'] = scan_smiles($blog_data['content']);
        }


        $tags = $model->get_tags_blog($blog_data['sid']);

        foreach ($tags as $tag) {

            /**
             * out_tag hook *
             */
            $blog_data['tags'][] = $this->hook->loader('out_tag', $tag);

        }

        // start comments

        if (isset($_POST['sub_comment'])) {

            if ($security->check_token('token_comment')) {

                if (!$security->check_token('captcha_code') and empty($this->user['id'])) {

                    $data['errors'][] = 'Mã xác nhận không chính xác';
                } else {

                    $continous = true;

                    if (isset($this->user['id'])) {

                        $ins_cmt['uid'] = $this->user['id'];
                    } else {

                        $ins_cmt['uid'] = 0;
                    }

                    if (!$ins_cmt['uid']) {

                        if (empty($_POST['name'])) {

                            $data['notices'][] = 'Bạn chưa nhập tên mình';
                            $continous = false;

                        } else {

                            $ins_cmt['name'] = h($_POST['name']);
                        }
                    } else {

                        $ins_cmt['name'] = $this->user['username'];
                    }

                    if (empty($_POST['msg'])) {

                        $data['notices'][] = 'Nội dung comment không được để trống';
                        $continous = false;

                    } else {

                        $ins_cmt['msg'] = h($_POST['msg']);
                        /**
                         * in_msg_comment_content hook *
                         */
                        $ins_cmt['msg'] = $this->hook->loader('in_msg_comment_content', $ins_cmt['msg']);
                    }

                    if ($continous == true) {

                        $ins_cmt['sid'] = $blog_data['sid'];
                        $ins_cmt['ip'] = client_ip();

                        $model->insert_comment($ins_cmt);
                    }
                }
            }
        }

        $limit = 5;
        /**
         * num_comment hook *
         */
        $limit = $this->hook->loader('num_comment', $limit);
        $p->setLimit($limit);
        $p->SetGetPage('cmtPage');
        $start = $p->getStart();
        $sql_limit = $start.','.$limit;

        $blog_data['comments'] = $model->get_comments($blog_data['sid'], $sql_limit);

        foreach ($blog_data['comments'] as $_cmt_k => $_cmt) {

            $_cmt['msg'] = $bbcode->parse($_cmt['msg']);

            $_cmt['msg'] = scan_smiles($_cmt['msg']);
            /**
             * out_msg_comment_content hook *
             */
            $_cmt['msg'] = $this->hook->loader('out_msg_comment_content', $_cmt['msg']);

            $blog_data['comments'][$_cmt_k]['msg'] = $_cmt['msg'];
        }

        $p->setTotal($model->total_result);

        $data['comments_pagination'] = $p->navi_page('?cmtPage={cmtPage}#comments');

        $data['token_comment'] = $security->get_token('token_comment');

        $captcha_security_key = $security->get_token('captcha_security_key', 4);
        $data['captcha_src'] = _HOME . '/captcha/image/image_' . $captcha_security_key . '.jpg';
        // end comments


        // start like and dislike
        if (isset($_GET['_t_'])) {

            if ($security->check_token('_t_', 'GET')) {

                if (isset ($this->user['id']) && !empty($this->user['id'])) {

                    $ldata['fromid'] = $this->user['id'];

                } else {

                    $ldata['ip'] = client_ip();
                }

                $ldata['toid'] = $blog_data['sid'];

                if (isset($_GET['_like_'])) {

                    $model->do_like($ldata);

                } else {

                    if (isset($_GET['_dislike_'])) {

                        $model->do_dislike($ldata);
                    }
                }
                redirect($blog_data['full_url']);
            }
        }
        // end like and dislike

        /**
         * like & dislike
         */
        $blog_data['likes'] = $model->get_like($blog_data['sid']);
        $blog_data['dislikes'] = $model->get_dislike($blog_data['sid']);

        /**
         * num_likes hook *
         */
        $blog_data['likes'] = $this->hook->loader('num_likes', $blog_data['likes']);

        /**
         * num_dislikes hook *
         */
        $blog_data['dislikes'] = $this->hook->loader('num_dislikes', $blog_data['dislikes']);

        $blog_data['is_liked'] = $model->is_liked($blog_data['sid']);
        $blog_data['is_disliked'] = $model->is_disliked($blog_data['sid']);

        /**
         * get like token
         */
        $token_like = $security->get_token('_t_');

        /**
         * set link like & dislike
         */
        $link_like = '<a href="' . $blog_data['full_url'] . '?_like_&_t_=' . $token_like . '" rel="nofollow" title="Like">' . icon('like') . '</a>';
        $link_dislike = '<a href="' . $blog_data['full_url'] . '?_dislike_&_t_=' . $token_like . '" rel="nofollow" title="Dislike">' . icon('dislike') . '</a>';

        if ($blog_data['is_liked']) {

            $link_like = icon('like');
        }
        if ($blog_data['is_disliked']) {

            $link_dislike = icon('dislike');
        }

        /**
         * hook link like, dislike
         */
        $blog_data['link_like'] = $this->hook->loader('link_like', $link_like);
        $blog_data['link_dislike'] = $this->hook->loader('link_dislike', $link_dislike);

        /**
         * get link and file download
         */
        $links = $model->get_links($blog_data['sid']);
        $files = $model->get_files($blog_data['sid']);

        /**
         * links_download hook & files_download hook*
         */
        $blog_data['downloads']['links'] = $this->hook->loader('links_download', $links);
        $blog_data['downloads']['files'] = $this->hook->loader('files_download', $files);

        if (empty($blog_data['downloads']['links']) && empty($blog_data['downloads']['files'])) {

            $blog_data['downloads'] = array();

        }

        $data['blog'] = $blog_data;

        if (count($data['blog']) && empty($data['blog']['type_view'])) {

            $data['blog']['type_view'] = 'default';
        }


        $this->blog = $data['blog'];

        $tree[] = url(_HOME, '<i itemprop="title">' . icon('home') . '</i>', 'title="' . get_config('title') . '" itemprop="url"');
        /**
         * first_blog_path hook *
         */
        $tree = $this->hook->loader('first_blog_path', $tree);

        /**
         * blog_data hook *
         */
        $data['blog'] = $this->hook->loader('blog_data', $data['blog']);

        $tree = array_merge($tree, $this->blog_path($id));
        $data['display_tree'] = display_tree($tree);

        $data['page_keyword'] = $data['blog']['keyword'];
        $data['page_des'] = $data['blog']['des'];
        $data['page_url'] = $data['blog']['full_url'];

        $this->view->data = $data;

        if ($data['blog']['type']) {

            $this->view->show('blog/' . $data['blog']['type']);
            return;
        }

        $this->view->show('blog');
    }

    public function manager($app = array('index'))
    {
        load_apps(__MODULES_PATH . '/blog/apps/manager', $app);
    }

    private function blog_path($id = 0, $from_recycle_bin = false)
    {
        static $tree = array();
        static $i;
        $i++;

        $model = $this->model->get('blog');

        if ($from_recycle_bin == true) {

            $model->not_filter_recycle_bin(true);

        }

        $blog = $model->get_blog_data($id);

        if (!empty($blog)) {

            if ($i != 1) {

                $tree[] = url($blog['full_url'], '<i itemprop="title">' . $blog['name'] . '</i>', 'title="' . $blog['title'] . '" itemprop="url"');
            }

            $parent = $blog['parent'];

            $this->blog_path($parent);

        } else {


            $tree = array_reverse ($tree);
        }
        return $tree;
    }



}