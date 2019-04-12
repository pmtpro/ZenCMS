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
if (empty($_SESSION['process']['DatabaseInfo'])) {
    redirect('install?do=DatabaseInfo');
    exit;
}
if (empty($_SESSION['process']['ImportDatabase'])) {
    redirect('install?do=ImportDatabase');
    exit;
}
if (defined('__ZEN_DB_HOST') && defined('__ZEN_DB_USER') && defined('__ZEN_DB_PASSWORD') && defined('__ZEN_DB_NAME')) {
    if (!__ZEN_DB_HOST || !__ZEN_DB_NAME) {
        redirect('install?do=DatabaseInfo');
        exit;
    }
    if (!$db->connect(__ZEN_DB_HOST, __ZEN_DB_USER, __ZEN_DB_PASSWORD, __ZEN_DB_NAME, false)) {
        redirect('install?do=DatabaseInfo');
        exit;
    }
} else {
    redirect('install?do=DatabaseInfo');
    exit;
}

$db->query("DELETE FROM `zen_cms_users`");
$_SESSION['account_info']['username'] = '';
$_SESSION['account_info']['password'] = '';
$home = HOME;
$title = '';
$keyword = '';
$des = '';
$username = '';
$email = '';
$password = '';
$repassword = '';
$password_private = '';
if (isset($_POST['submit-next'])) {
    $home = htmlspecialchars(trim($_POST['home'], '/'));
    $title = htmlspecialchars($_POST['title']);
    $keyword = htmlspecialchars($_POST['keyword']);
    $des = htmlspecialchars($_POST['des']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];
    $password_private = $_POST['password_private'];

    if (empty($home) || empty ($username) || empty($email) || empty($password) || empty($repassword) || empty($password_private)) {
        $data['notices'] = 'Bạn phải nhập đầy đủ thông tin';
    } else {
        if (!preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $home)) {
            $data['notices'] = 'Địa chỉ trang chủ không chính xác';
        } else {
            update_key('home', $home);
            if (empty($title)) {
                $title = 'ZenCMS - Web Developers';
            }
            update_key('title', $title);
            update_key('keyword', $keyword);
            update_key('des', $des);
            if (!preg_match('/^([a-zA-Z0-9\-_]+)$/is', $_POST['username'])) {
                $data['notices'] = 'Định dạng tài khoản không đúng';
            } else {
                if (strlen($_POST['username']) < 4) {
                    $data['notices'] = 'Tên tài khoản quá ngắn (ít nhất 4 kí tự)';
                } else {
                    if (empty($_POST['email']) || !preg_match('/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/i', $_POST['email'])) {
                        $data['notices'] = 'Định dạng email không chính xác';
                    } else {
                        if ($password != $repassword) {
                            $data['notices'] = 'Không khớp mật khẩu';
                        } else {
                            if (strlen($password) < 2) {
                                $data['notices'] = 'Mật khẩu quá ngắn (ít nhất 2 kí tự)';
                            } else {
                                $insert['nickname'] = $username;
                                $insert['username'] = strtolower($username);
                                $insert['email'] = $email;
                                $insert['password'] = md5(md5($password));
                                if (!$db->query("INSERT INTO `zen_cms_users` SET
                                    `username` = '" . $insert['username'] . "',
                                    `email` = '" . $insert['email'] . "',
                                    `nickname` = '" . $insert['nickname'] . "',
                                    `password` = '" . $insert['password'] . "',
                                    `time_reg` = '" . time() . "',
                                    `protect` = '1',
                                    `perm` = 'admin'")
                                ) {
                                    $data['errors'] = 'Không thể tạo người dùng này';
                                } else {
                                    $workid = rand(1000000, 9999999);
                                    $verify = md5(md5($password_private));
                                    $content_private = "<?php
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');
define('ZEN_WORKID', $workid);
define('ZEN_VERITY_ACCESS', '$verify');//$password_private";
                                    $mode = 0755;
                                    $chmod1 = @chmod(__SYSTEMS_PATH, $mode);
                                    $chmod2 = @chmod(__SYSTEMS_INCLUDES_PATH, $mode);
                                    $chmod3 = @chmod(__SYSTEMS_INCLUDES_CONFIG_PATH, $mode);
                                    if (file_exists(__PRIVATE_FILE_PATH)) {
                                        @chmod(__PRIVATE_FILE_PATH, 0666);
                                    }
                                    @file_put_contents(__PRIVATE_FILE_PATH, $content_private);
                                    $_SESSION['process']['AdminData'] = true;
                                    $_SESSION['account_info']['username'] = $username;
                                    $_SESSION['account_info']['password'] = $password;
                                    $_SESSION['account_info']['password2'] = $password_private;
                                    redirect('install?do=Finished');
                                    exit;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
?>

<div class="row" style="text-align: center;">
    <div class="padded">
        <h1 style="font-size: 30px">Bước 3: Cấu hình trang & tài khoản</h1>
    </div>
</div>

<div class="login box" style="margin-top: 20px;">
    <div class="box-header">
        <span class="title">Thông tin</span>
    </div>
    <div class="box-content padded">
        <?php load_message() ?>
        <form class="separate-sections form-horizontal fill-up validatable" method="POST">
            <div class="form-group">
                <label class="control-label col-lg-4">Địa chỉ trang chủ</label>
                <div class="col-lg-8"><input type="text" name="home" value="<?php echo $home ?>" placeholder="http://"/></div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-4">Tiêu đề trang</label>
                <div class="col-lg-8">
                    <input type="text" name="title" value="<?php echo $title ?>" placeholder="Tiêu đề trang"/>
                    <div class="note pull-right">Có thể sửa sau</div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-4">Keyword</label>
                <div class="col-lg-8">
                    <textarea name="keyword" placeholder="Keyword"><?php echo $keyword ?></textarea>
                    <div class="note pull-right">Có thể sửa sau</div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-4">Mô tả</label>
                <div class="col-lg-8">
                    <textarea name="des" placeholder="Mô tả trang"><?php echo $des ?></textarea>
                    <div class="note pull-right">Có thể sửa sau</div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-4">Tên tài khoản</label>
                <div class="col-lg-8">
                    <input type="text" name="username" value="<?php echo $username ?>" placeholder="Tài khoản"/>
                    <div class="note pull-right">Chỉ bao gồm: a-z, 0-9, và các kí tự -_</div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-4">Email</label>
                <div class="col-lg-8">
                    <input type="text" name="email" value="<?php echo $email ?>" placeholder="Email"/>
                    <div class="note pull-right">Mail dùng để khôi phục tài khoản</div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-4">Mật khẩu</label>
                <div class="col-lg-8"><input type="password" name="password" placeholder="Mật khẩu"/></div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-4">Nhập lại mật khẩu</label>
                <div class="col-lg-8"><input type="password" name="repassword" placeholder="Nhập lại mật khẩu"/></div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-4">Mật khẩu cấp 2</label>
                <div class="col-lg-8">
                    <input type="password" name="password_private" placeholder="Mật khẩu cấp 2" value="<?php echo $password_private ?>"/>
                    <div class="note pull-right">Mật khẩu dùng để truy cập các mục hệ thống</div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-4"></label>
                <div class="col-lg-8"><button type="submit" name="submit-next" class="btn btn-blue">Tiếp tục <i class="icon-signin"></i></button></div>
            </div>
        </form>
    </div>
</div>