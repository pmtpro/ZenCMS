<?php load_header() ?>

    <h1 class="title">Admin cpanel</h1>
    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">
        <div class="sub_title border_blue">Quản lí file</div>
        <div class="item_non_border">

            <?php foreach($public_cpanel as $url => $cp): ?>
                <a href="<?php echo $url ?>" class="button BgBlue"><?php echo $cp ?></a>
            <?php endforeach ?>

            <div class="manager_dir_bar">

                <?php foreach ($dir_manager_bar as $url => $bar): ?>
                    <a href="<?php echo $url ?>" class="button"><?php echo $bar ?></a>
                <?php endforeach ?>

            </div>

        </div>
    </div>

    <div class="detail_content">
        <div class="title border_blue">
            Upload file
        </div>

        <?php if ($file): ?>
            <div class="path">
                <code><?php echo $file ?></code>
            </div>
        <?php endif ?>

        <?php load_message() ?>

        <div class="content">
            <form method="POST" enctype="multipart/form-data">
                <div class="item">
                    <input type="file" name="file"/>
                </div>
                <div class="item">
                    <input type="hidden" name="token_upload" value="<?php echo $token_upload ?>"/>
                    <input type="submit" name="sub_upload" value="Upload" class="button BgBlue"/>
                </div>
            </form>
        </div>
    </div>

<?php load_footer() ?>