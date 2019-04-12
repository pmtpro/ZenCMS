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

if (isset($_POST['submit-import-data'])) {
    $file_content = file(__SQL_FILE);
    if (!$file_content) {
        $data['errors'] = 'Không thể đọc file:<br/><code>' . __SQL_FILE . '</code>';
    } else {
        $query = "";
        $failer = array();
        foreach($file_content as $sql_line){
            if(trim($sql_line) != "" && strpos($sql_line, "--") === false){
                $query .= $sql_line;
                if (substr(rtrim($query), -1) == ';'){
                    $result = $db->query($query, false);
                    if (!$result) {
                        $failer[] = $query;
                    }
                    $query = "";
                }
            }
        }
        if (!empty($failer)) {
            $data['errors'] = 'Không thể hoàn thành. Vui lòng kiểm tra lại database hiện tại';
            $data['notices'] = 'Lỗi: <br/>';
            $e = 0;
            foreach ($failer as $qu) {
                $e++;
                $data['notices'] .= '<b>' . $e . ':</b> <code>' . $qu . '</code><br/><br/>';
            }
        } else {
            $_SESSION['process']['ImportDatabase'] = true;
            redirect('/?do=AdminData');
            exit;
        }
    }
}
?>
<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            Cài đặt ZenCMS <small>Bước 5</small>
        </h3>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box red">
            <div class="portlet-title"><div class="caption"><i class="fa fa-check"></i> Nhập khẩu database</div></div>
            <div class="portlet-body form">
                <form class="form-horizontal" method="POST">
                    <div class="form-wizard">
                        <div class="form-body">
                            <?php load_step(5) ?>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">
                                    <h3 class="block">Nhập khẩu từ file</h3>
                                    <?php load_message() ?>
                                    <div class="alert alert-info">
                                        Hệ thống sẽ nhập khẩu file <b><code><?php echo __SQL_FILE_NAME ?></code></b> vào database!<br/>
                                        Vui lòng chắc chắn database của bạn đang trống.<br/>
                                        Chúng tôi sẽ không chịu trách nhiệm về dữ liệu cũ trong database hiện tại (Nếu có).<br/>
                                        <table>
                                            <tr>
                                                <td>DB HOST</td>
                                                <td style="padding-left: 10px"><code><?php echo __ZEN_DB_HOST ?></code></td>
                                            </tr>
                                            <tr>
                                                <td>DB USERNAME</td>
                                                <td style="padding-left: 10px"><code><?php echo __ZEN_DB_USER ?></code></td>
                                            </tr>
                                            <tr>
                                                <td>DB NAME</td>
                                                <td style="padding-left: 10px"><code><?php echo __ZEN_DB_NAME ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <a href="<?php echo HOME ?>?do=DatabaseInfo" class="btn btn-default pull-left"><span class="fa fa-arrow-left"></span> Trở lại</a>
                            <button name="submit-import-data" type="submit" class="btn btn-primary pull-right">Đồng ý & tiếp tục <span class="fa fa-arrow-right"></span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>