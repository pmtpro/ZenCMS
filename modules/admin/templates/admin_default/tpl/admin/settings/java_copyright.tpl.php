<?php load_header() ?>

    <h1 class="title"><?php echo icon('title'); ?> Admin cpanel</h1>
    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">
        <h2 class="title border_blue">Cài đặt bản quyền java</h2>

        <?php load_message() ?>

        <div class="tip">
            Hệ thống sẽ tự gắn bản quyền vào file MANIFEST.MF bao gồm:<br/>
            - MIDlet-Delete-Confirm: Dòng chữ sẽ hiện khi người dùng xóa ứng dụng (game)<br/>
            - MIDlet-Info-URL: <?php echo _HOME ?>
        </div>
        <form method="post">
            <div class="item">
                Dòng chữ sẽ hiện khi người dùng xóa ứng dụng (game)<br/>
                <input type="text" name="delete_confirm_java" value="<?php echo get_config('delete_confirm_java') ?>" />
            </div>
            <div class="item">
                Info Url<br/>
                <span class="text_smaller gray">Mặc định <?php echo _HOME ?></span>
            </div>
            <div class="item">
                <input type="submit" name="sub" value="Lưu thay đổi" class="button BgBlue"/>
            </div>
        </form>
    </div>

<?php load_footer() ?>