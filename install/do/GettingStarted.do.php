<?php
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');

$license_file = __SITE_PATH . '/license.txt';

if (file_exists($license_file)) {

    $license = file_get_contents($license_file);
} else {

    $license = "Read more: http://zencms.vn/license";
}

$license = nl2br($license);

if (isset($_POST['sub_continous'])) {

    if (!empty($_POST['agree'])) {

        $_SESSION['agree'] = true;

        redirect('install?do=DatabaseInfo');
    } else {

        $data['notices'] = 'Bạn phải đồng ý với các điều khoản của ZenCMS mới có thể tiếp tục';
    }
}
?>

<div class="detail_content">
    <h1 class="title border_blue">Chào mừng bạn đến với ZenCMS</h1>

    <?php load_message() ?>

    <div class="content">
        <h3>Điều khoản sử dụng</h3>
    </div>

    <div class="license">
        <?php echo  $license ?>
    </div>
    <form method="POST">
        <div class="item">
            <label for="agree">
                <input type="checkbox" name="agree" id="agree" value="1"/> Tôi đồng ý với điều khoản của ZenCMS
            </label>
        </div>

        <div class="item" style="text-align: center">
            <input type="submit" name="sub_continous" value="Tiếp tục" class="button BgGreen"/>
        </div>
    </form>
</div>