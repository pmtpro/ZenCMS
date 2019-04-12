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

/**
 * @param string $icon
 * @param bool $more
 * @return string
 */
if (!function_exists('icon')) {

    function icon($icon = '', $more = false)
    {
        global $template_config;

        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        $style = '';
        $width = '';
        $alt = '';

        $src = icon_src($icon);

        if (empty($src)) {

            return '';
        }

        if (empty ($more)) {

            return '<img src="' . icon_src($icon) . '" alt="' . $icon . '" class="icon"/>';
        }

        if (is_array($more)) {

            if (!isset($more['alt'])) {

                $alt = $template_config['icon'][$icon];

            } else {

                $alt = $more['alt'];

            }
            if (isset($more['width'])) {

                if (is_numeric($more['width'])) $more['width'] = $more['width'] . 'px';

                $style = 'width:' . $more['width'] . ';';
            }

            if (isset($more['height'])) {

                if (is_numeric($more['height'])) $more['height'] = $more['height'] . 'px';

                $style .= 'height:' . $more['height'] . ';';
            }

        } else {

            if (is_numeric($more)) {

                $width = 'width="' . $width . 'px"';

            } else {

                $style = $more;
            }
        }

        return '<img src="' . icon_src($icon) . '" alt="' . $alt . '" class="icon" ' . $width . ' style="' . $style . '" />';
    }
}

/**
 * @param string $icon
 * @return string
 */
if (!function_exists('icon_src')) {

    function icon_src($icon = '')
    {
        global $template_config, $registry;

        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        static $_icon_src;

        if (isset($_icon_src[$icon])) {

            return $_icon_src[$icon];
        }

        $get = explode('|', $icon);

        $url = '';

        $template_location = 'templates/' . _TEMPLATE;

        $icon_dir = $template_config['icon_dir'];

        /**
         * check if module has own template
         */
        $getsetting = $registry->settings->get(__MODULE_NAME);

        if (!empty($getsetting)) {

            if (!isset($getsetting->setting['own_template'])) {

                $getsetting->setting['own_template'] = null;
            }

            $own_template = $getsetting->setting['own_template'];

        } else {

            $own_template = null;
        }

        if (!is_null($own_template) && !empty($own_template)) {

            $template_location = 'modules/' . __MODULE_NAME . '/templates/' . $own_template;

            $icon_dir = 'images/icons';
        }

        if (count($get) != 1) {

            $icon_base = $template_location . '/' . __FOLDER_TPL_NAME . '/' . trim($get[0]) . '/icons';

            $icon_name = $get[1];

        } else {

            $icon_base = $template_location . '/' . $icon_dir;

            $icon_name = $icon;

        }

        $names[] = $icon_name;
        $names[] = $icon_name . '.png';
        $names[] = $icon_name . '.jpg';
        $names[] = $icon_name . '.gif';

        /**
         * find icon
         */
        foreach ($names as $name) {

            if (file_exists(__SITE_PATH . '/' . $icon_base . '/' . $name)) {

                $url = _HOME . '/' . $icon_base . '/' . $name;

                break;
            }
        }

        $_icon_src[$icon] = $url;

        return $url;
    }
}

/**
 * put onlick message to your link
 *
 * @param string $text
 * @return string
 */
if (!function_exists('cfm')) {

    function cfm($text = '')
    {
        return 'onclick="return confirm(\'' . $text . '\')"';
    }
}
/**
 *
 * @param $str
 * @return string
 */
if (!function_exists('scan_smiles')) {

    function scan_smiles($str)
    {
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        static $smiles_cache = array();

        $str = " " . $str . " ";

        if (empty($smiles_cache)) {

            $file = __FILES_PATH . '/systems/cache/smiles.dat';

            if (file_exists($file) && ($smiles = file_get_contents($file)) !== FALSE) {

                $smiles_cache = unserialize($smiles);

                return strtr($str, $smiles_cache);

            } else {

                return $str;
            }
        } else {

            return strtr($str, $smiles_cache);
        }
    }
}


/**
 * Get whole words from string...
 *
 * @param $text
 * @param int $n
 * @return bool|string
 */
if (!function_exists('subwords')) {

    function subwords($text, $n = 10)
    {

        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        $text = trim(preg_replace("/\s+/", " ", $text));

        $word_array = explode(" ", $text);

        if (count($word_array) <= $n)

            return implode(" ", $word_array);

        else {

            $text = '';
            foreach ($word_array as $length => $word) {

                $text .= $word;
                if ($length == $n) break;
                else $text .= " ";
            }
        }
        return $text;
    }
}


/**
 * emove html tag, bbcode tag
 *
 * @param $str
 * @param string $type
 * @return bool|string
 */
if (!function_exists('removeTag')) {

    function removeTag($str, $type = BBCODE_HTML)
    {
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        if ($type == HTML) {
            /**
             * remove html tag
             */
            $str = strip_tags($str);

            return $str;

        } elseif ($type == BBCODE) {

            $bbcode = load_library('bbcode');

            /**
             * remove bbcode tag
             */
            $str = $bbcode->strip($str);

            return $str;
        }

        /**
         * remove html tag
         */
        $str = strip_tags($str);

        $bbcode = load_library('bbcode');

        /**
         * remove bbcode tag
         */
        $str = $bbcode->strip($str);

        return $str;
    }
}


/**
 *
 * @param string $link
 * @param string $link_title
 * @param string $add
 * @return string
 */
if (!function_exists('url')) {

    function url($link = '', $link_title = '', $add = '')
    {
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        if (isset($link)) {

            if (!isset($link_title)) {

                $link_title = $link;
            }

            if (preg_match("/^http/", $link)) {

                $url = $link;

            } elseif (preg_match("~^/~", $link)) {

                $url = _HOME . $link;

            } else {

                if (preg_match('/^(\?|&)/', $link)) {

                    $url = _HOME . $_SERVER['REQUEST_URI'] . $link;

                } else {

                    $url = _HOME . '/' . $link;
                }
            }

            return '<a href="' . $url . '" ' . $add . '>' . $link_title . '</a>';
        }
    }
}


/**
 * convert string to hex
 *
 * @param string $string
 * @return string
 */
if (!function_exists('strToHex')) {

    function strToHex($string = '')
    {
        $hex = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $hex .= dechex(ord($string[$i]));
        }
        return $hex;
    }
}


/**
 * Convert hex to string
 *
 * @param string $hex
 * @return string
 */
if (!function_exists('hexToStr')) {

    function hexToStr($hex = '')
    {
        $string = '';
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $string;
    }
}

if (!function_exists('str_to_time')) {

    function str_to_time($str)
    {
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        $time_arr = explode(' ', $str);
        $time_d = 0;
        $time_h = 0;
        $time_m = 0;
        $time_s = 0;
        foreach ($time_arr as $v) {
            switch (substr($v, -1)) {
                case 'd':
                    $time_d = intval($v);
                    break;
                case 'h':
                    $time_h = intval($v);
                    break;
                case 'm':
                    $time_m = intval($v);
                    break;
                case 's':
                    $time_s = intval($v);
                    break;
            }
        }

        $total = $time_s + $time_m * 60 + $time_h * 60 * 60 + $time_d * 24 * 60 * 60;

        return $total;
    }
}

/**
 * this function will be convert array to json data.
 *
 * @param $array
 * @return bool|string
 */
if (!function_exists('array_to_json')) {

    function array_to_json($array)
    {

        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        if (!is_array($array)) {
            return false;
        }

        if (function_exists('json_encode')) {

            return json_encode($array);

        }

        $associative = count(array_diff(array_keys($array), array_keys(array_keys($array))));

        if ($associative) {

            $construct = array();

            foreach ($array as $key => $value) {

                // We first copy each key/value pair into a staging array,
                // formatting each key and value properly as we go.

                // Format the key:
                if (is_numeric($key)) {
                    $key = "key_$key";
                }
                $key = "'" . addslashes($key) . "'";

                // Format the value:
                if (is_array($value)) {
                    $value = array_to_json($value);
                } else if (!is_numeric($value) || is_string($value)) {
                    $value = "'" . addslashes($value) . "'";
                }

                // Add to staging array:
                $construct[] = "$key: $value";
            }

            // Then we collapse the staging array into the JSON form:
            $result = "{ " . implode(", ", $construct) . " }";

        } else { // If the array is a vector (not associative):

            $construct = array();
            foreach ($array as $value) {

                // Format the value:
                if (is_array($value)) {
                    $value = array_to_json($value);
                } else if (!is_numeric($value) || is_string($value)) {
                    $value = "'" . addslashes($value) . "'";
                }

                // Add to staging array:
                $construct[] = $value;
            }

            // Then we collapse the staging array into the JSON form:
            $result = "[ " . implode(", ", $construct) . " ]";
        }

        return $result;
    }
}


/**
 * Auto make sub directory
 *
 * @return array
 */
if (!function_exists('auto_mkdir')) {

    function auto_mkdir($dir)
    {
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        if (!is_dir($dir)) {

            return false;
        }

        /**
         * Make sure it has a trailing slash
         */
        $dir = rtrim($dir, '/') . '/';
        $today = getdate();
        $subdir = $today['mon'] . '-' . $today['year'];
        $full_dir = $dir . $subdir;

        if (is_dir($full_dir)) {

            return $subdir;
        }

        $ok = @mkdir($full_dir);

        if ($ok) {
            return $subdir;
        }
        return false;
    }
}

/**
 * @param $path
 * @param $mode
 * @return bool
 */
if (!function_exists('changemod')) {

    function changemod($path, $mode)
    {

        if (function_exists('chmod')) {

            return chmod($path, $mode);

        } else {

            set_global_msg('chmod function is disableds. Please turn off safe_mode');

            return false;
        }
    }
}

/**
 * get ext file via file name
 *
 * @param stringn $str
 * @return string
 */
if (!function_exists('get_ext')) {

    function get_ext($str)
    {
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        $ext = end(explode('.', $str));

        return $ext;
    }
}

/**
 * This function will convert the file size
 * from the number format to a format string
 *
 * @param int $a_bytes
 * @return string
 */
if (!function_exists('get_size')) {

    function get_size($a_bytes = 0)
    {
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        $a_bytes = (int)$a_bytes;

        if ($a_bytes < 1024) {
            return $a_bytes . ' B';
        } elseif ($a_bytes < 1048576) {
            return round($a_bytes / 1024, 2) . ' KB';
        } elseif ($a_bytes < 1073741824) {
            return round($a_bytes / 1048576, 2) . ' MB';
        } elseif ($a_bytes < 1099511627776) {
            return round($a_bytes / 1073741824, 2) . ' GB';
        } elseif ($a_bytes < 1125899906842624) {
            return round($a_bytes / 1099511627776, 2) . ' TB';
        } elseif ($a_bytes < 1152921504606846976) {
            return round($a_bytes / 1125899906842624, 2) . ' PB';
        } elseif ($a_bytes < 1180591620717411303424) {
            return round($a_bytes / 1152921504606846976, 2) . ' EB';
        } elseif ($a_bytes < 1208925819614629174706176) {
            return round($a_bytes / 1180591620717411303424, 2) . ' ZB';
        } else {
            return round($a_bytes / 1208925819614629174706176, 2) . ' YB';
        }
    }
}


/**
 * get time dir of file via file path
 *
 * @param stringn $str
 * @return string
 */
if (!function_exists('get_time_dir')) {

    function get_time_dir($str)
    {
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        $list = explode('/', $str);
        $num = count($list);
        return $list[$num - 2];
    }
}


/**
 * get file extension allowed upload
 *
 * @return string
 */
if (!function_exists('show_file_allowed_upload')) {

    function show_file_allowed_upload()
    {
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        $files_allowed = '';
        $spa = ', ';
        $i = 0;

        foreach (sys_config('exts') as $ext) {

            $i++;
            if ($i % 2 == 0) {
                $color = 'red';
            } else {
                $color = 'green';
            }
            if ($i == count(sys_config('exts'))) {
                $spa = '';
            }

            $files_allowed .= '<i style="color:' . $color . '; font-size:10px;">' . $ext . '</i>' . $spa;
        }
        return $files_allowed;
    }
}


/**
 * return online status
 *
 * @param $time
 * @return bool
 */
if (!function_exists('is_online')) {

    function is_online($time = 0)
    {
        global $registry;

        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        if (empty($time)) {

            $time = $registry->user['last_login'];
        }

        $time = (int)$time;

        if ($time > time()) {
            return false;
        }

        $t = hook(_PUBLIC, 'time_hold_online', 180);

        if (time() - $time > $t) {

            return false;
        }

        return true;
    }
}

/**
 *
 * @param string $string
 * @return string
 */
if (!function_exists('br2nl')) {

    function br2nl($string)
    {
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        return preg_replace('/\<br(\s*?)\/?>/i', "\n", $string);
    }
}

/**
 * @param int $len
 * @param string $type
 * @return string
 */
if (!function_exists('rand_str')) {

    function rand_str($len = 5, $type = '')
    {
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        if (empty($type)) $s = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        elseif ($type === 'num') $s = '0123456789';

        mt_srand((double)microtime() * 1000000);

        $unique_id = '';

        for ($i = 0; $i < $len; $i++)
            $unique_id .= substr($s, (mt_rand() % (strlen($s))), 1);

        return $unique_id;
    }
}

/**
 * get current page url
 *
 * @return bool|string
 */
if (!function_exists('curPageURL')) {

    function curPageURL()
    {
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        $pageURL = 'http';
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {

            $pageURL .= "s";
        }

        $pageURL .= "://";

        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }
}


/**
 * @param string $url
 * @param string $msg
 */
if (!function_exists('redirect')) {

    function redirect($url = '', $msg = '')
    {
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        if (empty($url)) {
            $url = _HOME . $_SERVER['REQUEST_URI'];
        }

        if (preg_match('/^http/', $url)) {

            $link = $url;

        } else {

            if (preg_match('~^/~', $url)) {

                $link = _HOME . $url;
            } else {

                if (preg_match('/^(\?|&)/', $url)) $link = _HOME . $_SERVER['REQUEST_URI'] . $url;

                else $link = _HOME . '/' . $url;
            }
        }

        if (!empty($msg)) {

            $_SESSION['msg']['success'] = $msg;

            session_write_close();
        }

        header("Location: $link");

    }
}

if (!function_exists('go_back')) {

    function go_back()
    {

        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        if (isset($_SESSION['last_url'])) {

            if (is_array($_SESSION['last_url'])) {

                $_SESSION['last_url'] = array_unique($_SESSION['last_url']);

                end($_SESSION['last_url']);

                $key = key($_SESSION['last_url']);

                unset($_SESSION['last_url'][$key]);

                $move = end($_SESSION['last_url']);

                if ($move != curPageURL()) {

                    redirect($move);
                }
            }
        }
    }
}

/**
 * @param string $url
 * @param string $msg_request
 * @param int $time_hold
 * @return string
 */
if (!function_exists('wait_redirect')) {

    function wait_redirect($url = '', $msg_request = '', $time_hold = 0)
    {
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        $msg_request = str_replace('{s}', '<b id="time_hold">' . $time_hold . ' giây</b>', $msg_request);

        if (preg_match('/^http/', $url)) {

            $link = $url;

        } else {

            if (preg_match('~^/~', $url)) {

                $link = _HOME . $url;

            } else {

                if (preg_match('/^(\?|&)/', $url)) $link = _HOME . $_SERVER['REQUEST_URI'] . $url;

                else $link = _HOME . '/' . $url;
            }
        }

        $msg_request = $msg_request . '<br/>Nếu trình duyệt không tự chuyển <b><u>' . url($link, 'Click vào đây') . '</u></b> để đến trang đích';
        $msg_request .= '<script language="javascript" type="text/javascript">
<!-- 
var i= ' . $time_hold . ';
function time() {
	if(i >= 0) {
		document.getElementById("time_hold").innerHTML=i+" giây";
		i--;
		setTimeout("time()",1000);
	}
    else
    {
        document.getElementById("time_hold").innerHTML= \'' . icon('loading') . '\';
    }
}
time();
--> 
</script>';

        $time_trans = $time_hold + 2;

        echo '<meta HTTP-EQUIV="REFRESH" content="' . $time_trans . '; url=' . $link . '">';

        return $msg_request;
    }
}


/**
 * reload page with message
 *
 * @param string $msg
 */
if (!function_exists('reload')) {

    function reload($msg = '')
    {
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        redirect('', $msg);
    }
}


/**
 * reload data user
 *
 * @param int $uid
 * @return bool
 */
if (!function_exists('_reload_user_data')) {

    function _reload_user_data($uid = 0)
    {
        global $registry;

        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        if (empty($uid)) {

            $uid = $registry->user['id'];
        }
        return _load_user($uid);
    }
}

/**
 * load the use data
 *
 * @param $uid
 * @return bool
 */
if (!function_exists('_load_user')) {

    function _load_user($uid)
    {
        global $registry;

        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        $query = $registry->db->query("SELECT * FROM " . tb() . "users where id='$uid'");


        if ($registry->db->num_row($query) == 1) {

            $row = $registry->db->fetch_array($query);

            $row = _handle_user_data($row);

            return $registry->db->sqlQuoteRm($row);
        }

        return false;

    }
}

/**
 * update login
 * update time login
 * update last ip login
 *
 * @param $uid
 */
if (!function_exists('_update_login')) {

    function _update_login($uid)
    {
        global $registry;

        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        $registry->db->query("UPDATE " . tb() . "users SET `last_login`='" . time() . "', `last_ip` = '" . client_ip() . "' where `id`='$uid'");
    }
}

/**
 * @param $data
 * @return mixed
 */
if (!function_exists('_handle_user_data')) {

    function _handle_user_data($data)
    {

        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        $u = $data;

        if (isset($data['username'])) {

            $u['uname'] = $data['username'];
        }

        if (isset($data['nickname'])) {

            $u['name'] = $data['nickname'];
        }

        if (isset($data['avatar'])) {

            $u['avatar'] = $data['avatar'] ? $data['avatar'] : tpl_config('default_avatar');

            $u['full_avatar'] = $data['avatar'] ? _URL_FILES_POSTS . '/images/' . $data['avatar'] : _BASE_TEMPLATE_IMG . '/' . tpl_config('default_avatar');

        }

        if (isset($data['password'])) {

            $u['pass'] = $data['password'];
        }

        if (isset($u['birth'])) {

            $list_birth = explode('-', $u['birth']);

            if (count($list_birth) == 3) {

                $u['birth_list']['day'] = $list_birth[0];
                $u['birth_list']['month'] = $list_birth[1];
                $u['birth_list']['year'] = $list_birth[2];

            } else {

                $u['birth_list']['day'] = '';
                $u['birth_list']['month'] = '';
                $u['birth_list']['year'] = '';

            }
        }

        if (isset($u['sex'])) {

            if (empty($u['sex'])) {

                $u['sex'] = 'sex_unknown';
            }
        }

        if (isset($u['security_code'])) {

            $u['security_code'] = @unserialize($u['security_code']);
        }

        if (isset($u['smiles'])) {

            $u['smiles'] = @unserialize($u['smiles']);
        }

        if (empty($u['smiles'])) {

            $u['smiles'] = array();
        }

        return $u;
    }
}

if (!function_exists('_clean_user_data_log')) {

    function _clean_user_data_log()
    {
        global $registry;

        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        if (isset($_SESSION['ss_user_id']))
            unset($_SESSION['ss_user_id']);
        if (isset($_SESSION['ss_zen_token']))
            unset($_SESSION['ss_zen_token']);
        if (isset($_COOKIE['ck_user_id']))
            unset($_COOKIE['ck_user_id']);
        if (isset($_COOKIE['ck_zen_token']))
            unset($_COOKIE['ck_zen_token']);

        unset($registry->user);
    }

}


/**
 * @param $uid
 * @param string $name
 * @return bool
 */
if (!function_exists('still_valid_security_code')) {

    function still_valid_security_code($uid, $name = '')
    {
        global $registry;

        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        if (empty($uid)) {

            return false;
        }

        $user = $registry->model->get()->_get_user_data($uid);

        if (!empty($user)) {

            if (isset($user['security_code'][$name])) {

                if (empty($user['security_code'][$name]['time_expired']) || empty($user['security_code'][$name]['time_start'])) {

                    return true;

                } else {

                    if (time() - $user['security_code'][$name]['time_start'] > $user['security_code'][$name]['time_expired']) {
                        return false;
                    }
                    return true;
                }
            }
            return false;
        }
        return false;
    }
}


/**
 * @param $uid
 * @param $name
 * @param $value
 * @param bool $expired
 * @return bool
 */
if (!function_exists('set_security_code')) {

    function set_security_code($uid, $name, $value, $expired = false)
    {
        global $registry;

        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        if (empty($name) || empty($uid) || empty($value)) {

            return false;
        }

        $user = $registry->model->get()->_get_user_data($uid);

        if (empty($user)) {
            return false;
        }

        $data = $user['security_code'];
        $arr['code'] = $value;
        $arr['time_start'] = time();
        $arr['time_expired'] = str_to_time($expired);
        $data[$name] = $arr;
        $update['security_code'] = serialize($data);

        if ($registry->model->_update_user($uid, $update)) {

            return true;
        }
        return false;

        return false;
    }
}

/**
 * check is device is mobile
 *
 * @return mixed
 */
if (!function_exists('is_mobile')) {

    function is_mobile()
    {
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        /**
         * load DDetect library
         */
        $device = load_library('DDetect');

        return $device->isMobile();
    }
}

/**
 * @param array $tree
 * @param string $mixed
 * @return string
 */
if (!function_exists('display_tree')) {

    function display_tree($tree = array(), $mixed = '')
    {
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        $out = '';

        if (isset($tree)) {

            if (is_array($tree)) {

                $i = 0;

                foreach ($tree as $url) {

                    if ($url != '') {

                        $i++;

                        if ($i != count($tree)) $out .= '<span class="tree" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">' . $url . '</span>' . $mixed;

                        else $out .= '<span class="tree" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">' . $url . '</span>';
                    }
                }
            }
        }

        return $out;
    }
}

/**
 *
 * @param array $tree
 * @param string $mixed
 */
if (!function_exists('display_tree_modulescp')) {

    function display_tree_modulescp($tree = array(), $mixed = '')
    {
        global $registry;

        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        /**
         * load permission library
         */
        $perm = load_library('permission');

        $perm->set_user($registry->user);


        if ($perm->is_super_manager()) {

            $kstree[] = url(_HOME . '/admin', 'Admin CP');
            $kstree[] = url(_HOME . '/admin/general/modulescp', 'Modules cpanel');
        }

        if (!empty($tree)) {

            return (isset($kstree) ? display_tree($kstree, $mixed) . $mixed : '') . display_tree($tree, $mixed);

        }

        return display_tree($kstree, $mixed);
    }
}


/**
 * function send mail user PHPmailer
 *
 * @param bool $to_email
 * @param bool $subject
 * @param bool $content
 * @param bool $altbody
 * @return mixed
 */
if (!function_exists('send_mail')) {

    function send_mail($to_email = false, $subject = false, $content = false, $altbody = false)
    {
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }
        /**
         * load PHPmailer library
         */
        $mailer = load_library('PHPmailer');
        $mailer->IsSMTP();
        $mailer->IsHTML(true);
        $mailer->AddAddress($to_email, $to_email);
        $mailer->Subject = $subject;
        $mailer->Body = $content;
        $mailer->AltBody = $altbody;
        $mailer->WordWrap = 50;

        /**
         * action send mail
         */
        $send = $mailer->Send();

        if (!$send) {

            set_global_msg($mailer->ErrorInfo);

        }
        return $send;
    }
}


/**
 * get mime type of file by file extension
 *
 * @param $ext
 * @param bool $all_type
 * @return string
 */
if (!function_exists('get_mime_type')) {

    function get_mime_type($ext, $all_type = false)
    {

        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {

            return load_function(__FUNCTION__, func_get_args());
        }

        $list = sys_config('mimes');

        $ext = strtolower($ext);

        if (array_key_exists($ext, $list)) {

            if ($all_type == true) {

                return $list[$ext];

            } else {

                if (!is_array($list[$ext])) {

                    return $list[$ext];

                } else {

                    return $list[$ext][0];
                }
            }

        } else {

            return 'application/octet-stream';
        }
    }
}

/**
 * get mime type via file path
 *
 * @param $file
 * @return bool|mixed|string
 */
if (!function_exists('get_file_mime_type')) {

    function get_file_mime_type($file)
    {
        if (function_exists('finfo_file')) {

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $type = finfo_file($finfo, $file);
            finfo_close($finfo);
        } else {

            $type = mime_content_type($file);
        }

        if (!$type || in_array($type, array('application/octet-stream', 'text/plain'))) {
            $secondOpinion = exec('file -b --mime-type ' . escapeshellarg($file), $foo, $returnCode);
            if ($returnCode === 0 && $secondOpinion) {
                $type = $secondOpinion;
            }
        }

        if (!$type || in_array($type, array('application/octet-stream', 'text/plain'))) {

            $exifImageType = exif_imagetype($file);

            if ($exifImageType !== false) {

                $type = image_type_to_mime_type($exifImageType);
            }
        }

        return $type;
    }
}