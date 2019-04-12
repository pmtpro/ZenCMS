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

if ($_SESSION['process']['UpgradeDatabase'] == true && isset($_POST['next'])) {
    redirect('install?do=Finished');
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
        'desc' => 'Thêm cột `func_import` VARCHAR( 50 )',
        'function' => function() use ($db) {
                if (db_check_column_is_exists('zen_cms_config', 'func_import')) return true;
                return $db->query('ALTER TABLE  `zen_cms_config` ADD  `func_import` VARCHAR( 50 ) NOT NULL');
            },
        'upgrade' => !empty($_SESSION['upgradeProcess'][0]) ? true:false
    ),
    array(
        'name' => 'zen_cms_config',
        'desc' => 'Thêm cột `func_export` VARCHAR( 50 )',
        'function' => function() use ($db) {
                if (db_check_column_is_exists('zen_cms_config', 'func_export')) return true;
                return $db->query('ALTER TABLE  `zen_cms_config` ADD  `func_export` VARCHAR( 50 ) NOT NULL');
            },
        'upgrade' => !empty($_SESSION['upgradeProcess'][1]) ? true:false
    ),
    array(
        'name' => 'zen_cms_config',
        'desc' => 'Thêm cột `locate` VARCHAR( 50 )',
        'function' => function() use ($db) {
                if (db_check_column_is_exists('zen_cms_config', 'locate')) return true;
                return $db->query('ALTER TABLE  `zen_cms_config` ADD  `locate` VARCHAR( 50 ) NOT NULL');
            },
        'upgrade' => !empty($_SESSION['upgradeProcess'][2]) ? true:false
    ),
    array(
        'name' => 'zen_cms_config',
        'desc' => 'Thêm cột `for` VARCHAR( 20 )',
        'function' => function() use ($db) {
                if (db_check_column_is_exists('zen_cms_config', 'for')) return true;
                return $db->query('ALTER TABLE  `zen_cms_config` ADD  `for` VARCHAR( 20 ) NOT NULL');
            },
        'upgrade' => !empty($_SESSION['upgradeProcess'][3]) ? true:false
    ),
    array(
        'name' => 'zen_cms_config',
        'desc' => 'Xóa key thừa và giữ lại: home, title, keyword, des, email, mail_host, mail_port, mail_smtp_secure, mail_smtp_auth, mail_setfrom, mail_username, mail_password, mail_name',
        'function' => function() use ($db) {
                return $db->query("DELETE FROM  `zen_cms_config` WHERE `key`!='home' AND `key`!='title' AND `key`!='keyword' AND `key`!='des' AND `key`!='email' AND `key`!='mail_host' AND `key`!='mail_port' AND `key`!='mail_smtp_secure' AND `key`!='mail_smtp_auth' AND `key`!='mail_setfrom' AND `key`!='mail_username' AND `key`!='mail_password' AND `key`!='mail_name'");
            },
        'upgrade' => !empty($_SESSION['upgradeProcess'][4]) ? true:false
    ),
    array(
        'name' => 'zen_cms_widgets',
        'desc' => 'Thêm cột `template` VARCHAR( 100 )',
        'function' => function() use ($db) {
                if (db_check_column_is_exists('zen_cms_widgets', 'template')) return true;
                return $db->query('ALTER TABLE  `zen_cms_widgets` ADD  `template` VARCHAR( 100 ) NOT NULL');
            },
        'upgrade' => !empty($_SESSION['upgradeProcess'][5]) ? true:false
    ),
    array(
        'name' => 'zen_cms_users',
        'desc' => 'Thay đổi kiểu dữ liệu cột `birth` từ VARCHAR(20) sang INT',
        'function' => function() use ($db) {
                return $db->query('ALTER TABLE `zen_cms_users` MODIFY `birth` INTEGER');
            },
        'upgrade' => !empty($_SESSION['upgradeProcess'][6]) ? true:false
    ),
    array(
        'name' => 'zen_cms_users',
        'desc' => 'Thêm cột `protect` INT',
        'function' => function() use ($db) {
                if (db_check_column_is_exists('zen_cms_users', 'protect')) return true;
                return $db->query('ALTER TABLE  `zen_cms_users` ADD  `protect` INT NOT NULL');
            },
        'upgrade' => !empty($_SESSION['upgradeProcess'][7]) ? true:false
    ),
    array(
        'name' => 'zen_cms_users_set',
        'desc' => 'Thêm bảng `zen_cms_users_set`',
        'sql' => 'CREATE TABLE IF NOT EXISTS `zen_cms_users_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `key` varchar(50) NOT NULL,
  `value` varchar(255) NOT NULL,
  `func_import` varchar(50) NOT NULL,
  `func_export` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;',
        'function' => function() use ($db) {
                return $db->query('CREATE TABLE IF NOT EXISTS `zen_cms_users_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `key` varchar(50) NOT NULL,
  `value` varchar(255) NOT NULL,
  `func_import` varchar(50) NOT NULL,
  `func_export` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;');
            },
        'upgrade' => !empty($_SESSION['upgradeProcess'][8]) ? true:false
    ),
    array(
        'name' => 'zen_cms_blogs',
        'desc' => 'Xóa cột `type_url`',
        'function' => function() use ($db) {
                if (!db_check_column_is_exists('zen_cms_blogs', 'type_url')) return true;
                return $db->query('ALTER TABLE `zen_cms_blogs` DROP COLUMN `type_url`');
            },
        'upgrade' => !empty($_SESSION['upgradeProcess'][8]) ? true:false
    ),
    array(
        'name' => 'zen_cms_blogs',
        'desc' => 'Xóa cột `type_title`',
        'function' => function() use ($db) {
                if (!db_check_column_is_exists('zen_cms_blogs', 'type_title')) return true;
                return $db->query('ALTER TABLE `zen_cms_blogs` DROP COLUMN `type_title`');
            },
        'upgrade' => !empty($_SESSION['upgradeProcess'][9]) ? true:false
    ),
    array(
        'name' => 'zen_cms_blogs',
        'desc' => 'Xóa cột `type_view`',
        'function' => function() use ($db) {
                if (!db_check_column_is_exists('zen_cms_blogs', 'type_view')) return true;
                return $db->query('ALTER TABLE `zen_cms_blogs` DROP COLUMN `type_view`');
            },
        'upgrade' => !empty($_SESSION['upgradeProcess'][10]) ? true:false
    ),
    array(
        'name' => 'zen_cms_blogs',
        'desc' => 'Xóa cột `font`',
        'function' => function() use ($db) {
                if (!db_check_column_is_exists('zen_cms_blogs', 'font')) return true;
                return $db->query('ALTER TABLE `zen_cms_blogs` DROP COLUMN `font`');
            },
        'upgrade' => !empty($_SESSION['upgradeProcess'][11]) ? true:false
    ),
    array(
        'name' => 'zen_cms_blogs',
        'desc' => 'Xóa cột `color`',
        'function' => function() use ($db) {
                if (!db_check_column_is_exists('zen_cms_blogs', 'color')) return true;
                return $db->query('ALTER TABLE `zen_cms_blogs` DROP COLUMN `color`');
            },
        'upgrade' => !empty($_SESSION['upgradeProcess'][12]) ? true:false
    ),
    array(
        'name' => 'zen_cms_blogs',
        'desc' => 'Đổi tên cột `recycle_bin` thành `status`',
        'function' => function() use ($db) {
                if (db_check_column_is_exists('zen_cms_blogs', 'status')) return true;
                return $db->query('ALTER TABLE `zen_cms_blogs` CHANGE `recycle_bin` `status` INT');
            },
        'upgrade' => !empty($_SESSION['upgradeProcess'][13]) ? true:false
    ),
    array(
        'name' => 'zen_cms_blogs',
        'desc' => 'Cập nhật lại trạng thái bài viết (chuyển tất cả bài có status khác 0 sang 2)',
        'function' => function() use ($db) {
                if (!db_check_column_is_exists('zen_cms_blogs', 'status')) return false;
                return $db->query("UPDATE `zen_cms_blogs` SET `status` = '2' WHERE `status` != '0'");
            },
        'upgrade' => !empty($_SESSION['upgradeProcess'][14]) ? true:false
    ),
    array(
        'name' => 'zen_cms_mobigate',
        'desc' => 'Thêm bảng `zen_cms_mobigate`',
        'function' => function() use ($db) {
                return $db->query("CREATE TABLE IF NOT EXISTS `zen_cms_mobigate` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `appid` text NOT NULL,
  `stt` int(100) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
            },
        'upgrade' => !empty($_SESSION['upgradeProcess'][15]) ? true:false
    ),
);

$countSuccess = 0;
?>

<div class="row" style="text-align: center;">
    <div class="padded">
        <h1 style="font-size: 30px">Nâng cấp database</h1>
    </div>
</div>

<div class="login box" style="margin-top: 20px;">
    <div class="box-header">
        <span class="title">Nâng cấp database</span>
    </div>
    <div class="box-content scrollable">
        <?php load_message() ?>
        <?php foreach ($upgrade as $key => $item): ?>
            <div class="box-section news with-icons">
                <?php if (isset($_POST['submit-upgrade']) && empty($_SESSION['process']['UpgradeDatabase'])) { ?>
                    <?php if (empty($_SESSION['upgradeProcess'][$key])): ?>
                        <?php if (call_user_func($item['function'])): ?>
                            <?php $countSuccess++ ?>
                            <?php $_SESSION['upgradeProcess'][$key] = true?>
                            <div class="avatar green">
                                <i class="icon-ok icon-2x"></i>
                            </div>
                        <?php else: ?>
                            <div class="avatar purple">
                                <i class="icon-ban-circle icon-2x"></i>
                            </div>
                        <?php endif ?>
                     <?php else: ?>
                        <div class="avatar green">
                            <i class="icon-ok icon-2x"></i>
                        </div>
                     <?php endif ?>
                <?php } elseif ($_SESSION['process']['UpgradeDatabase'] == true) { ?>
                    <div class="avatar green">
                        <i class="icon-ok icon-2x"></i>
                    </div>
                <?php } else { ?>
                    <?php if (empty($_SESSION['upgradeProcess'][$key])): ?>
                        <div class="avatar purple">
                            <i class="icon-question-sign"></i>
                        </div>
                    <?php else: ?>
                        <div class="avatar green">
                            <i class="icon-ok icon-2x"></i>
                        </div>
                    <?php endif ?>
                <?php } ?>
                <div class="news-content">
                    <div class="news-title"><?php echo $item['name'] ?></div>
                    <div class="news-text"><?php echo $item['desc'] ?></div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>

<?php if ($countSuccess == count($upgrade)) $_SESSION['process']['UpgradeDatabase'] = true;?>

<form method="POST" class="separate-sections fill-up">
    <div class="row">
        <div class="col-md-6 col-xs-6">
            <a href="<?php echo HOME ?>/install?do=DatabaseInfo&next=UpgradeDatabase" class="btn btn-default fill-up"><i class="icon-arrow-left"></i> Trở lại</a>
            <button name="submit-reset" type="submit" class="btn btn-default">Làm lại bước này <i class="icon-refresh"></i></button>
        </div>
        <div class="col-md-6 col-xs-6">
            <?php if ($_SESSION['process']['UpgradeDatabase'] == true): ?>
                <button name="next" type="submit" class="btn btn-blue pull-right ">Tiếp tục <i class="icon-signin"></i></button>
            <?php else: ?>
                <button name="submit-upgrade" type="submit" class="btn btn-blue pull-right ">Bắt đầu nâng cấp <i class="icon-retweet"></i></button>
            <?php endif ?>
        </div>
    </div>
</form>