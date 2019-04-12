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
function config_htaccess() {
    $workDir = workDir();
    $htaccess_line = array();
    $default_htaccess = __SITE_PATH . '/files/systems/default/.htaccess.default';
    $htaccess_path = __SITE_PATH . '/.htaccess';
    if (!file_exists($htaccess_path)) file_put_contents($htaccess_path, '');
    if (file_exists($default_htaccess) && is_readable($default_htaccess) && is_writeable($htaccess_path)) {
        $handle = fopen($default_htaccess, "r");
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                if (preg_match('/^RewriteBase/i', $buffer)) {
                    $buffer = 'RewriteBase ' . (!$workDir ? '/' : '/' . $workDir . '/') . "\n";
                }
                $htaccess_line[] = $buffer;
            }
            fclose($handle);
        }
        $content = implode($htaccess_line, "");
        if (trim($content)) {
            return file_put_contents($htaccess_path, $content);
        } else return false;
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
    ),
    'rewrite_base' => array(
        'name' => 'Hoạt động trên thư mục con',
        'desc' => 'ZenCMS chạy trên mọi vị trí thư mục',
        'function' => 'config_htaccess'
    )
);

if (isset($_POST['next'])) {
    if ($_SESSION['checking_system_ok']) {
        $_SESSION['process']['CheckSystem'] = true;
        redirect('/?do=InstallOption');
        exit;
    }
}
if (isset($_POST['back'])) {
    $_SESSION['checking_system_ok'] = false;
    redirect('/?do=GettingStarted');
    exit;
}

$_SESSION['checking_system_ok'] = true;
?>

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            Cài đặt ZenCMS <small>Bước 2</small>
        </h3>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box red">
            <div class="portlet-title"><div class="caption"><i class="fa fa-check"></i> Kiểm tra hệ thống</div></div>
            <div class="portlet-body form">
                <form class="form-horizontal" method="POST">
                    <div class="form-wizard">
                        <div class="form-body">
                            <?php load_step(2) ?>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">
                                    <h3 class="block">Kiểm tra hệ thống</h3>
                                    <?php load_message() ?>
                                    <?php foreach ($list_check as $item): ?>
                                        <div class="form-group">
                                            <label class="control-label col-lg-3"><?php echo $item['name'] ?></label>
                                            <p class="form-control-static col-lg-9">
                                                <?php if ($item['function']()): ?>
                                                    <span class="fa fa-check" style="color: #45b6af"></span>
                                                <?php else: ?>
                                                    <?php $_SESSION['checking_system_ok'] = false; ?>
                                                    <span class="fa fa-times" style="color: #a94442"></span>
                                                <?php endif ?>
                                                <?php echo $item['desc'] ?>
                                            </p>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button name="back" type="submit" class="btn btn-default pull-left"><span class="fa fa-arrow-left"></span> Trở lại</button>
                            <button name="next" type="submit" class="btn btn-primary pull-right <?php if (!$_SESSION['checking_system_ok']):?>disabled<?php endif ?>">Tiếp theo <span class="fa fa-arrow-right"></span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>