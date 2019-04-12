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

if (empty($_SESSION['process']['CheckSystem'])) {
    redirect('/?do=CheckSystem');
    exit;
}
if (empty($_SESSION['process']['DatabaseInfo'])) {
    redirect('/?do=DatabaseInfo');
    exit;
}
if (empty($_SESSION['process']['ImportDatabase'])) {
    redirect('/?do=ImportDatabase');
    exit;
}
if (defined('__ZEN_DB_HOST') && defined('__ZEN_DB_USER') && defined('__ZEN_DB_PASSWORD') && defined('__ZEN_DB_NAME')) {
    if (!__ZEN_DB_HOST || !__ZEN_DB_NAME) {
        redirect('/?do=DatabaseInfo');
        exit;
    }
    if (!$db->connect(__ZEN_DB_HOST, __ZEN_DB_USER, __ZEN_DB_PASSWORD, __ZEN_DB_NAME, false)) {
        redirect('/?do=DatabaseInfo');
        exit;
    }
} else {
    redirect('/?do=DatabaseInfo');
    exit;
}

$db->query("DELETE FROM `zen_cms_users`");
$_SESSION['account_info']['username'] = '';
$_SESSION['account_info']['password'] = '';

$home = REAL_HOME;
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
                                    redirect('/?do=Finished');
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

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            Cài đặt ZenCMS <small>Bước 6</small>
        </h3>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box red">
            <div class="portlet-title"><div class="caption"><i class="fa fa-cogs"></i> Cấu hình trang & tài khoản</div></div>
            <div class="portlet-body form">
                <form class="form-horizontal" method="POST">
                    <div class="form-wizard">
                        <div class="form-body">
                            <?php load_step(6) ?>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">
                                    <?php load_message() ?>
                                    <div class="row">
                                        <h3 class="block col-lg-6 col-lg-offset-3">Cấu hình trang</h3>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-3">Địa chỉ trang chủ</label>
                                        <div class="col-lg-6">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-link"></i>
                                                </span>
                                                <input type="text" name="home" value="<?php echo $home ?>" placeholder="http://" class="form-control"/>
                                            </div>
                                            <div class="help-block pull-right">Không có dấu / ở sau</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-3">Tiêu đề trang</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="title" value="<?php echo $title ?>" placeholder="Tiêu đề trang" class="form-control"/>
                                            <div class="help-block pull-right">Có thể sửa sau</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-3">Keyword</label>
                                        <div class="col-lg-6">
                                            <textarea name="keyword" placeholder="Keyword" class="form-control"><?php echo $keyword ?></textarea>
                                            <div class="help-block pull-right">Có thể sửa sau</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-3">Mô tả</label>
                                        <div class="col-lg-6">
                                            <textarea name="des" placeholder="Mô tả trang" class="form-control"><?php echo $des ?></textarea>
                                            <div class="help-block pull-right">Có thể sửa sau</div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <h3 class="block col-lg-6 col-lg-offset-3">Cấu hình tài khoản</h3>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-3">Tên tài khoản</label>
                                        <div class="col-lg-6">
                                            <div class="input-group">
                                                <span class="input-group-addon">
												    <i class="fa fa-user"></i>
												</span>
                                                <input type="text" name="username" value="<?php echo $username ?>" placeholder="Tài khoản" class="form-control"/>
                                            </div>
                                            <div class="help-block pull-right">Chỉ bao gồm: a-z, 0-9, và các kí tự -_</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-3">Email</label>
                                        <div class="col-lg-6">
                                            <div class="input-group">
                                                <span class="input-group-addon">
												    <i class="fa fa-envelope"></i>
												</span>
                                                <input type="text" name="email" value="<?php echo $email ?>" placeholder="Email" class="form-control"/>
                                            </div>
                                            <div class="help-block pull-right">Mail dùng để khôi phục tài khoản</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-3">Mật khẩu</label>
                                        <div class="col-lg-6">
                                            <div class="input-group">
                                                <span class="input-group-addon">
												    <i class="fa fa-key"></i>
												</span>
                                                <input type="password" name="password" placeholder="Mật khẩu" class="form-control"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-3">Nhập lại mật khẩu</label>
                                        <div class="col-lg-6">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-key"></i>
                                                </span>
                                                <input type="password" name="repassword" placeholder="Nhập lại mật khẩu" class="form-control"/>
                                            </div>
                                            <div class="help-block pull-right">Mật khẩu để truy cập vào Admin CP</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-3">Mật khẩu cấp 2</label>
                                        <div class="col-lg-6">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-key"></i>
                                                </span>
                                                <input type="password" name="password_private" placeholder="Mật khẩu cấp 2" value="<?php echo $password_private ?>" class="form-control"/>
                                            </div>
                                            <div class="help-block pull-right">Mật khẩu dùng để truy cập các mục hệ thống</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-lg-6 col-lg-offset-3">
                                    <button name="submit-next" type="submit" class="btn btn-primary">Lưu và tiếp tục <span class="fa fa-arrow-right"></span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>