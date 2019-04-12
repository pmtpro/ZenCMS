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

$license_file = __SITE_PATH . '/license.txt';
if (file_exists($license_file)) {
    $license = file_get_contents($license_file);
} else {
    $license = "Read more: http://zencms.vn/license";
}

$license = nl2br($license);
if (isset($_POST['submit-agree'])) {
    $_SESSION['agree'] = true;
    redirect('/?do=CheckSystem');
} elseif (isset($_POST['submit-not-agree'])) {
    $data['notices'] = 'Để tiếp tục, bạn phải đồng ý với điều khoản của ZenCMS';
}
?>
<style>
    .license {
        overflow-y: scroll;
        height: 300px;
        padding: 15px;
        border: 1px solid #ddd;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            Cài đặt ZenCMS <small>Bước 1</small>
        </h3>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box red">
            <div class="portlet-title"><div class="caption"><i class="fa fa-legal"></i> Điều khoản sử dụng</div></div>
            <div class="portlet-body form">
                <form class="form-horizontal" method="POST">
                    <div class="form-wizard">
                        <div class="form-body">
                            <?php load_step(1) ?>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">
                                    <h3 class="block">Điều khoản sử dụng</h3>
                                    <?php load_message() ?>
                                    <div class="license">
                                        <?php echo  $license ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button name="submit-not-agree" type="submit" class="btn btn-default pull-left">Không đồng ý</button>
                            <button name="submit-agree" type="submit" class="btn btn-primary pull-right">Tôi đồng ý và tiếp tục <span class="fa fa-arrow-right"></span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>