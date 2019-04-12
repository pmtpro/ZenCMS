<?php load_header() ?>

    <div class="detail_content">
        <h1 class="title border_blue">Trang cá nhân</h1>
        <?php load_layout('display_user') ?>
    </div>

    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">
        <h2 class="sub_title border_orange">Thay đổi mật khẩu</h2>

        <?php load_message() ?>

        <form method="post">
            <div class="item">
                Mật khẩu cũ:<br/>
                <input type="password" name="oldpassword" value=""/>
            </div>
            <div class="item">
                Mật khẩu mới:<br/>
                <input type="password" name="newpassword" value=""/>
            </div>
            <div class="item">
                Nhập lại mật khẩu mới:<br/>
                <input type="password" name="re_newpassword" value=""/>
            </div>
            <div class="item">
                <input type="hidden" name="token_change_password" value="<?php echo $token ?>"/>
                <input type="submit" name="sub_change" value="Lưu thay đổi" class="button BgBlue"/>
            </div>
        </form>

    </div>

<?php load_footer() ?>