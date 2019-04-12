<?php load_header(); ?>
    <h1 class="title">Quản lí</h1>
    <div class="breadcrumb"><?php echo $display_tree; ?></div>

<?php load_message(); ?>

    <div class="detail_content">
        <h2 class="sub_title border_blue"><a href="<?php echo $blog['full_url'] ?>"
                                             target="_blank"><?php echo $blog['name'] ?></a></h2>

        <div class="tip">Hãy điền tên link và link vào form bên dưới</div>

        <div class="item_non_border">
            <a href="<?php echo _HOME ?>/blog/manager/links/<?php echo $blog['id'] ?>/step2" class="button BgBlue">Trở
                lại</a>
        </div>
    </div>

    <div class="detail_content">
        <h2 class="title">Sửa link</h2>

        <form method="post">
            <div class="more_info">

                Thông tin: Link này đã được click <b><?php echo $link['click'] ?></b> lần<br/>
                Đăng vào <?php echo get_time($link['time']); ?><br/>
                Đăng bởi <?php echo show_nick($link['uid'], true); ?>

            </div>
            <div class="item_non_border">
                Tên Link:<br/>
                <input type="text" name="name" value="<?php echo $link['name'] ?>"/>
            </div>
            <div class="item_non_border">
                Link:<br/>
                <input type="text" name="link" value="<?php echo $link['link'] ?>"/>
            </div>
            <div class="item_non_border">
                <input type="hidden" name="token_edit_link" value="<?php echo $token; ?>"/>
                <input type="submit" name="sub_edit" value="Lưu thay đổi" class="button BgGreen"/>
                <a href="<?php echo _HOME ?>/blog/manager/links/<?php echo $blog['id'] ?>/step2/delete/<?php echo $link['id'] ?>"
                   class="button BgRed">Xóa link</a>
            </div>
        </form>
    </div>

<?php load_footer(); ?>