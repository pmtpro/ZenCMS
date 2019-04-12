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

/**
 * @param $check_url
 * @param string $curURL
 * @return bool
 */
if (!function_exists('is_child_page')) {

    function is_child_page($check_url, $curURL = '') {
        if (empty($curURL)) {
            $curURL = getRouterUrl();
        }
        $curURL = trim($curURL, '/');
        $curURL = $curURL . '/';
        $check_url = trim($check_url, '/');
        $check_url = $check_url . '/';
        $pos = strpos($curURL, $check_url);
        if (is_numeric($pos) && $pos == 0) {
            return true;
        }
        return false;
    }
}


/**
 * put onlick message to your link
 *
 * @param string $text
 * @return string
 */
if (!function_exists('cfm')) {
    function cfm($text = '') {
        return 'onclick="return confirm(\'' . $text . '\')"';
    }
}

/**
 * get list smile
 * @return array
 */
if (!function_exists('list_smile')) {

    function list_smile() {
        static $static_function;
        if (isset($static_function['list_smile'])) {
            return $static_function['list_smile'];
        }
        $out = array();
        $file =  __FILES_PATH . '/systems/images/smiles/smiles.dat';
        if (file_exists($file) && ($smiles = file_get_contents($file)) !== FALSE) {
            $smiles_array = unserialize($smiles);
            $out = $smiles_array;
        }
        $static_function['list_smile'] = $out;
        return $out;
    }
}
/**
 *
 * @param $str
 * @return string
 */
if (!function_exists('replace_smile')) {

    function parse_smile($str)
    {
        $smiles_array = list_smile();
        $pattern = '';
        foreach($smiles_array as $key => $img) {
            $found_pos = strpos($str, $key);
            if ($found_pos !== false) {
                $pattern .= ($pattern === ''? '/(?:':'|') . '(?:^|\s|\]|>)(' . preg_quote($key, '/') . ')(?:$|\s|\[|<)';
            }
        }
        $pattern .= $pattern !== '' ? ')/' : '';
        if (empty($pattern)) return $str;
        $str = preg_replace_callback($pattern, function($match) use ($smiles_array) {
            $key = end($match);
            if (isset($smiles_array[$key])) return str_replace($key, '<img src="' . $smiles_array[$key]['full_url'] . '" title="' . $key . '" class="zen-smile"/>', $match[0]);
            else return $match[0];
        }, $str);

        $str = preg_replace_callback($pattern, function($match) use ($smiles_array) {
            $key = end($match);
            if (isset($smiles_array[$key])) return str_replace($key, '<img src="' . $smiles_array[$key]['full_url'] . '" title="' . $key . '" class="zen-smile"/>', $match[0]);
            else return $match[0];
        }, $str);
        return $str;
    }
}

/**
 * @param string $nick
 * @param string $perm
 * @return string
 */
if (!function_exists('display_nick')) {

    function display_nick($nick, $perm) {
        global $zen;
        if (isset($zen['config']['user_perm']['color'][$perm])) {
            return '<span style="color: ' . $zen['config']['user_perm']['color'][$perm] . '">' . $nick . '</span>';
        }
        return $nick;
    }
}
/**
 * Get whole words from string...
 *
 * @param $text
 * @param int $n
 * @return bool|string
 */
if (!function_exists('subWords')) {

    function subWords($text, $n = 10)
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
        } else {
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
}

/**
 * @param string $url
 * @param string $arg
 * @return string
 */
if (!function_exists('add_arg2url')) {

    function add_arg2url($url, $arg) {
        $query = parse_url($url, PHP_URL_QUERY);
        if( $query ) $url .= '&' . $arg;
        else $url .= '?' . $arg;
        $url = modQueryUrl($url, $arg);
        return $url;
    }
}

/**
 * mod argument of a url
 * @param string $url eg: http://localhost/index.php?a=1&b=2&c=3
 * @param string|array $arg eg: a=2&b=3 or array('a'=>2, 'b'=>3)
 * @return string eg: http://localhost/index.php?a=2&b=3&c=3
 */
if (!function_exists('modQueryUrl')) {

    function modQueryUrl($url, $arg) {
        /**
         * if $arg is string, parse it and convert to array
         */
        if (!is_array($arg)) {
            parse_str($arg, $argList);
        } else $argList = $arg;
        $parse = parse_url($url);
        $query = $parse['query'];
        parse_str($query, $arr);
        /**
         * merge old arg list and new arg list
         */
        $merge_arg = array_merge($arr, $argList);
        /**
         * convert array (arg) to query string
         */
        $parse['query'] = http_build_query($merge_arg, '', '&');
        /**
         * reverse parse url.
         * convert array (from parse_url) to url
         */
        $hashUrl = explode('?', $url);
        if (isset($hashUrl[1])) {
            $base = $hashUrl[0];
        } else $base = '';
        return $base . '?' . $parse['query'];
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

/**
 * this function will convert text to time. Sample: 2d => 2*24*60*60s
 * @param $str
 * @return bool|int
 */
if (!function_exists('textToTime')) {

    function textToTime($str)
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
if (!function_exists('arrayToJson')) {

    function arrayToJson($array)
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
                    $value = arrayToJson($value);
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
                    $value = arrayToJson($value);
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
 * This function will auto create sub directory in the $dir path
 * @param string $dirPath
 * @param bool|string $dirName
 * @return bool|string
 */
if (!function_exists('autoMkSubDir')) {

    function autoMkSubDir($dirPath, $dirName = false)
    {
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {
            return load_function(__FUNCTION__, func_get_args());
        }
        if (!is_dir($dirPath)) return false;

        /**
         * Make sure it has a trailing slash
         */
        $dirPath = rtrim($dirPath, '/') . '/';
        if (empty($dirName)) {
            $today = getdate();
            $subDir = $today['mon'] . '-' . $today['year'];
        } else {
            $subDir = $dirName;
        }
        $full_dir = $dirPath . $subDir;
        if (is_dir($full_dir)) return $subDir;
        $ok = mkdir($full_dir);
        if ($ok) return $subDir;
        return false;
    }
}

/**
 * @param $path
 * @param $mode
 * @return bool
 */
if (!function_exists('changeMod')) {

    function changeMod($path, $mode)
    {
        if (function_exists('chmod')) {
            return chmod($path, $mode);
        } else {
            set_global_msg('Chmod function is disabled. Please turn off safe_mode');
            return false;
        }
    }
}

/**
 * get file extension from file name
 * @param string $str
 * @return string
 */
if (!function_exists('getExt')) {

    function getExt($str) {
        $ext = end(explode('.', $str));
        return $ext;
    }
}

/**
 * This function will convert the file size
 * from the number format to a string format
 * @param int $a_bytes
 * @return string
 */
if (!function_exists('size2text')) {

    function size2text($a_bytes = 0) {
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
 * convert break tag to new line
 * @param string $string
 * @return string
 */
if (!function_exists('br2nl')) {

    function br2nl($string) {
        return preg_replace('/\<br(\s*?)\/?>/i', "\n", $string);
    }
}

/**
 * @param int $len
 * @param string $type
 * @return string
 */
if (!function_exists('randStr')) {

    function randStr($len = 5, $type = '')
    {
        /**
         * allow replace this function
         */
        if (check_function_replace(__FUNCTION__)) {
            return load_function(__FUNCTION__, func_get_args());
        }

        if ($type === 'num') $s = '0123456789';
        elseif ($type === 'upper') $s = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        elseif ($type === 'lower') $s = 'abcdefghijklmnopqrstuvwxyz';
        elseif ($type === 'text')  $s = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        else $s = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

        mt_srand((double)microtime() * 1000000);
        $unique_id = '';
        for ($i = 0; $i < $len; $i++)
            $unique_id .= substr($s, (mt_rand() % (strlen($s))), 1);
        return $unique_id;
    }
}

/**
 * redirect to a url
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
            $url = HOME . $_SERVER['REQUEST_URI'];
        }
        if (preg_match('/^http/', $url)) {
            $link = $url;
        } else {
            if (preg_match('~^/~', $url)) {
                $link = HOME . $url;
            } else {
                if (preg_match('/^(\?|&)/', $url)) $link = HOME . $_SERVER['REQUEST_URI'] . $url;
                else $link = HOME . '/' . $url;
            }
        }
        if (!empty($msg)) {
            $_SESSION['msg']['success'] = $msg;
            session_write_close();
        }
        header("Location: $link");
        exit;
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
        $msg_request = sprintf($msg_request, '<b id="time_hold">' . $time_hold . ' giây</b>', $msg_request);
        if (preg_match('/^http/', $url)) {
            $link = $url;
        } else {
            if (preg_match('~^/~', $url)) {
                $link = HOME . $url;
            } else {
                if (preg_match('/^(\?|&)/', $url)) $link = HOME . $_SERVER['REQUEST_URI'] . $url;
                else $link = HOME . '/' . $url;
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
 * @param string $msg
 */
if (!function_exists('reload')) {

    function reload($msg = '') {
        redirect('', $msg);
    }
}

if (!function_exists('contentOf')) {
    /**
     * get display content of a file
     * @param $file
     * @return string
     */
    function contentOf($file) {
        /**
         * start ob
         */
        ob_start();
        /**
         * include file
         */
        include $file;
        /**
         * get cache content
         */
        $content = ob_get_contents();
        /**
         * clean content displayed
         */
        ob_end_clean();
        return $content;
    }
}

/**
 * @param $function
 * @return bool
 */
if (!function_exists('is_func')) {

    function is_func($function) {
        if($function instanceof Closure){
            return true;
        }
        return false;
    }
}


/**
 * get mime type of file by file extension
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
        $list = sysConfig('mimes');
        $ext = strtolower($ext);
        if (array_key_exists($ext, $list)) {
            if ($all_type == true) {
                return $list[$ext];
            } else {
                if (!is_array($list[$ext])) {
                    return $list[$ext];
                } else return $list[$ext][0];
            }
        } else return 'application/octet-stream';
    }
}

/**
 * get mime type via file path
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
        } else $type = mime_content_type($file);

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
