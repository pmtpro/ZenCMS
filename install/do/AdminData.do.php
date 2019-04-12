<?php
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');

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

$home = _HOME;
$username = '';
$password = '';
$repassword = '';
$password_private = '';

if (isset($_POST['sub'])) {

    $home = $_POST['home'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];
    $password_private = $_POST['password_private'];

    if (empty($home) || empty ($username) || empty($password) || empty($repassword) || empty($password_private)) {

        $data['notices'] = 'Bạn phải nhập đầy đủ thông tin';

    } else {

        if (!preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $home)) {

            $data['notices'] = 'Địa chỉ trang chủ không chính xác';

        } else {

            $db->query("UPDATE `zen_cms_config` SET `value` = '$home' where `key` = 'home'");

            if (!preg_match('/^([a-zA-Z0-9\-_]+)$/is', $username)) {

                $data['notices'] = 'Định dạng tài khoản không đúng';

            } else {

                if (strlen($username) < 4) {

                    $data['notices'] = 'Tên tào khoản quá ngắn (ít nhất 4 kí tự)';
                } else {

                    if ($password != $repassword) {

                        $data['notices'] = 'Không khớp mật khẩu';

                    } else {

                        if (strlen($password) < 2) {

                            $data['notices'] = 'Mật khẩu quá ngắn (ít nhất 2 kí tự)';
                        } else {

                            $insert['nickname'] = $username;
                            $insert['username'] = strtolower($username);
                            $insert['password'] = md5(md5($password));

                            if (!$db->query("INSERT INTO `zen_cms_users` SET
                                    `username` = '" . $insert['username'] . "',
                                    `nickname` = '" . $insert['nickname'] . "',
                                    `password` = '" . $insert['password'] . "',
                                    `time_reg` = '" . time() . "',
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
?>

<div class="detail_content">
    <h1 class="title border_blue">Bước 3: Cài đặt</h1>

    <?php load_message() ?>

    <div class="tip">
        Hãy điền địa chỉ trang chủ, thông tin tài khoản của bạn vào đây. <br/>
        Đây là tài khoản dùng để truy cập các trang quản lí của bạn
    </div>

    <form method="POST">
        <div class="item">
            Địa chỉ trang chủ<br/>
            <input type="text" name="home" value="<?php echo $home ?>"/>
        </div>

        <div class="item">
            Tên tài khoản <span class="text_smaller gray">(a-z, 0-9, và các kí tự -_)</span><br/>
            <input type="text" name="username" value="<?php echo $username ?>"/>
        </div>
        <div class="item">
            Mật khẩu<br/>
            <input type="password" name="password" value="<?php echo $password ?>"/>
        </div>
        <div class="item">
            Nhập lại mật khẩu<br/>
            <input type="password" name="repassword" value="<?php echo $repassword ?>"/>
        </div>

        <div class="item">
            Mật khẩu cấp 2
            <div class="tip">
                Mật khẩu này dùng để truy cập vào các mục hệ thống
            </div>
            <input type="password" name="password_private" value="<?php echo $password_private ?>"/>
        </div>

        <div class="item">
            <input type="submit" name="sub" value="Tiếp tục" class="button BgGreen"/>
        </div>
    </form>
</div>