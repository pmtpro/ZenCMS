<?php load_header() ?>

    <div class="detail_content">
        <h1 class="title border_blue">Quên mật khẩu</h1>

        <?php load_message() ?>
        <div class="tip">Để lấy lại mật khẩu hãy nhập tên tài khoản vào form bên dưới</div>

        <form method="post">
            <div class="item">
                Tên tài khoản:<br/>
                <input type="text" name="username" />
            </div>
            <div class="item">
                <input type="submit" name="sub" value="Tiếp tục" class="button BgBlue"/>
            </div>
        </form>

    </div>

<?php load_footer() ?>