<?php load_header() ?>

    <div class="detail_content">
        <h1 class="title"><?php echo icon('title'); ?> Đăng kí thành viên</h1>

        <?php load_message() ?>

        <form method="POST">
            <table>
                <tbody>
                <tr>
                    <td>Tài khoản</td>
                    <td><input type="text" name="username"/></td>
                </tr>
                <tr>
                    <td>Mật khẩu</td>
                    <td><input type="password" name="password"/></td>
                </tr>
                <tr>
                    <td>Nhập lại Mật khẩu</td>
                    <td><input type="password" name="repassword"/></td>
                </tr>
                <?php if (get_config('register_turn_on_authorized_email')): ?>
                    <tr>
                        <td>Email</td>
                        <td><input type="text" name="email"/></td>
                    </tr>
                <?php endif ?>
                <tr>
                    <td>
                        Mã xác nhận:
                    </td>
                    <td>
                        <img src="<?php echo $captcha_src ?>"/><br/>
                        <input type="text" name="captcha_code" style="width:50px;"/>
                        <input type="hidden" name="token_register" value="<?php echo $token_register; ?>"/>
                        <input type="submit" name="sub" value="Đăng kí" class="button BgGreen"/>
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>
<?php load_footer() ?>