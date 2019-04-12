<?php load_header() ?>

    <h1 class="title">Quản lí</h1>
    <div class="breadcrumb"> <?php echo $display_tree; ?></div>

<?php load_message() ?>

    <div class="detail_content">

        <h2 class="sub_title border_orange"><?php echo $page_title; ?></h2>

        <form method="POST">

            <div class="item">
                <input type="checkbox" name="register_turn_off"
                       value="1" <?php if (get_config('register_turn_off')) echo 'checked' ?> />
                Không cho phép thành viên đăng kí mới
            </div>

            <div class="item">
                Thông báo ngừng đăng kí:<br/>
                <textarea name="register_message"><?php echo get_config('register_message') ?></textarea>
            </div>

            <div class="item">
                <input type="checkbox" name="register_turn_on_authorized_email"
                       value="1" <?php if (get_config('register_turn_on_authorized_email')) echo 'checked' ?> />
                Yêu cầu xác thực email khi đăng kí
            </div>
            <div class="item">
                <input type="submit" name="sub_settings" value="Lưu thay đổi" class="button BgGreen"/>
            </div>

        </form>

    </div>

<?php load_footer() ?>