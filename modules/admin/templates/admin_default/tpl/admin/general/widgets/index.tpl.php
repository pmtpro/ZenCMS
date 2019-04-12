<?php load_header() ?>

    <h1 class="title">Admin CP</h1>

    <div class="breadcrumb"><?php echo $display_tree ?></div>

<?php load_message() ?>

    <div class="detail_content">
        <h2 class="sub_title border_orange"><?php echo $page_title ?></h2>

        <div class="tip">Dưới đây là danh sách các vị trí đặt widget hệ thống đã tìm thấy trong template hiện tại</div>

        <?php foreach ($list_widget_groups as $wg): ?>

            <div class="item">
                <?php echo icon('item') ?>
                <a href="<?php echo _HOME ?>/admin/general/widgets/<?php echo urlencode($wg) ?>"><?php echo $wg ?></a>
            </div>

        <?php endforeach ?>

    </div>

<?php load_footer() ?>