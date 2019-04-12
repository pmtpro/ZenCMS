<?php load_header() ?>

    <h1 class="title"><?php echo icon('title'); ?> Admin cpanel</h1>
    <div class="breadcrumb"><?php echo $display_tree ?></div>

<?php load_message() ?>

    <div class="detail_content">
        <div class="sub_title border_blue"><?php echo $user['nickname'] ?></div>
        <div class="item">
            <?php echo icon('item') ?>
            <a href="<?php echo _HOME ?>/account/manager/permission/<?php echo $user['username'] ?>" target="_blank">Thay đổi chức vụ</a>
        </div>

        <div class="item">
            <?php echo icon('item') ?>
            <a href="<?php echo _HOME ?>/account/wall/<?php echo $user['username'] ?>" target="_blank">Xem thông tin</a>
        </div>
        <div class="item">
            <?php echo icon('item') ?>
            Email: <?php echo $user['email'] ?>
        </div>
        <div class="item">
            <?php echo icon('item') ?>
            Đăng nhập lần cuối: <?php echo get_time($user['last_login']) ?>
        </div>

    </div>

<?php load_footer() ?>