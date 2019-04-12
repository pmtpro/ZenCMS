<?php load_header() ?>

    <h1 class="title">Quản lí</h1>
    <div class="breadcrumb"> <?php echo $display_tree; ?></div>

<?php load_message() ?>

    <div class="detail_content">
        <h2 class="sub_title border_orange"><?php echo $page_title; ?></h2>

        <form method="POST">

            <div class="tip">
                Dọn dẹp chatbox. Click nút dưới để bắt đầu dọn dẹp
            </div>

            <input type="checkbox" name="group[]" value="" /> Xóa trò chuyện bình thường<br/>
            <input type="checkbox" name="group[]" value="manager" /> Xóa tất cả trò chuyện của nhóm quản lí<br/>

            <div class="item">
                <input type="submit" name="sub_cleanup" value="Dọn dẹp" class="button BgRed"/>
            </div>

        </form>
    </div>
<?php load_footer() ?>