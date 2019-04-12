<?php load_header() ?>

    <div class="detail_content">
        <h1 class="title border_blue">Trang cá nhân</h1>

        <?php load_layout('display_user') ?>

    </div>

    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">
        <h2 class="sub_title border_orange">Thay đổi chữ kí</h2>

        <?php load_message() ?>

        <form method="post">
            <div class="item">
                <textarea name="sign"><?php echo $user['sign'] ?></textarea>
            </div>
            <div class="item">
                <input type="submit" name="sub_change" value="Lưu thay đổi" class="button BgGreen"/>
            </div>
        </form>
    </div>

<?php load_footer() ?>