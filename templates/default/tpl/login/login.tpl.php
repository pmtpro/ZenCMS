<?php load_header() ?>

    <div class="detail_content">
        <h1 class="title"><?php echo icon('title'); ?> Đăng nhập</h1>

        <?php load_message() ?>

        <form method="post">
            <div class="item">
                Tên đăng nhập:<br/>
                <input type="text" name="username"/>
            </div>
            <div class="item">
                Mật khẩu:<br/>
                <input type="password" name="password"/>
            </div>
            <?php if ($limit_login == true): ?>
                <div class="item">
                    <img src="<?php echo $captcha_src ?>" title="Nhập captcha"/><br/>
                    Nhập mã xác nhận: <br/>
                    <input type="text" name="captcha_code" style="width:50px;"/>
                </div>
            <?php endif; ?>
            <div class="item">
                <label for="remember_me">
                    <input type="checkbox" name="remember_me" value="1" id="remember_me"/> Ghi nhớ tôi
                </label>
            </div>
            <div class="item">
                <input type="hidden" name="token_login" value="<?php echo $token_login; ?>"/>
                <input type="submit" name="sub" value="Đăng nhập" class="button BgGreen"/>
            </div>
            <div class="item">
                <?php echo icon('login|forgot_password') ?> <a href="<?php echo _HOME ?>/account/forgot_password">Quên mật khẩu</a>
            </div>
        </form>
    </div>

<?php load_footer() ?>