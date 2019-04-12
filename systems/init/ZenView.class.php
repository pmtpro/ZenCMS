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

Class ZenView
{
    public static
        $tempOBJ,
        $registry_data = array(),
        $message = array(),
        $message_log = array(),
        $layout_data = array(),
        $menu = array(),
        $D = array();

    private static
        $instance,
        $current_template,
        $tpl_config,
        $display_output = null;

    public $data = array();

    private $registry;

    /**
     * @param $registry
     */
    function __construct($registry) {
        $this->registry = $registry;
        self::$current_template = $registry->template;
        self::$tempOBJ = $registry->templateOBJ;
        self::$tpl_config = self::$tempOBJ->getTempConfig();
    }

    /**
     * @param $registry
     * @return ZenView
     */
    public static function getInstance($registry) {
        if (!self::$instance) {
            self::$instance = new ZenView($registry);
        }
        return self::$instance;
    }

    /**
     * load header layout
     */
    public static function load_header() {
        self::load_layout('header');
    }

    /**
     * load footer layout
     */
    public static function load_footer() {
        self::load_layout('footer');
    }

    /**
     * Another way to load layout
     * @param $name
     * @param array $options
     */
    public static function layout($name, $options = array(
        'show_with' => false,
        'only_this_perm' => false,
        'data' => null,
        'disable_include_layout' => false
    ))
    {
        self::load_layout($name, $options);
    }

    /**
     * This function will load the "layout" in the desired location.
     * The priority order for the "layout" as follows:
     * First, it will look to the folder "layout" in __FOLDER_TPL_NAME.
     * If it is not compatible with the layout will look to the "layout" in the "layout" of the "template"
     *
     * @param $name
     * @param array $options
     * @return bool|string
     */
    public static function load_layout($name, $options = array(
        'show_with' => false,
        'only_this_perm' => false,
        'data' => null,
        'disable_include_layout' => false
    ))
    {
        global $registry_data, $registry;
        $show_with = $options['show_with'];
        $only_this_perm = $options['only_this_perm'];
        $special_data = $options['data'];
        $disable_include_layout = $options['disable_include_layout'];
        $permission = load_library('permission');//load permission library
        $_client = $registry->user;
        if (is_bool($show_with) && $show_with == true) {
            if (!$permission->is_manager()) return false;
        }
        if (!empty ($show_with)) {
            $check = 'is_' . $show_with;
            if (!$permission->$check($only_this_perm)) return false;
        }
        /**
         * get filename layout
         */
        $name = trim($name, '/');
        $file_name_layout = $name . '.layout.php';

        self::$layout_data[$name] = !empty($special_data)?$special_data : array();
        /**
         * fetch data
         */
        foreach (self::$registry_data as $key => $value) {
            $$key = $value;
        }

        if ($registry->isTempSystem) {
            $layout[] = __FILES_PATH . '/systems/templates/' . $registry->template . '/' . __FOLDER_TPL_NAME . '/layout/' . $file_name_layout;
            $layout[] = __FILES_PATH . '/systems/templates/' . $registry->template . '/layout/' . $file_name_layout;
        } else {
            /**
             * set layout path
             */
            $layout[] = _PATH_TEMPLATE_TPL . '/' . MODULE_NAME . '/layout/' . $file_name_layout;
            $layout[] = _PATH_TEMPLATE . '/layout/' . $file_name_layout;
            $layout[] = __MODULES_PATH . '/' . MODULE_NAME . '/' . __FOLDER_TPL_NAME . '/layout/' . $file_name_layout;
        }
        foreach ($layout as $file) {
            if (file_exists($file)) {
                if ($disable_include_layout == true) {
                    ob_start();
                    include $file;
                    $sResult = ob_get_contents();
                    ob_end_clean();
                    self::add_element($sResult);
                    return ob_get_clean();
                } else {
                    include $file;//load the layout
                }
                break;
            }
        }
    }


    /**
     * set menu by position
     * $config:
     * pos - Position of menu
     * group - Group menu by position (name, item)
     * @param array $config
     */
    public static function set_menu($config = array()) {
        $pos = $config['pos'];
        $name = $config['name'];
        $menu = $config['menu'];
        self::$menu[$pos] = array(
            'name' => $name
        );
        foreach($menu as $item) {
            ZenView::add_menu($pos, $item);
        }
    }

    /**
     * add menu to menu position exists
     * @param string $pos
     * @param array $item
     * @param bool $before
     */
    public static function add_menu($pos, $item = array(), $before = false) {
        if (!isset(self::$menu[$pos]) || !is_array(self::$menu[$pos])) {
            self::$menu[$pos] = array();
        }
        $menu_gen = ZenView::gen_menu($item);

        if ($before == true) {
            self::$menu[$pos]['menu'] = array_merge(array($menu_gen), is_array(self::$menu[$pos]['menu']) ? self::$menu[$pos]['menu'] : array());
        } else {
            self::$menu[$pos]['menu'][] = $menu_gen;
        }
    }

    /**
     * genera menu: gen id, gen title
     * @param array $menu_element
     * @return array
     */
    public static function gen_menu($menu_element = array()) {
        if (empty($menu_element['id'])) {
            if (!empty($menu_element['full_url'])) {
                $menu_element['id'] = md5($menu_element['full_url']);
            }
        }
        if (empty($menu_element['title']) && isset($menu_element['name'])) {
            $menu_element['title'] = $menu_element['name'];
        }

        return $menu_element;
    }

    /**
     * get menu by position
     * @param $pos
     * @param bool $rejectCurrURL
     * @return array
     */
    public static function get_menu($pos, $rejectCurrURL = false) {
        if ($rejectCurrURL == true) {
            $curURL = getRouterUrl();
            if(isset(self::$menu[$pos]['menu']) && is_array(self::$menu[$pos]['menu'])) {
                foreach (self::$menu[$pos]['menu'] as $key => $m) {
                    if ($m['full_url'] == $curURL) {
                        unset(self::$menu[$pos]['menu'][$key]);
                    }
                }
            }
        }
        if(isset(self::$menu[$pos])) {
            return self::$menu[$pos];
        }
        return array();
    }


    /**
     * set page title
     * @param $title
     */
    public static function set_title($title) {
        self::$registry_data['page_title'] = $title;
    }

    public static function get_title($return = false)
    {
        if ($return == true) {
            return self::$registry_data['page_title'];
        }
        echo self::$registry_data['page_title'];
    }


    /**
     * set keyword to meta keyword tag
     * @param $keyword
     */
    public static function set_keyword($keyword) {
        self::$registry_data['page_keyword'] = $keyword;
    }

    public static function get_keyword($return = false)
    {
        if ($return == true) {
            return self::$registry_data['page_keyword'];
        }
        echo self::$registry_data['page_keyword'];
    }


    /**
     * set description to meta description tag
     * @param $desc
     */
    public static function set_desc($desc) {
        self::$registry_data['page_des'] = $desc;
    }

    public static function get_desc($return = false)
    {
        if ($return == true) {
            return self::$registry_data['page_des'];
        }
        echo self::$registry_data['page_des'];
    }


    /**
     * set page cover
     * @param $image_url
     */
    public static function set_image($image_url) {
        self::$registry_data['page_image'] = $image_url;
    }

    public static function get_image($return = false)
    {
        if (empty(self::$registry_data['page_image'])) {
            self::$registry_data['page_image'] = dbConfig('image');
        }
        if ($return == true) {
            return self::$registry_data['page_image'];
        }
        echo self::$registry_data['page_image'];
    }

    /**
     * set page url
     * @param $page_url
     */
    public static function set_url($page_url) {
        self::$registry_data['page_url'] = $page_url;
    }

    public static function get_url($return = false)
    {
        if (!self::$registry_data['page_url']) self::$registry_data['page_url'] = HOME;
        if ($return == true) {
            return self::$registry_data['page_url'];
        }
        echo self::$registry_data['page_url'];
    }

    public static function add_meta($name, $content = null, $firstProperty = 'name') {
        $firstUnique_attr = '';
        if (is_array($name)) {
            $meta = '<meta';
            foreach ($name as $attr => $val) {
                $meta .= ' ' . $attr . '="' . $val . '"';
                if (!$firstUnique_attr) $firstUnique_attr = 'meta:' . $attr . '=' . $val;
            }
            $meta .= '/>';
        } else {
            $key = $firstProperty ? $firstProperty : 'name';
            $meta = '<meta ' . $key . '="' . $name . '" content="' . $content . '"/>';
            $firstUnique_attr = 'meta:' . $key . '=' . $name;
        }
        self::$registry_data['page_head'][$firstUnique_attr] = $meta;
    }

    /**
     * set noindex for page
     * @param string $bot_name  robots, googlebot, bingbot...
     */
    public static function noindex($bot_name = 'robots') {
        self::add_meta($bot_name, 'noindex');
    }

    /**
     * add js to head
     * @param string $js_file
     * @param string $pos
     */
    public static function add_js($js_file, $pos = 'head') {
        if (!preg_match('/^https?:\/\//is', $js_file)) {
            $js_file = _URL_FILES_SYSTEMS . '/js/' . $js_file;
        }
        $js_tag = '<script type="text/javascript" src="' . $js_file . '"></script>';
        if ($pos == 'head') {
            self::$registry_data['page_head'][] = $js_tag;
        } elseif ($pos == 'foot') {
            self::$registry_data['page_foot'][] = $js_tag;
        }
    }

    /**
     * add jquery from system
     * @param string $jquery_file_name
     * @param string $pos
     */
    public static function add_jquery($jquery_file_name, $pos = 'head') {
        $urlJquery = _URL_FILES_SYSTEMS . '/js/jquery/' . $jquery_file_name;
        ZenView::add_js($urlJquery, $pos);
    }

    public function load_global_jquery($jquery_name) {
        echo "<script type=\"text/javascript\" src=\"" . _URL_FILES_SYSTEMS . '/js/jquery/' . $jquery_name . "\"></script>\n";
    }

    /**
     * add css file to head
     * @param $css_file
     * @param null $attr
     */
    public static function add_css($css_file, $attr = null) {
        $add_attr = '';
        if (!empty($attr)) {
            $attr = preg_replace('/rel\s*=\s*("|\')\s*stylesheet\s*("|\')/is', '', $attr);
            $attr = preg_replace('/type\s*=\s*("|\')\s*text\/css\s*("|\')/is', '', $attr);
            $add_attr = ' ' . trim($attr);
        }
        self::$registry_data['page_head'][] = '<link href="' . $css_file . '" rel="stylesheet" type="text/css"' . $add_attr . '/>';
    }


    public static function load_bootstrap_css() {
        echo "<link href=\"" . _URL_FILES_SYSTEMS . "/bootstrap/css/bootstrap.min.css\" type=\"text/css\" rel=\"stylesheet\"/>\n";
    }

    public function load_bootstrap_js() {
        echo "<script type=\"text/javascript\" src=\"" . _URL_FILES_SYSTEMS . "/bootstrap/js/bootstrap.min.js\"></script>\n";
    }

    /**
     * add element to head
     * @param $element
     */
    public static function append_head($element) {
        self::$registry_data['page_head'][] = $element;
    }

    /**
     * @param bool $return
     * @return string
     */
    public static function get_head($return = false){
        if (is(ROLE_MANAGER) && MODULE_NAME != 'admin') {
            $admin_bar = ZenView::admin_navbar();
        } else $admin_bar = '';
        $out = self::print_arrLine(self::$registry_data['page_head']);
        $out = $out . "\n" . $admin_bar;
        if ($return == true) return $out;
        echo $out;
    }

    /**
     * @return string
     */
    public static function admin_navbar() {
        ZenView::add_css(_URL_FILES_SYSTEMS . '/styles/admin/ZenCMS-ADMIN-CP.css');
        ZenView::add_js(_URL_FILES_SYSTEMS . '/styles/admin/ZenCMS-ADMIN-CP.js', 'foot');
        return '<div class="ZenCMS-ADMIN-CP-NAV">
                <ul>
                    <li class="ZenCMS-ADMIN-CP-LOGO"><a href="' . HOME . '/admin"><img src="' . _URL_FILES_SYSTEMS . '/images/zen-cp-logo.png" alt=""/></a></li>
                    <li>
                        <a href="' . HOME . '/admin">Admin CP</a>
                    </li>
                    ' . hook('admin', 'admin_navbar') . '
                    <li class="ZenCMS-ADMIN-CP-BTN-CLOSE"><a>X</a></li>
                </ul>
            </div>
            <span class="ZenCMS-ADMIN-CP-BTN-OPEN"><a>Open</a></span>';
    }

    public static function add_admin_navbar($arr = array()) {
        run_hook('admin', 'admin_navbar', function($data) use ($arr) {
            $icon = ' ';
            if (!empty($arr['icon'])) $icon = '<span class="' . $arr['icon'] . '"></span> ';
            return $data . '<li><a href="' . $arr['full_url'] . '">' . $icon . '' . $arr['name'] . '</a></li>';
        });
    }

    /**
     * add element to foot
     * @param $element
     */
    public static function append_foot($element) {
        self::$registry_data['page_foot'][] = $element;
    }

    public static function get_foot($return = false) {
        $out = self::print_arrLine(self::$registry_data['page_foot']);
        if ($return == true) return $out;
        echo $out;
    }

    /**
     * set message
     * @param string $type: error, notice, success
     * @param string $msg
     * @param string $pos: If $pos is empty, it will be ZPUBLIC
     * @param bool|string $redirect: if $redirect = true it will reload this page, if $redirect is a string, it will redirect to $redirect
     */
    public static function set_message($type, $msg, $pos = ZPUBLIC, $redirect = false) {
        if ($msg == 1 && $type == 'success') {
            $msg = 'Thành công!';
        }
        if (empty($pos)) {
            $pos = ZPUBLIC;
        }
        if (empty($type)) return;
        $redirect_url = false;
        if (is_bool($redirect) && $redirect == true) {
            $msg = urlencode($msg);
            $redirect_url = getRouterUrl();
        } elseif (!is_bool($redirect) && $redirect) {
            $msg = urlencode($msg);
            $redirect_url = $redirect;
        }
        if ($redirect_url) {
            redirect(add_arg2url($redirect_url, 'message[' . $pos . '][' . $type . ']=' . $msg));
            return;
        }
        if (empty(self::$registry_data['message'][$pos][$type])) {
            self::$registry_data['message'][$pos][$type] = array();
        }
        if (is_array($msg)) {
            self::$registry_data['message'][$pos][$type] = array_merge(self::$registry_data['message'][$pos][$type], $msg);
        } else {
            self::$registry_data['message'][$pos][$type][] = $msg;
        }
    }

    /**
     * display message by position
     * @param string $pos
     * @param string $display_control
     */
    public static function display_message($pos = ZPUBLIC, $display_control = '') {
        self::add_element(self::get_message($pos, $display_control));
    }

    /**
     * get message by position
     * @param string $pos
     * @param string $display_control
     * @return bool|string
     */
    public static function get_message($pos = ZPUBLIC, $display_control = '') {
        $msg_display = '';
        $msg = '';
        $list_tmsg = array('error', 'notice', 'success', 'info', 'tip');
        $mapMsg = self::$tempOBJ->getMap('message');
        $secur = load_library('security');
        /**
         * check request message
         */
        if (isset($_REQUEST['message']) && is_array($_REQUEST['message'])) {
            if (isset($_REQUEST['message'][$pos]) && is_array($_REQUEST['message'][$pos])) {
                foreach($_REQUEST['message'][$pos] as $ktype => $request_msg) {
                    if (in_array($ktype, $list_tmsg)) {
                        if (!is_array($request_msg)) {
                            $request_msg = array($request_msg);
                        }
                        foreach ($request_msg as $msg) {
                            self::$registry_data['message'][$pos][$ktype][] = $secur->cleanXSS(urldecode(strip_tags($secur->cleanXSS($msg))));
                        }
                    }
                }
            }
        }
        /**
         * check message
         */
        foreach ($list_tmsg as $type_msg) {
            if (!empty(self::$registry_data['message'][$pos][$type_msg])) {
                $msg_display .= $mapMsg[$type_msg]['start'];
                foreach (self::$registry_data['message'][$pos][$type_msg] as $err) {
                    $msg .= $err . '<br/>';
                    $msg_display .= $err . '<br/>';
                }
                $msg_display .= $mapMsg[$type_msg]['end'];
            }
        }
        if (empty($msg_display)) {
            return false;
        }
        if (!empty($display_control)) {
            $msg_display = sprintf ($display_control, $msg);
        }
        return $msg_display;
    }


    /**
     * set error message by position
     * @param $msg
     * @param string $pos
     * @param bool $redirect
     */
    public static function set_error($msg, $pos = ZPUBLIC, $redirect = false) {
        ZenView::set_message('error', $msg, $pos, $redirect);
    }

    /**
     * set notice message
     * @param $msg
     * @param string $pos
     * @param bool $redirect
     */
    public static function set_notice($msg, $pos = ZPUBLIC, $redirect = false) {
        ZenView::set_message('notice', $msg, $pos, $redirect);
    }

    /**
     * set success message
     * @param $msg
     * @param string $pos
     * @param bool $redirect
     */
    public static function set_success($msg, $pos = ZPUBLIC, $redirect = false) {
        ZenView::set_message('success', $msg, $pos, $redirect);
    }

    /**
     * set tip message
     * @param $msg
     * @param string $pos
     */
    public static function set_tip($msg, $pos = ZPUBLIC) {
        if (empty(self::$registry_data['message'][$pos]['tip'])) {
            self::$registry_data['message'][$pos]['tip'] = array();
        }
        if (is_array($msg)) {
            self::$registry_data['message'][$pos]['tip'] = array_merge(self::$registry_data['message'][$pos]['tip'], $msg);
        } else {
            self::$registry_data['message'][$pos]['tip'][] = $msg;
        }
    }

    /**
     * This function will return true if any messages are successful,
     * return false if had error message or notice
     * @param string $pos
     * @return bool
     */
    public static function is_success($pos = ZPUBLIC) {
        $isSuccess = true;
        /**
         * if empty $pos, check in any position
         */
        if (empty($pos)) {
            if (is_array(self::$registry_data['message'])) {
                foreach(self::$registry_data['message'] as $pos=>$arr) {
                    if (!empty(self::$registry_data['message'][$pos]['error']) || !empty(self::$registry_data['message'][$pos]['notice'])) {
                        $isSuccess = false;
                    }
                }
            }
        } else {
            /**
             * check message in thif $pos
             */
            if (empty(self::$registry_data['message'][$pos]['error']) && empty(self::$registry_data['message'][$pos]['notice'])) {
                $isSuccess = true;
            } else $isSuccess = false;
        }
        return $isSuccess;
    }

    /**
     * This function will check existed of a msg
     * @param string $pos
     * @return bool
     */
    public static function message_exists($pos = ZPUBLIC) {
        if (empty(self::$registry_data['message'][$pos])) {
            return false;
        }
        return true;
    }

    /**
     * @param mixed $id
     * @param string $pos
     * @return bool
     */
    public static function render_msg($id, $pos = ZPUBLIC) {
        if (!isset(ZenView::$message_log[$id])) {
            return true;
        }
        $listType = array('success', 'notice', 'error', 'tip');
        $foundErr = false;
        foreach(ZenView::$message_log[$id] as  $msgArr) {
            if (in_array($msgArr['type'], $listType)) {
                if ($msgArr['type'] == 'notice' || $msgArr['type'] == 'error') $foundErr = true;
                $functionCall = 'ZenView::set_' . $msgArr['type'];
                if (empty($msgArr['pos'])) $msgArr['pos'] = $pos;
                call_user_func_array($functionCall, array($msgArr['msg'], $msgArr['pos'], $msgArr['redirect']));
            }
        }
        if ($foundErr == true) return false;
        else return true;
    }

    /**
     * @param mixed $id
     * @param string $type
     * @param string $msg
     * @param string $pos
     * @param bool|string $redirect
     */
    public static function log_msg($id, $type, $msg, $pos = '', $redirect = false) {
        if ($id && $type) {
            ZenView::$message_log[$id][] = array(
                'type' => $type,
                'msg' => $msg,
                'pos' => $pos,
                'redirect' => $redirect
            );
        }
    }
    /**
     * set the breadcrumb
     * @param $tree
     */
    public static function set_breadcrumb($tree) {
        if (!isset(self::$registry_data['page_breadcrumb_item']) || !is_array(self::$registry_data['page_breadcrumb_item'])) {
            self::$registry_data['page_breadcrumb_item'] = array();
        }
        if (is_array($tree)) {
            self::$registry_data['page_breadcrumb_item'] = array_merge(self::$registry_data['page_breadcrumb_item'], $tree);
        } else {
            self::$registry_data['page_breadcrumb_item'][] = $tree;
        }
    }

    public static function display_breadcrumb($inside_map = true) {

        if (empty(self::$registry_data['page_breadcrumb_item'])) {
            return;
        }
        $breadcrumb = self::$tempOBJ->getMap('breadcrumb');
        $inside = display_tree(self::$registry_data['page_breadcrumb_item']);
        self::$registry_data['page_breadcrumb'] =  $breadcrumb['start'] . $inside . $breadcrumb['end'];

        if ($inside_map == true) {
            self::add_element(self::$registry_data['page_breadcrumb']);
        } else {
            echo self::$registry_data['page_breadcrumb'];
        }
    }


    /**
     * set paging
     * @param $pagingElement
     * @param string $pos
     */
    public static function set_paging($pagingElement, $pos = ZPUBLIC) {
        if (!empty($pos)) {
            self::$registry_data['paging'][$pos] = $pagingElement;
        }
    }

    /**
     * @param $pos
     */
    public static function display_paging($pos = ZPUBLIC) {
        if (!empty(self::$registry_data['paging'][$pos])) {
            self::add_element(self::$registry_data['paging'][$pos]);
        }
    }


    /**
     * add element, output: self::$display_output
     * @param $data
     */
    public static function add_element($data) {
        echo $data;
    }

    /**
     * set text
     * @param null $text
     */
    public static function e($text = null) {
        self::add_element($text);
    }

    /**
     * Set section
     * @param $title
     * @param null $funcCallBack
     * @param array $options
     */
    public static function section($title, $funcCallBack = null, $options = array()) {
        $section = self::$tempOBJ->getMap('section');
        if (empty($options['before'])) {
            $options['before'] = '';
        } else {
            $options['before'] = $section['title']['before']['start'] . $options['before'] . $section['title']['before']['end'];
        }
        if (empty($options['after'])) {
            $options['after'] = '';
        } else {
            $options['after'] = $section['title']['after']['start'] . $options['after'] . $section['title']['after']['end'];
        }
        self::add_element($section['start']);
        if (!empty($title) && !empty($section['title']['start']) && !empty($section['title']['end'])) {
            self::add_element(self::replace_mark('before', $section['title']['start'], $options['before']) . $title . self::replace_mark('after', $section['title']['end'], $options['after']));
        }
        self::add_element($section['content']['start']);
        if (is_func($funcCallBack)) {
            call_user_func($funcCallBack);
        } else self::add_element($funcCallBack);
        self::add_element($section['content']['end']);
        self::add_element($section['end']);
    }

    /**
     * set block
     * @param $title
     * @param null $funcCallBack
     * @param array $options
     */
    public static function block($title, $funcCallBack = null, $options = array()) {
        $block = self::$tempOBJ->getMap('block');

        if (empty($options['before'])) {
            $options['before'] = '';
        } else {
            $options['before'] = $block['title']['before']['start'] . $options['before'] . $block['title']['before']['end'];
        }
        if (empty($options['after'])) {
            $options['after'] = '';
        } else {
            $options['after'] = $block['title']['after']['start'] . $options['after'] . $block['title']['after']['end'];
        }

        self::add_element($block['start']);

        if (!empty($title)) {
            self::add_element(self::replace_mark('before', $block['title']['start'], $options['before']) . $title . self::replace_mark('after', $block['title']['end'], $options['after']));
        }
        self::add_element($block['content']['start']);
        if (is_func($funcCallBack)) {
            call_user_func($funcCallBack);
        } else self::add_element($funcCallBack);
        self::add_element($block['content']['end']);
        self::add_element($block['end']);
    }

    /**
     * @param $funcCallBack
     */
    public static function padded($funcCallBack = null) {
        $padded = self::$tempOBJ->getMap('padded');
        self::add_element($padded['start']);
        if (is_func($funcCallBack)) {
            call_user_func($funcCallBack);
        } else self::add_element($funcCallBack);
        self::add_element($padded['end']);
    }

    /**
     * create row
     * @param $funcCallBack
     */
    public static function row($funcCallBack) {
        $rowItem = self::$tempOBJ->getMap('row');
        self::add_element($rowItem['start']);
        if (is_func($funcCallBack)) {
            call_user_func($funcCallBack);
        } else self::add_element($funcCallBack);
        self::add_element($rowItem['end']);
    }

    /**
     * create column
     * @param $funcCallBack
     */
    public static function col($funcCallBack) {
        if (is_func($funcCallBack)) {
            $row = self::$tempOBJ->getMap('col');
            self::add_element($row['start']);
            call_user_func($funcCallBack);
            self::add_element($row['end']);
        }
    }

    /**
     * @param $colNum
     * @param $funcCallBack
     */
    public static function col_item($colNum, $funcCallBack) {
        $row = self::$tempOBJ->getMap('col');
        self::add_element($row['item'][$colNum]['start']);
        if (is_func($funcCallBack)) {
            call_user_func($funcCallBack);
        } else self::add_element($funcCallBack);
        self::add_element($row['item'][$colNum]['end']);
    }

    /**
     * @param $markName
     * @param $subject
     * @param null $replace
     * @return mixed
     */
    public static function replace_mark($markName, $subject, $replace = null) {
        if (empty($replace)) return $subject;
        return str_replace('<!--' . $markName . '-->', $replace, $subject);
    }

    /**
     * @param $data
     * @param bool $only_data
     */
    public static function ajax_json_response($data, $only_data = false) {
        ZenView::ajax_response($data, 'text/json', $only_data);
    }

    public static function ajax_response($data, $type = 'text/json', $only_data = false) {
        if (is_ajax_request()){
            /*
            header('Vary: Accept');
            if (isset($_SERVER['HTTP_ACCEPT']) &&
                (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
                header('Content-type: application/json');
            } else {
                header('Content-type: text/plain');
            }
            */
            if (!empty($type)) {
                header('Content-type: ' . $type);
            }
            if ($only_data) {
                $responseData = $data;
            } else {
                $responseData = array();
                if (is_array(self::$registry_data['message'])) {
                    foreach(self::$registry_data['message'] as $pos => $val) {
                        if(!empty(self::$registry_data['message'][$pos]['error'])) {
                            $responseData['msg']['error'] = self::$registry_data['message'][$pos]['error'];
                        }
                        if(!empty(self::$registry_data['message'][$pos]['notice'])) {
                            $responseData['msg']['error'] = array_merge($responseData['msg']['error'], self::$registry_data['message'][$pos]['notice']);
                        }
                        if(!empty(self::$registry_data['message'][$pos]['success'])) {
                            $responseData['msg']['success'] = self::$registry_data['message'][$pos]['success'];
                        }
                    }
                }

                if (empty($responseData['msg']['error'])) {
                    $responseData['status'] = 1;
                } else {
                    $responseData['status'] = 0;
                }
                $responseData['data'] = $data;
            }

            if ($type == 'text/json') {
                echo json_encode($responseData);
            } else {
                $responseData = $data;
                echo $responseData;
            }
            exit;
        }
    }

    /**
     * @param $name
     * @param array $options
     */
    public function show($name, $options = array())
    {
        global $registry_data, $registry;
        if (empty($name)) show_error(1001);

        $registry_data = array();
        $tempName = self::$current_template;

        /**
         * set template directory
         */
        $template_dir = __TEMPLATES_PATH . '/' . $tempName;

        /**
         * set tpl directory
         */
        $template_tpl = $template_dir . '/' . __FOLDER_TPL_NAME;

        if ($registry->isTempSystem) {
            $template_dir = __FILES_PATH . '/systems/templates/' . $registry->template;
            $template_tpl = $template_dir . '/' . __FOLDER_TPL_NAME;
        }

        if (!is_dir($template_dir) || !is_readable($template_dir)) {
            show_error(1000);
        }

        $__load_map = false;

        $name = trim($name, '/');
        $n = strpos($name, ':');
        $feature = substr($name, 0, $n);
        if ($feature) $name = substr($name, $n+1);

        switch ($feature) {
            default;
                /**
                 * hash name
                 */
                $list_name = explode('/', $name);
                if (!isset($list_name[1])) $list_name[1] = $name;

                $package = $list_name[0];
                $name = implode($list_name, '/');
                $edit_list_name = $list_name;
                unset($edit_list_name[0]); //remove module name
                $after_name = implode($edit_list_name, '/');

                $module_tpl_dir = __MODULES_PATH . '/' . $package . '/tpl';
                $module_map_dir = __MODULES_PATH . '/' . $package . '/map';
                /**
                 * file tpl
                 */
                $tpl_loc = $template_tpl . '/' . $name;
                $tpl_module_loc = $module_tpl_dir . '/' . $after_name;
                $map_module_loc = $module_map_dir . '/' . $after_name;

                $check_file[0] = $tpl_loc . '.tpl.php';
                $check_file[1] = $tpl_module_loc . '.tpl.php';
                $check_file[2] = $map_module_loc . '.map.php';

                foreach ($check_file as $k => $file_show) {
                    if (file_exists($file_show)) {
                        $__path = $file_show;
                        if ($k == 2) $__load_map = true;
                        break;
                    }
                }
                break;
            case 'file':
                if (file_exists($name)) {
                    $__path = $name;
                }
                break;
            case 'page':
                /**
                 * hash name
                 */
                $list_name = explode('/', $name);
                if (!isset($list_name[1])) $list_name[1] = $name;
                $package = $list_name[0];
                $name = implode($list_name, '/');
                if (defined('_PATH_TEMPLATE')) {
                    $check_file[] = _PATH_TEMPLATE . '/pages/' . $name . '.tpl.php';
                }
                $edit_list_name = $list_name;
                unset($edit_list_name[0]); //remove module name
                $after_name = implode($edit_list_name, '/');
                $check_file[] = __MODULES_PATH . '/' . $package . '/pages/' . $after_name . '.tpl.php';
                $check_file[] = __FILES_PATH . '/systems/default/pages/' . $name . '.tpl.php';
                foreach ($check_file as $page) {
                    if (file_exists($page)) {
                        $__path = $page;
                        $options['only_map'] = true;
                        break;
                    }
                }
                break;
        }

        if (empty($__path)) {
            show_error(1001);
        }

        $this->data['_client'] = $this->registry->user;

        /**
         * merge initialize data and control data
         */
        self::$registry_data = array_merge($this->data, self::$registry_data);

        $this->standardized_data();

        $registry_data = self::$registry_data;
        self::$D = $registry_data;

        foreach ($registry_data as $key => $value) {
            $$key = $value;
        }

        if ($__load_map == true) {

            $edit_list_name_1 = $edit_list_name;
            /**
             * move pointer to end of list
             */
            end($edit_list_name_1);
            /**
             * get end key
             */
            $last_key = key($edit_list_name_1);
            /**
             * find auto run file
             */
            while ($last_key > 1) {
                unset($edit_list_name_1[$last_key]);
                $last_key--;
                $before_autoRun = implode($edit_list_name_1, '/');
                $map_module_loc_1 = $module_map_dir . '/' . $before_autoRun;
                $check_autoRun[] = $map_module_loc_1 . '/' . end($edit_list_name_1) . '.autorun.php';
            }
            if (!empty($check_autoRun)) {
                $check_autoRun = array_unique($check_autoRun);
                krsort($check_autoRun);
                foreach ($check_autoRun as $autoRun_file) {
                    if (file_exists($autoRun_file)) {
                        include $autoRun_file;
                    }
                }
            }
        }

        /**
         * start ob
         */
        ob_start();
        /**
         * include file
         */
        include $__path;
        /**
         * get cache content
         */
        self::$display_output = ob_get_contents();
        /**
         * clean content displayed
         */
        ob_end_clean();

        if (!empty($options['only_map'])) {
            echo self::$display_output;
        } else include $template_dir . '/page.php';
    }

    /**
     * standardized data: insert title, keyword, description, url, image, message {success, notices, errors}
     */
    public function standardized_data()
    {
        /**
         * check title
         * if title not exists then get default title
         */
        if (!isset(self::$registry_data['page_title'])) self::$registry_data['page_title'] = dbConfig('title');
        if (!isset(self::$registry_data['page_keyword'])) self::$registry_data['page_keyword'] = '';
        if (!isset(self::$registry_data['page_des'])) self::$registry_data['page_des'] = '';
        if (!isset(self::$registry_data['page_url'])) self::$registry_data['page_url'] = '';
        if (!isset(self::$registry_data['page_image'])) self::$registry_data['page_image'] = dbConfig('page_image');
        /**
         *
         * decode title
         */
        self::$registry_data['page_title'] = h_decode(self::$registry_data['page_title']);
        /**
         * get page head
         */
        if (isset(self::$registry_data['page_head'])) {
            if (!is_array(self::$registry_data['page_head'])) self::$registry_data['page_head'] = array(self::$registry_data['page_head']);
        } else self::$registry_data['page_head'] = array();
        /**
         * get page foot
         */
        if (isset(self::$registry_data['page_foot'])) {
            if (!is_array(self::$registry_data['page_foot'])) self::$registry_data['page_foot'] = array(self::$registry_data['page_foot']);
        } else self::$registry_data['page_foot'] = array();

        /**
         * get success message
         */
        if (isset(self::$registry_data['success'])) {
            if (!is_array(self::$registry_data['success'])) {
                self::$registry_data['success'] = array(self::$registry_data['success']);
            }
        } else {
            if (!empty($_SESSION['msg']['success'])) {
                self::$registry_data['success'] = array($_SESSION['msg']['success']);
                unset($_SESSION['msg']['success']);
            } else {
                self::$registry_data['success'] = array();
            }
        }

        /**
         * get errors message
         */
        if (isset(self::$registry_data['errors'])) {
            if (!is_array(self::$registry_data['errors'])) {
                self::$registry_data['errors'] = array(self::$registry_data['errors']);
            }
        } else {
            self::$registry_data['errors'] = array();
        }

        /**
         * get notices message
         */
        if (isset(self::$registry_data['notices'])) {
            if (!is_array(self::$registry_data['notices'])) {
                self::$registry_data['notices'] = array(self::$registry_data['notices']);
            }
        } else {
            self::$registry_data['notices'] = array();
        }
    }

    /**
     * print interface
     */
    public static function display_content() {
        echo self::$display_output;
    }

    /**
     * @param array $arr
     * @return string
     */
    public static function print_arrLine($arr = array()) {
        $out = '';
        foreach ($arr as $more) {
            $out .= $more . "\n";
        }
        $out = trim($out, "\n");
        return $out;
    }
}