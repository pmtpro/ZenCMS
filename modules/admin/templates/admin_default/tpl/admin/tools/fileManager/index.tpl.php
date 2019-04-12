<?php load_header() ?>

    <h1 class="title">Admin cpanel</h1>
    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">
        <div class="sub_title border_blue">Templates</div>
        <div class="item_non_border">
            <a href="<?php echo _HOME ?>/admin/general/templates" class="button BgRed">Trở về</a>
            <a href="<?php echo _HOME ?>/admin/general/templates/list" class="button BgGreen">Danh sách</a>
        </div>
    </div>

    <div class="detail_content">
        <div class="sub_title border_blue">Danh sách template</div>

        <?php load_message() ?>

        <div class="tip">
            Có tất cả <b><?php echo count($templates) ?></b> template trong dữ liệu của bạn
        </div>

        <table>
            <?php foreach ($templates as $key => $temp): ?>
                <tr>
                    <td>
                        <img src="<?php echo ($temp['screenshot']) ? $temp['screenshot'] : icon_src('noimage'); ?>" width="100px" />
                    </td>
                    <td style="vertical-align: top; padding: 3px;">
                        <?php echo $temp['name'] ?><br/>
                    <span class="text_smaller gray">
                        Tác giả: <?php echo $temp['author'] ?><br/>
                        Version: <?php echo $temp['version'] ?><br/>
                        Mô tả: <?php echo $temp['description'] ?>
                    </span><br/>
                        <u><a href="<?php echo _HOME ?>/admin/general/templates/edit/<?php echo $key ?>">Chỉnh sửa</a></u>
                    </td>

                </tr>
            <?php endforeach ?>
        </table>

    </div>

<?php load_footer() ?>