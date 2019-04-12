<?php load_header() ?>

    <div class="detail_content">
        <h1 class="title border_blue">Trang cá nhân</h1>
        <?php load_layout('display_user') ?>
    </div>

    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">

        <?php load_message() ?>

        <?php if (!empty($conversations)): ?>

            <h1 class="title">
                <?php echo is_online($conversations_partner['last_login']) ? icon('online', 'vertical-align: text-top;') : icon('offline', 'vertical-align: text-top;') ?>
                <?php echo show_nick($conversations_partner['username'], true, false) ?>
            </h1>

            <?php foreach ($conversations as $conver): ?>
                <div class="conversations_item">

                    <span class="conversations_author"><?php echo show_nick($conver['from'], true, false) ?></span>

                    <span class="conversations_time"> <?php echo get_time($conver['time']) ?></span><br/>

                    <div class="conversations_msg"><?php echo $conver['msg'] ?></div>

                </div>
            <?php endforeach; ?>

            <div class="list_page"><?php echo $conversations_pagination ?></div>

            <form method="post" action="<?php echo _HOME ?>/account/messages/compose">

                <textarea name="message" id="content"></textarea>

                <div class="item">
                    <input type="hidden" name="to" value="<?php echo $conversations_partner['username'] ?>"/>
                    <input type="submit" name="sub_send" value="Trả lời" class="button BgGreen"/>
                </div>

            </form>

        <?php endif; ?>
    </div>

<?php load_footer() ?>