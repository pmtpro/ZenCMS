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

if (empty($_SESSION['process']['CheckSystem'])) {
    redirect('install?do=CheckSystem');
    exit;
}

$host = 'localhost';
$username = '';
$password = '';
$name = '';

if (file_exists(__DB_FILE_PATH)) {
    if (defined('__ZEN_DB_HOST') && defined('__ZEN_DB_USER') && defined('__ZEN_DB_PASSWORD') && defined('__ZEN_DB_NAME')) {
        if (__ZEN_DB_HOST && __ZEN_DB_USER && __ZEN_DB_NAME) {
            $host = __ZEN_DB_HOST;
            $username = __ZEN_DB_USER;
            $password = __ZEN_DB_PASSWORD;
            $name = __ZEN_DB_NAME;
            $data['notices'] = 'Đã tồn tại thông tin database. Bạn có muốn sử dụng thông tin này?';
        }
    }
}

if (isset ($_POST['submit-connect-db'])) {
    $host = $_POST['host'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];

    if (empty($host) || empty($username) || empty($name)) {
        $data['notices'] = 'Bạn phải nhập đầy đủ thông tin database';
    } else {
        if (@$db->connect(false, $host, $username, $password, $name)) {
            $dbcontent = "<?php if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');
define('__ZEN_DB_HOST', '$host');
define('__ZEN_DB_USER', '$username');
define('__ZEN_DB_PASSWORD', '$password');
define('__ZEN_DB_NAME', '$name');";
            $mode = 0755;
            $chmod1 = @chmod(__SYSTEMS_PATH, $mode);
            $chmod2 = @chmod(__SYSTEMS_INCLUDES_PATH, $mode);
            $chmod3 = @chmod(__SYSTEMS_INCLUDES_CONFIG_PATH, $mode);
            if (file_exists(__DB_FILE_PATH)) {
                @chmod(__DB_FILE_PATH, 0666);
            }
            if (file_put_contents(__DB_FILE_PATH, $dbcontent)) {
                $_SESSION['process']['DatabaseInfo'] = true;
                redirect('install?do=' . (!empty($_GET['next'])? htmlspecialchars($_GET['next']) : 'ImportDatabase'));
            } else {
                $data['notices'] = 'Vui lòng chmod các file, thư mục sau:<br/><code>' .
                    __SYSTEMS_PATH . ' - 0755</code><br/><code>' .
                    __SYSTEMS_INCLUDES_PATH . ' - 0755</code><br/><code>' .
                    __SYSTEMS_INCLUDES_CONFIG_PATH . ' - 0755</code><br/><code>' .
                    __DB_FILE_PATH . ' - 0666</code><br/>';
                if (file_exists(__DB_FILE_PATH)) {
                    $data['notices'] .= '<code>' . __DB_FILE_PATH . '</code>';
                }
            }
        } else {
            $data['errors'] = $db->error_msg_name . ': ' . $db->error_msg_content;
        }
    }
}
?>

<div class="row" style="text-align: center;">
    <div class="padded">
        <h1 style="font-size: 30px">B1: Cài đặt Database</h1>
    </div>
</div>

<div class="login box" style="margin-top: 20px;">
    <div class="box-header">
        <span class="title">Thông tin database</span>
    </div>
    <div class="box-content padded">
        <?php load_message() ?>
        <form class="separate-sections form-horizontal fill-up validatable" method="POST">
            <div class="form-group">
                <label class="control-label col-lg-4">Database Host</label>
                <div class="col-lg-8"><input type="text" name="host" value="<?php echo $host ?>" placeholder="localhost"/></div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-4">Database Username</label>
                <div class="col-lg-8"><input type="text" name="username" value="<?php echo $username ?>" placeholder="DB Username"/></div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-4">Database Password</label>
                <div class="col-lg-8"><input type="password" name="password" placeholder="DB Password"/></div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-4">Database Name</label>
                <div class="col-lg-8"><input type="text" name="name" placeholder="DB Name" value="<?php echo $name ?>"/></div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-4"></label>
                <div class="col-lg-8"><button type="submit" name="submit-connect-db" class="btn btn-blue">Cài đặt</button></div>
            </div>
        </form>
    </div>
</div>