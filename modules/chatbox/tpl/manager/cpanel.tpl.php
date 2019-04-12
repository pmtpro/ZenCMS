<?php load_header() ?>

    <h1 class="title">Quản lí</h1>
    <div class="breadcrumb"> <?php echo $display_tree; ?></div>

<?php load_message() ?>

    <div class="detail_content">
        <h2 class="sub_title border_orange"><?php echo $page_title; ?></h2>

        <form method="POST">

            <div class="item">
                <label for="chatbox_allow_guest_chat">
                    <input type="checkbox" name="chatbox_allow_guest_chat" id="chatbox_allow_guest_chat" <?php echo get_config('chatbox_allow_guest_chat') ? 'checked' : '' ?> />
                    Cho phép khách chat
                </label>
            </div>

            <div class="item">
                Số lượng tin hiển thị trên 1 trang:<br/>
                <input type="text" name="chatbox_num_item_per_page" value="<?php echo get_config('chatbox_num_item_per_page') ?>" />
            </div>

            <div class="item">
                <input type="submit" name="sub" value="Lưu thay đổi" class="button BgBlue"/>
            </div>
        </form>
    </div>
<?php load_footer() ?>