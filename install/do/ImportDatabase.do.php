<?php
if (!defined('__ZEN_KEY_ACCESS')) exit('No direct script access allowed');

if (empty($_SESSION['process']['DatabaseInfo'])) {

    redirect('install?do=DatabaseInfo');
    exit;
}

if (defined('__ZEN_DB_HOST') && defined('__ZEN_DB_USER') && defined('__ZEN_DB_PASSWORD') && defined('__ZEN_DB_NAME')) {

    if (!__ZEN_DB_HOST || !__ZEN_DB_NAME) {

        redirect('install?do=DatabaseInfo');
        exit;
    }

    if (!$db->connect(__ZEN_DB_HOST, __ZEN_DB_USER, __ZEN_DB_PASSWORD, __ZEN_DB_NAME, false)) {

        redirect('install?do=DatabaseInfo');
        exit;
    }

} else {

    redirect('install?do=DatabaseInfo');
    exit;
}

if (isset($_POST['sub_continous_import_data'])) {

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

            redirect('install?do=AdminData');
            exit;
        }
    }
}

?>

<div class="detail_content">
    <h1 class="title border_blue">Nhập khẩu dữ liệu</h1>
    <?php load_message() ?>
    <div class="tip">
        Hệ thống sẽ nhập khẩu file <b><code><?php echo __SQL_FILE_NAME ?></code></b> vào database!<br/>
        Vui lòng chắc chắn database của bạn đang trắng.<br/>
        Chúng tôi sẽ không chịu trách nhiệm về dữ liệu cũ trong database hiện tại (Nếu có)
    </div>
    <div class="item" style="text-align: center">
        <form method="POST">
            <a href="<?php echo _HOME ?>/install?do=DatabaseInfo" class="button BgRed">Trở lại</a>
            <input type="submit" name="sub_continous_import_data" class="button BgGreen"
                   value="Đồng ý nhập khẩu dữ liệu"/>
        </form>
    </div>
</div>
