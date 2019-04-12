<?php load_header() ?>

    <div class="detail_content">
        <h1 class="title"><?php echo $page_title; ?></h1>

        <?php load_message() ?>

        <?php if (IS_MEMBER): ?>
            <?php load_layout('formChatMember') ?>
        <?php else: ?>

            <?php if (get_config('chatbox_allow_guest_chat')): ?>
                <?php load_layout('formChat') ?>
            <?php else: ?>
                <div class="notice">Chỉ thành viên mới được trò chuyện</div>
            <?php endif ?>

        <?php endif ?>

        <?php foreach ($list as $chat): ?>

            <div class="chat_item">

                <img src="<?php echo $chat['user']['full_avatar'] ?>" alt="Avatar"
                     class="chat_avatar <?php if (is_online($chat['user']['last_login'])) echo 'chat_avatar_online' ?>">

                <?php if ($chat['user']['id']): ?>

                    <?php if (is_online($chat['user']['last_login'])): ?>
                        <?php echo icon('online', 'float:left; padding-top: 2px;') ?>
                    <?php else: ?>
                        <?php echo icon('offline', 'float:left; padding-top: 2px;') ?>
                    <?php endif ?>

                <?php endif ?>

                <span class="chat_message">

                    <b><?php echo show_nick($chat['user'], $chat['user']['id'] ? true : false) ?></b>: <?php echo $chat['content'] ?>
                    <span class="time_chat"><?php echo get_time($chat['time']) ?></span>

                    <?php if($chat['who_edit']):?>

                        <br/><span class="chat_edit_info">
                            Đã sửa: <b><?php echo $chat['who_edit'] ?></b>
                            <?php echo get_time($chat['time_edit']) ?> [<?php echo $chat['edit'] ?>]
                        </span>

                    <?php endif ?>

                    <div class="chat_manager_bar">
                        <?php foreach ($chat['manager_bar'] as $item): ?>
                            <u><?php echo $item ?></u>
                        <?php endforeach ?>
                    </div>

                </span>

                <div style="clear: both;"></div>
            </div>

        <?php endforeach ?>
        <?php echo $chat_pagination ?>
    </div>

<?php load_footer() ?>