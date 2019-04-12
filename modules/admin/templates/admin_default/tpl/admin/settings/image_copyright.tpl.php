<?php load_header() ?>

    <h1 class="title"><?php echo icon('title'); ?> Admin cpanel</h1>
    <div class="breadcrumb"><?php echo $display_tree ?></div>

<?php load_message() ?>


    <div class="detail_content">
        <h2 class="title border_blue">Cài đặt đóng dấu</h2>

        <form method="post">
            <div class="item">
                <label for="turn_on_watermark">
                    <input type="checkbox" name="turn_on_watermark" id="turn_on_watermark" value="1" <?php if (get_config('turn_on_watermark')) echo 'checked' ?> />
                    Bật đóng dấu ảnh
                </label>
            </div>
            <div class="item">
                <input type="submit" name="sub_setting" value="Lưu thay đổi" class="button BgGreen"/>
            </div>
        </form>

        <div class="item" style="text-align: center"><?php echo $logo_watermark ?></div>
        <form method="post" enctype="multipart/form-data">
            <div class="item">
                Chọn file và tải lên <br/>
                <input type="file" name="logo" />
            </div>
            <div class="item">
                <input type="submit" name="sub_upload" value="Tải lên" class="button BgBlue"/>
            </div>
        </form>
    </div>

<?php load_footer() ?>