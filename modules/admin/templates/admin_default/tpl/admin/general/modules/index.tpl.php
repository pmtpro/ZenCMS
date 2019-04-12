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
<?php load_message() ?>

    <div class="detail_content">
        <h2 class="sub_title border_orange"><?php echo $page_title ?></h2>

        <form method="post">
            <div class="item"><b>Module nền: </b></div>

            <?php foreach ($modules[BACKGROUND] as $name => $mod): ?>
                <div class="item">
                    <?php if ($mod['protected']): ?>
                        <?php echo icon('item') ?>
                    <?php else: ?>
                        <input type="checkbox" name="background[]" value="<?php echo $name ?>" <?php if (in_array($name, $module_actived[BACKGROUND])) echo 'checked'; ?>/>
                    <?php endif ?>
                    <b><?php echo $name ?></b>
                    <?php if (!$mod['protected']): ?>
                    <i class="text_smaller gray">
                        <u>
                            <a href="<?php echo _HOME ?>/admin/general/modules/uninstall/<?php echo $name ?>" style="color: red">Uninstall</a>
                        </u>
                    </i>
                    <?php endif ?><br/>
                <span class="text_smaller gray">
                    - ID: <?php echo $mod['id'] ?><br/>
                    - <?php echo $mod['name'] ?><br/>
                    - Phiên bản: <?php echo $mod['version'] ?><br/>
                    - Tác giả: <?php echo $mod['author'] ?><br/>
                    - Mô tả: <?php echo $mod['des'] ?><br/>
                    <?php if ($mod['option']): ?>
                        - Tùy chọn: <i><?php echo $mod['option'] ?></i><br/>
                    <?php endif ?>
                    <?php if ($mod['readme']): ?>
                        - <u><a href="<?php echo $mod['readme'] ?>" target="_blank">Readme</a></u><br/>
                    <?php endif ?>
                </span>
                </div>
            <?php endforeach; ?>

            <div class="item"><b>Module ứng dụng: </b></div>

            <?php foreach ($modules[APP] as $name => $mod): ?>
                <div class="item">
                    <?php if ($mod['protected']): ?>
                        <?php echo icon('item') ?>
                    <?php else: ?>
                        <input type="checkbox" name="app[]" value="<?php echo $name ?>" <?php if (in_array($name, $module_actived[APP])) echo 'checked'; ?>/>
                    <?php endif ?>
                    <b><?php echo $name ?></b>
                    <?php if (!$mod['protected']): ?>
                        <i class="text_smaller gray">
                            <u>
                                <a href="<?php echo _HOME ?>/admin/general/modules/uninstall/<?php echo $name ?>" style="color: red">Uninstall</a>
                            </u>
                        </i>
                    <?php endif ?><br/>
                <span class="text_smaller gray">
                    - ID: <?php echo $mod['id'] ?><br/>
                    - <?php echo $mod['name'] ?><br/>
                    - Phiên bản: <?php echo $mod['version'] ?><br/>
                    - Tác giả: <?php echo $mod['author'] ?><br/>
                    - Mô tả: <?php echo $mod['des'] ?><br/>
                    <?php if ($mod['option']): ?>
                        - Tùy chọn: <i><?php echo $mod['option'] ?></i><br/>
                    <?php endif ?>
                    <?php if ($mod['readme']): ?>
                        - <u><a href="<?php echo $mod['readme'] ?>" target="_blank">Readme</a></u><br/>
                    <?php endif ?>
                </span>
                </div>
            <?php endforeach; ?>
            <div class="item">
                <input type="submit" name="sub" value="Lưu thay đổi" class="button BgGreen"/>
            </div>
        </form>
    </div>

<?php load_footer() ?>