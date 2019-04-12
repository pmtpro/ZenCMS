<?php load_header() ?>

    <h1 class="title">Admin cpanel</h1>
    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">
        <div class="sub_title border_blue">Quản lí file</div>
        <div class="item_non_border">
            <?php foreach($public_cpanel as $url => $cp): ?>
                <a href="<?php echo $url ?>" class="button BgBlue"><?php echo $cp ?></a>
            <?php endforeach ?>
        </div>
    </div>

    <div class="detail_content">
        <div class="sub_title border_blue">Xem: <?php echo $fileinfo['name'] ?></div>

        <?php if ($path): ?>
            <div class="path">
                <?php echo $path ?>
            </div>
        <?php endif ?>

        <?php load_message() ?>

        <?php load_layout('fileinfo') ?>

        <div class="content" style="text-align: center">

            <?php if($fileinfo['is_image']): ?>
                <img src="<?php echo $fileinfo['full_url'] ?>" />
            <?php else: ?>
                <a href="<?php echo $fileinfo['full_url'] ?>" target="_blank">
                    <?php echo $fileinfo['full_url'] ?>
                </a>
            <?php endif ?>
        </div>

    </div>

<?php load_footer() ?>