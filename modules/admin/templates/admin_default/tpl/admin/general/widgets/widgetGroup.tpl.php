<?php load_header() ?>

    <h1 class="title">Admin CP</h1>

    <div class="breadcrumb"><?php echo $display_tree ?></div>

<?php load_message() ?>

    <div class="detail_content">
        <h2 class="title border_blue">Quản lí widget</h2>

        <div class="item_non_border">
            <a href="<?php echo _HOME ?>/admin/general/widgets" class="button BgRed">Danh sách vị trí</a>
            <a href="<?php echo _HOME ?>/admin/general/widgets/<?php echo $wg ?>/new" class="button BgGreen">Tạo mới</a>
        </div>
    </div>

<?php if (!empty($wg)): ?>

    <div class="detail_content">
        <h2 class="sub_title border_orange">Danh sách widget trong <b><?php echo $wg ?></b></h2>

        <div class="tip">Vị trí này có <?php echo count($widgets) ?> widget</div>

        <?php if (count($widgets)): ?>

            <form method="POST">
                <?php foreach ($widgets as $widget): ?>

                    <div class="item">
                        <input type="text" name="weight[<?php echo $widget['id'] ?>]"
                               value="<?php echo $widget['weight'] ?>" style="width: 20px"/>
                        <?php echo $widget['title'] ?>
                        <span class="text_smaller gray">
                        <i>
                            <a href="<?php echo _HOME ?>/admin/general/widgets/<?php echo urlencode($wg) ?>/review/<?php echo $widget['id'] ?>" target="_blank">Xem</a>,
                            <a href="<?php echo _HOME ?>/admin/general/widgets/<?php echo urlencode($wg) ?>/edit/<?php echo $widget['id'] ?>">Sửa</a>,
                            <a href="<?php echo _HOME ?>/admin/general/widgets/<?php echo urlencode($wg) ?>/unbound/<?php echo $widget['id'] ?>">Bỏ</a>,
                            <a href="<?php echo _HOME ?>/admin/general/widgets/<?php echo urlencode($wg) ?>/delete/<?php echo $widget['id'] ?>">Xóa</a>
                        </i>
                    </span>
                    </div>

                <?php endforeach ?>

                <div class="item_non_border">
                    <input type="submit" name="sub_order" value="Sắp xếp" class="button BgBlue"/>
                </div>
            </form>

        <?php endif ?>
    </div>

<?php endif ?>



<?php load_footer() ?>