<?php load_header() ?>

    <h1 class="title">Admin cpanel</h1>
    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">
        <div class="sub_title border_blue">Quản lí file</div>
        <div class="item_non_border">
            <?php foreach ($public_cpanel as $url => $cp): ?>
                <a href="<?php echo $url ?>" class="button BgBlue"><?php echo $cp ?></a>
            <?php endforeach ?>
        </div>
    </div>

    <div class="detail_content">
        <div class="sub_title border_blue">Đổi tên</div>

        <?php if ($file): ?>
            <div class="path">
                <?php echo $file ?>
            </div>
        <?php endif ?>

        <?php load_message() ?>

        <div class="content">

            <form method="POST">

                <?php foreach ($selected as $item): ?>
                    <div class="item">

                        <?php echo base64_decode($item) ?><br/>

                        <?php if (isset($failure[$item])): ?>
                            <div class="notice">
                                <?php echo $failure[$item] ?>
                            </div>
                        <?php endif ?>

                        Đổi thành: <input type="text" name="<?php echo $item ?>" value="<?php echo base64_decode($item) ?>"/>
                    </div>
                <?php endforeach ?>

                <input type="submit" name="sub_do_rename" value="Đổi tên"/>

            </form>

        </div>
    </div>

<?php load_footer() ?>