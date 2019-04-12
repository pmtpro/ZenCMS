<?php load_header() ?>

    <h1 class="title">Admin CP</h1>

    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">
        <h2 class="title border_blue">Menu</h2>

        <div class="content">
            <?php foreach ($menus as $url => $name): ?>
                <a href="<?php echo $url ?>" class="button BgBlue"><?php echo $name ?></a>
            <?php endforeach ?>
        </div>
    </div>

    <div class="detail_content">
        <h2 class="sub_title border_orange"><?php echo $page_title ?></h2>
        <div class="tip">
            Khi hủy cài đặt ứng dụng này, các chức năng liên quan đến ứng dụng sẽ mất, Bạn có chắc chắn hủy ứng dụng này?
        </div>
        <?php load_message() ?>
        <form method="POST">
            <div class="item">
                <input type="submit" name="sub_uninstall" value="Uninstall" class="button BgBlue"/>
                <a href="<?php echo _HOME ?>/admin/general/modules" class="button BgRed">Hủy</a>
            </div>
        </form>
    </div>

<?php load_footer() ?>