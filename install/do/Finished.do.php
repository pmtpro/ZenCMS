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

if (empty($_SESSION['process']['AdminData'])) {

    redirect('install?do=AdminData');
    exit;
}

if (empty($_SESSION['account_info']['username']) || empty($_SESSION['account_info']['password'])) {

    redirect('install?do=AdminData');
    exit;
}

$license_file = __SITE_PATH . '/license.txt';

if (file_exists($license_file)) {

    $license = file_get_contents($license_file);
} else {

    $license = "Read more: http://zencms.vn/license";
}

?>

<div class="detail_content">
    <h1 class="title border_orange">Hoàn thành</h1>

    <h1 class="content" style="text-align: center; padding: 5px; color: red; line-height: 30px; font-size: 150%;">
        Chúc mừng bạn đã cài đặt thành công ZenCMS<br/>
    </h1>

    <div class="content" style="text-align: center">
        <div class="info">
            Thông tin tài khoản:<br/>
            <span class="text_smaller gray">
                - Username: <?php echo $_SESSION['account_info']['username'] ?><br/>
                - Password: <?php echo $_SESSION['account_info']['password'] ?><br/>
                - Password 2: <?php echo $_SESSION['account_info']['password2'] ?>
            </span>
        </div>
        <div class="item">
           <a href="<?php _HOME ?>/home" class="button BgGreen">Tiếp tục</a>
        </div>
    </div>

</div>