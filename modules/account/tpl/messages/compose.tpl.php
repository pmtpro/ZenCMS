<?php load_header() ?>

    <div class="detail_content">
        <h1 class="title border_blue">Trang cá nhân</h1>
        <?php load_layout('display_user') ?>
    </div>

    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">
        <h1 class="title border_blue">Soạn thư</h1>

        <?php load_message() ?>

        <form method="post">
            <div class="item">
                Người nhận:<br/>
                <input type="text" name="to" value="<?php echo $message['to'] ?>" placeholder="Tài khoản người nhận"/>
            </div>
            <div class="item">
                Nội dung:<br/>
                <textarea name="message" id="content"><?php echo $message['message'] ?></textarea>
            </div>
            <div class="item">
                <input type="submit" name="sub_send" value="Gửi tin nhắn" class="button BgGreen" />
            </div>
        </form>
    </div>

<?php load_footer() ?>