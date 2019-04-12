<?php load_header(); ?>
    <h1 class="title">Quản lí</h1>
    <div class="breadcrumb"><?php echo $display_tree; ?></div>

<?php load_message(); ?>

    <div class="detail_content">
        <h2 class="sub_title border_blue">
            <a href="<?php echo $blog['full_url'] ?>" target="_blank"><?php echo $blog['name'] ?></a>
        </h2>

        <div class="tip">Bạn có thể đổi tên file, hoặc xóa file</div>

        <div class="item_non_border">
            <a href="<?php echo _HOME ?>/blog/manager/files/<?php echo $blog['id'] ?>/step2" class="button BgBlue">Trở
                lại</a>
        </div>
    </div>

    <div class="detail_content">
        <h2 class="sub_title border_blue">Sửa file</h2>


        <div class="more_info">

            Thông tin: File này đã được download <b><?php echo $file['down'] ?></b> lần<br/>
            Đăng vào <?php echo get_time($file['time']); ?><br/>
            Đăng bởi <?php echo show_nick($file['uid'], true); ?>

        </div>
        <div class="more_info">
            <form method="post">
                <?php foreach ($file['actions_editor'] as $act => $title): ?>
                    <input type="image" class="button_image" name="sub_<?php echo $act; ?>" src="<?php echo icon_src($act); ?>" style="margin-right: 10px;" title="<?php echo $title; ?>" value="1"/>
                <?php endforeach; ?>
            </form>
        </div>
        <form method="post">
            <div class="item_non_border">
                Tên file:<br/>
                <input type="text" name="name" value="<?php echo $file['name'] ?>"/>
            </div>

            <div class="item_non_border">
                <input type="hidden" name="token_edit_file" value="<?php echo $token; ?>"/>
                <input type="submit" name="sub_edit" value="Lưu thay đổi" class="button BgGreen"/>
                <a href="<?php echo _HOME ?>/blog/manager/files/<?php echo $blog['id'] ?>/step2/delete/<?php echo $file['id'] ?>"
                   class="button BgRed">Xóa file này</a>
            </div>
        </form>
    </div>

<?php load_footer(); ?>