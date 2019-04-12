<?php load_header() ?>

    <div class="detail_content">
        <h1 class="title border_blue">Trang cá nhân</h1>

        <?php load_layout('display_user') ?>

    </div>

<?php load_message() ?>

    <div class="detail_content">
        <h2 class="sub_title border_orange">Tài khoản của bạn</h2>

        <div class="item">
            <?php echo icon('account|messages', 'vertical-align: text-top;') ?>
            <a href="<?php echo _HOME ?>/account/messages">Tin nhắn</a>
        </div>
        <div class="item">
            <?php echo icon('account|settings', 'vertical-align: text-top;') ?>
            <a href="<?php echo _HOME ?>/account/settings">Cài đặt tài khoản</a>
        </div>
    </div>

<?php load_footer() ?>