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

$license_file = __SITE_PATH . '/license.txt';
if (file_exists($license_file)) {
    $license = file_get_contents($license_file);
} else {
    $license = "Read more: http://zencms.vn/license";
}

$license = nl2br($license);
if (isset($_POST['submit-agree'])) {
    $_SESSION['agree'] = true;
    redirect('install?do=CheckSystem');
} elseif (isset($_POST['submit-not-agree'])) {
    $data['notices'] = 'Để tiếp tục, bạn phải đồng ý với điều khoản của ZenCMS';
}
?>
<div class="row" style="text-align: center;">
    <div class="padded">
        <h1 style="font-size: 30px">Cài đặt ZenCMS</h1>
    </div>
</div>

<div class="login box" style="margin-top: 20px;">
    <div class="box-header">
        <span class="title">Điều khoản sử dụng</span>
    </div>
    <div class="box-content padded">
        <?php load_message() ?>
        <div class="detail_content">
            <blockquote class="license"><?php echo  $license ?></blockquote>
        </div>
    </div>
</div>

<form method="POST" class="separate-sections fill-up">
    <div class="row">
        <div class="col-md-6 col-xs-6">
            <button name="submit-not-agree" type="submit" class="btn btn-default fill-up">Không đồng ý</button>
        </div>
        <div class="col-md-6 col-xs-6">
            <button name="submit-agree" type="submit" class="btn btn-blue pull-right">Tôi đồng ý và tiếp tục <i class="icon-signin"></i></button>
        </div>
    </div>
</form>