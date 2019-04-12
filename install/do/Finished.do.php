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
if (empty($_SESSION['process']['ImportDatabase']) && empty($_SESSION['process']['UpgradeDatabase'])) {
    redirect('install?do=ImportDatabase');
    exit;
}
if (empty($_SESSION['process']['AdminData']) && empty($_SESSION['process']['UpgradeDatabase'])) {
    redirect('install?do=AdminData');
    exit;
}

if ((empty($_SESSION['account_info']['username']) || empty($_SESSION['account_info']['password'])) && empty($_SESSION['process']['UpgradeDatabase'])) {
    redirect('install?do=AdminData');
    exit;
}

$license_file = __SITE_PATH . '/license.txt';

if (file_exists($license_file)) {
    $license = file_get_contents($license_file);
} else {
    $license = "Read more: http://zencms.vn/license";
}
$data['success'] = 'Chú ý: Sau khi cài đặt xong hãy xóa thư mục <code>/install</code> để đảm bảo an toàn';
/**
<span class="text_smaller gray">
    - Username: <?php echo $_SESSION['account_info']['username'] ?><br/>
    - Password: <?php echo $_SESSION['account_info']['password'] ?><br/>
    - Password 2: <?php echo $_SESSION['account_info']['password2'] ?>
</span>
 */
?>
<div class="row" style="text-align: center;">
    <div class="padded">
        <h1 style="font-size: 30px">Chúc mừng bạn đã cài đặt thành công ZenCMS</h1>
    </div>
</div>
<div class="login box" style="margin-top: 20px;">
    <div class="box-content padded" style="text-align: center">
        <?php load_message() ?>
        <div class="row">
            <a href="<?php echo HOME ?>/home" class="btn btn-blue">Tiếp tục <i class="icon-signin"></i></a>
        </div>
    </div>
</div>