<?php load_header() ?>

    <h1 class="title"><?php echo icon('title'); ?> Admin cpanel</h1>
    <div class="breadcrumb"><?php echo $display_tree ?></div>
<?php load_message() ?>

    <div class="detail_content">
        <div class="sub_title border_blue">Quản lí giao diện</div>
        <div class="tip">
            Hãy chọn template tương thích với từng thiết bị hoặc chỉ đơn giản là mobile và pc
        </div>
        <div class="item_non_border">
            <a href="<?php echo _HOME ?>/admin/general/templates/import" class="button BgBlue">Tải lên</a>
            <a href="<?php echo _HOME ?>/admin/general/templates/list" class="button BgGreen">Danh sách</a>
        </div>
    </div>

    <div class="detail_content">
        <div class="sub_title border_blue">Chung</div>
        <div class="tip">
            Đây là 2 cài đặt cơ bản cho website
        </div>
        <form method="post">
            <div class="item">
                Giao diện điện thoại<br/>

                <select name="Mobile">
                    <option value="" <?php if (empty($current['Mobile'])) echo 'selected'; ?>>Chưa chọn</option>
                    <?php foreach ($templates as $key => $temp): ?>
                        <option value="<?php echo $key ?>" <?php if ($current['Mobile'] == $key) echo 'selected'; ?>><?php echo $temp["name"] ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="item">
                Giao diện máy tính (và các thiết bị khác)<br/>
                <select name="other">
                    <option value="" <?php if (empty($current['other'])) echo 'selected'; ?>>Chưa chọn</option>
                    <?php foreach ($templates as $key => $temp): ?>
                        <option value="<?php echo $key ?>" <?php if ($current['other'] == $key) echo 'selected'; ?>>
                            <?php echo $temp['name'] ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="item">
                <input type="submit" name="sub_general" value="Lưu thay đổi" class="button BgGreen"/>
            </div>
        </form>
    </div>

    <div class="detail_content">
        <div class="sub_title border_blue">Theo hệ điều hành</div>
        <div class="tip">
            Nếu không chọn một trong các mục ở đây, template mobile hoặc pc hoặc default sẽ được kích hoạt tùy từng thiết bị truy cập
        </div>
        <form method="post">

            <?php foreach ($device_os as $os): ?>

            <div class="item">
                <?php echo $os ?><br/>
                <select name="<?php echo $os ?>">
                    <option value="" <?php if (empty($current[$os])) echo 'selected'; ?>>Chưa chọn</option>
                    <?php foreach ($templates as $key => $temp): ?>
                        <option value="<?php echo $key ?>" <?php if (isset($current[$os]) && $current[$os] == $key) echo 'selected'; ?>>
                            <?php echo $temp['name'] ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>

            <?php endforeach; ?>

            <div class="item">
                <input type="submit" name="sub_by_os" value="Lưu thay đổi" class="button BgGreen"/>
            </div>

        </form>
    </div>

<?php load_footer() ?>