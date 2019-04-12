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
function gd_exists() {
    if (extension_loaded('gd') && function_exists('gd_info')) {
        return true;
    }
    else {
        return false;
    }
}
function check_php_version() {
    if (phpversion() >= 5.3) {
        return true;
    } else return false;
}
$list_check = array(
    'php_version' => array(
        'name' => 'PHP 5.3',
        'desc' => 'ZenCMS chạy trên nền PHP 5.3 hoặc cao hơn',
        'function' => 'check_php_version'
    ),
    'gd_exists' => array(
        'name' => 'Hỗ trợ GD library',
        'desc' => 'Để ZenCMS hoạt động tốt, hosting phải hỗ trợ GD library',
        'function' => 'gd_exists'
    )
);

if (isset($_POST['next'])) {
    if ($_SESSION['checking_system_ok']) {
        $_SESSION['process']['CheckSystem'] = true;
        redirect('install?do=InstallOption');
        exit;
    }
}
if (isset($_POST['back'])) {
    $_SESSION['checking_system_ok'] = false;
    redirect('install?do=GettingStarted');
    exit;
}

$_SESSION['checking_system_ok'] = true;
?>
<div class="row" style="text-align: center;">
    <div class="padded">
        <h1 style="font-size: 30px">Kiểm tra hệ thống</h1>
    </div>
</div>

<div class="login box" style="margin-top: 20px;">
    <div class="box-header">
        <span class="title">Kiểm tra hệ thống</span>
    </div>
    <div class="box-content scrollable">
        <?php load_message() ?>
        <?php foreach ($list_check as $item): ?>
            <div class="box-section news with-icons">
                <?php if ($item['function']()): ?>
                    <div class="avatar green">
                        <i class="icon-ok icon-2x"></i>
                    </div>
                <?php else: ?>
                    <?php $_SESSION['checking_system_ok'] = false; ?>
                    <div class="avatar purple">
                        <i class="icon-ban-circle icon-2x"></i>
                    </div>
                <?php endif ?>
                <div class="news-content">
                    <div class="news-title"><?php echo $item['name'] ?></div>
                    <div class="news-text"><?php echo $item['desc'] ?></div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>
<form method="POST" class="separate-sections fill-up">
    <div class="row">
        <div class="col-md-6 col-xs-6">
            <button name="back" type="submit" class="btn btn-default fill-up"><i class="icon-arrow-left"></i> Trở lại</button>
        </div>
        <div class="col-md-6 col-xs-6">
            <button name="next" type="submit" class="btn btn-blue pull-right <?php if (!$_SESSION['checking_system_ok']):?>disabled<?php endif ?>">Tiếp theo <i class="icon-signin"></i></button>
        </div>
    </div>
</form>