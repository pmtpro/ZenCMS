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
        <div class="sub_title border_blue">Unzip complete</div>

        <div class="path">
            <?php echo $file ?>
        </div>

        <?php load_message() ?>

        <div class="content">

            <code class="ad_list">
                <?php echo base64_decode($selected) ?>:
                <?php echo $info[$selected]['perms'] ?>
            </code>

            <?php foreach ($extract_list as $item): ?>
                <code class="ad_list">
                    <?php echo $item['filename'] ?>
                    <span class="text_smaller gray"><?php echo get_size($item['size']) ?></span>
                </code>
            <?php endforeach ?>

        </div>
    </div>

<?php load_footer() ?>