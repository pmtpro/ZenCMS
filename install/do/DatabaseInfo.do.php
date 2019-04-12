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

$host = 'localhost';
$username = '';
$password = '';
$name = '';

if (file_exists(__DB_FILE_PATH)) {
    if (defined('__ZEN_DB_HOST') && defined('__ZEN_DB_USER') && defined('__ZEN_DB_PASSWORD') && defined('__ZEN_DB_NAME')) {
        if (__ZEN_DB_HOST && __ZEN_DB_USER && __ZEN_DB_NAME) {
            $host = __ZEN_DB_HOST;
            $username = __ZEN_DB_USER;
            $password = __ZEN_DB_PASSWORD;
            $name = __ZEN_DB_NAME;
            $data['notices'] = 'Đã tồn tại thông tin database. Bạn có muốn sử dụng thông tin này?';
        }
    }
}

if (isset ($_POST['submit-connect-db'])) {
    $host = $_POST['host'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];

    if (empty($host) || empty($username) || empty($name)) {
        $data['notices'] = 'Bạn phải nhập đầy đủ thông tin database';
    } else {
        if (@$db->connect(false, $host, $username, $password, $name)) {
            $dbcontent = "<?php if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');
define('__ZEN_DB_HOST', '$host');
define('__ZEN_DB_USER', '$username');
define('__ZEN_DB_PASSWORD', '$password');
define('__ZEN_DB_NAME', '$name');";
            $mode = 0755;
            $chmod1 = @chmod(__SYSTEMS_PATH, $mode);
            $chmod2 = @chmod(__SYSTEMS_INCLUDES_PATH, $mode);
            $chmod3 = @chmod(__SYSTEMS_INCLUDES_CONFIG_PATH, $mode);
            if (file_exists(__DB_FILE_PATH)) {
                @chmod(__DB_FILE_PATH, 0666);
            }
            if (file_put_contents(__DB_FILE_PATH, $dbcontent)) {
                $_SESSION['process']['DatabaseInfo'] = true;
                redirect('/?do=' . (!empty($_GET['next'])? htmlspecialchars($_GET['next']) : 'ImportDatabase'));
            } else {
                $data['notices'] = 'Vui lòng chmod các file, thư mục sau:<br/><code>' .
                    __SYSTEMS_PATH . ' - 0755</code><br/><code>' .
                    __SYSTEMS_INCLUDES_PATH . ' - 0755</code><br/><code>' .
                    __SYSTEMS_INCLUDES_CONFIG_PATH . ' - 0755</code><br/><code>' .
                    __DB_FILE_PATH . ' - 0666</code><br/>';
                if (file_exists(__DB_FILE_PATH)) {
                    $data['notices'] .= '<code>' . __DB_FILE_PATH . '</code>';
                }
            }
        } else {
            $data['errors'] = $db->error_msg_name . ': ' . $db->error_msg_content;
        }
    }
}
?>

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            Cài đặt ZenCMS <small>Bước 4</small>
        </h3>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box red">
            <div class="portlet-title"><div class="caption"><i class="fa fa-check"></i> Cài đặt Database</div></div>
            <div class="portlet-body form">
                <form class="form-horizontal" method="POST">
                    <div class="form-wizard">
                        <div class="form-body">
                            <?php load_step(4) ?>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">
                                    <h3 class="block">Thông tin Database</h3>
                                    <?php load_message() ?>
                                    <div class="form-group">
                                        <label class="control-label col-lg-3">Database Host</label>
                                        <div class="col-lg-9"><input type="text" name="host" value="<?php echo $host ?>" placeholder="localhost" class="form-control"/></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-3">Database Username</label>
                                        <div class="col-lg-9"><input type="text" name="username" value="<?php echo $username ?>" placeholder="DB Username" class="form-control"/></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-3">Database Password</label>
                                        <div class="col-lg-9"><input type="password" name="password" placeholder="DB Password" class="form-control"/></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-3">Database Name</label>
                                        <div class="col-lg-9"><input type="text" name="name" placeholder="DB Name" value="<?php echo $name ?>" class="form-control"/></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <a href="<?php echo HOME ?>?do=InstallOption" class="btn btn-default pull-left"><span class="fa fa-arrow-left"></span> Trở lại</a>
                            <button name="submit-connect-db" type="submit" class="btn btn-primary pull-right">Cài đặt & tiếp tục <span class="fa fa-arrow-right"></span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
