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
            Tạo mục mới
        </div>

        <?php if ($file): ?>
            <div class="path">
                <code><?php echo $file ?></code>
            </div>
        <?php endif ?>

        <?php load_message() ?>

        <div class="content">
            <form method="POST">
                <div class="item">
                    <?php if ($type == 'dir'): ?>
                        Tên thư mục:<br/>
                    <?php else: ?>
                        Tên file:<br/>
                    <?php endif ?>
                    <input type="text" name="name" value=""/>
                </div>
                <div class="item">
                    <input type="hidden" value="<?php echo $type ?>" name="type"/>
                    <input type="hidden" name="token_new" value="<?php echo $token_new ?>"/>
                    <input type="submit" name="sub_new" value="Tạo" class="button BgBlue"/>
                </div>
            </form>
        </div>
    </div>

<?php load_footer() ?>