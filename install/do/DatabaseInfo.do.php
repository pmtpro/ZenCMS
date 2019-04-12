<?php
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');

$host = 'localhost';
$username = '';
$password = '';
$name = '';

if (isset ($_POST['sub_step1'])) {

    $host = $_POST['host'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];

    if (empty($host) || empty($username) || empty($name)) {

        $data['notices'] = 'Bạn phải nhập đầy đủ thông tin database';

    } else {

        if ($db->connect($host, $username, $password, $name, false)) {

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

            if (@file_put_contents(__DB_FILE_PATH, $dbcontent)) {

                $_SESSION['process']['DatabaseInfo'] = true;

                redirect('install?do=ImportDatabase');

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

<div class="detail_content">
    <h1 class="title border_blue">Bước 1: Cài đặt Database</h1>

    <?php load_message() ?>

    <form method="POST">
        <div class="item">
            DB host:<br/>
            <input type="text" name="host" value="<?php echo $host ?>"/>
        </div>
        <div class="item">
            DB username:<br/>
            <input type="text" name="username" value="<?php echo $username ?>"/>
        </div>
        <div class="item">
            DB password:<br/>
            <input type="password" name="password" value=""/>
        </div>
        <div class="item">
            DB name:<br/>
            <input type="text" name="name" value="<?php echo $name ?>"/>
        </div>
        <div class="item">
            <input type="submit" name="sub_step1" class="button BgBlack" value="Cài đặt"/>
        </div>
    </form>
</div>