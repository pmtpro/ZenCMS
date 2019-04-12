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
/**
 * define home url
 */
define('HOME', getHttpHost());
/**
 * define real home url
 */
define('REAL_HOME', real_home());

define('ZENCMS_VERSION', '6.0.0');

function update_key($key, $value) {
    global $db;
    $query = $db->query("SELECT * FROM `zen_cms_config` WHERE `key`='$key'");
    if (!$db->num_row($query)) {
        if (!$db->query("INSERT INTO `zen_cms_config` SET `key`='$key'")) {
            return false;
        }
    }
    return $db->query("UPDATE `zen_cms_config` SET `value`='$value' WHERE `key`='$key'");
}

function workDir() {
    $request = trim(dirname($_SERVER["PHP_SELF"]),'/');
    $request = trim($request,"\\");
    if (strpos($request, '/') !== false) {
        $hash_request = explode('/', $request);
        /**
         * move pointer to last element
         */
        end($hash_request);
        /**
         * get las key of $hash_home
         */
        $last_key = key($hash_request);
        unset($hash_request[$last_key]);
        return implode('/', $hash_request);
    } else return '';
}

function getHttpHost() {
    static $_static_function;
    if (isset($_static_function['getHttpHost'])) {
        return $_static_function['getHttpHost'];
    }
    $scheme = (isset($_SERVER['HTTPS']) && ($_SERVER['SERVER_PORT'] == '443')) ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $request = trim(dirname($_SERVER["PHP_SELF"]),'/');
    $request = trim($request,"\\");
    $home = sprintf('%s://%s/%s', $scheme, $host, $request);
    $home = rtrim($home, '/');
    $_static_function['getHttpHost'] = $home;
    return $home;
}

function real_home() {
    static $_static_function;
    if (isset($_static_function['real_home'])) {
        return $_static_function['real_home'];
    }
    $currHome = getHttpHost();
    $hash_home = explode('/', $currHome);
    if (count($hash_home) != 3) {
        /**
         * move pointer to last element
         */
        end($hash_home);
        /**
         * get las key of $hash_home
         */
        $last_key = key($hash_home);
        unset($hash_home[$last_key]);
        $home = rtrim(implode('/', $hash_home), '/');
    } else $home = $currHome;
    $_static_function['real_home'] = $home;
    return $home;
}


function load_message() {
    global $data;
    if (isset ($data['errors'])) {
        echo '<div class="alert alert-danger">
  <button type="button" class="close" data-dismiss="alert">×</button>
  <strong>Lỗi:</strong> ' . $data['errors'] . '
</div>';
    }
    if (isset ($data['notices'])) {
        echo '<div class="alert alert-warning">
  <button type="button" class="close" data-dismiss="alert">×</button>
  <strong>Chú ý:</strong> ' . $data['notices'] . '
</div>';
    }
    if (isset ($data['success'])) {
        echo '<div class="alert alert-success">
  <button type="button" class="close" data-dismiss="alert">×</button>
  ' . $data['success'] . '
</div>';
    }
}

function load_step($active = 1) {
    echo '<ul class="nav nav-pills nav-justified steps">
    <li ' . ($active == 1 ?  'class="active"' : ($active > 1 ? 'class="done"' : '')) . '>
        <a href="#tab1" data-toggle="tab" class="step">
                    <span class="number">
                    1 </span>
                    <span class="desc">
                    <i class="fa fa-check"></i> Điều khoản sử dụng </span>
        </a>
    </li>
    <li ' . ($active == 2 ?  'class="active"' : ($active > 2 ? 'class="done"' : '')) . '>
        <a href="#tab2" data-toggle="tab" class="step">
                    <span class="number">
                    2 </span>
                    <span class="desc">
                    <i class="fa fa-check"></i> Kiểm tra hệ thống </span>
        </a>
    </li>
    <li ' . ($active == 3 ?  'class="active"' : ($active > 3 ? 'class="done"' : '')) . '>
        <a href="#tab3" data-toggle="tab" class="step">
                    <span class="number">
                    3 </span>
                    <span class="desc">
                    <i class="fa fa-check"></i> Lựa chọn cài đặt </span>
        </a>
    </li>
    <li ' . ($active == 4 ?  'class="active"' : ($active > 4 ? 'class="done"' : '')) . '>
        <a href="#tab4" data-toggle="tab" class="step">
                    <span class="number">
                    4 </span>
                    <span class="desc">
                    <i class="fa fa-check"></i> Cài đặt Database </span>
        </a>
    </li>
    <li ' . ($active == 5 ?  'class="active"' : ($active > 5 ? 'class="done"' : '')) . '>
        <a href="#tab5" data-toggle="tab" class="step">
                    <span class="number">
                    5 </span>
                    <span class="desc">
                    <i class="fa fa-check"></i> Tùy chỉnh database </span>
        </a>
    </li>
    <li ' . ($active == 6 ?  'class="active"' : ($active > 6 ? 'class="done"' : '')) . '>
        <a href="#tab5" data-toggle="tab" class="step">
                    <span class="number">
                    6 </span>
                    <span class="desc">
                    <i class="fa fa-check"></i> Cấu hình chính </span>
        </a>
    </li>
    <li ' . ($active == 7 ?  'class="active"' : ($active > 7 ? 'class="done"' : '')) . '>
        <a href="#tab6" data-toggle="tab" class="step">
                    <span class="number">
                    7 </span>
                    <span class="desc">
                    <i class="fa fa-check"></i> Hoàn thành </span>
        </a>
    </li>
</ul>';
}

if (!function_exists('redirect')) {
    function redirect($url = '', $msg = '')
    {
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
    }
}


function remove_comments(&$output)
{
    $lines = explode("\n", $output);
    $output = "";

    // try to keep mem. use down
    $linecount = count($lines);

    $in_comment = false;
    for($i = 0; $i < $linecount; $i++)
    {
        if( preg_match("/^\/\*/", preg_quote($lines[$i])) )
        {
            $in_comment = true;
        }

        if( !$in_comment )
        {
            $output .= $lines[$i] . "\n";
        }

        if( preg_match("/\*\/$/", preg_quote($lines[$i])) )
        {
            $in_comment = false;
        }
    }

    unset($lines);
    return $output;
}

//
// remove_remarks will strip the sql comment lines out of an uploaded sql file
//
function remove_remarks($sql)
{
    $lines = explode("\n", $sql);

    // try to keep mem. use down
    $sql = "";

    $linecount = count($lines);
    $output = "";

    for ($i = 0; $i < $linecount; $i++)
    {
        if (($i != ($linecount - 1)) || (strlen($lines[$i]) > 0))
        {
            if (isset($lines[$i][0]) && $lines[$i][0] != "#")
            {
                $output .= $lines[$i] . "\n";
            }
            else
            {
                $output .= "\n";
            }
            // Trading a bit of speed for lower mem. use here.
            $lines[$i] = "";
        }
    }

    return $output;

}

//
// split_sql_file will split an uploaded sql file into single sql statements.
// Note: expects trim() to have already been run on $sql.
//
function split_sql_file($sql, $delimiter)
{
    // Split up our string into "possible" SQL statements.
    $tokens = explode($delimiter, $sql);

    // try to save mem.
    $sql = "";
    $output = array();

    // we don't actually care about the matches preg gives us.
    $matches = array();

    // this is faster than calling count($oktens) every time thru the loop.
    $token_count = count($tokens);
    for ($i = 0; $i < $token_count; $i++)
    {
        // Don't wanna add an empty string as the last thing in the array.
        if (($i != ($token_count - 1)) || (strlen($tokens[$i] > 0)))
        {
            // This is the total number of single quotes in the token.
            $total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
            // Counts single quotes that are preceded by an odd number of backslashes,
            // which means they're escaped quotes.
            $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);

            $unescaped_quotes = $total_quotes - $escaped_quotes;

            // If the number of unescaped quotes is even, then the delimiter did NOT occur inside a string literal.
            if (($unescaped_quotes % 2) == 0)
            {
                // It's a complete sql statement.
                $output[] = $tokens[$i];
                // save memory.
                $tokens[$i] = "";
            }
            else
            {
                // incomplete sql statement. keep adding tokens until we have a complete one.
                // $temp will hold what we have so far.
                $temp = $tokens[$i] . $delimiter;
                // save memory..
                $tokens[$i] = "";

                // Do we have a complete statement yet?
                $complete_stmt = false;

                for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); $j++)
                {
                    // This is the total number of single quotes in the token.
                    $total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
                    // Counts single quotes that are preceded by an odd number of backslashes,
                    // which means they're escaped quotes.
                    $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);

                    $unescaped_quotes = $total_quotes - $escaped_quotes;

                    if (($unescaped_quotes % 2) == 1)
                    {
                        // odd number of unescaped quotes. In combination with the previous incomplete
                        // statement(s), we now have a complete statement. (2 odds always make an even)
                        $output[] = $temp . $tokens[$j];

                        // save memory.
                        $tokens[$j] = "";
                        $temp = "";

                        // exit the loop.
                        $complete_stmt = true;
                        // make sure the outer loop continues at the right point.
                        $i = $j;
                    }
                    else
                    {
                        // even number of unescaped quotes. We still don't have a complete statement.
                        // (1 odd and 1 even always make an odd)
                        $temp .= $tokens[$j] . $delimiter;
                        // save memory.
                        $tokens[$j] = "";
                    }

                } // for..
            } // else
        }
    }

    return $output;
}