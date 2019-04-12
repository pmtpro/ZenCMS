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
if (empty($_SESSION['process']['ImportDatabase']) && empty($_SESSION['process']['UpgradeDatabase'])) {
    redirect('/?do=ImportDatabase');
    exit;
}
if (empty($_SESSION['process']['AdminData']) && empty($_SESSION['process']['UpgradeDatabase'])) {
    redirect('/?do=AdminData');
    exit;
}

if ((empty($_SESSION['account_info']['username']) || empty($_SESSION['account_info']['password'])) && empty($_SESSION['process']['UpgradeDatabase'])) {
    redirect('/?do=AdminData');
    exit;
}

$license_file = __SITE_PATH . '/license.txt';

if (file_exists($license_file)) {
    $license = file_get_contents($license_file);
} else {
    $license = "Read more: http://zencms.vn/license";
}
$data['success'] = 'Chú ý: Sau khi cài đặt xong hãy xóa thư mục <code>/install</code> để đảm bảo an toàn';
?>

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            Cài đặt ZenCMS <small>Bước 7</small>
        </h3>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box red">
            <div class="portlet-title"><div class="caption"><i class="fa fa-check"></i> Hoàn thành</div></div>
            <div class="portlet-body form">
                <form class="form-horizontal" method="POST">
                    <div class="form-wizard">
                        <div class="form-body">
                            <?php load_step(7) ?>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">
                                    <h3 class="block text-center">Chúc mừng bạn đã cài đặt thành công ZenCMS</h3>
                                    <?php load_message() ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions text-center">
                            <a href="<?php echo REAL_HOME ?>/admin" class="btn btn-primary pull-right">Trang quản trị <span class="fa fa-arrow-right"></span></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>