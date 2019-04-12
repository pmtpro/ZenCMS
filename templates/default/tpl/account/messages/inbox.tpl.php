<?php load_header() ?>

    <div class="detail_content">
        <h1 class="title border_blue">Trang cá nhân</h1>
        <?php load_layout('display_user') ?>
    </div>

    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">

        <h1 class="title border_blue">Cuộc trò truyện</h1>

        <?php load_message() ?>

        <?php if (empty($inboxs)): ?>

            <div class="tip">Bạn không có cuộc trò chuyện nào gần đây</div>

        <?php else: ?>

            <?php foreach ($inboxs as $inbox): ?>

                <a href="<?php echo _HOME ?>/account/messages/inbox/<?php echo $inbox['id'] ?>">

                    <div class="inboxs_item <?php if(!$inbox['readed']) echo 'bg_unread'; ?>">

                        <?php echo is_online($inbox['user']['last_login']) ? icon('online', 'vertical-align: text-top;') : icon('offline', 'vertical-align: text-top;') ?>

                        <span class="inboxs_author"><?php echo $inbox['from']; ?></span>

                        <span class="inboxs_time"> <?php echo get_time($inbox['time']) ?></span>

                        <div class="inboxs_msg"><?php echo $inbox['sub_msg'] ?></div>

                    </div>

                </a>

            <?php endforeach; ?>

            <div class="list_page"><?php echo $inboxs_pagination ?></div>

        <?php endif; ?>
    </div>

<?php load_footer() ?>