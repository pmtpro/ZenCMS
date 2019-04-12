<?php load_header(); ?>
    <h1 class="title">Quản lí</h1>
    <div class="breadcrumb"><?php echo $display_tree; ?></div>

<?php load_message(); ?>

    <div class="detail_content">
        <h2 class="sub_title border_blue"><a href="<?php echo $blog['full_url'] ?>"
                                             target="_blank"><?php echo $blog['name'] ?></a></h2>

        <div class="tip">
            Các định dạng cho phép:<br/>
            <?php echo $files_allowed; ?>
        </div>
        <div class="tip">Bỏ trống tên để lấy trực tiếp tên file</div>
        <div class="item_non_border">
            <a href="<?php echo _HOME ?>/blog/manager/files/<?php echo $blog['id'] ?>/step2" class="button BgBlue">Trở lại</a>
            <a href="<?php echo _HOME ?>/blog/manager/files/<?php echo $blog['id'] ?>/step2/add?remote" class="button BgGreen">Nhập khẩu từ xa</a>

        </div>
    </div>

    <div class="detail_content">
        <h3 class="sub_title border_red">Chọn tệp tin</h3>

        <form method="post" enctype="multipart/form-data">
            <div class="item_non_border">
                <input type="submit" name="sub_add" value="Tải lên" class="button BgGreen"/>
            </div>
            <?php foreach ($form_files as $file): ?>
                <div class="list">
                    FILE: <input type="file" name="file<?php echo $file; ?>"/><br/>
                    NAME: <input type="text" name="name<?php echo $file; ?>" placeholder="Tùy chọn"/>
                </div>
            <?php endforeach; ?>
            <div class="item_non_border">
                <input type="hidden" name="token_add_file" value="<?php echo $token; ?>"/>
                <input type="submit" name="sub_add" value="Tải lên" class="button BgGreen"/>
            </div>
        </form>
    </div>

<?php load_footer(); ?>