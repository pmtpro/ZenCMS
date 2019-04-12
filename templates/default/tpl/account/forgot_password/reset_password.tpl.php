<?php load_header() ?>

    <div class="detail_content">
        <h1 class="title border_blue">Khôi phục mật khẩu</h1>

        <?php load_message() ?>

        <?php if ($user): ?>
            <?php load_layout('display_user') ?>
        <?php endif; ?>

        <?php if (!isset($new_password)) : ?>
            <form method="post">
                <div class="item">
                    <input type="hidden" name="token_continous" value="<?php echo $token_continous ?>"/>
                    <input type="submit" name="sub_reset_password" value="Khôi phục mật khẩu" class="button BgRed"/>
                </div>
            </form>
        <?php else: ?>
            <div class="success">
                <p style="text-align: center">
                    Mật khẩu mới của bạn hiện tại là<br/>
                    <big style="font-size: 18px; font-weight: bold;"><?php echo $new_password; ?></big><br/>
                    Hãy <b><a href="<?php echo _HOME ?>/login">đăng nhập</a></b> với mật khẩu này và đổi mật khẩu ngay lần đăng nhập đầu
                </p>
            </div>
        <?php endif; ?>
    </div>

<?php load_footer() ?>