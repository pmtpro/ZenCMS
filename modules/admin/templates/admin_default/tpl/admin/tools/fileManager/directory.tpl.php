<?php load_header() ?>

    <h1 class="title">Admin cpanel</h1>
    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">
        <div class="sub_title border_blue">Quản lí file</div>
        <div class="item_non_border">

            <?php foreach ($public_cpanel as $url => $cp): ?>
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
        <div class="sub_title border_blue">Danh sách file</div>

        <?php if ($file): ?>
            <div class="path">
                <code><?php echo $file ?></code>
            </div>
        <?php endif ?>

        <?php load_message() ?>

        <div class="tip">
            Có tất cả <b><?php echo count($scans) ?></b> file trong thư mục này
        </div>
        <form method="GET">
            <input type="hidden" name="file" value="<?php echo $file ?>" />
            <table class="reference notranslate">
                <tbody>
                <tr>
                    <th align="left" valign="top" width="5%">Select</th>
                    <th align="left" valign="top">Name</th>
                    <th align="left" valign="top" width="10%">Size</th>
                    <th align="left" valign="top" width="15%">Last mod</th>
                    <th align="left" valign="top" width="5%">Perms</th>
                </tr>
                <?php foreach ($scans as $item): ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="selected[]" value="<?php echo $item['input_name'] ?>"/>
                        </td>
                        <td>
                            <code>
                                <a href="<?php echo _HOME ?>/admin/tools/fileManager?file=<?php echo $item['path'] ?>">
                                    <?php if ($item['ptype'] == DIR): ?>
                                        <b><?php echo $item['name'] ?>/</b>
                                    <?php else: ?>
                                        <?php echo $item['name'] ?>
                                    <?php endif ?>
                                </a>
                            </code>
                        </td>
                        <td class="font-small">
                            <?php echo $item['size'] ?>
                        </td>
                        <td class="font-small">
                            <?php echo $item['time'] ?>
                        </td class="font-small">
                        <td class="font-small">
                            <?php echo $item['perms'] ?>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>

            <div class="item">
                <?php foreach ($file_manager_bar as $name => $val): ?>
                    <input type="submit" name="<?php echo $name ?>" value="<?php echo $val ?>" class="button BgBlack"/>
                <?php endforeach ?>
            </div>
        </form>

        <?php load_layout('fileinfo') ?>

    </div>

<?php load_footer() ?>