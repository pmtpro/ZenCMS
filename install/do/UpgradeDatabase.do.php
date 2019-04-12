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

if ($_SESSION['process']['UpgradeDatabase'] == true && isset($_POST['next'])) {
    redirect('/?do=Finished');
    exit;
}

function db_check_column_is_exists($table, $colum)
{
    global $db;
    $exists = false;
    $columns = $db->query("show columns from $table");
    while ($c = $db->fetch_assoc($columns)) {
        if ($c['Field'] == $colum) {
            $exists = true;
            break;
        }
    }
    return $exists;
}

/**
 * reset
 */
if (isset($_POST['submit-reset']))  {
    unset($_SESSION['upgradeProcess']);
    unset($_SESSION['process']['UpgradeDatabase']);
}

$upgrade = array(
    array(
        'name' => 'zen_cms_config',
        'desc' => 'Thay đổi kiểu dữ liệu cột `value` từ varchar sang text',
        'sql'  => 'ALTER TABLE `zen_cms_config` MODIFY `value` TEXT',
        'function' => function() use ($db) {
                if (!db_check_column_is_exists('zen_cms_config', 'value')) return false;
                return $db->query('ALTER TABLE `zen_cms_config` MODIFY `value` TEXT');
            },
        'upgrade' => !empty($_SESSION['upgradeProcess'][0]) ? true:false
    ),
    array(
        'name' => 'zen_cms_widgets',
        'desc' => 'Thêm cột `callback` VARCHAR(100)',
        'sql'  => 'ALTER TABLE  `zen_cms_widgets` ADD  `callback` VARCHAR( 100 ) NOT NULL',
        'function' => function() use ($db) {
                if (db_check_column_is_exists('zen_cms_widgets', 'callback')) return true;
                return $db->query('ALTER TABLE  `zen_cms_widgets` ADD  `callback` VARCHAR( 100 ) NOT NULL');
            },
        'upgrade' => !empty($_SESSION['upgradeProcess'][1]) ? true:false
    ),
    array(
        'name' => 'zen_cms_blogs',
        'desc' => 'Thêm cột `time_update` INT',
        'sql'  => 'ALTER TABLE  `zen_cms_blogs` ADD  `time_update` INT NOT NULL AFTER  `time`',
        'function' => function() use ($db) {
                if (db_check_column_is_exists('zen_cms_blogs', 'time_update')) return true;
                return $db->query('ALTER TABLE  `zen_cms_blogs` ADD  `time_update` INT NOT NULL AFTER  `time`');
            },
        'upgrade' => !empty($_SESSION['upgradeProcess'][2]) ? true:false
    ),
    array(
        'name' => 'zen_cms_blogs',
        'desc' => 'Cập nhật dữ liệu cột `time_update`',
        'sql'  => 'UPDATE `zen_cms_blogs` SET `time_update` = `time`',
        'function' => function() use ($db) {
                if (!db_check_column_is_exists('zen_cms_blogs', 'time_update')) return false;
                return $db->query('UPDATE `zen_cms_blogs` SET `time_update` = `time`');
            },
        'upgrade' => !empty($_SESSION['upgradeProcess'][3]) ? true:false
    ),
    array(
        'name' => 'zen_cms_blogs',
        'desc' => 'Thêm cột `attach` INT',
        'sql'  => "ALTER TABLE  `zen_cms_blogs` ADD  `attach` INT NOT NULL DEFAULT  '0' AFTER  `view`",
        'function' => function() use ($db) {
                if (db_check_column_is_exists('zen_cms_blogs', 'attach')) return true;
                return $db->query("ALTER TABLE  `zen_cms_blogs` ADD  `attach` INT NOT NULL DEFAULT  '0' AFTER  `view`");
            },
        'upgrade' => !empty($_SESSION['upgradeProcess'][4]) ? true:false
    ),
    array(
        'name' => 'zen_cms_blogs',
        'desc' => 'Cập nhật dữ liệu cột `attach`',
        'sql'  => "",
        'function' => function() use ($db) {
                if (!db_check_column_is_exists('zen_cms_blogs', 'attach')) return false;
                $sql = "SELECT `id` FROM `zen_cms_blogs`";
                $query = $db->query($sql);
                while ($row = $db->fetch_array($query)) {
                    $bid = $row['id'];
                    $linkQuery = $db->query("SELECT `id` FROM `zen_cms_blogs_links` WHERE `sid` = '$bid'");
                    $fileQuery = $db->query("SELECT `id` FROM `zen_cms_blogs_files` WHERE `sid` = '$bid'");
                    if ($db->num_row($linkQuery) || $db->num_row($fileQuery)) {
                        $db->query("UPDATE `zen_cms_blogs` SET `attach` = '1' WHERE `id` = '$bid'");
                    }
                }
                return true;
            },
        'upgrade' => !empty($_SESSION['upgradeProcess'][5]) ? true:false
    ),
);

$countSuccess = 0;
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
            <div class="portlet-title"><div class="caption"><i class="fa fa-check"></i> Nâng cấp database</div></div>
            <div class="portlet-body form">
                <form class="form-horizontal" method="POST">
                    <div class="form-wizard">
                        <div class="form-body">
                            <?php load_step(5) ?>
                            <div class="tab-content">
                                <?php load_message() ?>
                                <div class="tab-pane active" id="tab1">
                                    <table class="table table-bordered table-striped">
                                        <thead><tr><td>Bảng</td><td>Mô tả</td><td>Câu lệnh</td><td>Kết quả</td></tr></thead>
                                        <?php foreach ($upgrade as $key => $item): ?>
                                            <tr>
                                                <td>
                                                    <?php echo $item['name'] ?>
                                                </td>
                                                <td>
                                                    <?php echo $item['desc'] ?>
                                                </td>
                                                <td>
                                                    <!-- Button trigger modal -->
                                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#sql-<?php echo $key ?>">Xem</button>
                                                    <div class="modal fade" id="sql-<?php echo $key ?>" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Đóng</span></button>
                                                                    <h4 class="modal-title">Câu lệnh</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p><?php echo $item['sql'] ?></p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                                                                </div>
                                                            </div><!-- /.modal-content -->
                                                        </div><!-- /.modal-dialog -->
                                                    </div><!-- /.modal -->
                                                </td>
                                                <td>
                                                    <?php if (isset($_POST['submit-upgrade']) && empty($_SESSION['process']['UpgradeDatabase'])) { ?>
                                                        <?php if (empty($_SESSION['upgradeProcess'][$key])): ?>
                                                            <?php if (call_user_func($item['function'])): ?>
                                                                <?php $countSuccess++ ?>
                                                                <?php $_SESSION['upgradeProcess'][$key] = true?>
                                                                <label class="label label-success">Thành công</label>
                                                            <?php else: ?>
                                                                <label class="label label-danger">Thất bại</label>
                                                            <?php endif ?>
                                                        <?php else: ?>
                                                            <label class="label label-success">Thành công</label>
                                                        <?php endif ?>
                                                    <?php } elseif ($_SESSION['process']['UpgradeDatabase'] == true) { ?>
                                                        <label class="label label-success">Thành công</label>
                                                    <?php } else { ?>
                                                        <?php if (empty($_SESSION['upgradeProcess'][$key])): ?>
                                                            <label class="label label-default">Chưa làm gì</label>
                                                        <?php else: ?>
                                                            <label class="label label-success">Thành công</label>
                                                        <?php endif ?>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                        <?php
                                        if ($countSuccess && $countSuccess == count($_SESSION['upgradeProcess'])) {
                                            $_SESSION['process']['UpgradeDatabase'] = true;
                                        }
                                        ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <a href="<?php echo HOME ?>?do=DatabaseInfo&next=UpgradeDatabase" class="btn btn-default pull-left"><span class="fa fa-arrow-left"></span> Trở lại</a>&nbsp;
                            <button name="submit-reset" type="submit" class="btn btn-default">Làm lại <i class="fa fa-refresh"></i></button>
                            <?php if ($_SESSION['process']['UpgradeDatabase'] == true): ?>
                                <button name="next" type="submit" class="btn btn-primary pull-right">Tiếp tục <i class="fa fa-arrow-right"></i></button>
                            <?php else: ?>
                                <button name="submit-upgrade" type="submit" class="btn btn-primary pull-right">Bắt đầu nâng cấp <i class="fa fa-retweet"></i></button>
                            <?php endif ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>