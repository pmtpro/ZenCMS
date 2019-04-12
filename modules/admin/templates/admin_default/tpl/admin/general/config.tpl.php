<?php load_header() ?>

    <h1 class="title">Admin CP</h1>

    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">

        <h2 class="sub_title border_orange"><?php echo $page_title ?></h2>

        <?php load_message() ?>

        <form method="POST">

            <div class="item">
                Địa chỉ trang chủ:<br/>
                <input type="text" name="home" value="<?php echo get_config('home'); ?>"/>
            </div>

            <div class="item">
                Tiêu đề trang:<br/>
                <input type="text" name="title" value="<?php echo get_config('title'); ?>"/>
            </div>

            <div class="item">
                Keyword:<br/>
                <textarea name="keyword"><?php echo get_config('keyword'); ?></textarea>
            </div>

            <div class="item">
                Mô tả website:<br/>
                <textarea name="des"><?php echo get_config('des'); ?></textarea>
            </div>

            <div class="item"><input type="submit" name="sub" value="Lưu thay đổi" class="button BgGreen"/></div>
        </form>
    </div>

    <div class="detail_content">
        <h2 class="sub_title border_orange">Cấu hình mail</h2>

        <form method="post">
            <div class="item">
                Host SMTP:<br/>

                <div class="tip">Ví dụ: Gmail là smtp.gmail.com, Yahoo là smtp.mail.yahoo.com</div>
                <input type="text" name="mail_host" value="<?php echo get_config('mail_host') ?>"
                       placeholder="Ví dụ: smtp.gmail.com"/>
            </div>
            <div class="item">
                Cổng gửi mail:<br/>

                <div class="tip">Mặc định là 587</div>
                <input type="text" name="mail_port" value="<?php echo get_config('mail_port') ?>"
                       placeholder="Ví dụ: 587"/>
            </div>
            <div class="item">
                Mã hóa:<br/>
                <select name="mail_smtp_secure">
                    <option value="tls" <?php echo get_config('mail_smtp_secure') == 'tls' ? 'selected' : '' ?> >TLS
                    </option>
                    <option value="ssl" <?php echo get_config('mail_smtp_secure') == 'ssl' ? 'selected' : '' ?> >SSL
                    </option>
                </select>
            </div>
            <div class="item">
                <input type="checkbox" name="mail_smtp_auth"
                       value="1" <?php echo get_config('mail_smtp_auth') ? 'checked' : '' ?> /> Xác thực SMTP
            </div>
            <div class="item">
                <div class="tip">
                    Chú ý. Phải sừ dụng tên đăng nhập chính xác với từng nhà cung cấp dịnh vụ. <br/>
                    Ví dụ host mail bạn là smtp.gmail.com thì tài khoản ở dưới phải là tài khoản mail gmail
                </div>
                Tên đăng nhập tài khoản email của bạn:<br/>
                <input type="text" name="mail_username" value="<?php echo get_config('mail_username') ?>"/>
            </div>
            <div class="item">
                Mật khẩu:<br/>
                <input type="password" name="mail_password"
                       value="<?php echo base64_decode(get_config('mail_password')) ?>"/>
            </div>
            <div class="item">
                Thông tin người gửi<br/>
                Email:<br/>
                <input type="text" name="mail_setfrom" value="<?php echo get_config('mail_setfrom') ?>"
                       placeholder="Ví dụ: name@gmail.com"/><br/>
                Tên:<br/>
                <input type="text" name="mail_name" value="<?php echo get_config('mail_name') ?>"
                       placeholder="Ví dụ: ZenCMS"/>
            </div>
            <div class="item">
                <input type="submit" name="sub_mail" value="Lưu thay đổi" class="button BgGreen"/>
            </div>
        </form>
    </div>

<?php load_footer() ?>